@auth
<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="{{ url('home') }}">Tunas Jaya</a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="{{ url('home') }}">TJ</a>
        </div>
        <ul class="sidebar-menu">
            <li class="menu-header">Dashboard</li>
            <li class="{{ Request::is('home') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('home') }}"><i class="fas fa-fire"></i><span>Dashboard</span></a>
            </li>
            
            @if (Auth::user()->role == 'superadmin')
            <li class="nav-item dropdown">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
                    <i class="fas fa-user-shield"></i> <span>Management User</span>
                </a>
                <ul class="dropdown-menu">
                    <li class="{{ Request::is('user_management') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ url('user_management') }}">Management User</a>
                    </li>
                </ul>
            </li>
            @endif

            <li class="nav-item dropdown">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
                    <i class="fas fa-user-tie"></i> <span>Vendor</span>
                </a>
                <ul class="dropdown-menu">
                    <li class="{{ Request::is('vendor') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ url('vendor') }}">Vendor</a>
                    </li>
                    <li class="{{ Request::is('pembelian') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ url('pembelian') }}">Pembelian</a>
                    </li>
                    <li class="{{ Request::is('pembelian_detail') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ url('pembelian_detail') }}">Pembelian Detail</a>
                    </li>
                </ul>
            </li>

            <li class="nav-item dropdown">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
                    <i class="fas fa-box"></i> <span>Stock</span>
                </a>
                <ul class="dropdown-menu">
                    <li class="{{ Request::is('stock') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ url('stock') }}">Stock</a>
                    </li>
                    <li class="{{ Request::is('order_motor') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ url('order_motor') }}">Order Motor</a>
                    </li>
                    <li class="{{ Request::is('sold_motor') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ url('sold_motor') }}">Penjualan Motor</a>
                    </li>
                </ul>
            </li>

            <li class="nav-item dropdown">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
                    <i class="fas fa-database"></i> <span>Master Data</span>
                </a>
                <ul class="dropdown-menu">
                    <li class="{{ Request::is('master_motor') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ url('master_motor') }}">Master Motor</a>
                    </li>
                    <li class="{{ Request::is('master_warna') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ url('master_warna') }}">Master Warna</a>
                    </li>
                </ul>
            </li>

            <li class="nav-item dropdown">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
                    <i class="fas fa-chart-bar"></i> <span>Reports</span>
                </a>
                <ul class="dropdown-menu">
                    <li class="{{ Request::is('report/penjualan') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ url('report/penjualan') }}">Laporan Penjualan</a>
                    </li>
                    <li class="{{ Request::is('report/pembelian') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ url('report/pembelian') }}">Laporan Pembelian</a>
                    </li>
                    <li class="{{ Request::is('report/stock') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ url('report/stock') }}">Laporan Stock</a>
                    </li>
                </ul>
            </li>
        </ul>
    </aside>
</div>
@endauth
