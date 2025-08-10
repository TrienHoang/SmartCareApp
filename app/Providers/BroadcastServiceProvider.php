<?php

namespace App\Providers;

use Illuminate\Broadcasting\BroadcastServiceProvider as BroadcastingBroadcastServiceProvider;
use Illuminate\Support\Facades\Broadcast;

class BroadcastServiceProvider extends BroadcastingBroadcastServiceProvider
{
    public function boot()
    {
        Broadcast::routes();

        require base_path('routes/channels.php');
    }
}
