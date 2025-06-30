<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class DoctorStatsExport implements FromView
{
    protected $doctor;
    protected $totalPatients;
    protected $todayAppointments;
    protected $totalRevenue;
    protected $visitsChart;

    public function __construct($doctor, $totalPatients, $todayAppointments, $totalRevenue, $visitsChart)
    {
        $this->doctor = $doctor;
        $this->totalPatients = $totalPatients;
        $this->todayAppointments = $todayAppointments;
        $this->totalRevenue = $totalRevenue;
        $this->visitsChart = $visitsChart;
    }

    public function view(): View
    {
        return view('doctor.dashboard.stats-excel', [
            'doctor' => $this->doctor,
            'totalPatients' => $this->totalPatients,
            'todayAppointments' => $this->todayAppointments,
            'totalRevenue' => $this->totalRevenue,
            'visitsChart' => $this->visitsChart,
        ]);
    }
}
