<?php if (!defined('ABSPATH')) { exit; } ?>
<div class="wrpm-wrap">
    <div class="wrpm-header">
        <div>
            <h1>Pengaturan WP Reseller Manage</h1>
            <p class="wrpm-subtitle">Konfigurasikan gateway notifikasi, desain branding invoice PDF, serta backup data JSON.</p>
        </div>
    </div>

    <?php if (!empty($_GET['msg'])): ?>
        <div class="notice notice-info is-dismissible wrpm-mt-1" style="margin-left:0; padding:10px; border-left:4px solid #6366f1; background:#fff; box-shadow:0 1px 3px rgba(0,0,0,0.05); border-radius:4px;">
            <p style="margin:0; font-weight:500; color:#374151;"><?php echo esc_html(urldecode($_GET['msg'])); ?></p>
        </div>
    <?php endif; ?>

    <div class="wrpm-grid wrpm-grid-3 wrpm-mt-2">
        <!-- Settings Form Column -->
        <div class="wrpm-col-span-2">
            <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
                <?php wp_nonce_field('wrpm_save_settings'); ?>
                <input type="hidden" name="action" value="wrpm_save_settings" />

                <!-- General Reminder Offsets -->
                <div class="wrpm-card">
                    <div class="wrpm-card-header">
                        <h2>Sistem Otomasi & Milestones</h2>
                    </div>
                    <div class="wrpm-card-body">
                        <div class="wrpm-form-group">
                            <label class="wrpm-label">Jarak Milestone H- (Hari, Pisahkan dengan koma)</label>
                            <input type="text" name="reminder_offsets" class="wrpm-input" value="<?php echo esc_attr(implode(',', !empty($settings['reminder_offsets']) ? $settings['reminder_offsets'] : [7,3,1])); ?>" placeholder="7,3,1" />
                            <small class="wrpm-text-muted">Interval waktu pengiriman reminder otomatis ke customer sebelum tanggal kadaluwarsa layanan.</small>
                        </div>
                        <div class="wrpm-form-group wrpm-mt-1">
                            <label class="wrpm-label">Waktu Cron Harian (WIB)</label>
                            <input type="time" name="cron_time" class="wrpm-input" value="<?php echo esc_attr(!empty($settings['cron_time']) ? $settings['cron_time'] : '08:00'); ?>" />
                        </div>
                    </div>
                </div>

                <!-- WAHA WhatsApp Gateway Config -->
                <div class="wrpm-card wrpm-mt-2">
                    <div class="wrpm-card-header">
                        <h2>WhatsApp Gateway (WAHA API)</h2>
                    </div>
                    <div class="wrpm-card-body">
                        <div class="wrpm-form-group">
                            <label class="wrpm-checkbox-label">
                                <input type="checkbox" name="waha_enabled" value="1" <?php checked(!empty($settings['waha_enabled']), 1); ?> /> Aktifkan WhatsApp Gateway (WAHA)
                            </label>
                        </div>
                        <div class="wrpm-form-group wrpm-mt-1">
                            <label class="wrpm-label">WAHA API URL</label>
                            <input type="url" name="waha_api_url" class="wrpm-input" value="<?php echo esc_attr(!empty($settings['waha_api_url']) ? $settings['waha_api_url'] : ''); ?>" placeholder="http://localhost:3000" />
                        </div>
                        <div class="wrpm-form-group wrpm-mt-1">
                            <label class="wrpm-label">WAHA API Token (Bearer Authorization)</label>
                            <input type="password" name="waha_api_token" class="wrpm-input" value="<?php echo esc_attr(!empty($settings['waha_api_token']) ? $settings['waha_api_token'] : ''); ?>" />
                        </div>
                        <div class="wrpm-form-group wrpm-mt-1">
                            <label class="wrpm-label">Session Name (Default: default)</label>
                            <input type="text" name="waha_session_name" class="wrpm-input" value="<?php echo esc_attr(!empty($settings['waha_session_name']) ? $settings['waha_session_name'] : 'default'); ?>" />
                        </div>
                    </div>
                </div>

                <!-- Telegram Integration -->
                <div class="wrpm-card wrpm-mt-2">
                    <div class="wrpm-card-header">
                        <h2>Telegram Bot Gateway</h2>
                    </div>
                    <div class="wrpm-card-body">
                        <div class="wrpm-form-group">
                            <label class="wrpm-checkbox-label">
                                <input type="checkbox" name="telegram_enabled" value="1" <?php checked(!empty($settings['telegram_enabled']), 1); ?> /> Aktifkan Telegram Notification
                            </label>
                        </div>
                        <div class="wrpm-form-group wrpm-mt-1">
                            <label class="wrpm-label">Telegram Bot Token</label>
                            <input type="password" name="telegram_bot_token" class="wrpm-input" value="<?php echo esc_attr(!empty($settings['telegram_bot_token']) ? $settings['telegram_bot_token'] : ''); ?>" />
                        </div>
                        <div class="wrpm-form-group wrpm-mt-1">
                            <label class="wrpm-label">Default Telegram Chat ID / Channel ID</label>
                            <input type="text" name="telegram_default_chat_id" class="wrpm-input" value="<?php echo esc_attr(!empty($settings['telegram_default_chat_id']) ? $settings['telegram_default_chat_id'] : ''); ?>" />
                        </div>
                    </div>
                </div>

                <!-- Email SMTP Configuration -->
                <div class="wrpm-card wrpm-mt-2">
                    <div class="wrpm-card-header">
                        <h2>SMTP Email Gateway</h2>
                    </div>
                    <div class="wrpm-card-body">
                        <div class="wrpm-form-group">
                            <label class="wrpm-checkbox-label">
                                <input type="checkbox" name="smtp_enabled" value="1" <?php checked(!empty($settings['smtp_enabled']), 1); ?> /> Aktifkan Pengiriman Email via SMTP Khusus
                            </label>
                        </div>
                        <div class="wrpm-form-grid wrpm-mt-1">
                            <div class="wrpm-form-group">
                                <label class="wrpm-label">SMTP Host</label>
                                <input type="text" name="smtp_host" class="wrpm-input" value="<?php echo esc_attr(!empty($settings['smtp_host']) ? $settings['smtp_host'] : ''); ?>" />
                            </div>
                            <div class="wrpm-form-group">
                                <label class="wrpm-label">SMTP Port</label>
                                <input type="number" name="smtp_port" class="wrpm-input" value="<?php echo esc_attr(!empty($settings['smtp_port']) ? $settings['smtp_port'] : '587'); ?>" />
                            </div>
                            <div class="wrpm-form-group">
                                <label class="wrpm-label">SMTP Username</label>
                                <input type="text" name="smtp_user" class="wrpm-input" value="<?php echo esc_attr(!empty($settings['smtp_user']) ? $settings['smtp_user'] : ''); ?>" />
                            </div>
                            <div class="wrpm-form-group">
                                <label class="wrpm-label">SMTP Password</label>
                                <input type="password" name="smtp_pass" class="wrpm-input" value="<?php echo esc_attr(!empty($settings['smtp_pass']) ? $settings['smtp_pass'] : ''); ?>" />
                            </div>
                            <div class="wrpm-form-group">
                                <label class="wrpm-label">SMTP Secure</label>
                                <select name="smtp_secure" class="wrpm-select">
                                    <option value="tls" <?php echo !empty($settings['smtp_secure']) && $settings['smtp_secure'] === 'tls' ? 'selected' : ''; ?>>TLS (Rekomendasi)</option>
                                    <option value="ssl" <?php echo !empty($settings['smtp_secure']) && $settings['smtp_secure'] === 'ssl' ? 'selected' : ''; ?>>SSL</option>
                                    <option value="none" <?php echo !empty($settings['smtp_secure']) && $settings['smtp_secure'] === 'none' ? 'selected' : ''; ?>>None</option>
                                </select>
                            </div>
                            <div class="wrpm-form-group">
                                <label class="wrpm-label">Sender Email (From)</label>
                                <input type="email" name="smtp_from_email" class="wrpm-input" value="<?php echo esc_attr(!empty($settings['smtp_from_email']) ? $settings['smtp_from_email'] : ''); ?>" />
                            </div>
                            <div class="wrpm-form-group">
                                <label class="wrpm-label">Sender Name</label>
                                <input type="text" name="smtp_from_name" class="wrpm-input" value="<?php echo esc_attr(!empty($settings['smtp_from_name']) ? $settings['smtp_from_name'] : ''); ?>" />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Customizer Branding Invoice PDF -->
                <div class="wrpm-card wrpm-mt-2">
                    <div class="wrpm-card-header">
                        <h2>Kustomisasi Invoice PDF & Branding</h2>
                    </div>
                    <div class="wrpm-card-body">
                        <div class="wrpm-form-grid">
                            <div class="wrpm-form-group">
                                <label class="wrpm-label">Judul Invoice Dokumen</label>
                                <input type="text" name="pdf_invoice_title" class="wrpm-input" value="<?php echo esc_attr(!empty($settings['pdf_invoice_title']) ? $settings['pdf_invoice_title'] : 'INVOICE'); ?>" placeholder="INVOICE" />
                            </div>
                            <div class="wrpm-form-group">
                                <label class="wrpm-label">Warna Primer Invoice (HEX)</label>
                                <input type="color" name="pdf_primary_color" class="wrpm-input-color" value="<?php echo esc_attr(!empty($settings['pdf_primary_color']) ? $settings['pdf_primary_color'] : '#1e293b'); ?>" />
                            </div>
                            <div class="wrpm-form-group">
                                <label class="wrpm-label">Nama Perusahaan / Toko</label>
                                <input type="text" name="pdf_company_name" class="wrpm-input" value="<?php echo esc_attr(!empty($settings['pdf_company_name']) ? $settings['pdf_company_name'] : ''); ?>" />
                            </div>
                            <div class="wrpm-form-group">
                                <label class="wrpm-label">Alamat / Lokasi</label>
                                <input type="text" name="pdf_company_address" class="wrpm-input" value="<?php echo esc_attr(!empty($settings['pdf_company_address']) ? $settings['pdf_company_address'] : ''); ?>" />
                            </div>
                            <div class="wrpm-form-group">
                                <label class="wrpm-label">Kontak Support (Telp/WA)</label>
                                <input type="text" name="pdf_company_phone" class="wrpm-input" value="<?php echo esc_attr(!empty($settings['pdf_company_phone']) ? $settings['pdf_company_phone'] : ''); ?>" />
                            </div>
                        </div>
                        <div class="wrpm-form-group wrpm-mt-1">
                            <label class="wrpm-label">Instruksi & Detail Rekening Pembayaran</label>
                            <textarea name="pdf_payment_details" class="wrpm-input" rows="4"><?php echo esc_textarea(!empty($settings['pdf_payment_details']) ? $settings['pdf_payment_details'] : ''); ?></textarea>
                            <small class="wrpm-text-muted">Akan ditampilkan di bagian bawah invoice PDF cetak.</small>
                        </div>
                    </div>
                </div>

                <!-- Separation Milestone Reminder Templates -->
                <div class="wrpm-card wrpm-mt-2">
                    <div class="wrpm-card-header">
                        <h2>Template Notifikasi (Terpisah per Milestone)</h2>
                    </div>
                    <div class="wrpm-card-body">
                        <!-- Email template -->
                        <div class="wrpm-form-group">
                            <label class="wrpm-label">Subjek Email Reminder</label>
                            <input type="text" name="email_subject" class="wrpm-input" value="<?php echo esc_attr(!empty($settings['email_subject']) ? $settings['email_subject'] : '[Reminder] {product_label} akan expired'); ?>" />
                        </div>
                        <div class="wrpm-form-group wrpm-mt-1">
                            <label class="wrpm-label">Template Email Body</label>
                            <textarea name="email_template" class="wrpm-input" rows="4"><?php echo esc_textarea(!empty($settings['email_template']) ? $settings['email_template'] : ''); ?></textarea>
                        </div>

                        <!-- Telegram template -->
                        <div class="wrpm-form-group wrpm-mt-2">
                            <label class="wrpm-label">Template Telegram Message</label>
                            <textarea name="telegram_template" class="wrpm-input" rows="3"><?php echo esc_textarea(!empty($settings['telegram_template']) ? $settings['telegram_template'] : ''); ?></textarea>
                        </div>

                        <!-- WhatsApp General Template -->
                        <div class="wrpm-form-group wrpm-mt-2">
                            <label class="wrpm-label">Template WhatsApp (General)</label>
                            <textarea name="whatsapp_template" class="wrpm-input" rows="3"><?php echo esc_textarea(!empty($settings['whatsapp_template']) ? $settings['whatsapp_template'] : ''); ?></textarea>
                        </div>

                        <!-- Milestone H-7 WhatsApp Template -->
                        <div class="wrpm-form-group wrpm-mt-2">
                            <label class="wrpm-label">Template WhatsApp (Khusus H-7)</label>
                            <textarea name="whatsapp_template_h7" class="wrpm-input" rows="3"><?php echo esc_textarea(!empty($settings['whatsapp_template_h7']) ? $settings['whatsapp_template_h7'] : ''); ?></textarea>
                        </div>

                        <!-- Milestone H-3 WhatsApp Template -->
                        <div class="wrpm-form-group wrpm-mt-2">
                            <label class="wrpm-label">Template WhatsApp (Khusus H-3)</label>
                            <textarea name="whatsapp_template_h3" class="wrpm-input" rows="3"><?php echo esc_textarea(!empty($settings['whatsapp_template_h3']) ? $settings['whatsapp_template_h3'] : ''); ?></textarea>
                        </div>

                        <!-- Milestone H-1 WhatsApp Template -->
                        <div class="wrpm-form-group wrpm-mt-2">
                            <label class="wrpm-label">Template WhatsApp (Khusus H-1)</label>
                            <textarea name="whatsapp_template_h1" class="wrpm-input" rows="3"><?php echo esc_textarea(!empty($settings['whatsapp_template_h1']) ? $settings['whatsapp_template_h1'] : ''); ?></textarea>
                        </div>

                        <div class="wrpm-mt-1" style="display: flex; align-items: center; justify-content: space-between; background: #f8fafc; padding: 10px 15px; border-radius: 8px; border: 1px dashed #cbd5e1; flex-wrap: wrap; gap: 10px;">
                            <small class="wrpm-text-muted" style="margin: 0; font-weight: 500;">Variabel dasar yang didukung: <code>{customer_name}</code>, <code>{product_label}</code>, <code>{expires_at}</code>, <code>{price}</code>...</small>
                            <button type="button" class="wrpm-btn wrpm-btn-secondary wrpm-btn-small" id="wrpm-btn-show-shortcodes" style="padding: 6px 12px; font-size: 11px; display: inline-flex; align-items: center; font-weight: 600;">
                                <span class="dashicons dashicons-editor-help" style="font-size: 14px; width: 14px; height: 14px; margin-right: 4px; vertical-align: text-bottom;"></span> Lihat Semua Variabel (Shortcode)
                            </button>
                        </div>
                    </div>
                </div>

