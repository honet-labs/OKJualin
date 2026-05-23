<?php if (!defined('ABSPATH')) { exit; } ?>
<div class="wrpm-wrap">
    <?php if ($action === 'add' || $action === 'edit'): ?>
        <!-- Add / Edit Page -->
        <div class="wrpm-header">
            <div>
                <h1><?php echo $action === 'edit' ? 'Edit Shortlink Affiliate' : 'Tambah Shortlink Affiliate'; ?></h1>
                <p class="wrpm-subtitle">Bungkus link affiliate Anda ke dalam shortlink domain Anda sendiri.</p>
            </div>
            <div class="wrpm-actions">
                <a class="wrpm-btn wrpm-btn-secondary" href="<?php echo admin_url('admin.php?page=wrpm-shortlinks'); ?>">Kembali</a>
            </div>
        </div>

        <div class="wrpm-card wrpm-mt-2">
            <div class="wrpm-card-body">
                <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
                    <?php wp_nonce_field('wrpm_save_shortlink'); ?>
                    <input type="hidden" name="action" value="wrpm_save_shortlink" />
                    <?php if ($row): ?>
                        <input type="hidden" name="id" value="<?php echo esc_attr($row['id']); ?>" />
                    <?php endif; ?>

                    <div class="wrpm-form-grid">
                        <div class="wrpm-form-group">
                            <label class="wrpm-label">Judul / Nama Shortlink <span class="wrpm-required">*</span></label>
                            <input type="text" name="title" class="wrpm-input" value="<?php echo $row ? esc_attr($row['title']) : ''; ?>" placeholder="Contoh: Affiliate VPS Niagahoster" required />
                        </div>

                        <div class="wrpm-form-group">
                            <label class="wrpm-label">Key Shortlink (Slug) <span class="wrpm-required">*</span></label>
                            <input type="text" id="wrpm-short-key" name="short_key" class="wrpm-input" value="<?php echo $row ? esc_attr($row['short_key']) : ''; ?>" placeholder="Contoh: vps-promo" required />
                            <small class="wrpm-text-muted" style="display: block; margin-top: 4px;">
                                Link Anda nantinya: <code id="wrpm-shortlink-preview"><?php echo esc_url(home_url('/go/')); ?><span id="wrpm-preview-key"><?php echo $row ? esc_html($row['short_key']) : 'vps-promo'; ?></span></code>
                            </small>
                        </div>
                    </div>

                    <div class="wrpm-form-group wrpm-mt-1">
                        <label class="wrpm-label">URL Tujuan (Destination URL) <span class="wrpm-required">*</span></label>
                        <input type="url" name="destination_url" class="wrpm-input" value="<?php echo $row ? esc_url($row['destination_url']) : ''; ?>" placeholder="https://referral-link.com/id=123" required />
                    </div>

                    <div class="wrpm-form-actions wrpm-mt-2">
                        <button type="submit" class="wrpm-btn wrpm-btn-primary">Simpan Shortlink</button>
                    </div>
                </form>
            </div>
        </div>

        <script>
        jQuery(document).ready(function($) {
            $('#wrpm-short-key').on('input', function() {
                var val = $(this).val().toLowerCase().replace(/[^a-z0-9-_]/g, '-').replace(/-+/g, '-');
                $(this).val(val);
                $('#wrpm-preview-key').text(val ? val : 'vps-promo');
            });
        });
        </script>

    <?php else: ?>
        <!-- List Page -->
        <div class="wrpm-header">
            <div>
                <h1>Daftar Shortlink Affiliate</h1>
                <p class="wrpm-subtitle">Bungkus dan lacak performa klik dari link affiliate/share Anda.</p>
            </div>
            <div class="wrpm-actions">
                <a class="wrpm-btn wrpm-btn-primary" href="<?php echo admin_url('admin.php?page=wrpm-shortlinks&action=add'); ?>">
                    <span class="dashicons dashicons-plus"></span> Tambah Baru
                </a>
            </div>
        </div>

        <div class="wrpm-card wrpm-mt-2">
            <div class="wrpm-card-body">
                <?php if (empty($rows)): ?>
                    <div class="wrpm-empty-state">
                        <span class="dashicons dashicons-admin-links" style="font-size: 3rem; width: 3rem; height: 3rem; color: #cbd5e1; margin-bottom: 12px;"></span>
                        <p>Belum ada shortlink yang dibuat. Mulai dengan membuat shortlink baru atau tambahkan link affiliate di Daftar Harga Produk.</p>
                    </div>
                <?php else: ?>
                    <div style="display: flex; justify-content: flex-end; margin-bottom: 16px;">
                        <input type="text" class="wrpm-input wrpm-table-search" placeholder="Cari shortlink..." style="max-width: 300px; width: 100%;" />
                    </div>
                    <table class="wrpm-table">
                        <thead>
                            <tr>
                                <th>Judul</th>
                                <th>Shortlink (Klik untuk Menyalin)</th>
                                <th>URL Tujuan</th>
                                <th>Jumlah Klik</th>
                                <th>Tanggal Dibuat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($rows as $r): 
                                $short_url = home_url('/go/' . $r['short_key']);
                            ?>
                                <tr>
                                    <td><strong><?php echo esc_html($r['title']); ?></strong></td>
                                    <td>
                                        <div style="display: flex; align-items: center; gap: 8px;">
                                            <code style="font-size: 13px; color: #4f46e5; background: #e0e7ff; padding: 4px 8px; border-radius: 4px; border: 1px solid #c7d2fe;"><?php echo esc_html($short_url); ?></code>
                                            <button class="wrpm-btn wrpm-btn-secondary wrpm-btn-small wrpm-copy-link-btn" data-link="<?php echo esc_attr($short_url); ?>" title="Salin Shortlink">
                                                <span class="dashicons dashicons-admin-page" style="font-size: 14px; width: 14px; height: 14px;"></span>
                                            </button>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="<?php echo esc_url($r['destination_url']); ?>" target="_blank" class="wrpm-text-muted" style="text-decoration: none; max-width: 250px; display: inline-block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                            <?php echo esc_html($r['destination_url']); ?>
                                        </a>
                                    </td>
                                    <td>
                                        <span class="wrpm-badge wrpm-badge-success" style="font-size: 12px; font-weight: 700; padding: 4px 10px;">
                                            <span class="dashicons dashicons-chart-bar" style="font-size: 14px; width: 14px; height: 14px; margin-right: 4px; margin-top: 1px; vertical-align: text-bottom;"></span> <?php echo number_format($r['clicks']); ?> Klik
                                        </span>
                                    </td>
                                    <td><?php echo esc_html(date_i18n(get_option('date_format'), strtotime($r['created_at']))); ?></td>
                                    <td>
                                        <a class="wrpm-btn wrpm-btn-secondary wrpm-btn-small" href="<?php echo admin_url('admin.php?page=wrpm-shortlinks&action=edit&id=' . $r['id']); ?>">
                                            <span class="dashicons dashicons-edit"></span> Edit
                                        </a>
                                        <a class="wrpm-btn wrpm-btn-danger wrpm-btn-small" href="<?php echo wp_nonce_url(admin_url('admin-post.php?action=wrpm_delete_shortlink&id=' . $r['id']), 'wrpm_delete_shortlink_' . $r['id']); ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus shortlink ini?');">
                                            <span class="dashicons dashicons-trash"></span> Hapus
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <!-- Pagination -->
                    <?php if ($total_pages > 1): ?>
                        <div class="wrpm-pagination wrpm-mt-2">
                            <span class="wrpm-pagination-info">Menampilkan halaman <?php echo $paged; ?> dari <?php echo $total_pages; ?> (Total: <?php echo $total_rows; ?> data)</span>
                            <div class="wrpm-pagination-buttons">
                                <?php if ($paged > 1): ?>
                                    <a class="wrpm-pagination-btn" href="<?php echo admin_url('admin.php?page=wrpm-shortlinks&paged=' . ($paged - 1)); ?>">&laquo; Prev</a>
                                <?php endif; ?>
                                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                    <a class="wrpm-pagination-btn <?php echo $paged === $i ? 'active' : ''; ?>" href="<?php echo admin_url('admin.php?page=wrpm-shortlinks&paged=' . $i); ?>"><?php echo $i; ?></a>
                                <?php endfor; ?>
                                <?php if ($paged < $total_pages): ?>
                                    <a class="wrpm-pagination-btn" href="<?php echo admin_url('admin.php?page=wrpm-shortlinks&paged=' . ($paged + 1)); ?>">Next &raquo;</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>

        <script>
        jQuery(document).ready(function($) {
            // Live Search
            $('.wrpm-table-search').on('keyup input', function() {
                var val = $(this).val().toLowerCase().trim();
                $('.wrpm-table tbody tr').each(function() {
                    var title = $(this).find('strong').text().toLowerCase();
                    var link = $(this).find('code').text().toLowerCase();
                    var dest = $(this).find('a.wrpm-text-muted').text().toLowerCase();
                    if (title.indexOf(val) > -1 || link.indexOf(val) > -1 || dest.indexOf(val) > -1) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });

            // Clipboard Copying
            $('.wrpm-copy-link-btn').on('click', function(e) {
                e.preventDefault();
                var linkText = $(this).data('link');
                var $btn = $(this);
                
                navigator.clipboard.writeText(linkText).then(function() {
                    var originalHtml = $btn.html();
                    $btn.html('<span class="dashicons dashicons-yes" style="font-size: 14px; width: 14px; height: 14px; color: #10b981;"></span>').css('border-color', '#10b981');
                    setTimeout(function() {
                        $btn.html(originalHtml).css('border-color', '');
                    }, 1500);
                });
            });
        });
        </script>
    <?php endif; ?>
</div>
