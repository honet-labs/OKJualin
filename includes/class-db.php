<?php
if (!defined('ABSPATH')) { exit; }

class WRPM_DB {
    public static function get_table($name) {
        global $wpdb;
        return $wpdb->prefix . 'wrpm_' . $name;
    }

    public static function install() {
        if (!function_exists('dbDelta')) {
            require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        }

        global $wpdb;
        $charset = $wpdb->get_charset_collate();

        $t_prices = self::get_table('product_prices');
        $t_reseller = self::get_table('reseller_products');
        $t_customers = self::get_table('customers');
        $t_sellers = self::get_table('sellers');
        $t_active = self::get_table('active_products');
        $t_reminders = self::get_table('active_reminders');
        $t_logs = self::get_table('logs');

        $sql_prices = "CREATE TABLE {$t_prices} (
            id CHAR(36) NOT NULL,
            name VARCHAR(200) NOT NULL,
            category VARCHAR(100) NOT NULL DEFAULT '',
            tags TEXT NULL,
            seller_id CHAR(36) NULL,
            reseller_price BIGINT(20) NOT NULL DEFAULT 0,
            sale_price BIGINT(20) NOT NULL DEFAULT 0,
            duration_days INT(11) NOT NULL DEFAULT 0,
            description LONGTEXT NULL,
            notes LONGTEXT NULL,
            created_at DATETIME NOT NULL,
            updated_at DATETIME NOT NULL,
            updated_by BIGINT(20) NOT NULL DEFAULT 0,
            PRIMARY KEY (id),
            KEY name (name(80)),
            KEY category (category(40)),
            KEY seller_id (seller_id)
        ) {$charset};";