<!-- Modal Popup for Shortcodes -->
<div id="wrpm-shortcode-modal" class="wrpm-modal" style="display: none; position: fixed; z-index: 99999; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px); align-items: center; justify-content: center;">
    <div class="wrpm-modal-content" style="background-color: #fff; margin: auto; padding: 25px; border-radius: 12px; max-width: 600px; width: 90%; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); border: 1px solid #e2e8f0; animation: wrpmFadeIn 0.3s ease; position: relative;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px;">
            <h3 style="margin: 0; font-size: 18px; color: #1e293b; font-weight: 700; display: flex; align-items: center; font-family: inherit;">
                <span class="dashicons dashicons-editor-code" style="margin-right: 8px; color: #6366f1; font-size: 20px; width: 20px; height: 20px;"></span>
                Daftar Lengkap Variabel / Shortcode Notifikasi
            </h3>
            <span id="wrpm-modal-close" style="color: #64748b; font-size: 24px; font-weight: bold; cursor: pointer; transition: color 0.2s; line-height: 1;" onmouseover="this.style.color='#0f172a'" onmouseout="this.style.color='#64748b'">&times;</span>
        </div>
        
        <p style="font-size: 13px; color: #64748b; margin-top: 0; margin-bottom: 15px; line-height: 1.5;">Gunakan shortcode di bawah ini pada template subjek email, isi email, pesan Telegram, atau template WhatsApp. <strong>Klik pada shortcode untuk menyalin secara cepat.</strong></p>
        
        <div style="max-height: 320px; overflow-y: auto; border: 1px solid #e2e8f0; border-radius: 8px;">
            <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 13px; margin: 0;">
                <thead>
                    <tr style="background-color: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                        <th style="padding: 12px; font-weight: 600; color: #334155;">Shortcode</th>
                        <th style="padding: 12px; font-weight: 600; color: #334155;">Keterangan</th>
                        <th style="padding: 12px; font-weight: 600; color: #334155;">Contoh Output</th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 10px 12px;"><code class="wrpm-copyable-code" style="cursor: pointer; background: #e0e7ff; color: #4f46e5; font-weight: 600; padding: 3px 6px; border-radius: 4px; font-size: 12px;" title="Klik untuk menyalin">{customer_name}</code></td>
                        <td style="padding: 10px 12px; color: #334155; font-weight: 500;">Nama customer</td>
                        <td style="padding: 10px 12px; color: #64748b; font-style: italic;">Yusha</td>
                    </tr>
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 10px 12px;"><code class="wrpm-copyable-code" style="cursor: pointer; background: #e0e7ff; color: #4f46e5; font-weight: 600; padding: 3px 6px; border-radius: 4px; font-size: 12px;" title="Klik untuk menyalin">{customer_email}</code></td>
                        <td style="padding: 10px 12px; color: #334155; font-weight: 500;">Email customer</td>
                        <td style="padding: 10px 12px; color: #64748b; font-style: italic;">yusha@example.com</td>
                    </tr>
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 10px 12px;"><code class="wrpm-copyable-code" style="cursor: pointer; background: #e0e7ff; color: #4f46e5; font-weight: 600; padding: 3px 6px; border-radius: 4px; font-size: 12px;" title="Klik untuk menyalin">{customer_phone}</code></td>
                        <td style="padding: 10px 12px; color: #334155; font-weight: 500;">Nomor HP/WhatsApp customer</td>
                        <td style="padding: 10px 12px; color: #64748b; font-style: italic;">08123456789</td>
                    </tr>
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 10px 12px;"><code class="wrpm-copyable-code" style="cursor: pointer; background: #e0e7ff; color: #4f46e5; font-weight: 600; padding: 3px 6px; border-radius: 4px; font-size: 12px;" title="Klik untuk menyalin">{customer_telegram}</code></td>
                        <td style="padding: 10px 12px; color: #334155; font-weight: 500;">Username Telegram customer</td>
                        <td style="padding: 10px 12px; color: #64748b; font-style: italic;">@yushamember</td>
                    </tr>
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 10px 12px;"><code class="wrpm-copyable-code" style="cursor: pointer; background: #e0e7ff; color: #4f46e5; font-weight: 600; padding: 3px 6px; border-radius: 4px; font-size: 12px;" title="Klik untuk menyalin">{customer_whatsapp}</code></td>
                        <td style="padding: 10px 12px; color: #334155; font-weight: 500;">WhatsApp customer terformat</td>
                        <td style="padding: 10px 12px; color: #64748b; font-style: italic;">628123456789</td>
                    </tr>
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 10px 12px;"><code class="wrpm-copyable-code" style="cursor: pointer; background: #e0e7ff; color: #4f46e5; font-weight: 600; padding: 3px 6px; border-radius: 4px; font-size: 12px;" title="Klik untuk menyalin">{product_label}</code></td>
                        <td style="padding: 10px 12px; color: #334155; font-weight: 500;">Label/Nama Layanan</td>
                        <td style="padding: 10px 12px; color: #64748b; font-style: italic;">VPS SG 8GB</td>
                    </tr>
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 10px 12px;"><code class="wrpm-copyable-code" style="cursor: pointer; background: #e0e7ff; color: #4f46e5; font-weight: 600; padding: 3px 6px; border-radius: 4px; font-size: 12px;" title="Klik untuk menyalin">{expires_at}</code></td>
                        <td style="padding: 10px 12px; color: #334155; font-weight: 500;">Tanggal kadaluwarsa layanan</td>
                        <td style="padding: 10px 12px; color: #64748b; font-style: italic;"><?php echo date_i18n(get_option('date_format'), time() + 7 * DAY_IN_SECONDS); ?></td>
                    </tr>
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 10px 12px;"><code class="wrpm-copyable-code" style="cursor: pointer; background: #e0e7ff; color: #4f46e5; font-weight: 600; padding: 3px 6px; border-radius: 4px; font-size: 12px;" title="Klik untuk menyalin">{start_date}</code></td>
                        <td style="padding: 10px 12px; color: #334155; font-weight: 500;">Tanggal mulai aktif layanan</td>
                        <td style="padding: 10px 12px; color: #64748b; font-style: italic;"><?php echo date_i18n(get_option('date_format'), time()); ?></td>
                    </tr>
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 10px 12px;"><code class="wrpm-copyable-code" style="cursor: pointer; background: #e0e7ff; color: #4f46e5; font-weight: 600; padding: 3px 6px; border-radius: 4px; font-size: 12px;" title="Klik untuk menyalin">{duration_days}</code></td>
                        <td style="padding: 10px 12px; color: #334155; font-weight: 500;">Durasi masa aktif layanan</td>
                        <td style="padding: 10px 12px; color: #64748b; font-style: italic;">30 Hari</td>
                    </tr>
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 10px 12px;"><code class="wrpm-copyable-code" style="cursor: pointer; background: #e0e7ff; color: #4f46e5; font-weight: 600; padding: 3px 6px; border-radius: 4px; font-size: 12px;" title="Klik untuk menyalin">{price}</code></td>
                        <td style="padding: 10px 12px; color: #334155; font-weight: 500;">Harga jual layanan</td>
                        <td style="padding: 10px 12px; color: #64748b; font-style: italic;">Rp 150,000</td>
                    </tr>
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 10px 12px;"><code class="wrpm-copyable-code" style="cursor: pointer; background: #e0e7ff; color: #4f46e5; font-weight: 600; padding: 3px 6px; border-radius: 4px; font-size: 12px;" title="Klik untuk menyalin">{remaining_days}</code></td>
                        <td style="padding: 10px 12px; color: #334155; font-weight: 500;">Sisa hari kadaluwarsa (milestone)</td>
                        <td style="padding: 10px 12px; color: #64748b; font-style: italic;">7 Hari</td>
                    </tr>
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 10px 12px;"><code class="wrpm-copyable-code" style="cursor: pointer; background: #e0e7ff; color: #4f46e5; font-weight: 600; padding: 3px 6px; border-radius: 4px; font-size: 12px;" title="Klik untuk menyalin">{notes}</code></td>
                        <td style="padding: 10px 12px; color: #334155; font-weight: 500;">Catatan layanan aktif</td>
                        <td style="padding: 10px 12px; color: #64748b; font-style: italic;">VPS OS Ubuntu 22.04</td>
                    </tr>
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 10px 12px;"><code class="wrpm-copyable-code" style="cursor: pointer; background: #e0e7ff; color: #4f46e5; font-weight: 600; padding: 3px 6px; border-radius: 4px; font-size: 12px;" title="Klik untuk menyalin">{invoice_url}</code></td>
                        <td style="padding: 10px 12px; color: #334155; font-weight: 500;">Link unduh PDF Invoice digital</td>
                        <td style="padding: 10px 12px; color: #64748b; font-style: italic; overflow-wrap: anywhere; max-width: 150px;">http://domain.com/...pdf</td>
                    </tr>
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 10px 12px;"><code class="wrpm-copyable-code" style="cursor: pointer; background: #e0e7ff; color: #4f46e5; font-weight: 600; padding: 3px 6px; border-radius: 4px; font-size: 12px;" title="Klik untuk menyalin">{company_name}</code></td>
                        <td style="padding: 10px 12px; color: #334155; font-weight: 500;">Nama perusahaan Anda (Branding)</td>
                        <td style="padding: 10px 12px; color: #64748b; font-style: italic;">HONET Labs</td>
                    </tr>
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 10px 12px;"><code class="wrpm-copyable-code" style="cursor: pointer; background: #e0e7ff; color: #4f46e5; font-weight: 600; padding: 3px 6px; border-radius: 4px; font-size: 12px;" title="Klik untuk menyalin">{company_address}</code></td>
                        <td style="padding: 10px 12px; color: #334155; font-weight: 500;">Alamat kantor/toko Anda</td>
                        <td style="padding: 10px 12px; color: #64748b; font-style: italic;">Jakarta, Indonesia</td>
                    </tr>
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 10px 12px;"><code class="wrpm-copyable-code" style="cursor: pointer; background: #e0e7ff; color: #4f46e5; font-weight: 600; padding: 3px 6px; border-radius: 4px; font-size: 12px;" title="Klik untuk menyalin">{company_phone}</code></td>
                        <td style="padding: 10px 12px; color: #334155; font-weight: 500;">No. HP Support CS</td>
                        <td style="padding: 10px 12px; color: #64748b; font-style: italic;">+62899999999</td>
                    </tr>
                    <tr>
                        <td style="padding: 10px 12px;"><code class="wrpm-copyable-code" style="cursor: pointer; background: #e0e7ff; color: #4f46e5; font-weight: 600; padding: 3px 6px; border-radius: 4px; font-size: 12px;" title="Klik untuk menyalin">{payment_details}</code></td>
                        <td style="padding: 10px 12px; color: #334155; font-weight: 500;">Detail Pembayaran/Rekening</td>
                        <td style="padding: 10px 12px; color: #64748b; font-style: italic;">Bank BCA 123456 a/n HONET</td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <div style="margin-top: 20px; display: flex; justify-content: flex-end;">
            <button type="button" class="wrpm-btn wrpm-btn-primary" id="wrpm-modal-close-btn" style="padding: 8px 20px; font-weight: 600;">Tutup</button>
        </div>
    </div>
