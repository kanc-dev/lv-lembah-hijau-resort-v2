<?php

namespace App\Http\Controllers;

use App\Models\RoomOccupancyHistory;
use Illuminate\Http\Request;

class RoomOccupancyHistoryController extends Controller
{
    public function getOccupancyHistory(Request $request)
    {
        $request->validate([
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:1900|max:' . now()->year,
        ]);

        $month = $request->input('month');
        $year = $request->input('year');

        // Ambil data berdasarkan bulan dan tahun
        $occupancyHistories = RoomOccupancyHistory::whereYear('tanggal', $year)
            ->whereMonth('tanggal', $month)
            ->get();

        // Format data untuk kalender
        $formattedData = $occupancyHistories->map(function ($history) {
            return [
                'date' => $history->tanggal->format('Y-m-d'),
                'total_rooms' => $history->total_rooms,
                'total_capacity' => $history->total_capacity,
                'occupied_capacity' => $history->occupied_capacity,
                'available_capacity' => $history->available_capacity,
                'occupancy_percentage' => $history->occupancy_percentage,
            ];
        });

        return response()->json($formattedData);
    }
}
