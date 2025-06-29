<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\Service;
use App\Models\OrderStatusLog;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $services = Service::where('status', 'active')->get();

        Order::factory()
            ->count(10)
            ->create()
            ->each(function ($order) use ($services) {
                $total = 0;

                $selected = $services->random(min($services->count(), rand(1, 3)));

                foreach ($selected as $service) {
                    $qty = rand(1, 2);
                    $price = $service->price;
                    $order->services()->attach($service->id, [
                        'quantity' => $qty,
                        'price' => $price,
                    ]);
                    $total += $qty * $price;
                }

                $order->update(['total_amount' => $total]);

                      

            });
    }
}

