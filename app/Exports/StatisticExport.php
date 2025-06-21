<?php
namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class StatisticExport implements FromView
{
    protected $statistics;

    public function __construct($statistics)
    {
        $this->statistics = $statistics;
    }

    public function view(): View
    {
        return view('exports.monthly', [
            'statistics' => $this->statistics
        ]);
    }
}
