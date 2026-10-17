<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\ZktController;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\ipzkt;

class SyncZktAttendance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zkt:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch attendance data from all ZKTeco devices and process them automatically';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting ZKTeco attendance and employee sync...');

        $devices = ipzkt::all();
        if ($devices->isEmpty()) {
            $this->error('No ZKTeco devices found in the database. Aborting.');
            return;
        }

        // 1. مزامنة أي موظفين معلقين للأجهزة المتصلة
        foreach ($devices as $device) {
            if ($device->ip) {
                try {
                    $synced = \App\Http\Controllers\IpzktController::syncPendingEmployeesToDevice($device);
                    if ($synced > 0) {
                        $this->info("Successfully pushed {$synced} pending employee(s) to device [{$device->name}] ({$device->ip})");
                    }
                } catch (\Throwable $e) {
                    $this->warn("User sync check skipped for [{$device->name}]: " . $e->getMessage());
                }
            }
        }

        $ips = $devices->pluck('ip')->filter()->toArray();
        if (empty($ips)) {
            $this->info('No IP devices for attendance pull.');
            return;
        }

        // 2. سحب سجلات الحضور والانصراف
        $request = new Request();
        $request->replace([
            'ipz' => $ips,
            'searchs' => Carbon::yesterday()->format('Y-m-d'),
            'searche' => Carbon::today()->format('Y-m-d'),
        ]);

        $controller = new ZktController();
        try {
            $controller->create($request);
            $this->info('ZKTeco attendance sync completed successfully.');
        } catch (\Exception $e) {
            $this->error('An error occurred during attendance sync: ' . $e->getMessage());
        }
    }
}