</div>

<style>
@keyframes wrpmFadeIn {
    from { opacity: 0; transform: translateY(-12px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>

<script>
jQuery(document).ready(function($) {
    // Show Modal
    $('#wrpm-btn-show-shortcodes').on('click', function(e) {
        e.preventDefault();
        $('#wrpm-shortcode-modal').css('display', 'flex');
    });

    // Close Modal
    $('#wrpm-modal-close, #wrpm-modal-close-btn').on('click', function() {
        $('#wrpm-shortcode-modal').hide();
    });

    // Close on outer click
    $(window).on('click', function(e) {
        if ($(e.target).is('#wrpm-shortcode-modal')) {
            $('#wrpm-shortcode-modal').hide();
        }
    });

    // Click to Copy Shortcode
    $('.wrpm-copyable-code').on('click', function() {
        var code = $(this).text();
        var $el = $(this);
        navigator.clipboard.writeText(code).then(function() {
            var origColor = $el.css('color');
            var origBg = $el.css('background');
            
            $el.css({
                'color': '#fff',
                'background': '#10b981'
            }).attr('title', 'Tersalin!');
            
            setTimeout(function() {
                $el.css({
                    'color': origColor,
                    'background': origBg
                }).attr('title', 'Klik untuk menyalin');
            }, 1000);
        });
    });
});
</script>

                <!-- GitHub Updater API Config -->
                <div class="wrpm-card wrpm-mt-2">
                    <div class="wrpm-card-header">
                        <h2>GitHub Auto-Updater</h2>
                    </div>
                    <div class="wrpm-card-body">
                        <div class="wrpm-form-group">
                            <label class="wrpm-label">Repositori GitHub (Format: username/repo)</label>
                            <input type="text" name="github_repo" class="wrpm-input" value="<?php echo esc_attr(!empty($settings['github_repo']) ? $settings['github_repo'] : ''); ?>" placeholder="honet-labs/wp-reseller-manage" />
                        </div>
                        <div class="wrpm-form-group wrpm-mt-1">
                            <label class="wrpm-label">Personal Access Token GitHub (Gunakan jika repositori private)</label>
                            <input type="password" name="github_token" class="wrpm-input" value="<?php echo esc_attr(!empty($settings['github_token']) ? $settings['github_token'] : ''); ?>" />
                        </div>
                    </div>
                </div>

                <div class="wrpm-form-actions wrpm-mt-2">
                    <button type="submit" class="wrpm-btn wrpm-btn-primary">Simpan Semua Pengaturan</button>
                </div>
            </form>
        </div>

        <!-- Sidebar Actions Column (Backup & Restore) -->
        <div>
            <!-- JSON Backup Card -->
            <div class="wrpm-card">
                <div class="wrpm-card-header">
                    <h2>Ekspor Data Backup JSON</h2>
                </div>
                <div class="wrpm-card-body">
                    <p class="wrpm-text-muted" style="margin-bottom:15px;">Ekspor seluruh basis data master harga, reseller product, customer, active product, reminder, logs, dan pengaturan plugin ke dalam 1 file JSON.</p>
                    <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
                        <?php wp_nonce_field('wrpm_backup_data'); ?>
                        <input type="hidden" name="action" value="wrpm_backup_data" />
                        <button type="submit" class="wrpm-btn wrpm-btn-primary" style="width: 100%;">
                            <span class="dashicons dashicons-download"></span> Ekspor Data (JSON)
                        </button>
                    </form>
                </div>
            </div>

            <!-- JSON Restore Card -->
            <div class="wrpm-card wrpm-mt-2">
                <div class="wrpm-card-header">
                    <h2>Impor Data & Restorasi</h2>
                </div>
                <div class="wrpm-card-body">
                    <p class="wrpm-text-muted" style="margin-bottom:15px;">Unggah file backup JSON yang sebelumnya diekspor untuk melakukan restorasi database secara cepat.</p>
                    <form method="post" action="<?php echo admin_url('admin-post.php'); ?>" enctype="multipart/form-data">
                        <?php wp_nonce_field('wrpm_restore_data'); ?>
                        <input type="hidden" name="action" value="wrpm_restore_data" />
                        <div class="wrpm-form-group">
                            <input type="file" name="restore_file" accept=".json" required />
                        </div>
                        <button type="submit" class="wrpm-btn wrpm-btn-secondary" style="width: 100%; margin-top:15px;" onclick="return confirm('PENTING: Mengimpor backup akan mengosongkan dan menimpa database aktif saat ini. Lanjutkan?');">
                            <span class="dashicons dashicons-upload"></span> Mulai Impor & Restorasi
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
