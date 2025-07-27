@extends('layouts.master')

@section('title')
    Dashboard
@endsection

@section('page-identifier')
    dashboard
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Dashboard</li>
@endsection

@push('css')
<link rel="stylesheet" href="{{ asset('css/dashboard-modern.css') }}">
@endpush

@section('content')
<div class="dashboard-container">
    <!-- Stats Cards Row -->
    <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
            <div class="modern-stats-card variant-blue">
                <div class="stats-icon">
                    <i class="fa fa-cube"></i>
                </div>
                <div class="stats-number">{{ number_format($kategori) }}</div>
                <div class="stats-label">Total Kategori</div>
                <a href="{{ route('kategori.index') }}" class="stats-footer">
                    Lihat Detail <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
            <div class="modern-stats-card variant-green">
                <div class="stats-icon">
                    <i class="fa fa-cubes"></i>
                </div>
                <div class="stats-number">{{ number_format($produk) }}</div>
                <div class="stats-label">Total Produk</div>
                <a href="{{ route('produk.index') }}" class="stats-footer">
                    Lihat Detail <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
            <div class="modern-stats-card variant-orange">
                <div class="stats-icon">
                    <i class="fa fa-id-card"></i>
                </div>
                <div class="stats-number">{{ number_format($member) }}</div>
                <div class="stats-label">Total Member</div>
                <a href="{{ route('member.index') }}" class="stats-footer">
                    Lihat Detail <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
            <div class="modern-stats-card variant-red">
                <div class="stats-icon">
                    <i class="fa fa-truck"></i>
                </div>
                <div class="stats-number">{{ number_format($supplier) }}</div>
                <div class="stats-label">Total Supplier</div>
                <a href="{{ route('supplier.index') }}" class="stats-footer">
                    Lihat Detail <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Monthly Stats -->
    <div class="monthly-stats">
        <div class="stat-item">
            <div class="stat-value positive">Rp {{ number_format($monthly_stats['total_penjualan']) }}</div>
            <div class="stat-label">Penjualan Bulan Ini</div>
        </div>
        <div class="stat-item">
            <div class="stat-value negative">Rp {{ number_format($monthly_stats['total_pembelian']) }}</div>
            <div class="stat-label">Pembelian Bulan Ini</div>
        </div>
        <div class="stat-item">
            <div class="stat-value negative">Rp {{ number_format($monthly_stats['total_pengeluaran']) }}</div>
            <div class="stat-label">Pengeluaran Bulan Ini</div>
        </div>
        <div class="stat-item">
            <div class="stat-value {{ $monthly_stats['profit'] >= 0 ? 'positive' : 'negative' }}">
                Rp {{ number_format($monthly_stats['profit']) }}
            </div>
            <div class="stat-label">Profit Bulan Ini</div>
        </div>
    </div>

    <!-- Main Content Row -->
    <div class="row">
        <!-- Chart Column -->
        <div class="col-lg-8">
            <div class="chart-container">
                <div class="chart-title">
                    📈 Grafik Pendapatan {{ tanggal_indonesia($tanggal_awal, false) }} s/d {{ tanggal_indonesia($tanggal_akhir, false) }}
                </div>
                <canvas id="salesChart" style="height: 300px;"></canvas>
            </div>
        </div>
        
        <!-- Recent Transactions -->
        <div class="col-lg-4">
            <div class="modern-content-card">
                <div class="card-header">
                    <i class="fa fa-clock-o"></i> Transaksi Terbaru
                </div>
                <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                    @if($recent_penjualan->count() > 0)
                        @foreach($recent_penjualan as $penjualan)
                        <div class="transaction-item">
                            <div class="transaction-icon sale">
                                <i class="fa fa-arrow-up"></i>
                            </div>
                            <div class="transaction-details">
                                <div class="transaction-id">Penjualan #{{ $penjualan['id_penjualan'] }}</div>
                                <div class="transaction-info">{{ $penjualan['member_nama'] }} • {{ $penjualan['total_item'] }} item</div>
                            </div>
                            <div class="transaction-amount">
                                <div class="transaction-total">Rp {{ number_format($penjualan['bayar']) }}</div>
                                <div class="transaction-time">{{ $penjualan['created_at'] }}</div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="text-center text-muted">
                            <i class="fa fa-inbox fa-3x"></i>
                            <p>Belum ada transaksi</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Top Products Row -->
    <div class="row">
        <!-- Top 5 Barang Terjual -->
        <div class="col-lg-6">
            <div class="modern-content-card">
                <div class="card-header">
                    <i class="fa fa-trophy"></i> Top 5 Barang Terjual
                </div>
                <div class="card-body">
                    @if($top_penjualan->count() > 0)
                        <table class="top-products-table">
                            <thead>
                                <tr>
                                    <th>Rank</th>
                                    <th>Produk</th>
                                    <th>Terjual</th>
                                    <th>Pendapatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($top_penjualan as $index => $item)
                                <tr>
                                    <td>
                                        <span class="rank-badge rank-{{ $index + 1 }}">{{ $index + 1 }}</span>
                                    </td>
                                    <td>
                                        <strong>{{ $item['nama_produk'] }}</strong><br>
                                        <small class="text-muted">Rp {{ number_format($item['harga_jual']) }}/unit</small>
                                    </td>
                                    <td>
                                        <span class="badge" style="background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%); color: white;">
                                            {{ number_format($item['total_terjual']) }} unit
                                        </span>
                                    </td>
                                    <td>
                                        <strong>Rp {{ number_format($item['total_pendapatan']) }}</strong>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="text-center text-muted">
                            <i class="fa fa-shopping-cart fa-3x"></i>
                            <p>Belum ada data penjualan</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Top 5 Barang Pembelian -->
        <div class="col-lg-6">
            <div class="modern-content-card">
                <div class="card-header">
                    <i class="fa fa-shopping-bag"></i> Top 5 Barang Pembelian
                </div>
                <div class="card-body">
                    @if($top_pembelian->count() > 0)
                        <table class="top-products-table">
                            <thead>
                                <tr>
                                    <th>Rank</th>
                                    <th>Produk</th>
                                    <th>Dibeli</th>
                                    <th>Pengeluaran</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($top_pembelian as $index => $item)
                                <tr>
                                    <td>
                                        <span class="rank-badge rank-{{ $index + 1 }}">{{ $index + 1 }}</span>
                                    </td>
                                    <td>
                                        <strong>{{ $item['nama_produk'] }}</strong><br>
                                        <small class="text-muted">Rp {{ number_format($item['harga_beli']) }}/unit</small>
                                    </td>
                                    <td>
                                        <span class="badge" style="background: linear-gradient(135deg, #FF9800 0%, #F57C00 100%); color: white;">
                                            {{ number_format($item['total_dibeli']) }} unit
                                        </span>
                                    </td>
                                    <td>
                                        <strong>Rp {{ number_format($item['total_pengeluaran']) }}</strong>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="text-center text-muted">
                            <i class="fa fa-truck fa-3x"></i>
                            <p>Belum ada data pembelian</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Purchases -->
    <div class="row">
        <div class="col-lg-12">
            <div class="modern-content-card">
                <div class="card-header">
                    <i class="fa fa-history"></i> Riwayat Pembelian Terbaru
                </div>
                <div class="card-body">
                    <div class="row">
                        @if($recent_pembelian->count() > 0)
                            @foreach($recent_pembelian as $pembelian)
                            <div class="col-lg-4 col-md-6">
                                <div class="transaction-item">
                                    <div class="transaction-icon purchase">
                                        <i class="fa fa-arrow-down"></i>
                                    </div>
                                    <div class="transaction-details">
                                        <div class="transaction-id">Pembelian #{{ $pembelian['id_pembelian'] }}</div>
                                        <div class="transaction-info">{{ $pembelian['total_item'] }} item</div>
                                    </div>
                                    <div class="transaction-amount">
                                        <div class="transaction-total">Rp {{ number_format($pembelian['bayar']) }}</div>
                                        <div class="transaction-time">{{ $pembelian['created_at'] }}</div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        @else
                            <div class="col-lg-12">
                                <div class="text-center text-muted">
                                    <i class="fa fa-inbox fa-3x"></i>
                                    <p>Belum ada data pembelian</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- ChartJS -->
