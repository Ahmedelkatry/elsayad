<?php
// Cleanup script: delete tenant 'pixel' and drop its database

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Tenant;
use Illuminate\Support\Facades\DB;

$tenantId = 'pixel';
$dbName = 'tenant' . $tenantId;

// 1. Try to delete tenant record
$tenant = Tenant::find($tenantId);
if ($tenant) {
    try {
        $tenant->delete();
        echo "✅ Tenant '$tenantId' record deleted from tenants table.\n";
    } catch (\Exception $e) {
        echo "⚠️ Could not delete tenant record: " . $e->getMessage() . "\n";
    }
} else {
    echo "ℹ️ Tenant '$tenantId' not found in tenants table.\n";
}

// 2. Drop the database if it exists
try {
    DB::statement("DROP DATABASE IF EXISTS `$dbName`");
    echo "✅ Database '$dbName' dropped successfully.\n";
} catch (\Exception $e) {
    echo "⚠️ Could not drop database: " . $e->getMessage() . "\n";
}

echo "\n🎉 Cleanup done. You can now register 'pixel' again.\n";
