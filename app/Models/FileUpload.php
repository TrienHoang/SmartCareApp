<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class FileUpload extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'appointment_id',
        'file_name',
        'file_path',
        'file_category',
        'note',
        'uploaded_at'
    ];

    protected $casts = [
        'uploaded_at' => 'datetime'
    ];

    protected $dates = [
        'deleted_at' => 'datetime'
    ];

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function uploadHistories()
    {
        return $this->hasMany(UploadHistory::class);
    }

    public function getFileUrlAttribute()
    {
        return Storage::disk('public')->url($this->file_path);
    }

    public function getFileSizeAttribute()
    {
        if (Storage::disk('public')->exists($this->file_path)) {
            return Storage::disk('public')->size($this->file_path);
        }
        return 0;
    }

    public function getFileSizeFormattedAttribute()
    {
        $bytes = $this->file_size;

        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' bytes';
        }
    }

    public function getFileExtensionAttribute()
    {
        return pathinfo($this->file_name, PATHINFO_EXTENSION);
    }

    public function getIsImageAttribute()
    {
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];
        return in_array(strtolower($this->file_extension), $imageExtensions);
    }

    public function getFileExistsAttribute()
    {
        return Storage::disk('public')->exists($this->file_path);
    }

    public function getFileIconAttribute()
    {
        $extension = strtolower($this->file_extension);

        switch ($extension) {
            case 'pdf':
                return 'fas fa-file-pdf text-red-500';
            case 'doc':
            case 'docx':
                return 'fas fa-file-word text-blue-500';
            case 'xls':
            case 'xlsx':
                return 'fas fa-file-excel text-green-500';
            case 'jpg':
            case 'jpeg':
            case 'png':
            case 'gif':
            case 'bmp':
            case 'webp':
                return 'fas fa-file-image text-purple-500';
            default:
                return 'fas fa-file text-gray-500';
        }
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('file_category', $category);
    }

    public function scopeByAppointment($query, $appointmentId)
    {
        return $query->where('appointment_id', $appointmentId);
    }

    public function scopeByDoctor($query, $doctorId)
    {
        return $query->whereHas('appointment', function ($q) use ($doctorId) {
            $q->where('doctor_id', $doctorId);
        });
    }

    public function scopeExistingFiles($query)
    {
        return $query->whereExists(function ($q) {
            // Placeholder
        });
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($fileUpload) {
            UploadHistory::create([
                'file_upload_id' => $fileUpload->id,
                'action' => 'created',
                'timestamp' => now()
            ]);
        });

        // ✅ Chỉ xóa file vật lý nếu đang force delete
        static::deleting(function ($fileUpload) {
            if ($fileUpload->isForceDeleting()) {
                if (Storage::disk('public')->exists($fileUpload->file_path)) {
                    Storage::disk('public')->delete($fileUpload->file_path);
                }
            }
        });
    }
}
