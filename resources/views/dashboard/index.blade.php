@extends('layouts.contentNavbarLayout')

@section('title', 'Dashboard')

@section('page-style')
    <style>
        :root {
            --success-gradient: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);
        }
        .welcome-banner {
            background: var(--success-gradient);
            border-radius: 20px;
            padding: 30px;
            color: white;
            position: relative;
            overflow: hidden;
            margin-bottom: 30px;
        }
        .welcome-banner i.bg-icon {
            position: absolute; right: -20px; bottom: -20px;
            font-size: 150px; opacity: 0.1;
        }
        .stat-card { border: none; border-radius: 16px; transition: all 0.3s ease; }
        .stat-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
        .icon-box { width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; }
        .chart-container { position: relative; height: 350px; width: 100%; }
        
        /* Style Tombol Aksi Cepat */
        .btn-quick-action {
            border: 1px solid #eee;
            background: white;
            border-radius: 16px;
            padding: 20px;
            display: flex;
            align-items: center;
            text-decoration: none;
            transition: all 0.3s;
            height: 100%;
        }
        .btn-quick-action:hover {
            border-color: #28a745;
            background: #f8fff9;
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }

        #chartLoader {
            position: absolute; top: 50%; left: 50%;
            transform: translate(-50%, -50%); display: none; z-index: 10;
        }
    </style>
@endsection

