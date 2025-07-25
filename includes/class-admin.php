<?php
/**
 * Admin interface for Altalayi Ticket System
 */

if (!defined('ABSPATH')) {
    exit;
}

class AltalayiTicketAdmin {
    
    private $db;
    
    public function __construct() {
        $this->db = new AltalayiTicketDatabase();
        
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'admin_init'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
    }
    
    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        // Main menu page - no capability check required anymore
        add_menu_page(
            __('Tire Tickets', 'altalayi-ticket'),
            __('Tire Tickets', 'altalayi-ticket'),
            'read', // Changed from 'manage_altalayi_tickets' to 'read'
            'altalayi-tickets',
            array($this, 'dashboard_page'),
            'dashicons-tickets-alt',
            30
        );
        
        // Dashboard submenu
        add_submenu_page(
            'altalayi-tickets',
            __('Dashboard', 'altalayi-ticket'),
            __('Dashboard', 'altalayi-ticket'),
            'read', // Changed from 'manage_altalayi_tickets' to 'read'
            'altalayi-tickets',
            array($this, 'dashboard_page')
        );
        
        // Open tickets submenu
        add_submenu_page(
            'altalayi-tickets',
            __('Open Tickets', 'altalayi-ticket'),
            __('Open Tickets', 'altalayi-ticket'),
            'read', // Changed from 'manage_altalayi_tickets' to 'read'
            'altalayi-open-tickets',
            array($this, 'open_tickets_page')
        );
        
        // Closed tickets submenu
        add_submenu_page(
            'altalayi-tickets',
            __('Closed Tickets', 'altalayi-ticket'),
            __('Closed Tickets', 'altalayi-ticket'),
            'read', // Changed from 'manage_altalayi_tickets' to 'read'
            'altalayi-closed-tickets',
            array($this, 'closed_tickets_page')
        );
        
        // View ticket submenu (hidden from menu)
        add_submenu_page(
            null, // Hidden submenu
            __('View Ticket', 'altalayi-ticket'),
            __('View Ticket', 'altalayi-ticket'),
            'read', // Changed from 'manage_altalayi_tickets' to 'read'
            'altalayi-view-ticket',
            array($this, 'view_ticket_page')
        );
        
        // Ticket statuses submenu
        add_submenu_page(
            'altalayi-tickets',
            __('Ticket Statuses', 'altalayi-ticket'),
            __('Ticket Statuses', 'altalayi-ticket'),
            'read', // Changed from 'manage_altalayi_tickets' to 'read'
            'altalayi-ticket-statuses',
            array($this, 'statuses_page')
        );
        
        // Settings submenu
        add_submenu_page(
            'altalayi-tickets',
            __('Settings', 'altalayi-ticket'),
            __('Settings', 'altalayi-ticket'),
            'read', // Changed from 'manage_altalayi_tickets' to 'read'
            'altalayi-ticket-settings',
            array($this, 'settings_page')
        );
    }
    
    /**
     * Admin initialization
     */
    public function admin_init() {
        // Register settings
        register_setting('altalayi_ticket_settings', 'altalayi_ticket_settings');
    }
    
    /**
     * Enqueue admin scripts and styles
     */
    public function enqueue_admin_scripts($hook) {
        // Only load on our admin pages
        if (strpos($hook, 'altalayi') === false) {
            return;
        }
        
        // Enqueue WordPress built-in scripts
        wp_enqueue_script('jquery');
        
        // Enqueue our admin CSS
        wp_enqueue_style(
            'altalayi-admin-css',
            ALTALAYI_TICKET_PLUGIN_URL . 'assets/css/admin.css',
            array(),
            '1.0.0'
        );
        
        // Enqueue our admin JS
        wp_enqueue_script(
            'altalayi-admin-js',
            ALTALAYI_TICKET_PLUGIN_URL . 'assets/js/admin.js',
            array('jquery'),
            '1.0.0',
            true
        );
        
        // Localize script for AJAX
        wp_localize_script('altalayi-admin-js', 'altalayi_admin_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('altalayi_admin_nonce'),
            'strings' => array(
                'confirm_delete' => __('Are you sure you want to delete this item?', 'altalayi-ticket'),
                'error_occurred' => __('An error occurred. Please try again.', 'altalayi-ticket'),
                'saving' => __('Saving...', 'altalayi-ticket'),
                'loading' => __('Loading...', 'altalayi-ticket')
            )
        ));
    }
    
    /**
     * Dashboard page
     */
    public function dashboard_page() {
        $stats = $this->db->get_statistics();
        $recent_tickets = $this->db->get_recent_tickets(10);
        
        include ALTALAYI_TICKET_PLUGIN_PATH . 'templates/admin/dashboard.php';
    }
    
    /**
     * Open tickets page
     */
    public function open_tickets_page() {
        $filters = $this->get_filters();
        $tickets = $this->db->get_tickets_by_status('open', $filters);
        $statuses = $this->db->get_statuses();
        $employees = get_users(); // Get all users since we removed role restrictions
        
        include ALTALAYI_TICKET_PLUGIN_PATH . 'templates/admin/open-tickets.php';
    }
    
    /**
     * Closed tickets page
     */
    public function closed_tickets_page() {
        $filters = $this->get_filters();
        $tickets = $this->db->get_tickets_by_status('closed', $filters);
        
        include ALTALAYI_TICKET_PLUGIN_PATH . 'templates/admin/closed-tickets.php';
    }
    
    /**
     * View ticket page
     */
    public function view_ticket_page() {
        $ticket_id = intval($_GET['ticket_id'] ?? 0);
        
        if (!$ticket_id) {
            wp_die(__('Invalid ticket ID', 'altalayi-ticket'));
        }
        
        $ticket = $this->db->get_ticket($ticket_id);
        
        if (!$ticket) {
            wp_die(__('Ticket not found', 'altalayi-ticket'));
        }
        
        $attachments = $this->db->get_ticket_attachments($ticket_id);
        $updates = $this->db->get_ticket_updates($ticket_id);
        $statuses = $this->db->get_statuses();
        $employees = get_users(); // Get all users since we removed role restrictions
        
        include ALTALAYI_TICKET_PLUGIN_PATH . 'templates/admin/view-ticket.php';
    }
    
    /**
     * Ticket statuses page
     */
    public function statuses_page() {
        $statuses = $this->db->get_statuses();
        
        include ALTALAYI_TICKET_PLUGIN_PATH . 'templates/admin/statuses.php';
    }
    
    /**
     * Settings page
     */
    public function settings_page() {
        $settings = get_option('altalayi_ticket_settings', array());
        
        include ALTALAYI_TICKET_PLUGIN_PATH . 'templates/admin/settings.php';
    }
    
    /**
     * Get filters from request
     */
    private function get_filters() {
        return array(
            'customer_name' => sanitize_text_field($_GET['customer_name'] ?? ''),
            'customer_phone' => sanitize_text_field($_GET['customer_phone'] ?? ''),
            'date_from' => sanitize_text_field($_GET['date_from'] ?? ''),
            'date_to' => sanitize_text_field($_GET['date_to'] ?? ''),
            'status_id' => intval($_GET['status_id'] ?? 0),
            'assigned_to' => intval($_GET['assigned_to'] ?? 0)
        );
    }
}