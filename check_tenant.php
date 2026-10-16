<?php
// Check tenant DB and migrate location table

require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Get tenant
$tenant = \Stancl\Tenancy\Database\Models\Tenant::find('pixel');
if (!$tenant) {
    echo "Tenant 'pixel' not found!\n";
    exit(1);
}

echo "Found tenant: " . $tenant->id . "\n";

tenancy()->initialize($tenant);

$schema = \Illuminate\Support\Facades\DB::connection('tenant')->getSchemaBuilder();
$tables = $schema->getTableListing();
echo "Tables in pixel DB:\n";
foreach ($tables as $t) {
    echo "  - $t\n";
}

if (!in_array('employee_allowed_locations', $tables)) {
    echo "\nTable employee_allowed_locations NOT FOUND. Running migration...\n";
    \Illuminate\Support\Facades\Artisan::call('tenants:migrate', [
        '--tenants' => ['pixel'],
    ]);
    echo \Illuminate\Support\Facades\Artisan::output();
} else {
    echo "\n[OK] Table employee_allowed_locations EXISTS\n";
}
