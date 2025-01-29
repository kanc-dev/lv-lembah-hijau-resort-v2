<?php

namespace App\Exports;

use App\Models\RoomReport;
use App\Models\Branch;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Carbon\Carbon;

class RoomReportSheet implements FromView
{
    protected $startDate;
    protected $endDate;
    protected $branchId;

    public function __construct($startDate, $endDate, $branchId)
    {
        $this->startDate = Carbon::parse($startDate);
        $this->endDate = Carbon::parse($endDate);
        $this->branchId = $branchId;
    }

    public function view(): View
    {
        $reports = RoomReport::where('branch_id', $this->branchId)
            ->whereBetween('report_date', [$this->startDate, $this->endDate])
            ->orderBy('report_date')
            ->get();

        $groupedData = [];

        // Membuat daftar semua tanggal dalam range dan mengatur default kosong
        $allDates = [];
        for ($date = $this->startDate; $date->lte($this->endDate); $date->addDay()) {
            $allDates[$date->toDateString()] = ''; // Default kosong
        }

        // Mengelompokkan data berdasarkan event dan tanggal
        foreach ($reports as $report) {
            $eventName = $report->event ?? 'N/A';
            $date = $report->report_date->toDateString();

            if (!isset($groupedData[$eventName])) {
                $groupedData[$eventName] = $allDates; // Set all dates with empty value
            }

            // Menambahkan data jumlah kamar yang terisi
            $groupedData[$eventName][$date] = $groupedData[$eventName][$date] === '' ? 1 : $groupedData[$eventName][$date] + 1;
        }

        // Mendapatkan informasi branch
        $branch = Branch::find($this->branchId);

        return view('pages.room.export', [
            'groupedData' => $groupedData,
            'dates' => array_keys($allDates),
            'startDate' => $this->startDate->toDateString(),
            'endDate' => $this->endDate->toDateString(),
            'branch' => $branch,
        ]);
    }
}
