<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$kontrak = App\Models\Kontrak::with(['permintaan.tipeRumah', 'konsumen.profile', 'tukang.profile', 'rab'])->first();
if (!$kontrak) {
    die("No kontrak");
}

try {
    $pdf = Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.kontrak', compact('kontrak'))->setPaper('a4', 'portrait');
    $output = $pdf->output();
    file_put_contents('kontrak_test.pdf', $output);
    echo "PDF Generated!";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n" . $e->getTraceAsString();
}
