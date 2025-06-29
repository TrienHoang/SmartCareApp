<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Notifications\DatabaseNotification; 
use App\Models\User;

class NotificationFactory extends Factory
{
    /** The model that this factory is for. */
    protected $model = DatabaseNotification::class;

    public function definition(): array
    {
        return [
            'id'              => Str::uuid()->toString(),
            'type'            => 'App\\Notifications\\GenericNotification',
            'notifiable_type' => User::class,
            'notifiable_id'   => User::inRandomOrder()->value('id') ?? 1,
            'data'            => ['message' => $this->faker->sentence()],
            'read_at'         => null,
        ];
    }
}
