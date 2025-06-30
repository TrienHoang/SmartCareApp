<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class FileUpload extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'appointment_id',
        'file_name',
        'file_path',
        'file_category',
        'uploaded_at'
    ];

    protected $casts = [
        'uploaded_at' => 'datetime'
    ];

    /**
     * Relationship với User (người upload)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship với Appointment
     */
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    /**
     * Relationship với UploadHistory
     */
    public function uploadHistories()
    {
        return $this->hasMany(UploadHistory::class);
    }

    /**
     * Lấy URL file
     */
    public function getFileUrlAttribute()
    {
        return Storage::disk('public')->url($this->file_path);
    }

    /**
     * Lấy kích thước file (bytes)
     */
    public function getFileSizeAttribute()
    {
        if (Storage::disk('public')->exists($this->file_path)) {
            return Storage::disk('public')->size($this->file_path);
        }
        return 0;
    }

    /**
     * Lấy kích thước file định dạng human readable
     */
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

    /**
     * Lấy extension file
     */
    public function getFileExtensionAttribute()
    {
        return pathinfo($this->file_name, PATHINFO_EXTENSION);
    }

    /**
     * Kiểm tra file có phải là ảnh không
     */
    public function getIsImageAttribute()
    {
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];
        return in_array(strtolower($this->file_extension), $imageExtensions);
    }

    /**
     * Kiểm tra file có tồn tại không
     */
    public function getFileExistsAttribute()
    {
        return Storage::disk('public')->exists($this->file_path);
    }

    /**
     * Lấy icon CSS class theo loại file
     */
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

    /**
     * Scope: Lọc theo category
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('file_category', $category);
    }

    /**
     * Scope: Lọc theo appointment
     */
    public function scopeByAppointment($query, $appointmentId)
    {
        return $query->where('appointment_id', $appointmentId);
    }

    /**
     * Scope: Lọc theo doctor
     */
    public function scopeByDoctor($query, $doctorId)
    {
        return $query->whereHas('appointment', function ($q) use ($doctorId) {
            $q->where('doctor_id', $doctorId);
        });
    }

    /**
     * Scope: Chỉ lấy file còn tồn tại
     */
    public function scopeExistingFiles($query)
    {
        return $query->whereExists(function ($q) {
            // Có thể implement logic kiểm tra file tồn tại ở đây
            // Hoặc dùng accessor file_exists
        });
    }

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();

        // Tự động tạo upload history khi tạo mới
        static::created(function ($fileUpload) {
            UploadHistory::create([
                'file_upload_id' => $fileUpload->id,
                'action' => 'created',
                'timestamp' => now()
            ]);
        });

        // Xóa file khỏi storage khi xóa record
        static::deleting(function ($fileUpload) {
            if (Storage::disk('public')->exists($fileUpload->file_path)) {
                Storage::disk('public')->delete($fileUpload->file_path);
            }
        });
    }
}
