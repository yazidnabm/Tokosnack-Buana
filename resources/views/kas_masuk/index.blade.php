@extends('layouts.contentNavbarLayout')

@section('title', 'Kas Masuk')

@section('page-style')
    <style>
        /* Modern Table Styling */
        .table thead th {
            background-color: #f8f9fa;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            font-weight: 700;
            color: #6c757d;
            border-bottom: 2px solid #edf2f9;
        }

        .badge-date {
            background-color: rgba(40, 167, 69, 0.1);
            color: #28a745;
            font-weight: 600;
            padding: 0.5em 0.8em;
            border-radius: 8px;
        }

        .badge-qty {
            background-color: #f1f0f2;
            color: #696cff;
            font-weight: 700;
            padding: 0.4em 0.7em;
            border-radius: 6px;
        }

        .amount-text {
            font-family: 'Public Sans', sans-serif;
            font-size: 0.95rem;
        }

        .btn-action {
            width: 32px;
            height: 32px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            transition: all 0.2s;
        }

        /* Widget Stats */
        .card-widget {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            transition: transform 0.3s;
        }

        .card-widget:hover {
            transform: translateY(-5px);
        }

        /* PREMIUM FORM STYLE */
        .modal-content {
            border: none;
            border-radius: 20px;
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.15);
        }

        .form-label {
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #566a7f;
            margin-bottom: 0.5rem;
        }

        .input-group-merge .input-group-text {
            background-color: #f8f9fa;
            border-right: none;
            color: #a1acb8;
        }

        .input-group-merge .form-control {
            border-left: none;
            padding-left: 0.5rem;
        }

        .form-control:focus {
            border-color: #28a745;
            box-shadow: 0 4px 12px rgba(40, 167, 69, 0.1);
        }

        .bg-label-success {
            background-color: #e8fadf !important;
            color: #71dd37 !important;
        }

        /* Pagination Styling Custom */
        .pagination {
            margin-bottom: 0;
            gap: 5px;
        }

        .page-item.active .page-link {
            background-color: #28a745;
            border-color: #28a745;
        }

        .page-link {
            border-radius: 8px !important;
            color: #28a745;
        }
    </style>
@endsection

