<?php
/**
 * Elementor Integration for Altalayi Ticket System
 */

if (!defined('ABSPATH')) {
    exit;
}

class AltalayiTicketElementor {
    
    public function __construct() {
        add_action('elementor/widgets/widgets_registered', array($this, 'register_widgets'));
        add_action('elementor/elements/categories_registered', array($this, 'add_widget_categories'));
        add_action('elementor/frontend/after_enqueue_styles', array($this, 'enqueue_styles'));
    }
    
    /**
     * Add custom widget category
     */
    public function add_widget_categories($elements_manager) {
        $elements_manager->add_category(
            'altalayi-ticket',
            array(
                'title' => __('Altalayi Ticket System', 'altalayi-ticket'),
                'icon' => 'fa fa-ticket',
            )
        );
    }
    
    /**
     * Register widgets
     */
    public function register_widgets() {
        // Include widget files
        require_once ALTALAYI_TICKET_PLUGIN_PATH . 'includes/elementor/widgets/ticket-form-widget.php';
        require_once ALTALAYI_TICKET_PLUGIN_PATH . 'includes/elementor/widgets/ticket-login-widget.php';
        require_once ALTALAYI_TICKET_PLUGIN_PATH . 'includes/elementor/widgets/ticket-view-widget.php';
        
        // Register widgets
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \AltalayiTicketFormWidget());
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \AltalayiTicketLoginWidget());
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \AltalayiTicketViewWidget());
    }
    
    /**
     * Enqueue Elementor-specific styles
     */
    public function enqueue_styles() {
        wp_enqueue_style(
            'altalayi-ticket-elementor',
            ALTALAYI_TICKET_PLUGIN_URL . 'assets/css/elementor.css',
            array('altalayi-frontend-css'),
            ALTALAYI_TICKET_VERSION
        );
    }
}

// Initialize if Elementor is active
if (class_exists('\Elementor\Plugin')) {
    new AltalayiTicketElementor();
}
