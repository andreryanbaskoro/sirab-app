<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Permintaan;
use App\Models\Rab;
use App\Models\Kontrak;
use App\Enums\PermintaanStatus;
use App\Enums\RabStatus;
use App\Enums\KontrakStatus;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\LaporanKonsumenExport;
use App\Exports\LaporanKonsultanExport;
use App\Exports\LaporanPermintaanExport;
use App\Exports\LaporanRabExport;
use App\Exports\LaporanKontrakExport;

class LaporanController extends Controller
{
    private function getQueryData($type, Request $request)
    {
        $query = null;
        $search = $request->search;
        $status = $request->status;
        $tanggal_dari = $request->tanggal_dari;
        $tanggal_sampai = $request->tanggal_sampai;

        if ($type === 'konsumen') {
            $query = User::role('konsumen')->with('profile')->latest();
            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhereHas('profile', function($q2) use ($search) {
                          $q2->where('nama_lengkap', 'like', "%{$search}%");
                      });
                });
            }
        } elseif ($type === 'konsultan') {
            $query = User::role('konsultan')->with('profile')->latest();
            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhereHas('profile', function($q2) use ($search) {
                          $q2->where('nama_lengkap', 'like', "%{$search}%");
                      });
                });
            }
        } elseif ($type === 'permintaan') {
            $query = Permintaan::with(['konsumen.profile', 'konsultan.profile', 'tipeRumah'])->latest();
            if ($search) {
                $query->where('nomor_permintaan', 'like', "%{$search}%");
            }
            if ($status) {
                $query->where('status', $status);
            }
        } elseif ($type === 'rab') {
            $query = Rab::with(['permintaan.konsumen.profile', 'konsultan.profile', 'kontrak'])->latest();
            if ($search) {
                $query->where('nomor_rab', 'like', "%{$search}%");
            }
            if ($status) {
                $query->where('status', $status);
            }
        } elseif ($type === 'kontrak') {
            $query = Kontrak::with(['konsumen.profile', 'konsultan.profile', 'rab'])->latest();
            if ($search) {
                $query->where('nomor_kontrak', 'like', "%{$search}%");
            }
            if ($status) {
                $query->where('status', $status);
            }
        }

        if ($query) {
            if ($tanggal_dari) {
                $query->whereDate('created_at', '>=', $tanggal_dari);
            }
            if ($tanggal_sampai) {
                $query->whereDate('created_at', '<=', $tanggal_sampai);
            }
        }

        return $query;
    }

    public function index(Request $request)
    {
        $type = $request->get('type', 'permintaan');
        $query = $this->getQueryData($type, $request);

        if (!$query) {
            abort(404);
        }

        $data = $query->paginate(15)->appends($request->all());

        $statuses = [];
        if ($type === 'permintaan') $statuses = PermintaanStatus::cases();
        if ($type === 'rab') $statuses = RabStatus::cases();
        if ($type === 'kontrak') $statuses = KontrakStatus::cases();

        return view('admin.laporan.index', compact('data', 'type', 'statuses'));
    }

    public function exportExcel(Request $request)
    {
        $type = $request->get('type', 'permintaan');
        $query = $this->getQueryData($type, $request);
        
        if (!$query) {
            return back()->with('error', 'Tipe laporan tidak valid.');
        }

        $data = $query->get();
        $filename = 'Laporan_' . ucfirst($type) . '_' . date('Ymd_His') . '.xlsx';

        switch ($type) {
            case 'konsumen':
                return Excel::download(new LaporanKonsumenExport($data), $filename);
            case 'konsultan':
                return Excel::download(new LaporanKonsultanExport($data), $filename);
            case 'permintaan':
                return Excel::download(new LaporanPermintaanExport($data), $filename);
            case 'rab':
                return Excel::download(new LaporanRabExport($data), $filename);
            case 'kontrak':
                return Excel::download(new LaporanKontrakExport($data), $filename);
        }

        return back()->with('error', 'Export Excel gagal.');
    }

    public function exportPdf(Request $request)
    {
        $type = $request->get('type', 'permintaan');
        $query = $this->getQueryData($type, $request);
        
        if (!$query) {
            return back()->with('error', 'Tipe laporan tidak valid.');
        }

        $data = $query->get();
        $filename = 'Laporan_' . ucfirst($type) . '_' . date('Ymd_His') . '.pdf';

        $pdf = Pdf::loadView('pdf.laporan.' . $type, compact('data', 'request'))
            ->setPaper('a4', 'landscape');

        return $pdf->download($filename);
    }
}
