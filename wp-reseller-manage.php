<?php
/**
 * Plugin Name: WP Reseller Manage
 * Description: Manajemen reseller premium: master harga, reseller product, customer, active product tracker, automated reminders (email/telegram/whatsapp WAHA), brandable PDF invoice customizer, JSON backup & ECharts analytics dashboard.
 * Version: 0.0.9
 * Author: HONET
 * License: GPLv2 or later
 * Text Domain: wp-reseller-product-manager
 */

if (!defined('ABSPATH')) { exit; }

class WRPM_App {
    const VERSION = '0.0.9';

    private static $instance = null;
    public static function instance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        $this->define_constants();
        $this->includes();
        $this->init();
    }

    private function define_constants() {
        if (!defined('WRPM_PLUGIN_DIR')) {
            define('WRPM_PLUGIN_DIR', plugin_dir_path(__FILE__));
        }
        if (!defined('WRPM_PLUGIN_URL')) {
            define('WRPM_PLUGIN_URL', plugin_dir_url(__FILE__));
        }
    }

    private function includes() {
        require_once WRPM_PLUGIN_DIR . 'includes/class-db.php';
        require_once WRPM_PLUGIN_DIR . 'includes/class-notifier.php';
        require_once WRPM_PLUGIN_DIR . 'includes/class-pdf-invoice.php';
        require_once WRPM_PLUGIN_DIR . 'includes/class-backup.php';
        require_once WRPM_PLUGIN_DIR . 'includes/class-updater.php';
        require_once WRPM_PLUGIN_DIR . 'includes/class-reseller-manager.php';
        require_once WRPM_PLUGIN_DIR . 'includes/class-admin.php';
    }

    private function init() {
        // DB Upgrade handler
        add_action('admin_init', [$this, 'maybe_upgrade_db']);

        // Listen to shortlink redirects
        add_action('parse_request', [$this, 'handle_shortlink_redirect']);

        // Initialize modules
        if (is_admin()) {
            new WRPM_Admin();
        }

        // Initialize GitHub auto-updater
        new WRPM_Updater(__FILE__);

        // Cron Scheduling
        add_action('wrpm_daily_cron', [WRPM_Reseller_Manager::class, 'process_daily_cron']);
        if (!wp_next_scheduled('wrpm_daily_cron')) {
            wp_schedule_event(time(), 'daily', 'wrpm_daily_cron');
        }
    }

    public function handle_shortlink_redirect() {
        $request_uri = $_SERVER['REQUEST_URI'];
        $path = parse_url($request_uri, PHP_URL_PATH);
        
        $pos = strpos($path, '/go/');
        if ($pos !== false) {
            $key = substr($path, $pos + 4);
            $key = trim($key, '/');
            
            if (!empty($key)) {
                global $wpdb;
                $table = $wpdb->prefix . 'wrpm_shortlinks';
                
                $link = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$table} WHERE short_key = %s", $key), ARRAY_A);
                if ($link) {
                    $wpdb->query($wpdb->prepare("UPDATE {$table} SET clicks = clicks + 1 WHERE id = %s", $link['id']));
                    
                    wp_redirect($link['destination_url']);
                    exit;
                }
            }
        }
    }

    public function maybe_upgrade_db() {
        $db_ver = get_option('wrpm_db_version', '');
        if ($db_ver !== self::VERSION) {
            WRPM_DB::install();
            update_option('wrpm_db_version', self::VERSION);
        }
    }

    public static function activate() {
        WRPM_DB::install();
        if (!wp_next_scheduled('wrpm_daily_cron')) {
            wp_schedule_event(time(), 'daily', 'wrpm_daily_cron');
        }
    }

    public static function deactivate() {
        wp_clear_scheduled_hook('wrpm_daily_cron');
    }
}

register_activation_hook(__FILE__, [WRPM_App::class, 'activate']);
register_deactivation_hook(__FILE__, [WRPM_App::class, 'deactivate']);

// Boot the application
WRPM_App::instance();
