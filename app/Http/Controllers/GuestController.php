<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Companion;
use App\Models\Guest;
use App\Models\Notulensi;
use App\Models\NotulensiPhoto;
use App\Models\GuestPhoto;
use App\Models\CompanionAssign;
use App\Models\Identity;
use App\Models\Institution;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Pusher\Pusher;
use Auth;
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
        
        $acceptedGuests = Guest::where('status', 'accepted')
            ->whereDate('created_at', $dateNow)
            ->get();

        $dispositionGuests = Guest::where('status', 'disposition')
            ->whereDate('created_at', $dateNow)
            ->get();
        
        $completedGuests = Guest::where('status', 'completed')
            ->whereDate('created_at', $dateNow)
            ->get();


        return view('guests.index', compact('queue', 'pendingGuests', 'acceptedGuests', 'dispositionGuests','completedGuests'));
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
                      ->orWhere('idt.phone_number', 'like', "%{$search}%")
                      ->orWhere('inst.institution_name', 'like', "%{$search}%");
                });
            })
            ->select('guests.*', 'idt.phone_number', 'idt.full_name', 'inst.institution_name')
            ->orderBy('queue_number', 'ASC')
            ->get();
    
        return response()->json($pendingGuests);
    }
    

    public function getAcceptedGuests(Request $request)
    {
        $dateNow = Carbon::now()->format('Y-m-d');
        $search = $request->input('search');
    
        $acceptedGuests = Guest::join('identities as idt', 'idt.id', '=', 'guests.identity_id')
            ->join('institutions as inst', 'inst.id', '=', 'guests.institution_id')
            ->where('guests.status', 'accepted')
            ->with('companionAssign')
            ->whereDate('guests.created_at', $dateNow)
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('idt.full_name', 'like', "%{$search}%")
                      ->orWhere('idt.phone_number', 'like', "%{$search}%")
                      ->orWhere('inst.institution_name', 'like', "%{$search}%");
                });
            })
            ->select('guests.*', 'idt.phone_number', 'idt.full_name', 'inst.institution_name')
            ->orderBy('queue_number', 'ASC')
            ->get();
    
        return response()->json($acceptedGuests);
    }
    
    public function getDispositionGuests(Request $request)
    {
        $dateNow = Carbon::now()->format('Y-m-d');
        $search = $request->input('search');
    
        $acceptedGuests = Guest::join('identities as idt', 'idt.id', '=', 'guests.identity_id')
            ->join('institutions as inst', 'inst.id', '=', 'guests.institution_id')
            ->where('guests.status', 'disposition')
            ->with('companionAssign')
            ->whereDate('guests.created_at', $dateNow)
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('idt.full_name', 'like', "%{$search}%")
                    ->orWhere('idt.phone_number', 'like', "%{$search}%")
                    ->orWhere('inst.institution_name', 'like', "%{$search}%");
                });
            })
            ->select('guests.*', 'idt.phone_number', 'idt.full_name', 'inst.institution_name')
            ->orderBy('queue_number', 'ASC')
            ->get();
    
        return response()->json($acceptedGuests);
    }
    
    public function getCompletedGuests(Request $request)
    {
        $dateNow = Carbon::now()->format('Y-m-d');
        $search = $request->input('search');
    
        $acceptedGuests = Guest::join('identities as idt', 'idt.id', '=', 'guests.identity_id')
            ->join('institutions as inst', 'inst.id', '=', 'guests.institution_id')
            ->where('guests.status', 'completed')
            ->with('companionAssign')
            ->whereDate('guests.created_at', $dateNow)
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('idt.full_name', 'like', "%{$search}%")
                    ->orWhere('idt.phone_number', 'like', "%{$search}%")
                    ->orWhere('inst.institution_name', 'like', "%{$search}%");
                });
            })
            ->select('guests.*', 'idt.phone_number', 'idt.full_name', 'inst.institution_name')
            ->orderBy('queue_number', 'ASC')
            ->get();
    
        return response()->json($acceptedGuests);
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
        if (!$request->full_name) {
            return response()->json(['message' => 'Nama lengkap pengunjung harus diisi!'], 400);
        }

        $dateNow = Carbon::now('Asia/Jakarta')->format('Y-m-d');
        $queue = Guest::whereDate('created_at', $dateNow)->count() + 1;

        $phone_number = $request->phone_number;
        $fullName = strtoupper($request->full_name);
        $identity = Identity::where('phone_number', $phone_number)
            ->Where('full_name', $fullName)
            ->first();
            
        $identityID = 0;
        if (!$identity) {
            $storeIdentity = new Identity();
            $storeIdentity->full_name = $fullName;
            $storeIdentity->phone_number = $phone_number;
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
            $store->institution_id = $institutionID;
            $store->total_audience = $request->total_audience;
            $store->appointment = $request->appointment;
            $store->purpose = $request->purpose;
            $store->queue_number = $queue;
            $store->status = 'pending';
            $store->created_by = Auth::user()->id;
            $store->save();

            if($request->appointment == "yes") {
                if ($request->hasFile('photo')) {
                    $photo = $request->file('photo');
                    $originalExtension = $photo->getClientOriginalExtension();
                    $newFileName = time() . '.' . $originalExtension;
                    
                    $photoPath = $photo->storeAs('notulensi_images', $newFileName, 'public');
                    
                    $fileSize = $photo->getSize();
            
                    GuestPhoto::create([
                        'guest_id' => $store->id,
                        'photo_path' => $photoPath,
                        'file_name' => $newFileName,
                        'file_size' => $fileSize,
                        'file_extension' => $originalExtension,
                    ]);
                }
            }

            DB::commit();
            
            return response()->json(['message' => 'Guest added successfully!'], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Failed to add guest. Please try again. '.$e->getMessage()], 500);
        }
    }

    

    public function detail($a)
    {
        $guest = Guest::where('id', $a)
            ->first();

        $companions = Companion::all();

        return view('guests.detail', compact('guest', 'companions'));
    }

    public function edit($id)
    {
        $guest = Guest::findOrFail($id);

        return view('guests.edit', compact('guest'));
    }

    public function update(Request $request, $id)
    {
        if (!$request->full_name) {
            return response()->json(['message' => 'Nama lengkap pengunjung harus diisi!'], 400);
        }

        $guest = Guest::findOrFail($id);
        $dateNow = Carbon::now('Asia/Jakarta')->format('Y-m-d');
        
        $phone_number = $request->phone_number;
        $fullName = strtoupper($request->full_name);
        $identity = Identity::where('phone_number', $phone_number)
            ->where('full_name', $fullName)
            ->first();

        $identityID = $identity ? $identity->id : $guest->identity_id;
        if (!$identity) {
            $identity = new Identity();
            $identity->full_name = $fullName;
            $identity->phone_number = $phone_number;
            $identity->save();
            $identityID = $identity->id;
        }

        $institutionName = strtoupper($request->institution);
        $institution = Institution::where('institution_name', $institutionName)->first();
        $institutionID = $institution ? $institution->id : $guest->institution_id;
        if (!$institution) {
            $institution = new Institution();
            $institution->institution_name = $institutionName;
            $institution->save();
            $institutionID = $institution->id;
        }

        DB::beginTransaction();
        try {
            $guest->identity_id = $identityID;
            $guest->institution_id = $institutionID;
            $guest->total_audience = $request->total_audience;
            $guest->appointment = $request->appointment;
            $guest->purpose = $request->purpose;
            $guest->status = 'pending';
            $guest->updated_by = Auth::user()->id;
            $guest->save();

            if ($request->appointment == "yes" && $request->hasFile('photo')) {
                $photo = $request->file('photo');
                $originalExtension = $photo->getClientOriginalExtension();
                $newFileName = time() . '.' . $originalExtension;
                $photoPath = $photo->storeAs('notulensi_images', $newFileName, 'public');
                $fileSize = $photo->getSize();
                
                $guest->guestPhoto()->updateOrCreate(
                    ['guest_id' => $guest->id],
                    [
                        'photo_path' => $photoPath,
                        'file_name' => $newFileName,
                        'file_size' => $fileSize,
                        'file_extension' => $originalExtension,
                    ]
                );
            }

            DB::commit();
            return response()->json(['message' => 'Guest updated successfully!'], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Failed to update guest. Please try again. '.$e->getMessage()], 500);
        }
    }


    public function updateStatus(Request $request, $a)
    {
        $guest = Guest::where('id', $a)
            ->first();

        $guest->status = $request->status;
        $guest->save();

        if($request->status == 'accepted' || $request->status == 'disposition') {
            foreach($request->companion_id as $companion_id) {
                $assignCompanion = new CompanionAssign();
                $assignCompanion->guest_id = $a;
                $assignCompanion->companion_id = $companion_id;
                $assignCompanion->save();
            }
        }

        return redirect()->back();
    }

    public function guestNotulensiStore(Request $request, $guestId)
    {
        try{
            $request->validate([
                'notulensi' => 'required|string',
                'photos.*' => 'image|mimes:jpeg,png,jpg,gif',
                'appointment' => 'boolean',
            ]);

            $cNotulensi = Notulensi::where('guest_id', $guestId)
                ->first();
            if($cNotulensi) {
                return response()
                    ->json([
                        'success' => false,
                        'message' => 'Duplicate Data Notulensi'
                    ], 400);
            }
    
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

            $guest = Guest::where('id', $guestId) 
                ->first();
            $guest->status = 'completed';
            $guest->save();
    
            return response()->json(['success' => true, 'message' => 'Notulensi berhasil disimpan!'], 201);
        }catch(\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
       
    }
}