@section('content')
    {{-- WELCOME BANNER --}}
    <div class="welcome-banner">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h3 class="fw-bold mb-2 text-white">Dashboard Keuangan 👋</h3>
                <p class="mb-0 opacity-75">Pantau arus kas Toko Snack Bu Ana secara real-time.</p>
            </div>
        </div>
        <i class="bx bx-store bg-icon"></i>
    </div>

    {{-- CARDS STATISTIK --}}
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card stat-card shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <div class="icon-box bg-label-primary me-3"><i class="bx bx-wallet fs-3"></i></div>
                    <div>
                        <small class="text-muted d-block">Total Saldo</small>
                        <h4 class="fw-bold mb-0" id="saldoCard">
                            Rp {{ number_format($saldo, 0, ',', '.') }}
                        </h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <div class="icon-box bg-label-success me-3"><i class="bx bx-trending-up fs-3"></i></div>
                    <div>
                        <small class="text-muted d-block">Pemasukan</small>
                        <h4 class="fw-bold mb-0 text-success" id="masukCard">
                            Rp {{ number_format($totalMasuk, 0, ',', '.') }}
                        </h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <div class="icon-box bg-label-danger me-3"><i class="bx bx-trending-down fs-3"></i></div>
                    <div>
                        <small class="text-muted d-block">Pengeluaran</small>
                        <h4 class="fw-bold mb-0 text-danger" id="keluarCard">
                            Rp {{ number_format($totalKeluar, 0, ',', '.') }}
                        </h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- GRAFIK (Full Width) --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0" style="border-radius: 16px;">
                <div class="card-header bg-transparent py-3 border-0">
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                        <h5 class="fw-bold mb-0">Analisis Arus Kas</h5>
                        <div class="d-flex flex-wrap align-items-center gap-2">
                            <select id="filterType" class="form-select form-select-sm w-auto" onchange="toggleFilterInput()">
                                <option value="mingguan">Mingguan</option>
                                <option value="bulanan">Bulanan</option>
                                <option value="tahunan">Tahunan</option>
                            </select>
                            <input type="month" id="filterMonth" class="form-control form-control-sm w-auto d-none" value="{{ date('Y-m') }}" onchange="loadChartData()">
                            <select id="filterYear" class="form-select form-select-sm w-auto d-none" onchange="loadChartData()">
                                @for($i = date('Y'); $i >= date('Y')-5; $i--)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                            <button class="btn btn-sm btn-primary" onclick="loadChartData()"><i class="bx bx-refresh"></i></button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <div id="chartLoader" class="spinner-border text-primary" role="status"></div>
                        <canvas id="chartUtama"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- AKSI CEPAT (Di bawah grafik, 3 menu utama) --}}
    <h5 class="fw-bold mb-3">Aksi Cepat</h5>
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <a href="/kas-masuk" class="btn-quick-action shadow-sm">
                <div class="icon-box bg-label-success me-3"><i class="bx bx-plus fs-3"></i></div>
                <div>
                    <h6 class="mb-0 fw-bold text-dark">Input Kas Masuk</h6>
                    <small class="text-muted">Catat penjualan snack</small>
                </div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="/kas-keluar" class="btn-quick-action shadow-sm">
                <div class="icon-box bg-label-danger me-3"><i class="bx bx-minus fs-3"></i></div>
                <div>
                    <h6 class="mb-0 fw-bold text-dark">Input Kas Keluar</h6>
                    <small class="text-muted">Catat belanja modal/stok</small>
                </div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="/laporan" class="btn-quick-action shadow-sm">
                <div class="icon-box bg-label-info me-3"><i class="bx bx-file fs-3"></i></div>
                <div>
                    <h6 class="mb-0 fw-bold text-dark">Cetak Laporan</h6>
                    <small class="text-muted">Download PDF / Excel</small>
                </div>
            </a>
        </div>
    </div>

    {{-- Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        let myChart;
        const ctx = document.getElementById('chartUtama').getContext('2d');

        function initChart(labels = [], masuk = [], keluar = []) {
            const config = {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [
                        { label: 'Pemasukan', data: masuk, borderColor: '#28a745', backgroundColor: 'rgba(40, 167, 69, 0.1)', fill: true, tension: 0.4 },
                        { label: 'Pengeluaran', data: keluar, borderColor: '#dc3545', backgroundColor: 'rgba(220, 53, 69, 0.1)', fill: true, tension: 0.4 }
                    ]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    plugins: { legend: { position: 'top', align: 'end' } },
                    scales: { y: { beginAtZero: true, ticks: { callback: (val) => 'Rp ' + val.toLocaleString('id-ID') } } }
                }
            };
            if (myChart) myChart.destroy();
            myChart = new Chart(ctx, config);
        }

        async function loadSummaryData() {

            const type = document.getElementById('filterType').value;
            const month = document.getElementById('filterMonth').value;
            const year = document.getElementById('filterYear').value;

            try {

                const response = await fetch(
                    `/api/dashboard-summary?type=${type}&month=${month}&year=${year}`
                );

                const data = await response.json();

                document.getElementById('saldoCard').innerText =
                    'Rp ' + Number(data.saldo).toLocaleString('id-ID');

                document.getElementById('masukCard').innerText =
                    'Rp ' + Number(data.masuk).toLocaleString('id-ID');

                document.getElementById('keluarCard').innerText =
                    'Rp ' + Number(data.keluar).toLocaleString('id-ID');

            } catch (error) {

                console.error('Gagal mengambil summary:', error);

            }
        }
        function toggleFilterInput() {
            const type = document.getElementById('filterType').value;
            document.getElementById('filterMonth').classList.toggle('d-none', type !== 'bulanan');
            document.getElementById('filterYear').classList.toggle('d-none', type !== 'tahunan');
            loadChartData();
        }

        async function loadChartData() {
            const loader = document.getElementById('chartLoader');
            const type = document.getElementById('filterType').value;
            const month = document.getElementById('filterMonth').value;
            const year = document.getElementById('filterYear').value;
            loader.style.display = 'block';
            try {
                const response = await fetch(`/api/chart-data?type=${type}&month=${month}&year=${year}`);
                const res = await response.json();
                initChart(
                    res.labels,
                    res.masuk,
                    res.keluar
                );
                await loadSummaryData();
            } catch (error) { console.error("Gagal ambil data:", error); } 
            finally { loader.style.display = 'none'; }
        }

        document.addEventListener('DOMContentLoaded', async () => {

            await loadChartData();

            await loadSummaryData();

        });
    </script>
@endsection