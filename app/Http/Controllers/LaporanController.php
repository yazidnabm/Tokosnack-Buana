<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    /**
     * ======================
     * HALAMAN LAPORAN
     * ======================
     */
    public function index(Request $request)
    {
        $tanggalMulai = $request->tanggal_mulai;
        $tanggalSelesai = $request->tanggal_selesai;

        // Default tampilkan bulan berjalan
        if (!$tanggalMulai && !$tanggalSelesai) {

            $tanggalMulai = now()
                ->startOfMonth()
                ->format('Y-m-d');

            $tanggalSelesai = now()
                ->endOfMonth()
                ->format('Y-m-d');
        }

        // KAS MASUK
        $kasMasuk = DB::table('kas_masuk')
            ->when($tanggalMulai, fn($q) =>
                $q->whereDate('tanggal', '>=', $tanggalMulai)
            )
            ->when($tanggalSelesai, fn($q) =>
                $q->whereDate('tanggal', '<=', $tanggalSelesai)
            )
            ->orderBy('tanggal', 'asc')
            ->get();

        // KAS KELUAR
        $kasKeluar = DB::table('kas_keluar')
            ->when($tanggalMulai, fn($q) =>
                $q->whereDate('tanggal', '>=', $tanggalMulai)
            )
            ->when($tanggalSelesai, fn($q) =>
                $q->whereDate('tanggal', '<=', $tanggalSelesai)
            )
            ->orderBy('tanggal', 'asc')
            ->get();

        // PERHITUNGAN
        $totalMasuk = $kasMasuk->sum('jumlah');
        $totalKeluar = $kasKeluar->sum('jumlah');
        $saldo = $totalMasuk - $totalKeluar;

        $totalQtyMasuk = $kasMasuk->sum('quantity');
        $totalQtyKeluar = $kasKeluar->sum('quantity');

        return view('laporan.index', compact(
            'kasMasuk',
            'kasKeluar',
            'totalMasuk',
            'totalKeluar',
            'saldo',
            'totalQtyMasuk',
            'totalQtyKeluar',
            'tanggalMulai',
            'tanggalSelesai'
        ));
    }

    /**
     * ======================
     * EXPORT PDF
     * ======================
     */
    public function exportPdf(Request $request)
{
    $tanggalMulai = $request->tanggal_mulai;
    $tanggalSelesai = $request->tanggal_selesai;

    if (!$tanggalMulai && !$tanggalSelesai) {

        $tanggalMulai = now()
            ->startOfMonth()
            ->format('Y-m-d');

        $tanggalSelesai = now()
            ->endOfMonth()
            ->format('Y-m-d');
    }

    // REKAP KAS MASUK
    $kasMasuk = DB::table('kas_masuk')
        ->select(
            'sumber',
            DB::raw('SUM(quantity) as total_qty'),
            DB::raw('SUM(jumlah) as total_jumlah')
        )
        ->when($tanggalMulai, fn($q) =>
            $q->whereDate('tanggal', '>=', $tanggalMulai)
        )
        ->when($tanggalSelesai, fn($q) =>
            $q->whereDate('tanggal', '<=', $tanggalSelesai)
        )
        ->groupBy('sumber')
        ->orderBy('sumber')
        ->get();

    // REKAP KAS KELUAR
    $kasKeluar = DB::table('kas_keluar')
        ->select(
            'tujuan',
            DB::raw('SUM(quantity) as total_qty'),
            DB::raw('SUM(jumlah) as total_jumlah')
        )
        ->when($tanggalMulai, fn($q) =>
            $q->whereDate('tanggal', '>=', $tanggalMulai)
        )
        ->when($tanggalSelesai, fn($q) =>
            $q->whereDate('tanggal', '<=', $tanggalSelesai)
        )
        ->groupBy('tujuan')
        ->orderBy('tujuan')
        ->get();

    $totalMasuk = $kasMasuk->sum('total_jumlah');
    $totalKeluar = $kasKeluar->sum('total_jumlah');
    $saldo = $totalMasuk - $totalKeluar;

    $pdf = Pdf::loadView('laporan.pdf', compact(
        'kasMasuk',
        'kasKeluar',
        'totalMasuk',
        'totalKeluar',
        'saldo',
        'tanggalMulai',
        'tanggalSelesai'
     ))->setPaper('A4', 'portrait');

        return $pdf->download('laporan-arus-kas.pdf');
    }
}