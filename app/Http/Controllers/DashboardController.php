<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil bulan dan tahun saat ini
        $bulan = now()->month;
        $tahun = now()->year;

        // Statistik Bulan Berjalan
        $totalMasuk = DB::table('kas_masuk')
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->sum('jumlah') ?? 0;

        $totalKeluar = DB::table('kas_keluar')
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->sum('jumlah') ?? 0;

        $saldo = $totalMasuk - $totalKeluar;

        // Total transaksi bulan berjalan
        $totalTransaksi =
            DB::table('kas_masuk')
                ->whereMonth('tanggal', $bulan)
                ->whereYear('tanggal', $tahun)
                ->count()
            +
            DB::table('kas_keluar')
                ->whereMonth('tanggal', $bulan)
                ->whereYear('tanggal', $tahun)
                ->count();

        return view('dashboard.index', compact(
            'saldo',
            'totalMasuk',
            'totalKeluar',
            'totalTransaksi'
        ));
    }

    /**
     * Method Khusus API untuk Filter Grafik
     */
    public function getChartData(Request $request)
    {
        $type = $request->get('type', 'mingguan');
        $labels = [];
        $dataMasuk = [];
        $dataKeluar = [];

        if ($type == 'bulanan') {

            $monthYear = $request->get('month', date('Y-m'));

            $year = date('Y', strtotime($monthYear));
            $month = date('m', strtotime($monthYear));

            $daysInMonth = cal_days_in_month(
                CAL_GREGORIAN,
                $month,
                $year
            );

            for ($i = 1; $i <= $daysInMonth; $i++) {

                $date = $year . '-' . $month . '-' .
                    str_pad($i, 2, '0', STR_PAD_LEFT);

                $labels[] = $i;

                $dataMasuk[] = DB::table('kas_masuk')
                    ->whereDate('tanggal', $date)
                    ->sum('jumlah') ?? 0;

                $dataKeluar[] = DB::table('kas_keluar')
                    ->whereDate('tanggal', $date)
                    ->sum('jumlah') ?? 0;
            }

        } elseif ($type == 'tahunan') {

            $year = $request->get('year', date('Y'));

            $months = [
                'Jan',
                'Feb',
                'Mar',
                'Apr',
                'Mei',
                'Jun',
                'Jul',
                'Agu',
                'Sep',
                'Okt',
                'Nov',
                'Des'
            ];

            foreach ($months as $index => $m) {

                $labels[] = $m;

                $dataMasuk[] = DB::table('kas_masuk')
                    ->whereYear('tanggal', $year)
                    ->whereMonth('tanggal', $index + 1)
                    ->sum('jumlah') ?? 0;

                $dataKeluar[] = DB::table('kas_keluar')
                    ->whereYear('tanggal', $year)
                    ->whereMonth('tanggal', $index + 1)
                    ->sum('jumlah') ?? 0;
            }

        } else {

            for ($i = 6; $i >= 0; $i--) {

                $date = Carbon::now()
                    ->subDays($i)
                    ->format('Y-m-d');

                $labels[] = Carbon::parse($date)
                    ->translatedFormat('d M');

                $dataMasuk[] = DB::table('kas_masuk')
                    ->whereDate('tanggal', $date)
                    ->sum('jumlah') ?? 0;

                $dataKeluar[] = DB::table('kas_keluar')
                    ->whereDate('tanggal', $date)
                    ->sum('jumlah') ?? 0;
            }
        }

        return response()->json([
            'labels' => $labels,
            'masuk'  => $dataMasuk,
            'keluar' => $dataKeluar
        ]);
    }

    /**
     * Summary Card Dashboard
     */
    public function getSummaryData(Request $request)
    {
        $type = $request->get('type', 'mingguan');

        if ($type == 'bulanan') {

            $monthYear = $request->get(
                'month',
                now()->format('Y-m')
            );

            $tahun = date('Y', strtotime($monthYear));
            $bulan = date('m', strtotime($monthYear));

            $totalMasuk = DB::table('kas_masuk')
                ->whereYear('tanggal', $tahun)
                ->whereMonth('tanggal', $bulan)
                ->sum('jumlah');

            $totalKeluar = DB::table('kas_keluar')
                ->whereYear('tanggal', $tahun)
                ->whereMonth('tanggal', $bulan)
                ->sum('jumlah');

        } elseif ($type == 'tahunan') {

            $tahun = $request->get(
                'year',
                now()->year
            );

            $totalMasuk = DB::table('kas_masuk')
                ->whereYear('tanggal', $tahun)
                ->sum('jumlah');

            $totalKeluar = DB::table('kas_keluar')
                ->whereYear('tanggal', $tahun)
                ->sum('jumlah');

        } else {

            $startDate = Carbon::now()
                ->subDays(6)
                ->startOfDay();

            $endDate = Carbon::now()
                ->endOfDay();

            $totalMasuk = DB::table('kas_masuk')
                ->whereBetween('tanggal', [
                    $startDate,
                    $endDate
                ])
                ->sum('jumlah');

            $totalKeluar = DB::table('kas_keluar')
                ->whereBetween('tanggal', [
                    $startDate,
                    $endDate
                ])
                ->sum('jumlah');
        }

        return response()->json([
            'saldo'  => $totalMasuk - $totalKeluar,
            'masuk'  => $totalMasuk,
            'keluar' => $totalKeluar
        ]);
    }
}