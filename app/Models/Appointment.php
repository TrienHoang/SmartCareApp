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
        'check_in_time',
        'end_time',
        'status',
        'reason',
        'cancel_reason',
        'created_at',
        'updated_at',
        'treatment_plan_id', 
        'treatment_plan_item_id'
    ];

    protected $casts = [
        'appointment_time' => 'datetime',
        'check_in_time' => 'datetime',
        'end_time' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // 🧑‍🤝‍🧑 Quan hệ: bệnh nhân (User)
    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    // 👨‍⚕️ Quan hệ: bác sĩ
    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }

    // 🏥 Quan hệ: dịch vụ
    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    // 💰 Quan hệ: thanh toán
    public function payment()
    {
        return $this->hasOne(Payment::class, 'appointment_id')->latestOfMany();
    }

    // 📋 Quan hệ: hồ sơ bệnh án
    public function medicalRecord()
    {
        return $this->hasOne(MedicalRecord::class, 'appointment_id');
    }

    // 📎 Quan hệ: file đính kèm
    public function fileUploads()
    {
        return $this->hasMany(FileUpload::class, 'appointment_id');
    }

    // 📝 Quan hệ: log lịch hẹn
    public function logs()
    {
        return $this->hasMany(AppointmentLog::class, 'appointment_id');
    }

    // 🛒 Quan hệ: đơn hàng
    public function order()
    {
        return $this->hasOne(Order::class, 'appointment_id');
    }

    // ✅ Accessor: thời gian hiển thị đẹp
    public function getFormattedTimeAttribute()
    {
        return $this->appointment_time
            ? Carbon::parse($this->appointment_time)->format('d/m/Y H:i')
            : 'N/A';
    }

    // ✅ Accessor: trạng thái hiển thị rõ
    public function getStatusTextAttribute()
    {
        return match ($this->status) {
            'pending' => 'Chờ xác nhận',
            'confirmed' => 'Đã xác nhận',
            'completed' => 'Hoàn thành',
            'cancelled' => 'Đã hủy',
            default => ucfirst($this->status),
        };
    }

    // ✅ Accessor: tên bệnh nhân (dễ dùng)
    public function getPatientNameAttribute()
    {
        return $this->patient?->full_name ?? '---';
    }

    // ✅ Accessor: tên bác sĩ
    public function getDoctorNameAttribute()
    {
        return $this->doctor?->user?->full_name ?? '---';
    }

    // 🔍 Scope: theo trạng thái
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

    // 📅 Scope: theo thời gian
    public function scopeToday($query)
    {
        return $query->whereDate('appointment_time', Carbon::today());
    }

    public function scopeThisWeek($query)
    {
        return $query->whereBetween('appointment_time', [
            Carbon::now()->startOfWeek(),
            Carbon::now()->endOfWeek()
        ]);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('appointment_time', Carbon::now()->month)
                     ->whereYear('appointment_time', Carbon::now()->year);
    }


    
}
