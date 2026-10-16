<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\zkt;

$query = zkt::query()
    ->select('zkts.*')
    ->leftJoin('employees', 'zkts.numid', '=', 'employees.num');

$query->where(function ($q) {
    $q->orWhereNull('employees.free_d')
      ->orWhereNotNull('zkts.in')
      ->orWhereNotNull('zkts.out')
      ->orWhereRaw("NOT JSON_CONTAINS(employees.free_d, JSON_QUOTE(
          CASE DAYOFWEEK(zkts.date)
              WHEN 1 THEN 'الأحد'
              WHEN 2 THEN 'الاثنين'
              WHEN 3 THEN 'الثلاثاء'
              WHEN 4 THEN 'الأربعاء'
              WHEN 5 THEN 'الخميس'
              WHEN 6 THEN 'الجمعة'
              WHEN 7 THEN 'السبت'
          END
      ))");
});

echo "SQL: " . $query->toSql() . "\n";
