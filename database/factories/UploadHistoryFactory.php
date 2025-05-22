<?php

namespace Database\Factories;


use App\Models\FileUpload;
use Illuminate\Database\Eloquent\Factories\Factory;

class UploadHistoryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'file_upload_id' => FileUpload::inRandomOrder()->first()?->id ?? 1,
            'action' => fake()->randomElement(['upload', 'update', 'delete']),
            'timestamp' => now()
        ];
    }
}
