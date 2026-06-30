<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KasKeluarController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil Parameter Filter
        $perPage   = $request->get('per_page', 10);
        $startDate = $request->get('start_date');
        $endDate   = $request->get('end_date');

        // 2. Query Dasar untuk Statistik Global (Tanpa Paginate)
        $queryStats = DB::table('kas_keluar');

        if ($startDate && $endDate) {
            $queryStats->whereBetween('tanggal', [$startDate, $endDate]);
        }

        // Hitung Statistik Keseluruhan
        $totalPengeluaranAll = $queryStats->sum('jumlah');
        
        // Total transaksi sesuai filter tanggal
        $totalTransaksi = $queryStats->count();

        // Cari Tujuan Pengeluaran Terbanyak (Alokasi Dana Terbesar)
        $tujuanTerbanyak = DB::table('kas_keluar')
            ->select('tujuan', DB::raw('COUNT(*) as total_transaksi'))
            ->groupBy('tujuan')
            ->orderBy('total_transaksi', 'desc')
            ->first();

        // 3. Query untuk Tabel (Dengan Paginate)
        $queryTable = DB::table('kas_keluar')->orderBy('tanggal', 'desc');

        if ($startDate && $endDate) {
            $queryTable->whereBetween('tanggal', [$startDate, $endDate]);
        }

        $data = $queryTable->paginate($perPage);

        return view('kas_keluar.index', compact(
            'data',
            'totalPengeluaranAll',
            'totalTransaksi',
            'tujuanTerbanyak',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Simpan data kas keluar
     */
    public function store(Request $request)
    {
        $request->validate([
            'tanggal'  => 'required|date',
            'jumlah'   => 'required|numeric|min:1',
            'quantity' => 'required|integer|min:1',
            'tujuan'   => 'required|string|max:255',
        ]);

        DB::table('kas_keluar')->insert([
            'tanggal'    => $request->tanggal,
            'jumlah'     => $request->jumlah,
            'quantity'   => $request->quantity,
            'tujuan'     => $request->tujuan,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()
            ->with('success', 'Data kas keluar berhasil ditambahkan');
    }

    /**
     * Update data kas keluar
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal'  => 'required|date',
            'jumlah'   => 'required|numeric|min:1',
            'quantity' => 'required|integer|min:1',
            'tujuan'   => 'required|string|max:255',
        ]);

        DB::table('kas_keluar')
            ->where('id', $id)
            ->update([
                'tanggal'    => $request->tanggal,
                'jumlah'     => $request->jumlah,
                'quantity'   => $request->quantity,
                'tujuan'     => $request->tujuan,
                'updated_at' => now(),
            ]);

        return redirect()->back()
            ->with('success', 'Data kas keluar berhasil diperbarui');
    }

    /**
     * Hapus data kas keluar
     */
    public function destroy($id)
    {
        DB::table('kas_keluar')
            ->where('id', $id)
            ->delete();

        return redirect()->back()
            ->with('success', 'Data kas keluar berhasil dihapus');
    }
}