@section('content')

    {{-- ================= HEADER & BREADCRUMB ================= --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="/dashboard" class="text-success">Dashboard</a></li>
                    <li class="breadcrumb-item active">Kas Masuk</li>
                </ol>
            </nav>
            <h4 class="fw-bold mb-0">
                <i class="bx bx-down-arrow-circle text-success me-1"></i> Riwayat Kas Masuk
            </h4>
        </div>

        <div class="d-flex gap-2">
            <button class="btn btn-success shadow-sm px-4 fw-bold" data-bs-toggle="modal"
                data-bs-target="#modalTambahKasMasuk">
                <i class="bx bx-plus me-1"></i> TAMBAH TRANSAKSI
            </button>
        </div>
    </div>

    {{-- ================= STATS WIDGETS ================= --}}
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card card-widget shadow-sm border-start border-success border-5">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <small class="text-muted d-block mb-1">Total Pemasukan (Global)</small>
                            <h4 class="fw-bold mb-0 text-success">Rp {{ number_format($totalPemasukanAll, 0, ',', '.') }}
                            </h4>
                        </div>
                        <div class="bg-label-success p-3 rounded-3">
                            <i class="bx bx-money fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-widget shadow-sm border-start border-primary border-5">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <small class="text-muted d-block mb-1">Total Produk Terjual</small>
                            <h4 class="fw-bold mb-0 text-primary">{{ $totalItemAll }} Pcs</h4>
                        </div>
                        <div class="bg-label-primary p-3 rounded-3">
                            <i class="bx bx-package fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-widget shadow-sm border-start border-warning border-5">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <small class="text-muted d-block mb-1">Produk Terlaris</small>
                            <h4 class="fw-bold mb-0 text-warning" style="font-size: 1.1rem;">
                                {{ $produkTerlaris->sumber ?? 'N/A' }}
                                <span class="text-muted"
                                    style="font-size: 0.8rem">
                                    ({{ $produkTerlaris->total_qty ?? 0 }} Pcs)
                                </span>
                            </h4>
                        </div>
                        <div class="bg-label-warning p-3 rounded-3">
                            <i class="bx bx-crown fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ================= TABEL DATA ================= --}}
    <div class="card border-0 shadow-sm" style="border-radius: 15px;">
        <div class="card-header border-0 pt-4 pb-2">
            <form action="{{ route('kas-masuk.index') }}" method="GET" id="filterForm">
                <div class="row g-3">
                    {{-- Filter Per Page --}}
                    <div class="col-md-2">
                        <label class="small text-muted">Tampilkan</label>
                        <select name="per_page" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                        </select>
                    </div>

                    {{-- Filter Date Range --}}
                    <div class="col-md-6">
                        <label class="small text-muted">Rentang Tanggal</label>
                        <div class="input-group input-group-sm">
                            <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
                            <span class="input-group-text">s/d</span>
                            <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
                            <button type="submit" class="btn btn-primary"><i class="bx bx-filter-alt"></i> Filter</button>
                            @if ($startDate || $endDate)
                                <a href="{{ route('kas-masuk.index') }}" class="btn btn-outline-danger"><i
                                        class="bx bx-refresh"></i> Reset</a>
                            @endif
                        </div>
                    </div>

                    {{-- Search --}}
                    <div class="col-md-4 text-end">
                        <label class="small text-muted">Cari Data</label>
                        <div class="input-group input-group-merge input-group-sm">
                            <span class="input-group-text"><i class="bx bx-search"></i></span>
                            <input type="text" id="tableSearch" class="form-control"
                                placeholder="Cari sumber pemasukan...">
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="mainTable">
                    <thead>
                        <tr>
                            <th class="ps-4">No</th>
                            <th>Tanggal</th>
                            <th>Sumber Pemasukan</th>
                            <th class="text-center">Qty</th>
                            <th class="text-end">Jumlah</th>
                            <th class="text-center px-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $item)
                            <tr>
                                <td class="ps-4"><span
                                        class="text-muted fw-bold">{{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}</span>
                                </td>
                                <td>
                                    <span class="badge-date small">
                                        <i class="bx bx-calendar me-1"></i> {{ date('d/m/Y', strtotime($item->tanggal)) }}
                                    </span>
                                </td>
                                <td><span class="fw-bold text-dark">{{ $item->sumber }}</span></td>
                                <td class="text-center"><span class="badge-qty small">{{ $item->quantity }} Pcs</span>
                                </td>
                                <td class="text-end">
                                    <span class="text-success fw-bold amount-text">
                                        + Rp {{ number_format($item->jumlah, 0, ',', '.') }}
                                    </span>
                                </td>
                                <td class="text-center px-4">
                                    <div class="d-flex justify-content-center gap-2">
                                        <button class="btn btn-action btn-outline-secondary" data-bs-toggle="modal"
                                            data-bs-target="#modalEditKasMasuk{{ $item->id }}">
                                            <i class="bx bx-edit-alt"></i>
                                        </button>
                                        <form action="{{ route('kas-masuk.destroy', $item->id) }}" method="POST"
                                            class="d-inline" onsubmit="return confirm('Hapus transaksi ini?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-action btn-outline-danger"><i
                                                    class="bx bx-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                            {{-- MODAL EDIT (Tetap di dalam loop) --}}
                            <div class="modal fade" id="modalEditKasMasuk{{ $item->id }}" tabindex="-1"
                                aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header d-flex align-items-center">
                                            <div class="bg-label-success p-2 rounded-3 me-3"><i
                                                    class="bx bx-edit text-success fs-3"></i></div>
                                            <div>
                                                <h5 class="modal-title fw-bold mb-0">Edit Transaksi</h5>
                                                <small class="text-muted">ID: #{{ $item->id }}</small>
                                            </div>
                                            <button type="button" class="btn-close ms-auto"
                                                data-bs-dismiss="modal"></button>
                                        </div>
                                        <form method="POST" action="{{ route('kas-masuk.update', $item->id) }}">
                                            @csrf @method('PUT')
                                            <div class="modal-body p-4">
                                                <div class="row g-4">
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold">Tanggal</label>
                                                        <input type="date" name="tanggal" class="form-control"
                                                            value="{{ $item->tanggal }}" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold">Quantity</label>
                                                        <input
                                                            type="number"
                                                            name="quantity"
                                                            class="form-control"
                                                            value="{{ $item->quantity }}"
                                                            min="1"
                                                            step="1"
                                                            required
                                                        >
                                                    </div>
                                                    <div class="col-12">
                                                        <label class="form-label fw-bold">Nominal (Rp)</label>
                                                        <input
                                                            type="text"
                                                            class="form-control format-rupiah"
                                                            name="jumlah"
                                                            value="{{ number_format($item->jumlah,0,'','.') }}"
                                                            autocomplete="off"
                                                            inputmode="numeric"
                                                            required
                                                        >
                                                    </div>
                                                    <div class="col-12">
                                                        <label class="form-label fw-bold">Sumber</label>
                                                        <input
                                                            type="text"
                                                            name="sumber"
                                                            class="form-control"
                                                            value="{{ $item->sumber }}"
                                                            list="sumber-list"
                                                            autocomplete="off"
                                                            required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer p-4 pt-0">
                                                <button type="submit"
                                                    class="btn btn-success w-100 py-2 fw-bold shadow-sm">SIMPAN
                                                    PERUBAHAN</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">Belum ada data kas masuk</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Footer Tabel: Info & Pagination --}}
        <div class="card-footer border-0 py-3">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                <small class="text-muted">
                    Menampilkan {{ $data->firstItem() ?? 0 }} sampai {{ $data->lastItem() ?? 0 }} dari
                    {{ $data->total() }} data
                </small>
                <div>
                    {{ $data->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>

    {{-- ================= MODAL TAMBAH ================= --}}
    <div class="modal fade" id="modalTambahKasMasuk" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header d-flex align-items-center">
                    <div class="bg-label-success p-2 rounded-3 me-3"><i class="bx bx-plus-circle text-success fs-3"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold mb-0">Catat Pemasukan</h5>
                        <small class="text-muted">Masukkan rincian penjualan baru</small>
                    </div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('kas-masuk.store') }}">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Tanggal</label>
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text"><i class="bx bx-calendar"></i></span>
                                    <input type="date" name="tanggal" class="form-control"
                                        value="{{ date('Y-m-d') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Quantity</label>
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text"><i class="bx bx-package"></i></span>
                                    <input type="number" name="quantity" class="form-control" placeholder="1"
                                        min="1" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold">Nominal Total (Rp)</label>
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text fw-bold text-success">Rp</span>
                                    <input
                                        type="text"
                                        id="jumlahTambah"
                                        name="jumlah"
                                        class="form-control"
                                        placeholder="Masukkan nominal"
                                        autocomplete="off"
                                        inputmode="numeric"
                                        required
                                    >
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold">Sumber Pemasukan</label>

                                <div class="input-group input-group-merge">
                                    <span class="input-group-text">
                                        <i class="bx bx-shopping-bag"></i>
                                    </span>

                                    <input
                                        type="text"
                                        name="sumber"
                                        class="form-control"
                                        placeholder="Misal: Penjualan Keripik"
                                        list="sumber-list"
                                        autocomplete="off"
                                        required>
                                </div>

                                <datalist id="sumber-list">
                                    @foreach($sumberList as $sumber)
                                        <option value="{{ $sumber }}">
                                    @endforeach
                                </datalist>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer p-4 pt-0">
                        <button type="submit" class="btn btn-success w-100 py-2 fw-bold shadow-sm">TAMBAH
                            TRANSAKSI</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


{{-- ================= TOAST SUCCESS ================= --}}
@if(session('success'))
<div class="position-fixed bottom-0 end-0 p-4" style="z-index: 9999">

    <div
        id="successToast"
        class="toast show border-0 shadow-lg overflow-hidden"
        role="alert"
        style="
            min-width: 340px;
            border-radius: 16px;
            background: linear-gradient(135deg, #28c76f, #20b15a);
            animation: slideInRight 0.5s ease;
        "
    >

        <div class="d-flex align-items-center p-3">

            {{-- ICON --}}
            <div class="me-3">
                <div
                    class="d-flex align-items-center justify-content-center"
                    style="
                        width:48px;
                        height:48px;
                        border-radius:12px;
                        background: rgba(255,255,255,0.2);
                    "
                >
                    <i class="bx bx-check text-white fs-3"></i>
                </div>
            </div>

            {{-- TEXT --}}
            <div class="flex-grow-1">
                <h6 class="text-white fw-bold mb-1">
                    Berhasil
                </h6>

                <small class="text-white">
                    {{ session('success') }}
                </small>
            </div>

            {{-- BUTTON CLOSE --}}
            <button
                type="button"
                class="btn-close btn-close-white ms-3"
                onclick="closeToast()"
            ></button>

        </div>

        {{-- PROGRESS BAR --}}
        <div
            id="toastProgress"
            style="
                height:4px;
                background: rgba(255,255,255,0.7);
                width:100%;
                animation: progressBar 3s linear forwards;
            "
        ></div>

    </div>

</div>
@endif

{{-- ================= TOAST STYLE ================= --}}
<style>

    @keyframes slideInRight {
        from {
            transform: translateX(120%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes slideOutRight {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(120%);
            opacity: 0;
        }
    }

    @keyframes progressBar {
        from {
            width: 100%;
        }
        to {
            width: 0%;
        }
    }

    .toast-hide {
        animation: slideOutRight 0.4s ease forwards;
    }

</style>

{{-- ================= TOAST SCRIPT ================= --}}
<script>

    function formatRibuan(angka) {
    angka = angka.replace(/\D/g, '');
    return angka.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

document.addEventListener("DOMContentLoaded", function () {

    // FORM TAMBAH
    const tambah = document.getElementById("jumlahTambah");

    if (tambah) {

        tambah.addEventListener("input", function () {

            this.value = formatRibuan(this.value);

        });

        if (tambah.form) {

            tambah.form.addEventListener("submit", function () {

                tambah.value = tambah.value.replace(/\./g,'');

            });

        }

    }

    // FORM EDIT
    document.querySelectorAll(".format-rupiah").forEach(function(input){

        input.addEventListener("input", function(){

            this.value = formatRibuan(this.value);

        });

        if(input.form){

            input.form.addEventListener("submit", function(){

                input.value = input.value.replace(/\./g,'');

            });

        }

    });

});

    // Fitur Search Sederhana
    document.getElementById('tableSearch').addEventListener('keyup', function() {

        let value = this.value.toLowerCase();
        let rows = document.querySelectorAll('#mainTable tbody tr');

        rows.forEach(row => {

            let text = row.innerText.toLowerCase();

            row.style.display = text.includes(value) ? '' : 'none';

        });

    });

    function closeToast() {

        const toast = document.getElementById('successToast');

        if (toast) {

            toast.classList.add('toast-hide');

            setTimeout(() => {

                toast.remove();

            }, 400);

        }

    }

    document.addEventListener('DOMContentLoaded', function () {

        const toast = document.getElementById('successToast');

        if (toast) {

            setTimeout(() => {

                closeToast();

            }, 3000);

        }

    });

</script>
@endsection
