<?php

namespace App\Console\Commands;

use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CleanExpiredPayments extends Command
{
    protected $signature = 'payments:clean-expired';
    protected $description = 'Delete expired pending payments and their associated appointments';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->info('Cleaning up expired pending payments...');

        DB::beginTransaction();
        try {
            $expiredPayments = Payment::where('status', 'pending')
                ->whereNotNull('expires_at')
                ->where('expires_at', '<', Carbon::now())
                ->get();

            $count = 0;
            foreach ($expiredPayments as $payment) {
                $payment->delete();
                // Appointment deleted via cascade
                $count++;
                $this->info("Deleted payment ID: {$payment->id}");
            }

            DB::commit();
            $this->info("Successfully deleted {$count} expired payments.");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Failed to clean expired payments: " . $e->getMessage());
            $this->error('An error occurred while cleaning expired payments.');
        }
    }
}
