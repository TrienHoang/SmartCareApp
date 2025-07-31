<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'appointment_id', 'payment_id' ,'total_amount', 'status', 'ordered_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function services()
    {
        return $this->belongsToMany(Service::class, 'order_service')
            ->withPivot('quantity', 'price')
            ->withTimestamps();
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class, 'appointment_id');
    }

    // Lấy payment thông qua appointment
    public function payment()
    {
        return $this->hasOneThrough(
            Payment::class,      // Model cuối cần lấy
            Appointment::class,  // Model trung gian  
            'id',               // Foreign key của Appointment (appointment.id)
            'appointment_id',   // Foreign key của Payment (payment.appointment_id)
            'appointment_id',   // Local key của Order (order.appointment_id)
            'id'                // Local key của Appointment (appointment.id)
        );
    }

    // Lấy payment history thông qua appointment -> payment
    public function paymentHistories()
    {
        return $this->hasManyThrough(
            PaymentHistory::class,
            Payment::class,
            'appointment_id',    // Foreign key on payments table  
            'payment_id',        // Foreign key on payment_histories table
            'appointment_id',    // Local key on orders table
            'id'                 // Local key on payments table
        );
    }

    // Helper method để check có payment chưa
    public function hasPayment()
    {
        return $this->appointment && $this->appointment->payment;
    }

    // Helper method để lấy payment status
    public function getPaymentStatus()
    {
        return $this->hasPayment() ? $this->appointment->payment->status : null;
    }
}