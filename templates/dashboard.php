<?php if (!defined('ABSPATH')) { exit; } ?>
<div class="wrpm-wrap">
    <div class="wrpm-header">
        <div>
            <h1>WP Reseller Manage Dashboard</h1>
            <p class="wrpm-subtitle">Ikhtisar performa bisnis dan pelacakan reminder produk aktif.</p>
        </div>
        <div class="wrpm-actions">
            <a class="wrpm-btn wrpm-btn-primary" href="<?php echo admin_url('admin.php?page=wrpm-active-products&action=add'); ?>">
                <span class="dashicons dashicons-plus"></span> Produk Aktif Baru
            </a>
        </div>
    </div>

    <!-- KPIs Widgets Grid -->
    <div class="wrpm-grid wrpm-grid-4">
        <div class="wrpm-card wrpm-kpi-card">
            <div class="wrpm-kpi-icon"><span class="dashicons dashicons-cart"></span></div>
            <div class="wrpm-kpi-content">
                <span class="wrpm-kpi-label">Produk Reseller</span>
                <h3 class="wrpm-kpi-value"><?php echo number_format_i18n($total_reseller); ?></h3>
            </div>
        </div>
        <div class="wrpm-card wrpm-kpi-card wrpm-kpi-active">
            <div class="wrpm-kpi-icon"><span class="dashicons dashicons-yes-alt"></span></div>
            <div class="wrpm-kpi-content">
                <span class="wrpm-kpi-label">Produk Aktif</span>
                <h3 class="wrpm-kpi-value"><?php echo number_format_i18n($total_active); ?></h3>
            </div>
        </div>
        <div class="wrpm-card wrpm-kpi-card wrpm-kpi-expired">
            <div class="wrpm-kpi-icon"><span class="dashicons dashicons-warning"></span></div>
            <div class="wrpm-kpi-content">
                <span class="wrpm-kpi-label">Expired</span>
                <h3 class="wrpm-kpi-value"><?php echo number_format_i18n($total_expired); ?></h3>
            </div>
        </div>
        <div class="wrpm-card wrpm-kpi-card wrpm-kpi-income">
            <div class="wrpm-kpi-icon"><span class="dashicons dashicons-chart-line"></span></div>
            <div class="wrpm-kpi-content">
                <span class="wrpm-kpi-label">Total Pendapatan</span>
                <h3 class="wrpm-kpi-value">Rp <?php echo number_format_i18n($total_income, 0); ?></h3>
            </div>
        </div>
    </div>

    <!-- Chart Visualization Grid -->
    <div class="wrpm-grid wrpm-grid-2 wrpm-mt-2">
        <div class="wrpm-card">
            <div class="wrpm-card-header">
                <h2>Tren Pendapatan Bulanan</h2>
            </div>
            <div class="wrpm-card-body">
                <canvas id="wrpmRevenueChart" height="260"></canvas>
            </div>
        </div>
        <div class="wrpm-card">
            <div class="wrpm-card-header">
                <h2>Distribusi Status Produk</h2>
            </div>
            <div class="wrpm-card-body wrpm-center-chart">
                <canvas id="wrpmStatusChart" height="260" style="max-width: 260px;"></canvas>
            </div>
        </div>
    </div>

    <!-- Expiring Soon Tracker -->
    <div class="wrpm-card wrpm-mt-2">
        <div class="wrpm-card-header">
            <h2>Masa Aktif Akan Berakhir (7 Hari ke Depan)</h2>
        </div>
        <div class="wrpm-card-body">
            <?php if (empty($soon)): ?>
                <div class="wrpm-empty-state">
                    <span class="dashicons dashicons-info"></span>
                    <p>Semua produk aktif dalam kondisi aman. Tidak ada yang akan expired dalam 7 hari.</p>
                </div>
            <?php else: ?>
                <table class="wrpm-table">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>Customer</th>
                            <th>Tanggal Expired</th>
                            <th>Sisa Waktu</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($soon as $r): 
                            $diff = (int)floor((strtotime($r['expires_at']) - strtotime($today)) / DAY_IN_SECONDS);
                        ?>
                            <tr>
                                <td><strong><?php echo esc_html($r['product_label']); ?></strong></td>
                                <td><?php echo esc_html($r['customer_name']); ?></td>
                                <td><span class="dashicons dashicons-calendar-alt wrpm-text-muted"></span> <?php echo esc_html($r['expires_at']); ?></td>
                                <td>
                                    <?php if ($diff <= 1): ?>
                                        <span class="wrpm-badge wrpm-badge-danger"><?php echo $diff; ?> Hari Lagi</span>
                                    <?php elseif ($diff <= 3): ?>
                                        <span class="wrpm-badge wrpm-badge-warning"><?php echo $diff; ?> Hari Lagi</span>
                                    <?php else: ?>
                                        <span class="wrpm-badge wrpm-badge-success"><?php echo $diff; ?> Hari Lagi</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a class="wrpm-btn-link" href="<?php echo admin_url('admin.php?page=wrpm-active-products&action=edit&id=' . $r['id']); ?>">
                                        <span class="dashicons dashicons-edit"></span> Kelola
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
$labels = [];
$values = [];
foreach ($revenue_monthly as $rm) {
    $labels[] = $rm['label'];
    $values[] = $rm['revenue'];
}
?>

<script>
jQuery(document).ready(function($) {
    // Revenue Chart
    var revCanvas = document.getElementById('wrpmRevenueChart');
    if (revCanvas) {
        new Chart(revCanvas.getContext('2d'), {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($labels); ?>,
                datasets: [{
                    label: 'Pendapatan (IDR)',
                    data: <?php echo json_encode($values); ?>,
                    backgroundColor: 'rgba(99, 102, 241, 0.85)',
                    borderColor: 'rgba(99, 102, 241, 1)',
                    borderWidth: 1.5,
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                plugins: {
                    legend: { display: false }
                }
            }
        });
    }

    // Status distribution
    var statusCanvas = document.getElementById('wrpmStatusChart');
    if (statusCanvas) {
        new Chart(statusCanvas.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ['Aktif', 'Expired'],
                datasets: [{
                    data: [<?php echo $total_active; ?>, <?php echo $total_expired; ?>],
                    backgroundColor: ['rgba(34, 197, 94, 0.85)', 'rgba(239, 68, 68, 0.85)'],
                    borderColor: ['#22c55e', '#ef4444'],
                    borderWidth: 1.5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
    }
});
</script>
