<?php
/**
 * Plugin Name: Altalayi Tire Ticket System
 * Plugin URI: https://altalayi.com
 * Description: Professional ticket system for tire warranty and customer complaints management for Altalayi company.
 * Version: 1.0.0
 * Author: Altalayi Company
 * License: GPL v2 or later
 * Text Domain: altalayi-ticket
 * Domain Path: /languages
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('ALTALAYI_TICKET_VERSION', '1.0.0');
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
        require_once ALTALAYI_TICKET_PLUGIN_PATH . 'includes/class-database.php';
        require_once ALTALAYI_TICKET_PLUGIN_PATH . 'includes/class-admin.php';
        require_once ALTALAYI_TICKET_PLUGIN_PATH . 'includes/class-frontend.php';
        require_once ALTALAYI_TICKET_PLUGIN_PATH . 'includes/class-email.php';
        require_once ALTALAYI_TICKET_PLUGIN_PATH . 'includes/class-ajax.php';
        require_once ALTALAYI_TICKET_PLUGIN_PATH . 'includes/functions.php';
        
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
 * Get the URL for the create ticket page from settings
 */
function altalayi_get_create_ticket_url() {
    $settings = get_option('altalayi_ticket_settings', array());
    $page_id = $settings['frontend_create_page'] ?? '';
    
    if ($page_id && get_post_status($page_id) === 'publish') {
        return get_permalink($page_id);
    }
    
    // Fallback to old URL structure
    return home_url('/new-ticket');
}

/**
 * Get the URL for the access ticket page from settings
 */
function altalayi_get_access_ticket_url() {
    $settings = get_option('altalayi_ticket_settings', array());
    $page_id = $settings['frontend_access_page'] ?? '';
    
    if ($page_id && get_post_status($page_id) === 'publish') {
        return get_permalink($page_id);
    }
    
    // Fallback to old URL structure
    return home_url('/ticket-login');
}

/**
 * Get the URL for the ticket view page from settings
 */
function altalayi_get_ticket_view_url($ticket_number = '') {
    $settings = get_option('altalayi_ticket_settings', array());
    $page_id = $settings['frontend_view_page'] ?? '';
    
    if ($page_id && get_post_status($page_id) === 'publish') {
        $url = get_permalink($page_id);
        // Add ticket number as query parameter if provided
        if ($ticket_number) {
            $url = add_query_arg('ticket', $ticket_number, $url);
        }
        return $url;
    }
    
    // Fallback to old URL structure
    if ($ticket_number) {
        return home_url('/ticket/' . $ticket_number);
    }
    return home_url('/ticket/');
}

// Initialize the plugin
new AltalayiTicketSystem();
