<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Branch;
use App\Models\Event;
use App\Models\Guest;
use App\Models\Room;
use App\Models\RoomReport;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MonitoringController extends Controller
{
    public function index()
    {
        $today = Carbon::now()->startOfDay();

        $user = Auth::user();
        $branchId = $user->branch_id ?? '';

        if ($branchId) {
            $data['is_branch'] = true;
            $data['is_admin'] = false;
            $branches = Branch::where('id', $branchId)->get();
        } else {
            $data['is_branch'] = false;
            $data['is_admin'] = true;
            $branches = Branch::all();
        }

        $data['branch_list'] = Branch::all();

        $data['page_title'] = 'Dashboard';
        // dd($data);
        return view('pages.monitoring.index', compact('data'));
    }

    public function dailyOccupancy(Request $request)
    {
        $date = $request->input('date', now()->format('Y-m-d'));
        $data['occupancy_of_branch'] = $this->_getBranchesWithOccupancy($date, 'daily');

        return response()->json($data);
    }

    public function monthlyOccupancy(Request $request)
    {
        $month = $request->input('month', now()->format('Y-m'));
        $data['occupancy_of_branch'] = $this->_getBranchesWithOccupancy($month, 'monthly');

        return response()->json($data);
    }

    public function yearlyOccupancy(Request $request)
    {
        $year = $request->input('year', now()->format('Y'));
        $data['occupancy_of_branch'] = $this->_getBranchesWithOccupancy($year, 'yearly');

        return response()->json($data);
    }

    public function rangeOccupancy(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->endOfMonth()->format('Y-m-d'));

        $data['occupancy_of_branch'] = $this->_getBranchesWithOccupancy([$startDate, $endDate], 'range');

        return response()->json($data);
    }

    private function _getBranchesWithOccupancy($filter, $type)
    {
        $branches = Branch::all();

        $branchesWithOccupancy = $branches->map(function ($branch) use ($filter, $type) {
            $query = RoomReport::where('branch_id', $branch->id);

            if ($type === 'daily') {
                $query->whereDate('report_date', $filter);
            } elseif ($type === 'monthly') {
                $query->where('report_date', 'LIKE', "$filter%");
            } elseif ($type === 'yearly') {
                $query->whereYear('report_date', $filter);
            } elseif ($type === 'range' && is_array($filter)) {
                $query->whereBetween('report_date', [$filter[0], $filter[1]]);
            }

            $reports = $query->get();

            $totalRooms = $reports->sum('kapasitas');
            $occupiedRooms = $reports->sum('terisi');
            $emptyRooms = $reports->sum('sisa_bed');

            return [
                'id' => $branch->id,
                'name' => $branch->name,
                'occupancy' => [
                    'occupied' => $occupiedRooms,
                    'empty' => $emptyRooms,
                    'total' => $totalRooms,
                    'percentage_occupied' => $totalRooms > 0 ? round(($occupiedRooms / $totalRooms) * 100, 2) : 0,
                    'percentage_empty' => $totalRooms > 0 ? round(($emptyRooms / $totalRooms) * 100, 2) : 0,
                    'percentage_total' => 100,
                ],
            ];
        });

        return $branchesWithOccupancy;
    }

    public function getGraphOccupancy(Request $request)
    {
        $month = $request->get('month', null);
        $branchId = $request->get('branch_id', null);

        if (!$month) {
            $startDate = Carbon::parse(RoomReport::min('report_date'))->startOfDay();
            $endDate = Carbon::now()->endOfDay();
        } else {
            $startDate = Carbon::parse($month)->startOfMonth()->startOfDay();
            $endDate = Carbon::parse($month)->endOfMonth()->endOfDay();
        }

        $branches = $branchId ? Branch::where('id', $branchId)->get() : Branch::all();

        $dates = [];
        $occupancyData = [];

        for ($date = $startDate; $date <= $endDate; $date->addDay()) {
            $dateString = $date->format('Y-m-d');
            $dates[] = $dateString;

            foreach ($branches as $branch) {
                if (!isset($occupancyData[$branch->id])) {
                    $occupancyData[$branch->id] = [];
                }

                $report = RoomReport::where('branch_id', $branch->id)
                    ->whereDate('report_date', $dateString)
                    ->selectRaw('SUM(terisi) as occupied_rooms')
                    ->first();

                $occupancyData[$branch->id][] = $report ? (int)$report->occupied_rooms : 0;
            }
        }

        $series = $branches->map(function ($branch) use ($occupancyData) {
            return [
                'name' => $branch->name,
                'data' => $occupancyData[$branch->id] ?? array_fill(0, count($occupancyData), 0)
            ];
        });

        return response()->json([
            'categories' => $dates,
            'series' => $series
        ]);
    }
}
