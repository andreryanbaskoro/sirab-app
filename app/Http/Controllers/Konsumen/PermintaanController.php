<?php

namespace App\Http\Controllers\Konsumen;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\TipeRumah;
use App\Models\Permintaan;
use App\Enums\PermintaanStatus;
use App\Enums\RabStatus;
use App\Services\KontrakService;
use App\Services\NotificationService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PermintaanController extends Controller
{
    public function __construct(
        private KontrakService $kontrakService,
        private NotificationService $notifService
    ) {}

    public function index(Request $request)
    {
        $query = Permintaan::forKonsumen(Auth::id())
            ->with(['tukang.profile', 'tipeRumah', 'rab', 'kontrak'])
            ->latest();

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $data = $query->paginate(10)->appends($request->all());
        $statuses = PermintaanStatus::cases();

        return view('konsumen.permintaan.index', compact('data', 'statuses'));
    }

    public function create()
    {
        $tukangs = User::role('kepala_tukang')->with('profile')->aktif()->get();
        $tipeRumahs = TipeRumah::orderBy('nama_tipe')->get();
        return view('konsumen.permintaan.create', compact('tukangs', 'tipeRumahs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tukang_id' => 'required|exists:users,id',
            'tipe_rumah_id' => 'required|exists:tipe_rumahs,id',
            'lokasi_proyek' => 'required|string|max:255',
            'luas_bangunan' => 'required|numeric|min:1',
            'jenis_jasa' => 'required|in:harian,borongan',
            'catatan' => 'nullable|string',
            'dokumen' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',
        ]);

        $dokumenPath = null;
        if ($request->hasFile('dokumen')) {
            $dokumenPath = $request->file('dokumen')->store('dokumen-permintaan', 'public');
        }

        $permintaan = Permintaan::create([
            'konsumen_id' => Auth::id(),
            'tukang_id' => $request->tukang_id,
            'tipe_rumah_id' => $request->tipe_rumah_id,
            'lokasi_proyek' => $request->lokasi_proyek,
            'luas_bangunan' => $request->luas_bangunan,
            'jenis_jasa' => $request->jenis_jasa,
            'catatan' => $request->catatan,
            'dokumen_path' => $dokumenPath,
            'status' => PermintaanStatus::PENDING,
            'tanggal_permohonan' => now()->toDateString(),
        ]);

        try {
            $this->notifService->notifikasiPermintaanBaru($permintaan);
        } catch (\Exception $e) {}

        return redirect()->route('konsumen.permintaan.show', $permintaan)
            ->with('success', 'Permintaan RAB berhasil dibuat dengan nomor: ' . $permintaan->nomor_permintaan);
    }

    public function show(Permintaan $permintaan)
    {
        if ($permintaan->konsumen_id !== Auth::id()) abort(403);
        $permintaan->load(['tukang.profile', 'tipeRumah', 'rab.details', 'kontrak', 'validasis']);
        return view('konsumen.permintaan.show', compact('permintaan'));
    }

    // Moved to PembiayaanController: setujui, tolak, downloadRab, downloadKontrak

    public function cariTukang()
    {
        $tukangs = User::role('kepala_tukang')
            ->with(['profile', 'hargaJasaTukangs'])
            ->aktif()
            ->get();

        return view('konsumen.cari-tukang.index', compact('tukangs'));
    }
}
