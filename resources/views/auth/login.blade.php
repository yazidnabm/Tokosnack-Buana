{{-- resources/views/auth/login.blade.php --}}
@extends('layouts.blankLayout')

@section('title', 'Login - Toko Snack Bu Ana')

@section('page-style')
<style>
    .auth-card {
        border: none;
        border-radius: 15px;
        transition: transform 0.3s ease;
    }
    .auth-card:hover {
        transform: translateY(-5px);
    }
    .icon-wrapper {
        background: linear-gradient(135deg, #28a745, #20c997);
        box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
    }
    .form-control:focus {
        border-color: #28a745;
        box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.15);
    }
    .divider:after, .divider:before {
        content: "";
        flex: 1;
        height: 1px;
        background: #eee;
    }
</style>
@endsection

@section('content')
<div class="container-xxl">
    <div class="authentication-wrapper authentication-basic container-p-y d-flex align-items-center justify-content-center" style="min-height: 100vh;">
        <div class="authentication-inner mx-auto" style="max-width: 400px; width: 100%;">

            <div class="card auth-card shadow-lg">
                <div class="card-body p-sm-5 p-4">

                    <div class="text-center mb-4">
                        <div class="icon-wrapper rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                             style="width:72px; height:72px;">
                            <i class="bx bx-store text-white" style="font-size: 2rem;"></i>
                        </div>
                        <h4 class="fw-bold mb-1 text-dark">Selamat Datang!</h4>
                        <p class="text-muted small">Sistem Informasi Arus Kas <br> <span class="fw-semibold text-success">Toko Snack Bu Ana</span></p>
                    </div>

                    @if(session('error'))
                        <div class="alert alert-danger d-flex align-items-center py-2 mb-4" role="alert">
                            <i class="bx bx-error-circle me-2"></i>
                            <div>{{ session('error') }}</div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}" class="mb-3">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-semibold" for="email">Email</label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="bx bx-envelope"></i></span>
                                <input type="email" id="email" name="email" class="form-control" placeholder="Masukkan Email" required autofocus>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <label class="form-label fw-semibold" for="password">Password</label>
                            </div>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="bx bx-lock-alt"></i></span>
                                <input type="password" id="password" name="password" class="form-control" placeholder="Masukkan Password" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="remember-me">
                                <label class="form-check-label small" for="remember-me"> Ingat Saya </label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-success w-100 py-2 shadow-sm mb-3">
                            <i class="bx bx-log-in-circle me-1"></i> Masuk ke Dashboard
                        </button>
                    </form>
                 
                </div>
            </div>

            <p class="text-center small text-muted mt-4">
                &copy; {{ date('Y') }} <strong>Toko Snack Bu Ana</strong>. <br>
                Crafted by Yazid Nabil Mubarok for better management.
            </p>

        </div>
    </div>
</div>
@endsection