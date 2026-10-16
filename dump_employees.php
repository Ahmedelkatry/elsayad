<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\employees;

$emps = employees::whereNotNull('free_d')->get();
echo "Employees with free_d not null:\n";
foreach ($emps as $emp) {
    echo "ID: {$emp->id} | Num: {$emp->num} | Name: {$emp->name} | check: {$emp->check} | free_d: " . json_encode($emp->free_d, JSON_UNESCAPED_UNICODE) . "\n";
}
