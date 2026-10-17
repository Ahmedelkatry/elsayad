<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ipzkt;
use App\Http\Controllers\IpzktController;

class SyncZktUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zkt:sync-users {--device= : Specific device IP or name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync pending employees directly to all online ZKTeco devices via IP';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting employee auto-sync to ZKTeco devices...');

        $deviceFilter = $this->option('device');
        $query = ipzkt::query();

        if ($deviceFilter) {
            $query->where('ip', $deviceFilter)->orWhere('name', $deviceFilter);
        }

        $devices = $query->get();
        if ($devices->isEmpty()) {
            $this->error('No devices found to sync.');
            return;
        }

        $totalSynced = 0;
        foreach ($devices as $device) {
            $target = $device->ip ?: $device->name;
            if (!$device->ip) {
                $this->line("Skipping ADMS-only device [{$device->name}] (SN: {$device->sn}). Handled via ADMS queues.");
                continue;
            }

            $this->line("Checking device [{$device->name}] (IP: {$device->ip})...");
            $count = IpzktController::syncPendingEmployeesToDevice($device);

            if ($count > 0) {
                $this->info("✔ Pushed {$count} employee(s) to device [{$device->name}] ({$device->ip})");
                $totalSynced += $count;
            } else {
                $this->line("No pending employees or device currently offline.");
            }
        }

        $this->info("Employee sync finished. Total employees pushed: {$totalSynced}");
    }
}
