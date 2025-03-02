<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Identity; 
use App\Models\Guest; 
use App\Models\Instituion; 
class VisitorController extends Controller
{
    public function index()
    {
        $query = Identity::select('nik', 'full_name')
                        ->withCount('guests')
                        ->groupBy('nik', 'full_name', 'id'); 

        $visitors = $query->get();

        $oneVisit = 0;
        $moreThanOneVisit = 0;
        $moreThanThreeVisit = 0;
        $moreThanFiveVisit = 0;

        foreach ($visitors as $visitor) {
            $guestCount = $visitor->guests_count;

            if ($guestCount == 1) {
                $oneVisit++;
            } elseif ($guestCount > 1 && $guestCount <= 3) {
                $moreThanOneVisit++;
            } elseif ($guestCount > 3 && $guestCount <= 5) {
                $moreThanThreeVisit++;
            } elseif ($guestCount > 5) {
                $moreThanFiveVisit++;
            }
        }

        $responseData = [
            'total_visitors' => $visitors->count(),
            'one_visit' => $oneVisit,
            'more_than_one_visit' => $moreThanOneVisit,
            'more_than_three_visit' => $moreThanThreeVisit,
            'more_than_five_visit' => $moreThanFiveVisit,
        ];

        return view('visitors.index', compact('responseData'));
    }

    public function list(Request $request)
    {
        $search = $request->input('search');
        $visitFilter = $request->input('visit_filter');
        $hasSimilarity = $request->input('has_similarity'); 

        $perPage = 10;

        $query = Identity::withCount('guests')->with('guests.institution');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nik', 'like', '%' . $search . '%')
                ->orWhere('full_name', 'like', '%' . $search . '%');
            });
        }

        if ($visitFilter) {
            switch ($visitFilter) {
                case '1x':
                    $query->having('guests_count', '=', 1);
                    break;
                case 'more_than_1x':
                    $query->having('guests_count', '>', 1);
                    break;
                case 'more_than_3x':
                    $query->having('guests_count', '>', 3);
                    break;
                case 'more_than_5x':
                    $query->having('guests_count', '>', 5);
                    break;
                case 'more_than_10x':
                    $query->having('guests_count', '>', 10);
                    break;
            }
        }

        $dataVisitors = $query->get();

        $dataVisitors->transform(function ($visitor) {
            $isSimilarData = false;
            $similarityLabel = [];
            $similarNameDifferentNik = Identity::where('full_name', $visitor->full_name)
                ->where('nik', '!=', $visitor->nik)
                ->exists();

            if ($similarNameDifferentNik) {
                $isSimilarData = true;
                $similarityLabel[] = 'Nama mirip, NIK berbeda';
            }

            $similarInstitution = Guest::where('institution_id', function ($subQuery) use ($visitor) {
                    $subQuery->select('institution_id')
                            ->from('guests')
                            ->where('identity_id', $visitor->id)
                            ->limit(1);
                })
                ->where('identity_id', '!=', $visitor->id)
                ->exists();

            if ($similarInstitution) {
                $isSimilarData = true;
                $similarityLabel[] = 'Institusi sama, NIK berbeda';
            }

            $visitCount = $visitor->guests_count;
            $visitCategory = '';
            if ($visitCount == 1) {
                $visitCategory = '1x';
            } elseif ($visitCount > 1 && $visitCount <= 3) {
                $visitCategory = 'more_than_1x';
            } elseif ($visitCount > 3 && $visitCount <= 5) {
                $visitCategory = 'more_than_3x';
            } elseif ($visitCount > 5 && $visitCount <= 10) {
                $visitCategory = 'more_than_5x';
            } elseif ($visitCount > 10) {
                $visitCategory = 'more_than_10x';
            }

            return [
                'nik' => $visitor->nik,
                'full_name' => $visitor->full_name,
                'guests_count' => $visitCount,
                'visit_category' => $visitCategory,
                'is_similar_data' => $isSimilarData,
                'similarity_label' => $similarityLabel,
            ];
        });

        if ($hasSimilarity !== null) {
            $filteredVisitors = $dataVisitors->filter(function ($visitor) use ($hasSimilarity) {
                return ($hasSimilarity == 'true' && $visitor['is_similar_data']) || ($hasSimilarity == 'false' && !$visitor['is_similar_data']);
            });
        } else {
            $filteredVisitors = $dataVisitors;
        }

        $paginatedVisitors = new \Illuminate\Pagination\LengthAwarePaginator(
            $filteredVisitors->values()->all(),
            $filteredVisitors->count(),
            $perPage,
            $request->input('page', 1),
            ['path' => $request->url()]
        );

        return response()->json($paginatedVisitors);
    }
}
