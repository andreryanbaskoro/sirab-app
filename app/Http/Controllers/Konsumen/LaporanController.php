<?php

namespace App\Http\Controllers\Konsumen;

use App\Http\Controllers\Controller;
use App\Models\Rab;
use App\Enums\RabStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\LaporanRabExport;

class LaporanController extends Controller
{
    private function getQueryData(Request $request)
    {
        $search = $request->search;
        $status = $request->status;
        $tanggal_dari = $request->tanggal_dari;
        $tanggal_sampai = $request->tanggal_sampai;

        $query = Rab::with(['permintaan.konsumen.profile', 'tukang.profile', 'kontrak'])
            ->whereHas('permintaan', function ($q) {
                $q->where('konsumen_id', Auth::id());
            })
            ->latest();

        if ($search) {
            $query->where('nomor_rab', 'like', "%{$search}%");
        }
        if ($status) {
            $query->where('status', $status);
        }
        if ($tanggal_dari) {
            $query->whereDate('created_at', '>=', $tanggal_dari);
        }
        if ($tanggal_sampai) {
            $query->whereDate('created_at', '<=', $tanggal_sampai);
        }

        return $query;
    }

    public function index(Request $request)
    {
        $query = $this->getQueryData($request);
        $data = $query->paginate(15)->appends($request->all());
        $statuses = RabStatus::cases();

        return view('konsumen.laporan.index', compact('data', 'statuses'));
    }

    public function exportExcel(Request $request)
    {
        $query = $this->getQueryData($request);
        $data = $query->get();
        $filename = 'Laporan_Hasil_RAB_' . date('Ymd_His') . '.xlsx';

        return Excel::download(new LaporanRabExport($data), $filename);
    }

    public function exportPdf(Request $request)
    {
        $query = $this->getQueryData($request);
        $data = $query->get();
        $filename = 'Laporan_Hasil_RAB_' . date('Ymd_His') . '.pdf';
        
        $type = 'rab'; // Untuk view
        $pdf = Pdf::loadView('pdf.laporan.rab', compact('data', 'request', 'type'))
            ->setPaper('a4', 'landscape');

        return $pdf->download($filename);
    }
}
