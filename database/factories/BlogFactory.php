<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class BlogFactory extends Factory
{
    public function definition(): array
    {
        $title = fake()->sentence();
        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'content' => fake()->paragraph(5),
            'author_id' => User::inRandomOrder()->first()?->id ?? 1,
            'published_at' => now(),
            'thumbnail' => fake()->imageUrl()
        ];
    }
}

