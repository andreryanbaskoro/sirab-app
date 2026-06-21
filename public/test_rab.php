<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$rabs = App\Models\Rab::with('details')->get();
foreach ($rabs as $rab) {
    echo "\nRAB: " . $rab->nomor_rab . " - Details: " . $rab->details->count() . "\n";
    $mats = $rab->details->where('jenis_item', 'material')->whereNull('parent_id');
    echo "Orphan Mats count: " . $mats->count() . "\n";
}


foreach ($pekerjaans as $p) {
    echo "Pek: " . $p->nama_item . " - Children: " . $p->children->count() . "\n";
    foreach ($p->children as $c) {
        echo "  - Mat: " . $c->nama_item . "\n";
    }
}
