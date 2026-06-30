@php
use Illuminate\Support\Facades\Auth;
@endphp

<!-- Brand (only for navbar-full & xl) -->
@if(isset($navbarFull))
<div class="navbar-brand app-brand demo d-none d-xl-flex py-0 me-4">
    <a href="{{ url('/dashboard') }}" class="app-brand-link gap-2">
        <span class="app-brand-logo demo">@include('_partials.macros')</span>
        <span class="app-brand-text demo menu-text fw-bold text-heading">
            {{ config('variables.templateName') }}
        </span>
    </a>
</div>
@endif

<!-- Menu toggle -->
@if(!isset($navbarHideToggle))
<div class="layout-menu-toggle navbar-nav align-items-xl-center me-4 me-xl-0 {{ isset($contentNavbar) ? 'd-xl-none' : '' }}">
    <a class="nav-item nav-link px-0 me-xl-6" href="javascript:void(0)">
        <i class="icon-base bx bx-menu icon-md"></i>
    </a>
</div>
@endif

<div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">

    <!-- RIGHT NAVBAR -->
    <ul class="navbar-nav flex-row align-items-center ms-auto">

        <!-- USER -->
        @if(Auth::check())
        <li class="nav-item navbar-dropdown dropdown-user dropdown">
            <a class="nav-link dropdown-toggle hide-arrow p-0" href="javascript:void(0);" data-bs-toggle="dropdown">
                <div class="avatar avatar-online">
                    <span class="avatar-initial rounded-circle bg-label-secondary">
                    <i class="bx bx-user fs-4 text-dark"></i>
                </span>
                </div>
            </a>

            <ul class="dropdown-menu dropdown-menu-end">

                <!-- User Info -->
                <li>
                    <a class="dropdown-item" href="javascript:void(0);">
                        <div class="d-flex">
                            <div class="flex-shrink-0 me-3">
                                <div class="avatar avatar-online">
                                    <span class="avatar-initial rounded-circle bg-label-secondary">
                                        <i class="bx bx-user fs-4 text-dark"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0">{{ Auth::user()->name }}</h6>
                                <small class="text-muted">
                                    {{ Auth::user()->role ?? 'User' }}
                                </small>
                            </div>
                        </div>
                    </a>
                </li>

                <li>
                    <div class="dropdown-divider my-1"></div>
                </li>


                <!-- Settings (optional, can be removed later) -->
                <!-- <li>
                    <a class="dropdown-item" href="javascript:void(0);">
                        <i class="icon-base bx bx-cog icon-md me-3"></i>
                        <span>Settings</span>
                    </a>
                </li>

                <li>
                    <div class="dropdown-divider my-1"></div>
                </li> -->

                <!-- Logout -->
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item">
                            <i class="icon-base bx bx-power-off icon-md me-3"></i>
                            <span>Log Out</span>
                        </button>
                    </form>
                </li>

            </ul>
        </li>
        @endif
        <!-- /USER -->

    </ul>
</div>
