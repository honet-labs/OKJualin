<?php if (!defined('ABSPATH')) { exit; } ?>
<div class="wrpm-wrap">
    <div class="wrpm-header">
        <div>
            <h1>Laporan Keuangan & Penjualan</h1>
            <p class="wrpm-subtitle">Unduh rekapitulasi data penjualan bulanan untuk pelaporan pajak dan analisis performa keuangan.</p>
        </div>
    </div>

    <div class="wrpm-grid wrpm-grid-3 wrpm-mt-2">
        <!-- Quick stats card -->
        <div class="wrpm-card">
            <div class="wrpm-card-header">
                <h2>Total Volume Penjualan</h2>
            </div>
            <div class="wrpm-card-body">
                <?php
                $total_items = count($sales);
                $total_amount = 0;
                foreach ($sales as $s) {
                    $total_amount += (float)$s['price'];
                }
                ?>
                <h3 class="wrpm-text-indigo" style="font-size: 2.2rem; margin: 10px 0;"><?php echo $total_items; ?> Layanan</h3>
                <p class="wrpm-text-muted">Total transaksi layanan dengan status lunas (Paid).</p>
            </div>
        </div>

        <div class="wrpm-card">
            <div class="wrpm-card-header">
                <h2>Total Omset Bersih</h2>
            </div>
            <div class="wrpm-card-body">
                <h3 class="wrpm-text-success" style="font-size: 2.2rem; margin: 10px 0;">Rp <?php echo number_format_i18n($total_amount, 0); ?></h3>
                <p class="wrpm-text-muted">Omset bersih terakumulasi dari seluruh penjualan customer.</p>
            </div>
        </div>

        <!-- Export Monthly Report Widget -->
        <div class="wrpm-card">
            <div class="wrpm-card-header">
                <h2>Unduh Rekap Laporan Bulanan (PDF)</h2>
            </div>
            <div class="wrpm-card-body">
                <form method="get" action="<?php echo admin_url('admin-post.php'); ?>" target="_blank">
                    <input type="hidden" name="action" value="wrpm_monthly_report_pdf" />
                    <div class="wrpm-form-group">
                        <label class="wrpm-label">Pilih Bulan</label>
                        <select name="ym" class="wrpm-select" required>
                            <?php
                            $months = [];
                            foreach ($sales as $s) {
                                if ($s['start_date']) {
                                    $m = date('Y-m', strtotime($s['start_date']));
                                    $months[$m] = date('F Y', strtotime($s['start_date']));
                                }
                            }
                            krsort($months);
                            if (empty($months)) {
                                $curr = date('Y-m');
                                $months[$curr] = date('F Y');
                            }
                            foreach ($months as $k => $v):
                            ?>
                                <option value="<?php echo esc_attr($k); ?>"><?php echo esc_html($v); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="wrpm-form-actions wrpm-mt-1">
                        <button type="submit" class="wrpm-btn wrpm-btn-primary" style="width: 100%;">
                            <span class="dashicons dashicons-pdf"></span> Unduh Laporan PDF
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
