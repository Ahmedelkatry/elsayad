<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$ips = App\Models\ipzkt::all();
foreach ($ips as $ip) {
    echo "IP: {$ip->ip}, Port: {$ip->port}\n";
}
