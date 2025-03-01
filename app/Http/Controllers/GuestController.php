<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Companion;
use App\Models\Guest;
use App\Models\Notulensi;
use App\Models\NotulensiPhoto;
use App\Models\Identity;
use App\Models\Institution;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Pusher\Pusher;
use DB;
class GuestController extends Controller
{
    public function index()
    {
        $dateNow = Carbon::now()->format('Y-m-d');
        $queue = Guest::whereDate('created_at', $dateNow)
            ->count() + 1;

        $pendingGuests = Guest::where('status', 'pending')
            ->whereDate('created_at', $dateNow)
            ->get();


        return view('guests.index', compact('queue', 'pendingGuests'));
    }

    public function getPendingGuests(Request $request)
    {
        $dateNow = Carbon::now()->format('Y-m-d');
        $search = $request->input('search');
    
        $pendingGuests = Guest::join('identities as idt', 'idt.id', '=', 'guests.identity_id')
            ->join('institutions as inst', 'inst.id', '=', 'guests.institution_id')
            ->where('guests.status', 'pending')
            ->whereDate('guests.created_at', $dateNow)
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('idt.full_name', 'like', "%{$search}%")
                      ->orWhere('guests.phone_number', 'like', "%{$search}%")
                      ->orWhere('inst.institution_name', 'like', "%{$search}%");
                });
            })
            ->select('guests.*', 'idt.nik', 'idt.full_name', 'inst.institution_name')
            ->orderBy('queue_number', 'ASC')
            ->get();
    
        return response()->json($pendingGuests);
    }
    

    public function create()
    {
        
        $dateNow = Carbon::now()->format('Y-m-d');
        $queue = Guest::whereDate('created_at', $dateNow)
            ->count() + 1;
            
        return view('guests.create', compact('queue'));
    }

    public function store(Request $request)
    {
        if(!$request->full_name) {
            return redirect()->back()->with('error', 'Please input nama lengkap pengunjung');
        }

        $dateNow = Carbon::now('Asia/Jakarta')->format('Y-m-d');
        $queue = Guest::whereDate('created_at', $dateNow)->count() + 1;

        $nik = strtoupper($request->nik);
        $fullName = strtoupper($request->full_name);
        $identity = Identity::where('nik', $nik)
            ->orWhere('full_name', 'LIKE', '%' . $fullName . '%')
            ->first();
            
        $identityID = 0;
        if (!$identity) {
            $storeIdentity = new Identity();
            $storeIdentity->nik = $nik ?? null;
            $storeIdentity->full_name = $fullName;
            $storeIdentity->save();
            $identityID = $storeIdentity->id;
        } else {
            $identityID = $identity->id;
        }

        $institutionName = strtoupper($request->institution);
        $institution = Institution::where('institution_name', $institutionName)->first();
        $institutionID = 0;
        if (!$institution) {
            $storeInstitution = new Institution();
            $storeInstitution->institution_name = $institutionName;
            $storeInstitution->save();
            $institutionID = $storeInstitution->id;
        } else {
            $institutionID = $institution->id;
        }

        DB::beginTransaction();
        try {
            $store = new Guest();
            $store->date = Carbon::now('Asia/Jakarta')->format('Y-m-d');
            $store->time = Carbon::now('Asia/Jakarta')->format('H:i');
            $store->identity_id = $identityID;
            $store->phone_number = $request->phone_number;
            $store->institution_id = $institutionID;
            $store->total_audience = $request->total_audience;
            $store->appointment = $request->appointment;
            $store->purpose = $request->purpose;
            $store->queue_number = $queue;
            $store->status = 'pending';
            $store->save();

            $options = [
                'cluster' => 'ap1',
                'useTLS' => true
            ];
            $pusher = new Pusher(
                '4726565422b0bb85073b',
                '022e6107fa3d41051e2b',
                '1949892',
                $options
            );

            $data['message'] = 'Guest added successfully: ' . $fullName;
            $data['queue_number'] = $queue;
            $pusher->trigger('guest-channel', 'guest-added', $data);

            DB::commit();
            return redirect()->back()->with('success', 'Guest added successfully!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Failed to add guest. Please try again.');
        }
    }
    

    public function detail($a)
    {
        $guest = Guest::where('id', $a)
            ->first();

        $companions = Companion::all();

        return view('guests.detail', compact('guest', 'companions'));
    }

    public function update(Request $request, $a)
    {
        $guest = Guest::where('id', $a)
            ->first();

        $guest->status = $request->status;
        $guest->companion_id = $request->status == 'accepted' || $request->status == 'disposition' ? $request->companion_id : null;
        $guest->save();

        return redirect()->back();
    }

    public function guestNotulensiStore(Request $request, $guestId)
    {
        try{
            $request->validate([
                'notulensi' => 'required|string',
                'photos.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
                'appointment' => 'boolean',
            ]);
    
            $notulensi = Notulensi::create([
                'guest_id' => $guestId,
                'title' => $request->title,
                'notulensi' => preg_replace(
                    [
                        '/<div class="ql-tooltip[^>]*>.*?<\/div>/is',
                        '/class="[^"]*?ql-[^"]*?"/i',
                        '/contenteditable="true"/i',
                        '/<div><a[^>]*><\/a><input[^>]*><a><\/a><a><\/a><\/div>/is',
                    ],
                    '',
                    $request->input('notulensi'),
                ),
                'appointment' => $request->input('appointment', false),
            ]);
    
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $photo) {
                    $originalExtension = $photo->getClientOriginalExtension();
                    $newFileName = time() . '.' . $originalExtension;
                    
                    $photoPath = $photo->storeAs('notulensi_images', $newFileName, 'public');
                    
                    $fileSize = $photo->getSize();
            
                    NotulensiPhoto::create([
                        'notulensi_id' => $notulensi->id,
                        'photo_path' => $photoPath,
                        'file_name' => $newFileName,
                        'file_size' => $fileSize,
                        'file_extension' => $originalExtension,
                    ]);
                }
            }
            
            
    
            return response()->json(['success' => true, 'message' => 'Notulensi berhasil disimpan!'], 201);
        }catch(\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
       
    }
}
