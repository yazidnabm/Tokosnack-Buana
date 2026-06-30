@php
use Illuminate\Support\Facades\Route;
@endphp

<style>
    /* Kustomisasi Sidebar */
    #layout-menu {
        background: #ffffff !important;
        border-right: 1px solid rgba(0,0,0,0.05);
    }

    .app-brand {
        padding: 1.5rem 1.5rem !important;
        height: auto !important;
    }

    .app-brand-text {
        line-height: 1.2;
    }

    .menu-inner > .menu-item.active > .menu-link {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%) !important;
        color: #fff !important;
        box-shadow: 0 4px 12px rgba(40, 167, 69, 0.25);
    }

    .menu-item .menu-link {
        border-radius: 10px;
        margin: 0.2rem 1rem;
        padding-top: 0.65rem;
        padding-bottom: 0.65rem;
        transition: all 0.2s ease;
    }

    .menu-item .menu-link i {
        font-size: 1.3rem !important;
        margin-right: 0.8rem;
    }

    .menu-header {
        margin: 1.5rem 0 0.5rem 1.5rem !important;
        opacity: 0.5;
        font-weight: 700 !important;
    }

    /* Efek Hover */
    .menu-item:not(.active) .menu-link:hover {
        background-color: rgba(40, 167, 69, 0.05) !important;
        color: #28a745 !important;
    }

    /* Logo Styling */
    .brand-logo-wrapper {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #28a745, #20c997);
        border-radius: 10px;
        color: white;
        box-shadow: 0 4px 10px rgba(40, 167, 69, 0.2);
    }
</style>

<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">

    <div class="app-brand demo">
        <a href="{{url('/')}}" class="app-brand-link d-flex align-items-center">
            <span class="app-brand-logo demo">
                <div class="brand-logo-wrapper">
                    <i class="bx bx-store fs-3"></i>
                </div>
            </span>
            <span class="app-brand-text demo menu-text fw-bolder ms-3">
                <span class="text-dark" style="font-size: 1.1rem; letter-spacing: -0.5px;">Toko Snack Bu Ana</span>
                <small class="text-muted fw-normal" style="font-size: 0.7rem; letter-spacing: 0.5px; text-transform: uppercase;">Arus Kas Apps</small>
            </span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm d-flex align-items-center justify-content-center"></i>
        </a>
    </div>

    <div class="menu-divider mt-2"></div>
    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-3">
        @foreach ($menuData[0]->menu as $menu)

            @if (isset($menu->menuHeader))
                <li class="menu-header small text-uppercase">
                    <span class="menu-header-text">{{ __($menu->menuHeader) }}</span>
                </li>
            @else

            @php
                $activeClass = null;
                $currentRouteName = Route::currentRouteName();

                if ($currentRouteName === $menu->slug) {
                    $activeClass = 'active';
                }
                elseif (isset($menu->submenu)) {
                    if (gettype($menu->slug) === 'array') {
                        foreach($menu->slug as $slug){
                            if (str_contains($currentRouteName,$slug) and strpos($currentRouteName,$slug) === 0) {
                                $activeClass = 'active open';
                            }
                        }
                    } else {
                        if (str_contains($currentRouteName,$menu->slug) and strpos($currentRouteName,$menu->slug) === 0) {
                            $activeClass = 'active open';
                        }
                    }
                }
            @endphp

            <li class="menu-item {{$activeClass}}">
                <a href="{{ isset($menu->url) ? url($menu->url) : 'javascript:void(0);' }}" 
                   class="{{ isset($menu->submenu) ? 'menu-link menu-toggle' : 'menu-link' }}" 
                   @if (isset($menu->target) && !empty($menu->target)) target="_blank" @endif>
                    
                    @isset($menu->icon)
                        <i class="menu-icon tf-icons {{ $menu->icon }}"></i>
                    @endisset
                    
                    <div class="fw-medium">{{ isset($menu->name) ? __($menu->name) : '' }}</div>
                    
                    @isset($menu->badge)
                        <div class="badge rounded-pill bg-{{ $menu->badge[0] }} ms-auto">{{ $menu->badge[1] }}</div>
                    @endisset
                </a>

                @isset($menu->submenu)
                    @include('layouts.sections.menu.submenu',['menu' => $menu->submenu])
                @endisset
            </li>
            @endif
        @endforeach
    </ul>

</aside>