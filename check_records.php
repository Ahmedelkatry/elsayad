<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\zkt;
use Illuminate\Support\Facades\DB;

$allCount = zkt::count();
$fridayCount = zkt::whereRaw("DAYOFWEEK(date) = 6")->count();
$fridayAbsentCount = zkt::whereRaw("DAYOFWEEK(date) = 6")->whereNull('in')->whereNull('out')->count();

echo "All records: $allCount\n";
echo "Friday records: $fridayCount\n";
echo "Friday absent records: $fridayAbsentCount\n";

// Let's see if there are any matching employees for Friday absent records
$joinedFridayAbsentCount = zkt::leftJoin('employees', 'zkts.numid', '=', 'employees.num')
    ->whereRaw("DAYOFWEEK(zkts.date) = 6")
    ->whereNull('zkts.in')
    ->whereNull('zkts.out')
    ->whereNotNull('employees.free_d')
    ->count();

echo "Joined Friday absent records with employees having free_d: $joinedFridayAbsentCount\n";
