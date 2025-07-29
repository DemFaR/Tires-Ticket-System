<?php
/**
 * Plugin Name: Altalayi Tire Ticket System
 * Plugin URI: https://altalayi.com
 * Description: Professional ticket system for tire warranty and customer complaints management for Altalayi company. Features advanced email notifications, role-based access control, file uploads, comprehensive ticket management, full Arabic localization, and Polylang integration.
 * Version: 1.3.0
 * Author: Mohamed Ashraf
 * Author URI: https://altalayi.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: altalayi-ticket
 * Domain Path: /languages
 * Requires at least: 5.0
 * Tested up to: 6.6
 * Requires PHP: 7.4
 * Network: false
 * 
 * @package AltalayiTicketSystem
 * @version 1.3.0
 * @author Mohamed Ashraf
 * @copyright 2024 Altalayi Company
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('ALTALAYI_TICKET_VERSION', '1.2.0');
define('ALTALAYI_TICKET_PLUGIN_URL', plugin_dir_url(__FILE__));
define('ALTALAYI_TICKET_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('ALTALAYI_TICKET_PLUGIN_FILE', __FILE__);

// Main plugin class
class AltalayiTicketSystem {
    
    /**
     * Initialize the plugin
     */
    public function __construct() {
        add_action('init', array($this, 'init'));
        register_activation_hook(__FILE__, array($this, 'activate'));
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));
    }
    
    /**
     * Initialize plugin functionality
     */
    public function init() {
        // Handle language detection first
        $current_lang = altalayi_get_current_language();
        
        // Force Arabic locale if detected
        if ($current_lang === 'ar') {
            add_filter('locale', function($locale) {
                return 'ar';
            });
        }
        
        // Load text domain for translations
        load_plugin_textdomain('altalayi-ticket', false, dirname(plugin_basename(__FILE__)) . '/languages');
        
        // Include required files
        $this->include_files();
        
        // Initialize components
        new AltalayiTicketDatabase();
        new AltalayiTicketAdmin();
        new AltalayiTicketFrontend();
        new AltalayiTicketEmail();
        new AltalayiTicketAjax();
        
        // Add custom user role
        $this->add_custom_role();
    }
    
    /**
     * Include required files
     */
    private function include_files() {
        require_once ALTALAYI_TICKET_PLUGIN_PATH . 'includes/functions.php';
        require_once ALTALAYI_TICKET_PLUGIN_PATH . 'includes/class-database.php';
        require_once ALTALAYI_TICKET_PLUGIN_PATH . 'includes/class-admin.php';
        require_once ALTALAYI_TICKET_PLUGIN_PATH . 'includes/class-frontend.php';
        require_once ALTALAYI_TICKET_PLUGIN_PATH . 'includes/class-email.php';
        require_once ALTALAYI_TICKET_PLUGIN_PATH . 'includes/class-ajax.php';
        
        // Include Elementor integration if Elementor is active
        if (did_action('elementor/loaded')) {
            require_once ALTALAYI_TICKET_PLUGIN_PATH . 'includes/class-elementor.php';
        }
    }
    
    /**
     * Enqueue frontend scripts and styles
     */
    public function enqueue_scripts() {
        // Always load basic styles
        wp_enqueue_style('altalayi-ticket-style', ALTALAYI_TICKET_PLUGIN_URL . 'assets/css/frontend.css', array(), ALTALAYI_TICKET_VERSION);
        wp_enqueue_script('altalayi-ticket-script', ALTALAYI_TICKET_PLUGIN_URL . 'assets/js/frontend.js', array('jquery'), ALTALAYI_TICKET_VERSION, true);
        
        // Load theme compatibility styles for ticket pages or when shortcodes are detected
        global $wp, $post;
        $request = trim($wp->request, '/');
        $load_compat_styles = false;
        
        // Check for custom ticket URLs
        if (in_array($request, array('new-ticket', 'ticket-login')) || preg_match('/^ticket\/([^\/]+)$/', $request)) {
            $load_compat_styles = true;
        }
        
        // Check for shortcodes in post content
        if (is_object($post) && isset($post->post_content)) {
            if (has_shortcode($post->post_content, 'altalayi_ticket_form') || 
                has_shortcode($post->post_content, 'altalayi_ticket_login') || 
                has_shortcode($post->post_content, 'altalayi_ticket_view')) {
                $load_compat_styles = true;
            }
        }
        
        if ($load_compat_styles) {
            wp_enqueue_style('altalayi-ticket-theme-compat', ALTALAYI_TICKET_PLUGIN_URL . 'assets/css/theme-compat.css', array('altalayi-ticket-style'), ALTALAYI_TICKET_VERSION);
        }
        
        wp_localize_script('altalayi-ticket-script', 'altalayi_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('altalayi_ticket_nonce')
        ));
    }
    
    /**
     * Enqueue admin scripts and styles
     */
    public function admin_enqueue_scripts() {
        wp_enqueue_style('altalayi-ticket-admin-style', ALTALAYI_TICKET_PLUGIN_URL . 'assets/css/admin.css', array(), ALTALAYI_TICKET_VERSION);
        wp_enqueue_script('altalayi-ticket-admin-script', ALTALAYI_TICKET_PLUGIN_URL . 'assets/js/admin.js', array('jquery'), ALTALAYI_TICKET_VERSION, true);
        
        wp_localize_script('altalayi-ticket-admin-script', 'altalayi_admin_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('altalayi_admin_nonce')
        ));
    }
    
    /**
     * Add custom user role for ticket employees
     */
    private function add_custom_role() {
        add_role('altalayi_ticket_employee', __('Ticket Employee', 'altalayi-ticket'), array(
            'read' => true,
            'manage_altalayi_tickets' => true,
        ));
    }
    
    /**
     * Plugin activation
     */
    public function activate() {
        require_once ALTALAYI_TICKET_PLUGIN_PATH . 'includes/class-database.php';
        $database = new AltalayiTicketDatabase();
        $database->create_tables();
        
        // Add custom role
        $this->add_custom_role();
        
        // Add rewrite rules before flushing
        add_rewrite_rule('^ticket/([^/]+)/?', 'index.php?altalayi_ticket_action=view&ticket_number=$matches[1]', 'top');
        add_rewrite_rule('^ticket-login/?', 'index.php?altalayi_ticket_action=login', 'top');
        add_rewrite_rule('^new-ticket/?', 'index.php?altalayi_ticket_action=create', 'top');
        
        // Flush rewrite rules
        flush_rewrite_rules();
    }
    
    /**
     * Plugin deactivation
     */
    public function deactivate() {
        // Remove custom role
        remove_role('altalayi_ticket_employee');
        
        // Flush rewrite rules
        flush_rewrite_rules();
    }
}

