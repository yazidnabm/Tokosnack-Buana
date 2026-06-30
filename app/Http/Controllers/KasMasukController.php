<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KasMasukController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil Parameter Filter
        $perPage   = $request->get('per_page', 10);
        $startDate = $request->get('start_date');
        $endDate   = $request->get('end_date');

        // 2. Query Dasar untuk Statistik (Tanpa Paginate)
        $queryStats = DB::table('kas_masuk');

        if ($startDate && $endDate) {
            $queryStats->whereBetween('tanggal', [$startDate, $endDate]);
        }

        // Hitung Statistik Keseluruhan
        $totalPemasukanAll = $queryStats->sum('jumlah');
        $totalItemAll      = $queryStats->sum('quantity');

        // Cari Produk Terlaris Berdasarkan Total Quantity
        $produkTerlarisQuery = DB::table('kas_masuk')
            ->select(
                'sumber',
                DB::raw('SUM(quantity) as total_qty')
            );

        if ($startDate && $endDate) {
            $produkTerlarisQuery->whereBetween(
                'tanggal',
                [$startDate, $endDate]
            );
        }

        $produkTerlaris = $produkTerlarisQuery
            ->groupBy('sumber')
            ->orderByDesc('total_qty')
            ->first();

        // 3. Query untuk Tabel (Dengan Paginate)
        $queryTable = DB::table('kas_masuk')
            ->orderBy('tanggal', 'desc');

        if ($startDate && $endDate) {
            $queryTable->whereBetween('tanggal', [$startDate, $endDate]);
        }

        $data = $queryTable->paginate($perPage);

        return view('kas_masuk.index', compact(
            'data',
            'totalPemasukanAll',
            'totalItemAll',
            'produkTerlaris',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Simpan data kas masuk
     */
    public function store(Request $request)
    {
        $request->validate([
            'tanggal'  => 'required|date',
            'jumlah'   => 'required|numeric|min:1',
            'sumber'   => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
        ]);

        DB::table('kas_masuk')->insert([
            'tanggal'    => $request->tanggal,
            'jumlah'     => $request->jumlah,
            'sumber'     => $request->sumber,
            'quantity'   => $request->quantity,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()
            ->with('success', 'Data kas masuk berhasil ditambahkan');
    }

    /**
     * Update data kas masuk
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal'  => 'required|date',
            'jumlah'   => 'required|numeric|min:1',
            'sumber'   => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
        ]);

        DB::table('kas_masuk')
            ->where('id', $id)
            ->update([
                'tanggal'    => $request->tanggal,
                'jumlah'     => $request->jumlah,
                'sumber'     => $request->sumber,
                'quantity'   => $request->quantity,
                'updated_at' => now(),
            ]);

        return redirect()->back()
            ->with('success', 'Data kas masuk berhasil diperbarui');
    }

    /**
     * Hapus data kas masuk
     */
    public function destroy($id)
    {
        DB::table('kas_masuk')
            ->where('id', $id)
            ->delete();

        return redirect()->back()
            ->with('success', 'Data kas masuk berhasil dihapus');
    }
}