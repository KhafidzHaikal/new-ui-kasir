<!-- Mobile Toggle Button -->
<button class="mobile-toggle" id="mobileToggle">
    <i class="fa fa-bars"></i>
</button>

<!-- Sidebar Overlay for Mobile -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar" id="mainSidebar">
    <!-- Close button for mobile -->
    <button class="sidebar-close" id="sidebarClose">
        <i class="fa fa-times"></i>
    </button>
    
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="{{ url(auth()->user()->foto ?? asset('img/user.jpg')) }}" class="img-circle img-profil" alt="User Image">
            </div>
            <div class="pull-left info">
                <p>{{ auth()->user()->name }}</p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu" data-widget="tree">
            <li>
                <a href="{{ route('dashboard') }}">
                    <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                </a>
            </li>
            @if (auth()->user()->level == 3)
                <li class="header">MASTER</li>
                <li>
                    <a href="{{ route('kategori.index') }}">
                        <i class="fa fa-cube"></i> <span>Kategori</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('produk.index') }}">
                        <i class="fa fa-cubes"></i> <span>Produk</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('supplier.index') }}">
                        <i class="fa fa-truck"></i> <span>Supplier</span>
                    </a>
                </li>
            @elseif (auth()->user()->level == 6 || auth()->user()->level == 8)
                <li class="header">MASTER</li>
                <li>
                    <a href="{{ route('produk.index') }}">
                        <i class="fa fa-cubes"></i> <span>Produk</span>
                    </a>
                </li>
                <li class="header">TRANSAKSI</li>
                <li>
                    <a href="{{ route('pengeluaran.index') }}">
                        <i class="fa fa-money"></i> <span>Pengeluaran</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('penjualan.index') }}">
                        <i class="fa fa-upload"></i> <span>Penjualan</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('transaksi.index') }}">
                        <i class="fa fa-shopping-cart"></i> <span>Transaksi Aktif</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('transaksi.baru') }}">
                        <i class="fa fa-cart-arrow-down"></i> <span>Transaksi Baru</span>
                    </a>
                </li>
                <li class="header">REPORT</li>
                <li>
                    <a href="{{ route('laporan.kasir') }}">
                        <i class="fa fa-file-pdf-o"></i> <span>Laporan Kasir</span>
                    </a>
                </li>
            @elseif (auth()->user()->level == 7)
                <li class="header">USP</li>
                <li>
                    <a href="{{ route('simpanan.index') }}">
                        <i class="fa fa-bank"></i> <span>Simpanan</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('simpanan.create') }}">
                        <i class="fa fa-exchange"></i> <span>Transaksi Simpanan</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('pinjaman.index') }}">
                        <i class="fa fa-credit-card"></i> <span>Pinjaman</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('pengambilan.index') }}">
                        <i class="fa fa-hand-paper-o"></i> <span>Pengambilan</span>
                    </a>
                </li>
            @else
                <li class="header">MASTER</li>
                @if (auth()->user()->level == 1)
                    <li>
                        <a href="{{ route('kategori.index') }}">
                            <i class="fa fa-cube"></i> <span>Kategori</span>
                        </a>
                    </li>
                @endif
                <li>
                    <a href="{{ route('produk.index') }}">
                        <i class="fa fa-cubes"></i> <span>Produk</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('member.index') }}">
                        <i class="fa fa-id-card"></i> <span>Member</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('supplier.index') }}">
                        <i class="fa fa-truck"></i> <span>Supplier</span>
                    </a>
                </li>
                <li class="header">TRANSAKSI</li>
                <li>
                    <a href="{{ route('pengeluaran.index') }}">
                        <i class="fa fa-money"></i> <span>Pengeluaran</span>
                    </a>
                </li>
                @if (auth()->user()->level == 4 || auth()->user()->level == 1)
                    <li>
                        <a href="{{ route('jasa.index') }}">
                            <i class="fa fa-handshake-o"></i> <span>Jasa</span>
                        </a>
                    </li>
                @endif
                <li>
                    <a href="{{ route('pembelian.index') }}">
                        <i class="fa fa-download"></i> <span>Pembelian</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('penjualan.index') }}">
                        <i class="fa fa-upload"></i> <span>Penjualan</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('transaksi.index') }}">
                        <i class="fa fa-shopping-cart"></i> <span>Transaksi Aktif</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('transaksi.baru') }}">
                        <i class="fa fa-cart-arrow-down"></i> <span>Transaksi Baru</span>
                    </a>
                </li>
                <li class="header">REPORT</li>
                <li>
                    <a href="{{ route('laporan.index') }}">
                        <i class="fa fa-file-pdf-o"></i> <span>Laporan Umum</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('laporan.kasir') }}">
                        <i class="fa fa-file-pdf-o"></i> <span>Laporan Kasir</span>
                    </a>
                </li>
                <li class="header">SYSTEM</li>
                <li>
                    <a href="{{ route('user.index') }}">
                        <i class="fa fa-users"></i> <span>User</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('setting.index') }}">
                        <i class="fa fa-cogs"></i> <span>Pengaturan</span>
                    </a>
                </li>
            @endif
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>

<!-- Include responsive sidebar JavaScript -->
<script src="{{ asset('js/sidebar-responsive.js') }}"></script>
