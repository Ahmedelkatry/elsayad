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
        $this->info('Starting ZKTeco attendance sync...');

        $ips = ipzkt::pluck('ip')->toArray();
        if (empty($ips)) {
            $this->error('No ZKTeco IPs found in the database. Aborting.');
            return;
        }

        // We simulate the HTTP Request to reuse the existing controller logic
        $request = new Request();
        $request->replace([
            'ipz' => $ips,
            // Sync from yesterday to today to ensure no overnight or very early shifts are missed
            'searchs' => Carbon::yesterday()->format('Y-m-d'),
            'searche' => Carbon::today()->format('Y-m-d'),
        ]);

        $controller = new ZktController();
        try {
            $controller->create($request);
            $this->info('ZKTeco attendance sync completed successfully.');
        } catch (\Exception $e) {
            $this->error('An error occurred during sync: ' . $e->getMessage());
        }
    }
}
