<nav class="navbar">
    <div class="navbar-container">
        <div class="navbar-brand">
            <a href="@isAdmin {{ route('admin.dashboard') }}@elseisUser {{ route('dashboard') }}@endisUser" class="navbar-logo">
                <span class="navbar-logo-icon">💰</span>
                Kas Kelas
            </a>
        </div>

        <ul class="navbar-menu">
            {{-- Admin Menu --}}
            @admin
                <li><a href="{{ route('admin.dashboard') }}" class="nav-link @if(request()->routeIs('admin.dashboard')) active @endif">📊 Dashboard</a></li>
                <li><a href="{{ route('admin.data-kas') }}" class="nav-link @if(request()->routeIs('admin.data-kas')) active @endif">💰 Data Kas</a></li>
                <li><a href="{{ route('admin.users') }}" class="nav-link @if(request()->routeIs('admin.users')) active @endif">👥 Users</a></li>
                <li><a href="{{ route('admin.history') }}" class="nav-link @if(request()->routeIs('admin.history')) active @endif">📋 History</a></li>
            @endadmin

            {{-- User Menu --}}
            @user
                <li><a href="{{ route('dashboard') }}" class="nav-link @if(request()->routeIs('dashboard')) active @endif">📊 Dashboard</a></li>
                <li><a href="{{ route('riwayat') }}" class="nav-link @if(request()->routeIs('riwayat')) active @endif">📜 Riwayat</a></li>
            @enduser

            {{-- Logout (All Authenticated Users) --}}
            <li class="nav-item-user">
                <span class="user-name">{{ auth()->user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-logout">Logout</button>
                </form>
            </li>
        </ul>
    </div>
</nav>
