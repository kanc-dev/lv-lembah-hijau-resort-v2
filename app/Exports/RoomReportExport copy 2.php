<?php

namespace App\Exports;

use App\Models\RoomReport;
use App\Models\Branch;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RoomReportExport implements WithMultipleSheets, ShouldAutoSize
{
    protected $startDate;
    protected $endDate;
    protected $branchId;

    public function __construct($startDate, $endDate, $branchId = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->branchId = $branchId;
    }

    public function sheets(): array
    {
        $sheets = [];

        // Jika branchId diberikan, ekspor untuk branch tertentu
        if ($this->branchId) {
            $branch = Branch::find($this->branchId);
            $sheets[] = new RoomReportSheet($this->startDate, $this->endDate, $branch);
        } else {
            // Jika tidak ada branchId, ekspor untuk semua branch
            $branches = Branch::all();
            foreach ($branches as $branch) {
                $sheets[] = new RoomReportSheet($this->startDate, $this->endDate, $branch);
            }
        }

        return $sheets;
    }
}

class RoomReportSheet implements FromView, WithHeadings, ShouldAutoSize
{
    protected $startDate;
    protected $endDate;
    protected $branch;

    public function __construct($startDate, $endDate, $branch)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->branch = $branch;
    }

    public function view(): \Illuminate\View\View
    {
        $reports = RoomReport::whereBetween('report_date', [$this->startDate, $this->endDate])
            ->where('branch_id', $this->branch->id)
            ->orderBy('report_date')
            ->get();

        // Format data yang diperlukan untuk tampilan
        $groupedData = [];
        $allDates = $reports->pluck('report_date')->unique()->sort();

        foreach ($reports as $report) {
            $eventName = $report->event ?? 'N/A';
            $date = $report->report_date->format('Y-m-d');

            if (!isset($groupedData[$eventName])) {
                $groupedData[$eventName] = [];
            }

            $groupedData[$eventName][$date] = isset($groupedData[$eventName][$date])
                ? $groupedData[$eventName][$date] + 1
                : 1;
        }

        return view('pages.room.export', [
            'groupedData' => $groupedData,
            'allDates' => $allDates,
            'branchName' => $this->branch->name,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate
        ]);
    }

    public function headings(): array
    {
        return [
            'Event Name',
            'Unit',
            'Date', // Date column header
            'Room Occupied Count',
        ];
    }

    public function title(): string
    {
        return $this->branch->name; // Set sheet title to branch name
    }
}