<script src="{{ asset('AdminLTE-2/bower_components/chart.js/Chart.js') }}"></script>
<script>
$(function() {
    // Get context with jQuery - using jQuery's .get() method.
    var salesChartCanvas = $('#salesChart').get(0).getContext('2d');
    // This will get the first returned node in the jQuery collection.
    var salesChart = new Chart(salesChartCanvas);

    var salesChartData = {
        labels: {{ json_encode($data_tanggal) }},
        datasets: [
            {
                label: 'Pendapatan',
                fillColor           : 'rgba(102, 126, 234, 0.2)',
                strokeColor         : 'rgba(102, 126, 234, 1)',
                pointColor          : '#667eea',
                pointStrokeColor    : 'rgba(102, 126, 234, 1)',
                pointHighlightFill  : '#fff',
                pointHighlightStroke: 'rgba(102, 126, 234, 1)',
                data: {{ json_encode($data_pendapatan) }}
            }
        ]
    };

    var salesChartOptions = {
        pointDot : true,
        responsive : true,
        maintainAspectRatio: false,
        scaleGridLineColor : 'rgba(0,0,0,.05)',
        scaleGridLineWidth : 1,
        scaleShowHorizontalLines: true,
        scaleShowVerticalLines: true,
        bezierCurve : true,
        bezierCurveTension : 0.3,
        pointDotRadius : 4,
        pointDotStrokeWidth : 1,
        datasetStroke : true,
        datasetStrokeWidth : 2,
        datasetFill : true,
        animation: true,
        animationSteps: 60,
        animationEasing: "easeOutQuart",
        showTooltips: true,
        tooltipFillColor: "rgba(0,0,0,0.8)",
        tooltipFontFamily: "'Poppins', sans-serif",
        tooltipFontSize: 14,
        tooltipTemplate: "Tanggal <%=label%>: Rp <%=value.toLocaleString()%>"
    };

    salesChart.Line(salesChartData, salesChartOptions);

    // Add loading animation
    $('.modern-stats-card').each(function(index) {
        $(this).css('animation-delay', (index * 0.1) + 's');
        $(this).addClass('animate-in');
    });
});

// Add CSS animation for cards
$('<style>')
    .prop('type', 'text/css')
    .html(`
        .animate-in {
            animation: slideInUp 0.6s ease-out forwards;
            opacity: 0;
            transform: translateY(30px);
        }
        
        @keyframes slideInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    `)
    .appendTo('head');
</script>
@endpush