/**
 * Global helper functions for template access
 */

/**
 * Get current language code
 */
function altalayi_get_current_language() {
    // Check for language parameter in URL
    if (isset($_GET['lang']) && $_GET['lang'] === 'ar') {
        return 'ar';
    }
    
    // Check for Arabic URL path
    $request_uri = $_SERVER['REQUEST_URI'] ?? '';
    if (strpos($request_uri, '/ar/') !== false) {
        return 'ar';
    }
    
    // Check for WordPress locale
    $locale = get_locale();
    
    // Extract language code from locale
    if (strpos($locale, 'ar') === 0) {
        return 'ar'; // Arabic
    }
    
    return 'en'; // Default to English
}

/**
 * Get the URL for the create ticket page from settings with language support
 */
function altalayi_get_create_ticket_url($language = null) {
    if ($language === null) {
        $language = altalayi_get_current_language();
    }
    
    $settings = get_option('altalayi_ticket_settings', array());
    
    // Get the appropriate page based on language
    if ($language === 'ar') {
        $page_id = $settings['frontend_create_page_ar'] ?? $settings['frontend_create_page'] ?? '';
    } else {
        $page_id = $settings['frontend_create_page'] ?? '';
    }
    
    if ($page_id && get_post_status($page_id) === 'publish') {
        $url = get_permalink($page_id);
        // Add language parameter if needed
        if ($language === 'ar' && !isset($settings['frontend_create_page_ar'])) {
            $url = add_query_arg('lang', 'ar', $url);
        }
        return $url;
    }
    
    // Fallback to old URL structure with language code
    if ($language === 'ar') {
        return home_url('/ar/new-ticket');
    }
    return home_url('/new-ticket');
}

/**
 * Get the URL for the access ticket page from settings with language support
 */
function altalayi_get_access_ticket_url($language = null) {
    if ($language === null) {
        $language = altalayi_get_current_language();
    }
    
    $settings = get_option('altalayi_ticket_settings', array());
    
    // Get the appropriate page based on language
    if ($language === 'ar') {
        $page_id = $settings['frontend_access_page_ar'] ?? $settings['frontend_access_page'] ?? '';
    } else {
        $page_id = $settings['frontend_access_page'] ?? '';
    }
    
    if ($page_id && get_post_status($page_id) === 'publish') {
        $url = get_permalink($page_id);
        // Add language parameter if needed
        if ($language === 'ar' && !isset($settings['frontend_access_page_ar'])) {
            $url = add_query_arg('lang', 'ar', $url);
        }
        return $url;
    }
    
    // Fallback to old URL structure with language code
    if ($language === 'ar') {
        return home_url('/ar/ticket-login');
    }
    return home_url('/ticket-login');
}

/**
 * Get the URL for the ticket view page from settings with language support
 */
function altalayi_get_ticket_view_url($ticket_number = '', $language = null) {
    if ($language === null) {
        $language = altalayi_get_current_language();
    }
    
    $settings = get_option('altalayi_ticket_settings', array());
    
    // Get the appropriate page based on language
    if ($language === 'ar') {
        $page_id = $settings['frontend_view_page_ar'] ?? $settings['frontend_view_page'] ?? '';
    } else {
        $page_id = $settings['frontend_view_page'] ?? '';
    }
    
    if ($page_id && get_post_status($page_id) === 'publish') {
        $url = get_permalink($page_id);
        // Add ticket number as query parameter if provided
        if ($ticket_number) {
            $url = add_query_arg('ticket', $ticket_number, $url);
        }
        // Add language parameter if needed
        if ($language === 'ar' && !isset($settings['frontend_view_page_ar'])) {
            $url = add_query_arg('lang', 'ar', $url);
        }
        return $url;
    }
    
    // Fallback to old URL structure with language code
    if ($ticket_number) {
        if ($language === 'ar') {
            return home_url('/ar/ticket/' . $ticket_number);
        }
        return home_url('/ticket/' . $ticket_number);
    }
    
    if ($language === 'ar') {
        return home_url('/ar/ticket/');
    }
    return home_url('/ticket/');
}

// Initialize the plugin
new AltalayiTicketSystem();
