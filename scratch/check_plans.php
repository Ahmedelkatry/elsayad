<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$plans = \App\Models\PricingPlan::all(['id', 'name', 'duration_days', 'trial_days']);
foreach ($plans as $p) {
    echo "ID={$p->id} | {$p->name} | duration_days={$p->duration_days} | trial_days={$p->trial_days}\n";
}
