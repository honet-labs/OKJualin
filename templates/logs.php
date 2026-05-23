<?php if (!defined('ABSPATH')) { exit; } ?>
<div class="wrpm-wrap">
    <div class="wrpm-header">
        <div>
            <h1>Log Aktivitas Sistem (Audit Trail)</h1>
            <p class="wrpm-subtitle">Daftar pencatatan aktivitas, pembuatan data, pengiriman notifikasi, dan perubahan setting plugin.</p>
        </div>
    </div>

    <div class="wrpm-card wrpm-mt-2">
        <div class="wrpm-card-body">
            <?php if (empty($rows)): ?>
                <div class="wrpm-empty-state">
                    <span class="dashicons dashicons-list-view"></span>
                    <p>Log aktivitas masih kosong.</p>
                </div>
            <?php else: ?>
                <table class="wrpm-table">
                    <thead>
                        <tr>
                            <th>Waktu Kejadian</th>
                            <th>Petugas (User)</th>
                            <th>Aksi</th>
                            <th>Entitas</th>
                            <th>Pesan Deskripsi</th>
                            <th>Alamat IP</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($rows as $r): ?>
                            <tr>
                                <td><span class="dashicons dashicons-clock wrpm-text-muted"></span> <?php echo esc_html($r['happened_at']); ?></td>
                                <td><strong><?php echo esc_html($r['user_login']); ?></strong> <small class="wrpm-text-muted">(ID: <?php echo esc_html($r['user_id']); ?>)</small></td>
                                <td>
                                    <?php if (strpos($r['action'], 'delete') !== false || strpos($r['action'], 'fail') !== false): ?>
                                        <span class="wrpm-badge wrpm-badge-danger"><?php echo esc_html($r['action']); ?></span>
                                    <?php elseif (strpos($r['action'], 'update') !== false || strpos($r['action'], 'save') !== false): ?>
                                        <span class="wrpm-badge wrpm-badge-warning"><?php echo esc_html($r['action']); ?></span>
                                    <?php else: ?>
                                        <span class="wrpm-badge wrpm-badge-success"><?php echo esc_html($r['action']); ?></span>
                                    <?php endif; ?>
                                </td>
                                <td><code><?php echo esc_html($r['entity']); ?></code></td>
                                <td><?php echo esc_html($r['message']); ?></td>
                                <td><code><?php echo esc_html($r['ip'] ?: '-'); ?></code></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</div>