        $sql_reseller = "CREATE TABLE {$t_reseller} (
            id CHAR(36) NOT NULL,
            price_id CHAR(36) NOT NULL,
            product_name VARCHAR(200) NOT NULL,
            category VARCHAR(100) NOT NULL DEFAULT '',
            tags TEXT NULL,
            seller_id CHAR(36) NULL,
            reseller_name VARCHAR(200) NOT NULL DEFAULT '',
            reseller_contact VARCHAR(200) NOT NULL DEFAULT '',
            purchase_date DATE NULL,
            duration_days INT(11) NOT NULL DEFAULT 0,
            description LONGTEXT NULL,
            price BIGINT(20) NOT NULL DEFAULT 0,
            expires_at DATE NULL,
            payment_status VARCHAR(20) NOT NULL DEFAULT 'pending',
            payment_attachments LONGTEXT NULL,
            notes LONGTEXT NULL,
            created_at DATETIME NOT NULL,
            updated_at DATETIME NOT NULL,
            updated_by BIGINT(20) NOT NULL DEFAULT 0,
            PRIMARY KEY (id),
            KEY price_id (price_id),
            KEY seller_id (seller_id),
            KEY expires_at (expires_at),
            KEY payment_status (payment_status)
        ) {$charset};";

        $sql_customers = "CREATE TABLE {$t_customers} (
            id CHAR(36) NOT NULL,
            name VARCHAR(200) NOT NULL,
            email VARCHAR(190) NOT NULL DEFAULT '',
            phone VARCHAR(50) NOT NULL DEFAULT '',
            telegram VARCHAR(100) NOT NULL DEFAULT '',
            whatsapp VARCHAR(50) NOT NULL DEFAULT '',
            created_at DATETIME NOT NULL,
            updated_at DATETIME NOT NULL,
            updated_by BIGINT(20) NOT NULL DEFAULT 0,
            PRIMARY KEY (id),
            KEY email (email(80)),
            KEY name (name(80))
        ) {$charset};";

        $sql_sellers = "CREATE TABLE {$t_sellers} (
            id CHAR(36) NOT NULL,
            name VARCHAR(200) NOT NULL,
            email VARCHAR(190) NOT NULL DEFAULT '',
            phone VARCHAR(50) NOT NULL DEFAULT '',
            telegram VARCHAR(100) NOT NULL DEFAULT '',
            whatsapp VARCHAR(50) NOT NULL DEFAULT '',
            created_at DATETIME NOT NULL,
            updated_at DATETIME NOT NULL,
            updated_by BIGINT(20) NOT NULL DEFAULT 0,
            PRIMARY KEY (id),
            KEY email (email(80)),
            KEY name (name(80))
        ) {$charset};";

        $sql_active = "CREATE TABLE {$t_active} (
            id CHAR(36) NOT NULL,
            reseller_product_id CHAR(36) NOT NULL,
            product_label VARCHAR(255) NOT NULL,
            customer_id CHAR(36) NOT NULL,
            customer_name VARCHAR(200) NOT NULL,
            customer_contact VARCHAR(200) NOT NULL DEFAULT '',
            start_date DATE NOT NULL,
            duration_days INT(11) NOT NULL DEFAULT 0,
            expires_at DATE NOT NULL,
            status VARCHAR(20) NOT NULL DEFAULT 'active',
            price BIGINT(20) NOT NULL DEFAULT 0,
            payment_status VARCHAR(20) NOT NULL DEFAULT 'pending',
            payment_attachments LONGTEXT NULL,
            notes LONGTEXT NULL,
            created_at DATETIME NOT NULL,
            updated_at DATETIME NOT NULL,
            updated_by BIGINT(20) NOT NULL DEFAULT 0,
            PRIMARY KEY (id),
            KEY expires_at (expires_at),
            KEY status (status),
            KEY customer_id (customer_id),
            KEY reseller_product_id (reseller_product_id)
        ) {$charset};";

        $sql_reminders = "CREATE TABLE {$t_reminders} (
            id CHAR(36) NOT NULL,
            active_product_id CHAR(36) NOT NULL,
            customer_id CHAR(36) NOT NULL,
            offset_days INT(11) NOT NULL DEFAULT 0,
            reminder_date DATE NOT NULL,
            remaining_days INT(11) NOT NULL DEFAULT 0,
            status VARCHAR(20) NOT NULL DEFAULT 'pending',
            sent_via VARCHAR(50) NOT NULL DEFAULT '',
            sent_at DATETIME NULL,
            last_error TEXT NULL,
            created_at DATETIME NOT NULL,
            updated_at DATETIME NOT NULL,
            PRIMARY KEY (id),
            KEY active_product_id (active_product_id),
            KEY reminder_date (reminder_date),
            KEY status (status)
        ) {$charset};";

        $sql_logs = "CREATE TABLE {$t_logs} (
            id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            happened_at DATETIME NOT NULL,
            user_id BIGINT(20) NOT NULL DEFAULT 0,
            user_login VARCHAR(60) NOT NULL DEFAULT '',
            action VARCHAR(120) NOT NULL DEFAULT '',
            entity VARCHAR(60) NOT NULL DEFAULT '',
            entity_id CHAR(36) NOT NULL DEFAULT '',
            message TEXT NULL,
            meta LONGTEXT NULL,
            ip VARCHAR(45) NOT NULL DEFAULT '',
            PRIMARY KEY (id),
            KEY happened_at (happened_at),
            KEY action (action(60)),
            KEY entity (entity(40)),
            KEY entity_id (entity_id)
        ) {$charset};";

        dbDelta($sql_prices);
        dbDelta($sql_reseller);
        dbDelta($sql_customers);
        dbDelta($sql_sellers);
        dbDelta($sql_active);
        dbDelta($sql_reminders);
        dbDelta($sql_logs);

        // Ensure capabilities and settings are initialized
        self::ensure_caps();
    }

    public static function uninstall() {
        global $wpdb;
        $tables = ['product_prices', 'reseller_products', 'customers', 'sellers', 'active_products', 'active_reminders', 'logs'];
        foreach ($tables as $t) {
            $wpdb->query("DROP TABLE IF EXISTS " . self::get_table($t));
        }
        delete_option('wrpm_settings_v1');
    }

    private static function ensure_caps() {
        $admin = get_role('administrator');
        if ($admin) {
            $admin->add_cap('wrpm_manage');
            $admin->add_cap('wrpm_view_reports');
            $admin->add_cap('wrpm_manage_settings');
            $admin->add_cap('wrpm_view_logs');
        }

        if (!get_role('wrpm_manager')) {
            add_role('wrpm_manager', 'WRPM Manager', [
                'wrpm_manage' => true,
                'wrpm_view_reports' => true,
                'wrpm_view_logs' => true,
            ]);
        }
    }
}
