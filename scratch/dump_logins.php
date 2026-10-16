<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\employees;

// Run within tenancy if applicable, or print all tenants and query their DBs
if (class_exists(\Stancl\Tenancy\Tenancy::class)) {
    foreach (\App\Models\Tenant::all() as $tenant) {
        echo "=== Tenant: {$tenant->id} ===\n";
        $tenant->run(function() {
            $emps = employees::all();
            foreach ($emps as $emp) {
                echo "Num: {$emp->num} | Name: {$emp->name} | Mobile: {$emp->mobile} | Phone Password Exists?: " . ($emp->phone_password ? 'Yes' : 'No') . "\n";
            }
        });
    }
} else {
    $emps = employees::all();
    foreach ($emps as $emp) {
        echo "Num: {$emp->num} | Name: {$emp->name} | Mobile: {$emp->mobile} | Phone Password Exists?: " . ($emp->phone_password ? 'Yes' : 'No') . "\n";
    }
}
