<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$zkts = App\Models\zkt::orderBy('id', 'desc')->take(10)->get();
foreach ($zkts as $z) {
    echo "ID: {$z->id}, NumID: {$z->numid}, Name: {$z->name}, Date: {$z->date}, In: {$z->in}, Out: {$z->out}, Absent: {$z->absent}\n";
}
