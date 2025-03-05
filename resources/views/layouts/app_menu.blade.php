<div class="leftside-menu">
    <a href="index.html" class="logo logo-light">
        <span class="logo-lg">
            <img src="{{ $setting ? asset('storage/'.$setting->website_favicon) : asset('assets/images/favicon.ico') }}" alt="logo">
        </span>
        <span class="logo-sm">
            <img src="{{ $setting ? asset('storage/'.$setting->website_favicon) : asset('assets/images/favicon.ico') }}" alt="small logo">
        </span>
    </a>

    <a href="index.html" class="logo logo-dark">
        <span class="logo-lg">
            <img src="{{ $setting ? asset('storage/'.$setting->website_favicon) : asset('assets/images/favicon.ico') }}" alt="dark logo">
        </span>
        <span class="logo-sm">
            <img src="{{ $setting ? asset('storage/'.$setting->website_favicon) : asset('assets/images/favicon.ico') }}" alt="small logo">
        </span>
    </a>

    <div class="button-sm-hover" data-bs-toggle="tooltip" data-bs-placement="right" title="Show Full Sidebar">
        <i class="ri-checkbox-blank-circle-line align-middle"></i>
    </div>

    <div class="button-close-fullsidebar">
        <i class="ri-close-fill align-middle"></i>
    </div>

    <div class="h-100" id="leftside-menu-container" data-simplebar>
        <div class="leftbar-user">
            <a href="pages-profile.html">
                <img src="{{ asset('assets/images/users/avatar-1.jpg') }}" alt="user-image" height="42" class="rounded-circle shadow-sm">
                <span class="leftbar-user-name mt-2">Dominic Keller</span>
            </a>
        </div>

        <ul class="side-nav">
            <li class="side-nav-title">Navigation</li>

            <li class="side-nav-item">
                <a href="{{ route('dashboard') }}" class="side-nav-link">
                    <i class="uil-home-alt"></i>
                    <span> Dashboard </span>
                </a>
            </li>

            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#bukuTamuMenu" aria-expanded="false" aria-controls="bukuTamuMenu" class="side-nav-link">
                    <i class="uil-book-alt"></i>
                    <span> Buku Tamu </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="bukuTamuMenu">
                    <ul class="side-nav-second-level">
                        @if (Auth::user()->role == "superadmin" || Auth::user()->role == "data_entry_officer")    
                            <li>
                                <a href="{{ route('guests.create') }}">Isi Buku Tamu</a>
                            </li>
                        @endif
                        <li>
                            <a href="{{ route('guests.index') }}">List Tamu</a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="side-nav-item">
                <a href="{{ route('history_guests.index') }}" class="side-nav-link">
                    <i class="uil-file-alt"></i>
                    <span> Laporan Buku Tamu </span>
                </a>
            </li>
            <li class="side-nav-item">
                <a href="{{ route('notulensis.index') }}" class="side-nav-link">
                    <i class="uil-file-alt"></i>
                    <span>  Data Notulensi</span>
                </a>
            </li>
            <li class="side-nav-item">
                <a href="{{ route('visitors.index') }}" class="side-nav-link">
                    <i class="uil-file-alt"></i>
                    <span>  Data Pengunjung</span>
                </a>
            </li>
            <li class="side-nav-item">
                <a href="{{ route('companions.index') }}" class="side-nav-link">
                    <i class="uil-file-alt"></i>
                    <span>  Data Dinas</span>
                </a>
            </li>

            @if (Auth::user()->role == "superadmin")
                <li class="side-nav-item">
                    <a href="{{ route('users.index') }}" class="side-nav-link">
                        <i class="uil-user-circle"></i>
                        <span> Data Pengguna </span>
                    </a>
                </li>
                <li class="side-nav-item">
                    <a href="{{ route('website_settings.index') }}" class="side-nav-link">
                        <i class="uil uil-cog"></i>
                        <span> Pengaturan Website </span>
                    </a>
                </li>
            @endif
        </ul>
        <div class="clearfix"></div>
    </div>
</div>
