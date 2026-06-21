<?php

namespace App\Http\Controllers\Tukang;

use App\Http\Controllers\Controller;
use App\Models\Rab;
use App\Models\Permintaan;
use App\Models\Material;
use App\Models\Pekerjaan;
use App\Models\HargaJasaTukang;
use App\Enums\PermintaanStatus;
use App\Enums\RabStatus;
use App\Services\RabCalculatorService;
use App\Services\KontrakService;
use App\Services\NotificationService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RabController extends Controller
{
    public function __construct(
        private RabCalculatorService $rabService,
        private KontrakService $kontrakService,
        private NotificationService $notifService
    ) {}

    public function index()
    {
        $data = Rab::where('tukang_id', Auth::id())
            ->with(['permintaan.konsumen', 'permintaan.tipeRumah'])
            ->latest()
            ->paginate(10);

        return view('tukang.rab.index', compact('data'));
    }

    public function create(Permintaan $permintaan)
    {
        if ($permintaan->tukang_id !== Auth::id()) abort(403);
        if (!in_array($permintaan->status, [PermintaanStatus::DITERIMA_TUKANG, PermintaanStatus::DISUSUN_RAB])) {
            return back()->with('error', 'Permintaan belum diterima atau tidak valid untuk membuat RAB.');
        }

        $materials = Material::orderBy('nama_material')->get();
        $pekerjaans = Pekerjaan::orderBy('nama_pekerjaan')->get();
        $jasaTukangs = HargaJasaTukang::where('user_id', Auth::id())->latest()->get();
        $existingRab = $permintaan->rab;

        return view('tukang.rab.create', compact('permintaan', 'materials', 'pekerjaans', 'jasaTukangs', 'existingRab'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'permintaan_id' => 'required|exists:permintaans,id',
            'biaya_jasa_tukang' => 'nullable|numeric|min:0',
            'biaya_tambahan' => 'nullable|numeric|min:0',
            'profit_persen' => 'nullable|numeric|min:0|max:100',
            'use_ppn' => 'nullable|boolean',
            'catatan_tukang' => 'nullable|string',
        ]);

        $permintaan = Permintaan::findOrFail($request->permintaan_id);
        if ($permintaan->tukang_id !== Auth::id()) abort(403);

        // Delete existing RAB if editing
        if ($permintaan->rab) {
            $permintaan->rab->details()->delete();
            $permintaan->rab->delete();
        }

        $data = $request->all();
        $data['tukang_id'] = Auth::id();

        // Process orphan materials
        $orphanMaterials = [];
        if ($request->has('materials')) {
            foreach ($request->materials as $m) {
                if (!empty($m['material_id'])) {
                    $material = Material::find($m['material_id']);
                    $orphanMaterials[] = [
                        'material_id' => $m['material_id'],
                        'nama_item' => $material ? $material->nama_material : 'Material',
                        'qty' => (float)($m['qty'] ?? 1),
                        'satuan' => $m['satuan'] ?? 'unit',
                        'harga_satuan' => (float)($m['harga_satuan'] ?? 0),
                    ];
                }
            }
        }

        // Process pekerjaans and their nested materials
        $pekerjaans = [];
        if ($request->has('pekerjaans')) {
            foreach ($request->pekerjaans as $p) {
                if (!empty($p['pekerjaan_id'])) {
                    $pekerjaan = Pekerjaan::find($p['pekerjaan_id']);
                    
                    $childMaterials = [];
                    if (isset($p['materials']) && is_array($p['materials'])) {
                        foreach ($p['materials'] as $cm) {
                            if (!empty($cm['material_id'])) {
                                $childMat = Material::find($cm['material_id']);
                                $childMaterials[] = [
                                    'material_id' => $cm['material_id'],
                                    'nama_item' => $childMat ? $childMat->nama_material : 'Material',
                                    'qty' => (float)($cm['qty'] ?? 1),
                                    'satuan' => $cm['satuan'] ?? 'unit',
                                    'harga_satuan' => (float)($cm['harga_satuan'] ?? 0),
                                ];
                            }
                        }
                    }

                    $pekerjaans[] = [
                        'pekerjaan_id' => $p['pekerjaan_id'],
                        'nama_item' => $pekerjaan ? $pekerjaan->nama_pekerjaan : 'Pekerjaan',
                        'qty' => (float)($p['qty'] ?? 1),
                        'satuan' => $p['satuan'] ?? 'm2',
                        'harga_satuan' => (float)($p['harga_satuan'] ?? 0),
                        'child_materials' => $childMaterials,
                    ];
                }
            }
        }

        $data['orphan_materials'] = $orphanMaterials;
        $data['pekerjaans'] = $pekerjaans;

        $rab = $this->rabService->createRab($data);

        // Update RAB notes
        if ($request->catatan_tukang) {
            $rab->update(['catatan_tukang' => $request->catatan_tukang]);
        }

        $permintaan->update(['status' => PermintaanStatus::DISUSUN_RAB]);

        return redirect()->route('tukang.rab.show', $rab)->with('success', 'RAB berhasil disimpan.');
    }

    public function show(Rab $rab)
    {
        if ($rab->tukang_id !== Auth::id()) abort(403);
        $rab->load(['permintaan.konsumen.profile', 'permintaan.tipeRumah', 'details', 'permintaan.kontrak']);
        return view('tukang.rab.show', compact('rab'));
    }

    public function submit(Rab $rab)
    {
        if ($rab->tukang_id !== Auth::id()) abort(403);

        if ($rab->status !== RabStatus::DRAFT) {
            return back()->with('error', 'RAB sudah disubmit sebelumnya.');
        }

        $this->rabService->submitForApproval($rab);

        try {
            $this->notifService->notifikasiRabMenungguPersetujuan($rab);
        } catch (\Exception $e) {}

        return back()->with('success', 'RAB berhasil disubmit untuk persetujuan konsumen.');
    }

    public function cetakPdf(Rab $rab)
    {
        if ($rab->tukang_id !== Auth::id()) abort(403);
        $rab->load(['permintaan.konsumen.profile', 'permintaan.tukang.profile', 'permintaan.tipeRumah', 'details']);

        $pdf = Pdf::loadView('pdf.rab', compact('rab'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('RAB-' . $rab->nomor_rab . '.pdf');
    }

    public function downloadKontrak(Rab $rab)
    {
        if ($rab->permintaan->tukang_id !== Auth::id()) abort(403);

        $kontrak = $rab->kontrak;
        if (!$kontrak) return back()->with('error', 'Kontrak belum tersedia.');

        $kontrak->load(['permintaan.tipeRumah', 'konsumen.profile', 'tukang.profile', 'rab']);

        $pdf = Pdf::loadView('pdf.kontrak', compact('kontrak'))->setPaper('a4', 'portrait');
        return $pdf->download('Kontrak-' . $kontrak->nomor_kontrak . '.pdf');
    }
}
