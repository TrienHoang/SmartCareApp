<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'service_id',
        'appointment_time',
        'status',
        'reason',
        'cancel_reason',
        'end_time',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'appointment_time' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'end_time' => 'datetime',
    ];

    // Relationship với User (Patient)
    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    // Relationship với Doctor
    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }

    // Relationship với Service
    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    // Relationship với Payment
    public function payment()
    {
        return $this->hasOne(Payment::class, 'appointment_id');
    }

    // Relationship với Medical Record
    public function medicalRecord()
    {
        return $this->hasOne(MedicalRecord::class, 'appointment_id');
    }

    // Relationship với File Uploads
    public function fileUploads()
    {
        return $this->hasMany(FileUpload::class, 'appointment_id');
    }

    // Relationship với Appointment Logs
    public function logs()
    {
        return $this->hasMany(AppointmentLog::class, 'appointment_id');
    }

    // Accessor cho formatted time
    public function getFormattedTimeAttribute()
    {
        return $this->appointment_time ? 
            Carbon::parse($this->appointment_time)->format('d/m/Y H:i') : 
            'N/A';
    }

    // Accessor cho status text
    public function getStatusTextAttribute()
    {
        $statusMap = [
            'pending' => 'Chờ xác nhận',
            'confirmed' => 'Đã xác nhận',
            'completed' => 'Hoàn thành',
            'cancelled' => 'Đã hủy'
        ];

        return $statusMap[$this->status] ?? $this->status;
    }

    // Scope cho các trạng thái
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    // Scope cho hôm nay
    public function scopeToday($query)
    {
        return $query->whereDate('appointment_time', Carbon::today());
    }

    // Scope cho tuần này
    public function scopeThisWeek($query)
    {
        return $query->whereBetween('appointment_time', [
            Carbon::now()->startOfWeek(),
            Carbon::now()->endOfWeek()
        ]);
    }

    // Scope cho tháng này
    public function scopeThisMonth($query)
    {
        return $query->whereMonth('appointment_time', Carbon::now()->month)
                    ->whereYear('appointment_time', Carbon::now()->year);
    }
    public function order(){
        return $this->hasOne(Order::class, 'appointment_id');
    }
}