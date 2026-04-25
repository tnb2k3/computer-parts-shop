<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Admin Dashboard' ?></title>
    <link rel="stylesheet" href="/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="admin-body">
    <div class="admin-container">
        <aside class="admin-sidebar">
            <h2>Admin Panel</h2>
            <nav class="admin-nav">
                <a href="/admin/dashboard" class="nav-item active">Dashboard</a>
                <a href="/admin/categories" class="nav-item">Danh muc</a>
                <a href="/admin/products" class="nav-item">San pham</a>
                <a href="/admin/orders" class="nav-item">Don hang</a>
                <a href="/admin/users" class="nav-item">Tai khoan</a>
                <a href="/admin/reviews" class="nav-item">Danh gia</a>
                <hr>
                <a href="/" class="nav-item">Ve trang chu</a>
                <a href="/logout" class="nav-item">Dang xuat</a>
            </nav>
        </aside>

        <main class="admin-main">
            <div class="admin-content">
                <h1><?= htmlspecialchars($title ?? 'Dashboard') ?></h1>
                
                <div class="stats-grid">
                    <div class="stat-card">
                        <h3>Tong don hang</h3>
                        <p class="stat-number"><?= $stats['total_orders'] ?? 0 ?></p>
                    </div>
                    <div class="stat-card">
                        <h3>Don cho xu ly</h3>
                        <p class="stat-number"><?= $stats['pending_orders'] ?? 0 ?></p>
                    </div>
                    <div class="stat-card">
                        <h3>Don hoan thanh</h3>
                        <p class="stat-number"><?= $stats['completed_orders'] ?? 0 ?></p>
                    </div>
                    <div class="stat-card">
                        <h3>Doanh thu</h3>
                        <p class="stat-number"><?= number_format($stats['total_revenue'] ?? 0, 0, ',', '.') ?> VND</p>
                    </div>
                </div>

                <div class="charts-section">
                    <div class="chart-card">
                        <h2>Doanh thu theo Danh muc</h2>
                        <div class="chart-container">
                            <canvas id="categoryChart"></canvas>
                        </div>
                        <table class="revenue-table">
                            <thead>
                                <tr>
                                    <th>Danh muc</th>
                                    <th>Doanh thu</th>
                                    <th>So luong</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($revenueByCategory as $cat): ?>
                                <tr>
                                    <td><span class="cat-dot"></span> <?= htmlspecialchars($cat['category_name']) ?></td>
                                    <td class="revenue-amount"><?= number_format($cat['revenue'], 0, ',', '.') ?> VND</td>
                                    <td><?= $cat['quantity'] ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="chart-card">
                        <div class="chart-header">
                            <h2>Doanh thu theo thoi gian</h2>
                            <div class="time-tabs">
                                <a href="?period=day" class="time-tab <?= $timePeriod === 'day' ? 'active' : '' ?>">Ngay</a>
                                <a href="?period=week" class="time-tab <?= $timePeriod === 'week' ? 'active' : '' ?>">Tuan</a>
                                <a href="?period=month" class="time-tab <?= $timePeriod === 'month' ? 'active' : '' ?>">Thang</a>
                                <a href="?period=year" class="time-tab <?= $timePeriod === 'year' ? 'active' : '' ?>">Nam</a>
                            </div>
                        </div>
                        <div class="chart-container chart-container-time">
                            <canvas id="timeChart"></canvas>
                        </div>
                    </div>
                </div>

                <h2>Don hang gan day</h2>
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Khach hang</th>
                            <th>Tong tien</th>
                            <th>Trang thai</th>
                            <th>Ngay dat</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentOrders as $order): ?>
                            <tr>
                                <td>#<?= $order->id ?></td>
                                <td><?= htmlspecialchars($order->customer_name) ?></td>
                                <td><?= $order->getFormattedTotal() ?></td>
                                <td><span class="status-<?= $order->status ?>"><?= $order->getStatusLabel() ?></span></td>
                                <td><?= date('d/m/Y', strtotime($order->created_at)) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <style>
    .charts-section {
        display: grid;
        grid-template-columns: 1fr;
        gap: 2rem;
        margin: 2rem 0;
    }
    .chart-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    .chart-card h2 {
        margin-bottom: 1rem;
        font-size: 1.25rem;
        color: #333;
    }
    .chart-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
        margin-bottom: 1rem;
    }
    .chart-header h2 { margin: 0; }
    .time-tabs {
        display: flex;
        gap: 0.5rem;
        background: #f0f0f0;
        padding: 0.25rem;
        border-radius: 8px;
    }
    .time-tab {
        padding: 0.5rem 1rem;
        text-decoration: none;
        color: #666;
        border-radius: 6px;
        font-size: 0.9rem;
        transition: all 0.2s;
    }
    .time-tab:hover { background: #e0e0e0; }
    .time-tab.active {
        background: #d70018;
        color: white;
    }
    .chart-container {
        height: 300px;
        margin-bottom: 1.5rem;
    }
    .chart-container-time { height: 350px; }
    .revenue-table {
        width: 100%;
        border-collapse: collapse;
    }
    .revenue-table th,
    .revenue-table td {
        padding: 0.75rem;
        text-align: left;
        border-bottom: 1px solid #eee;
    }
    .revenue-table th {
        color: #666;
        font-weight: 600;
        font-size: 0.875rem;
    }
    .revenue-amount {
        color: #d70018;
        font-weight: 600;
    }
    .cat-dot {
        display: inline-block;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: #4a90d9;
        margin-right: 8px;
    }
    .revenue-table tbody tr:nth-child(1) .cat-dot { background: #2563eb; }
    .revenue-table tbody tr:nth-child(2) .cat-dot { background: #64748b; }
    .revenue-table tbody tr:nth-child(3) .cat-dot { background: #a78bfa; }
    .revenue-table tbody tr:nth-child(4) .cat-dot { background: #ca8a04; }
    .revenue-table tbody tr:nth-child(5) .cat-dot { background: #d97706; }
    </style>

    <script>
    const categoryData = <?= json_encode(array_map(fn($c) => [
        'name' => $c['category_name'],
        'revenue' => (float)$c['revenue']
    ], $revenueByCategory)) ?>;

    new Chart(document.getElementById('categoryChart'), {
        type: 'bar',
        data: {
            labels: categoryData.map(c => c.name),
            datasets: [{
                data: categoryData.map(c => c.revenue),
                backgroundColor: ['#2563eb', '#64748b', '#a78bfa', '#ca8a04', '#d97706', '#059669', '#dc2626', '#7c3aed', '#0891b2', '#be123c'],
                borderRadius: 4,
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: (ctx) => new Intl.NumberFormat('vi-VN').format(ctx.raw) + ' VND'
                    }
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    grid: { drawBorder: false },
                    ticks: {
                        callback: (value) => {
                            if (value >= 1000000) return (value / 1000000) + ' Tr';
                            if (value >= 1000) return (value / 1000) + 'K';
                            return value;
                        }
                    }
                },
                y: { grid: { display: false } }
            }
        }
    });

    const timeData = <?= json_encode(array_map(fn($t) => [
        'label' => $t['label'],
        'revenue' => (float)$t['revenue']
    ], $revenueByTime)) ?>;

    new Chart(document.getElementById('timeChart'), {
        type: 'bar',
        data: {
            labels: timeData.map(t => t.label),
            datasets: [{
                data: timeData.map(t => t.revenue),
                backgroundColor: '#4a90d9',
                borderRadius: 4,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: (ctx) => new Intl.NumberFormat('vi-VN').format(ctx.raw) + ' VND'
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { drawBorder: false },
                    ticks: {
                        callback: (value) => {
                            if (value >= 1000000) return (value / 1000000) + ' Tr';
                            if (value >= 1000) return (value / 1000) + 'K';
                            return value;
                        }
                    }
                },
                x: { grid: { display: false } }
            }
        }
    });
    </script>
</body>
</html>
