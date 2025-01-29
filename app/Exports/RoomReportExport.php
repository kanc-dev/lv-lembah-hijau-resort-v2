<?php

namespace App\Exports;

use App\Models\Branch;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

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

        // Jika branch_id diberikan, export hanya untuk branch itu
        if ($this->branchId) {
            $sheets[] = new RoomReportSheet($this->startDate, $this->endDate, $this->branchId);
        } else {
            // Jika branch_id kosong (admin), buatkan satu sheet untuk setiap branch
            $branches = Branch::all();
            foreach ($branches as $branch) {
                $sheets[] = new RoomReportSheet($this->startDate, $this->endDate, $branch->id);
            }
        }

        return $sheets;
    }
}
