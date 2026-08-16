<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$desas = App\Models\Desa::all();
foreach ($desas as $desa) {
    echo $desa->nama_desa . ': ' . json_encode($desa->additional_data) . "\n";
}
