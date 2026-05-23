<?php if (!defined('ABSPATH')) { exit; } ?>
<div class="wrpm-wrap">
    <div class="wrpm-header">
        <div>
            <h1>Antrean Pengiriman Reminder</h1>
            <p class="wrpm-subtitle">Daftar jadwal pengiriman reminder otomatis (H-7, H-3, H-1) ke pelanggan beserta log status pengiriman.</p>
        </div>
    </div>

    <div class="wrpm-card wrpm-mt-2">
        <div class="wrpm-card-body">
            <?php if (empty($rows)): ?>
                <div class="wrpm-empty-state">
                    <span class="dashicons dashicons-calendar"></span>
                    <p>Antrean reminder masih kosong. Pastikan Anda telah membuat data pelacakan produk aktif.</p>
                </div>
            <?php else: ?>
                <table class="wrpm-table">
                    <thead>
                        <tr>
                            <th>Customer</th>
                            <th>Produk</th>
                            <th>Milestone</th>
                            <th>Tanggal Kirim</th>
                            <th>Status Kirim</th>
                            <th>Dikirim Melalui</th>
                            <th>Waktu Kirim</th>
                            <th>Keterangan / Error</th>
                            <th>Aksi Manual</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($rows as $r): ?>
                            <tr>
                                <td><strong><?php echo esc_html($r['customer_name']); ?></strong></td>
                                <td><?php echo esc_html($r['product_label']); ?></td>
                                <td>H-<?php echo esc_html($r['offset_days']); ?></td>
                                <td><span class="dashicons dashicons-clock wrpm-text-muted"></span> <?php echo esc_html($r['reminder_date']); ?></td>
                                <td>
                                    <?php if ($r['status'] === 'sent'): ?>
                                        <span class="wrpm-badge wrpm-badge-success">Terkirim</span>
                                    <?php else: ?>
                                        <span class="wrpm-badge wrpm-badge-warning">Pending</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo esc_html($r['sent_via'] ?: '-'); ?></td>
                                <td><?php echo esc_html($r['sent_at'] ?: '-'); ?></td>
                                <td>
                                    <?php if ($r['last_error']): ?>
                                        <small class="wrpm-text-danger"><?php echo esc_html($r['last_error']); ?></small>
                                    <?php else: ?>
                                        <span class="wrpm-text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a class="wrpm-btn wrpm-btn-secondary wrpm-btn-small" href="<?php echo wp_nonce_url(admin_url('admin-post.php?action=wrpm_send_reminder_manual&id=' . $r['id']), 'wrpm_send_reminder_' . $r['id']); ?>" onclick="return confirm('Kirim reminder secara manual sekarang juga?');">
                                        <span class="dashicons dashicons-share-alt2"></span> Trigger Now
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
