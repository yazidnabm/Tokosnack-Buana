@extends('layouts.contentNavbarLayout')

@section('title', 'Laporan Arus Kas')

@section('content')

{{-- ================= HEADER ================= --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">
            <i class="bx bx-line-chart me-1"></i>
            Laporan Arus Kas
        </h4>
        <p class="text-muted mb-0">Ringkasan pemasukan dan pengeluaran</p>
    </div>

    {{-- EXPORT PDF --}}
    <a href="{{ route('laporan.export-pdf', request()->query()) }}"
       class="btn btn-danger"
       target="_blank">
        <i class="bx bxs-file-pdf me-1"></i>
        Export PDF
    </a>
</div>

{{-- ================= FILTER TANGGAL ================= --}}
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('laporan.index') }}" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label">Tanggal Mulai</label>
                <input type="date" name="tanggal_mulai" class="form-control"
                       value="{{ $tanggalMulai }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Tanggal Selesai</label>
                <input type="date" name="tanggal_selesai" class="form-control"
                       value="{{ $tanggalSelesai }}">
            </div>
            <div class="col-md-4">
                <button class="btn btn-primary w-100">
                    <i class="bx bx-filter me-1"></i>
                    Tampilkan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ================= RINGKASAN ================= --}}
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body">
                <span class="fw-semibold">Total Kas Masuk</span>
                <h4 class="text-success mt-2 mb-0">
                    Rp {{ number_format($totalMasuk, 0, ',', '.') }}
                </h4>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body">
                <span class="fw-semibold">Total Kas Keluar</span>
                <h4 class="text-danger mt-2 mb-0">
                    Rp {{ number_format($totalKeluar, 0, ',', '.') }}
                </h4>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body">
                <span class="fw-semibold">Saldo</span>
                <h4 class="text-primary mt-2 mb-0">
                    Rp {{ number_format($saldo, 0, ',', '.') }}
                </h4>
            </div>
        </div>
    </div>
</div>

{{-- ================= TABEL KAS MASUK ================= --}}
<div class="card mb-4">
    <div class="card-body">
        <h5 class="card-title mb-3">
            <i class="bx bx-down-arrow-circle text-success me-1"></i>
            Kas Masuk
        </h5>

        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Tanggal</th>
                        <th>Sumber</th>
                        <th class="text-center">Quantity</th>
                        <th class="text-end">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kasMasuk as $item)
                        <tr>
                            <td>{{ date('d-m-Y', strtotime($item->tanggal)) }}</td>
                            <td>{{ $item->sumber }}</td>
                            <td class="text-center">{{ $item->quantity }}</td>
                            <td class="text-success fw-semibold text-end">
                                Rp {{ number_format($item->jumlah, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">
                                Tidak ada data kas masuk
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ================= TABEL KAS KELUAR ================= --}}
<div class="card">
    <div class="card-body">
        <h5 class="card-title mb-3">
            <i class="bx bx-up-arrow-circle text-danger me-1"></i>
            Kas Keluar
        </h5>

        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Tanggal</th>
                        <th>Tujuan</th>
                        <th class="text-center">Quantity</th>
                        <th class="text-end">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kasKeluar as $item)
                        <tr>
                            <td>{{ date('d-m-Y', strtotime($item->tanggal)) }}</td>
                            <td>{{ $item->tujuan }}</td>
                            <td class="text-center">{{ $item->quantity }}</td>
                            <td class="text-danger fw-semibold text-end">
                                Rp {{ number_format($item->jumlah, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">
                                Tidak ada data kas keluar
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
