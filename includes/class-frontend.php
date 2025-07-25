<?php
/**
 * Frontend interface for Altalayi Ticket System
 */

if (!defined('ABSPATH')) {
    exit;
}

class AltalayiTicketFrontend {
    
    private $db;
    private $current_action;
    private $current_ticket_number;
    private $current_ticket;
    private $current_phone;
    
    public function __construct() {
        $this->db = new AltalayiTicketDatabase();
        
        add_action('init', array($this, 'init'));
        add_action('init', array($this, 'add_rewrite_rules'));
        add_action('wp', array($this, 'handle_custom_pages'), 1); // Higher priority
        add_shortcode('altalayi_ticket_form', array($this, 'ticket_form_shortcode'));
        add_shortcode('altalayi_ticket_login', array($this, 'ticket_login_shortcode'));
        add_shortcode('altalayi_ticket_view', array($this, 'ticket_view_shortcode'));
        add_action('wp_ajax_nopriv_submit_ticket', array($this, 'ajax_submit_ticket'));
        add_action('wp_ajax_nopriv_login_ticket', array($this, 'ajax_login_ticket'));
        add_action('wp_ajax_nopriv_add_customer_response', array($this, 'ajax_add_customer_response'));
        add_action('wp_ajax_submit_ticket', array($this, 'ajax_submit_ticket'));
        add_action('wp_ajax_login_ticket', array($this, 'ajax_login_ticket'));
        add_action('wp_ajax_add_customer_response', array($this, 'ajax_add_customer_response'));
    }
    
    /**
     * Get the URL for the create ticket page from settings
     */
    public function get_create_ticket_url() {
        return altalayi_get_create_ticket_url();
    }
    
    /**
     * Get the URL for the access ticket page from settings
     */
    public function get_access_ticket_url() {
        return altalayi_get_access_ticket_url();
    }
    
    /**
     * Add rewrite rules for custom URLs
     */
    public function add_rewrite_rules() {
        add_rewrite_rule('^ticket/([^/]+)/?', 'index.php?altalayi_ticket_action=view&ticket_number=$matches[1]', 'top');
        add_rewrite_rule('^ticket-login/?', 'index.php?altalayi_ticket_action=login', 'top');
        add_rewrite_rule('^new-ticket/?', 'index.php?altalayi_ticket_action=create', 'top');
        
        // Add query vars
        add_filter('query_vars', array($this, 'add_query_vars'));
        
        // Check if we need to flush rewrite rules
        $rules = get_option('rewrite_rules');
        if (!isset($rules['^new-ticket/?']) || !isset($rules['^ticket-login/?'])) {
            flush_rewrite_rules(false);
        }
    }
    
    /**
     * Initialize frontend functionality
     */
    public function init() {
        // Start session if not already started
        if (!session_id()) {
            session_start();
        }
    }
    
    /**
     * Add custom query vars
     */
    public function add_query_vars($vars) {
        $vars[] = 'altalayi_ticket_action';
        $vars[] = 'ticket_number';
        return $vars;
    }
    
    /**
     * Handle custom pages
     */
    public function handle_custom_pages() {
        global $wp;
        
        // Get the current request
        $request = $wp->request;
        
        // Remove trailing slash
        $request = trim($request, '/');
        
        // Check for our custom pages
        switch ($request) {
            case 'new-ticket':
                $this->serve_page('create');
                break;
            case 'ticket-login':
                $this->serve_page('login');
                break;
            default:
                // Check for ticket view URL
                if (preg_match('/^ticket\/([^\/]+)$/', $request, $matches)) {
                    $this->serve_page('view', $matches[1]);
                }
                break;
        }
    }
    
    /**
     * Serve a custom page using WordPress theme integration
     */
    private function serve_page($action, $ticket_number = null) {
        // Start session if needed
        if (!session_id()) {
            session_start();
        }
        
        // Set status header
        status_header(200);
        
        // Load WordPress theme properly
        nocache_headers();
        
        // Set up page data for theme integration
        global $wp_query, $post;
        
        // Create a fake post object for theme compatibility
        $post = new stdClass();
        $post->ID = 0;
        $post->post_author = 1;
        $post->post_date = current_time('mysql');
        $post->post_date_gmt = current_time('mysql', 1);
        $post->post_content = $this->get_page_content($action, $ticket_number);
        $post->post_title = $this->get_page_title($action, $ticket_number);
        $post->post_excerpt = '';
        $post->post_status = 'publish';
        $post->comment_status = 'closed';
        $post->ping_status = 'closed';
        $post->post_password = '';
        $post->post_name = $action;
        $post->to_ping = '';
        $post->pinged = '';
        $post->post_modified = current_time('mysql');
        $post->post_modified_gmt = current_time('mysql', 1);
        $post->post_content_filtered = '';
        $post->post_parent = 0;
        $post->guid = home_url('/' . $action);
        $post->menu_order = 0;
        $post->post_type = 'page';
        $post->post_mime_type = '';
        $post->comment_count = 0;
        $post->filter = 'raw';
        
        // Set up query
        $wp_query->is_page = true;
        $wp_query->is_singular = true;
        $wp_query->is_home = false;
        $wp_query->is_archive = false;
        $wp_query->is_category = false;
        $wp_query->queried_object = $post;
        $wp_query->queried_object_id = 0;
        $wp_query->post = $post;
        $wp_query->posts = array($post);
        $wp_query->post_count = 1;
        $wp_query->found_posts = 1;
        $wp_query->max_num_pages = 1;
        
        // Make sure theme stylesheets are loaded
        add_action('wp_enqueue_scripts', array($this, 'enqueue_theme_compatible_styles'), 999);
        add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend_scripts'));
        
        // Add filter to modify page content
        add_filter('the_content', array($this, 'filter_ticket_page_content'), 999);
        
        // Add body classes for theme compatibility
        add_filter('body_class', array($this, 'add_ticket_body_classes'));
        
        // Store current action and ticket number for content filter
        $this->current_action = $action;
        $this->current_ticket_number = $ticket_number;
        
        // Load the theme template
        get_header();
        ?>
        <div class="altalayi-ticket-wrapper">
            <div class="container">
                <div class="content-area">
                    <main class="site-main">
                        <article class="altalayi-ticket-page">
                            <div class="entry-content">
                                <?php echo $this->get_page_content($action, $ticket_number); ?>
                            </div>
                        </article>
                    </main>
                </div>
            </div>
        </div>
        <?php
        get_footer();
        exit;
    }
    
    /**
     * Get page title for theme integration
     */
    private function get_page_title($action, $ticket_number = null) {
        switch ($action) {
            case 'create':
                return __('Submit New Ticket - Altalayi Tire Support', 'altalayi-ticket');
            case 'login':
                return __('Ticket Login - Altalayi Tire Support', 'altalayi-ticket');
            case 'view':
                return sprintf(__('Ticket #%s - Altalayi Tire Support', 'altalayi-ticket'), esc_html($ticket_number));
            default:
                return __('Altalayi Tire Support', 'altalayi-ticket');
        }
    }
    
    /**
     * Get page content for theme integration
     */
    private function get_page_content($action, $ticket_number = null) {
        ob_start();
        
        switch ($action) {
            case 'create':
                include ALTALAYI_TICKET_PLUGIN_PATH . 'templates/frontend/ticket-create.php';
                break;
            case 'login':
                include ALTALAYI_TICKET_PLUGIN_PATH . 'templates/frontend/ticket-login.php';
                break;
            case 'view':
                $this->display_ticket_view_content($ticket_number);
                break;
        }
        
        return ob_get_clean();
    }
    
    /**
     * Enqueue theme-compatible styles
     */
    public function enqueue_theme_compatible_styles() {
        wp_enqueue_style('altalayi-ticket-theme-compat', ALTALAYI_TICKET_PLUGIN_URL . 'assets/css/theme-compat.css', array(), ALTALAYI_TICKET_VERSION);
    }
    
    /**
     * Enqueue frontend scripts and styles
     */
    public function enqueue_frontend_scripts() {
        // Only load on ticket pages
        if (!isset($this->current_action)) {
            return;
        }
        
        // Enqueue jQuery
        wp_enqueue_script('jquery');
        
        // Enqueue frontend CSS
        wp_enqueue_style(
            'altalayi-frontend-css',
            ALTALAYI_TICKET_PLUGIN_URL . 'assets/css/frontend.css',
            array(),
            ALTALAYI_TICKET_VERSION
        );
        
        // Enqueue frontend JS
        wp_enqueue_script(
            'altalayi-frontend-js',
            ALTALAYI_TICKET_PLUGIN_URL . 'assets/js/frontend.js',
            array('jquery'),
            ALTALAYI_TICKET_VERSION,
            true
        );
        
        // Localize script for AJAX
        wp_localize_script('altalayi-frontend-js', 'altalayi_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('altalayi_ticket_nonce'),
            'strings' => array(
                'uploading' => __('Uploading...', 'altalayi-ticket'),
                'upload_error' => __('Upload failed. Please try again.', 'altalayi-ticket'),
                'file_too_large' => __('File is too large. Maximum size is 5MB.', 'altalayi-ticket'),
                'invalid_file_type' => __('Invalid file type. Only images and PDF files are allowed.', 'altalayi-ticket'),
                'submitting' => __('Submitting...', 'altalayi-ticket'),
                'submit_error' => __('Submission failed. Please try again.', 'altalayi-ticket')
            )
        ));
    }
    
    /**
     * Filter page content for theme integration
     */
    public function filter_ticket_page_content($content) {
        if (isset($this->current_action)) {
            return $this->get_page_content($this->current_action, $this->current_ticket_number);
        }
        return $content;
    }
    
    /**
     * Add body classes for ticket pages
     */
    public function add_ticket_body_classes($classes) {
        if (isset($this->current_action)) {
            $classes[] = 'altalayi-ticket-page';
            $classes[] = 'altalayi-ticket-' . $this->current_action;
            
            // Add theme-specific classes
            $theme = get_template();
            if (strpos($theme, 'liquid') !== false) {
                $classes[] = 'liquid-site';
            }
            if (class_exists('Elementor\Plugin')) {
                $classes[] = 'elementor-page';
            }
        }
        return $classes;
    }
    
    /**
     * Display ticket view content
     */
    private function display_ticket_view_content($ticket_number) {
        if (!$ticket_number) {
            echo '<h1>' . __('Invalid ticket number', 'altalayi-ticket') . '</h1>';
            return;
        }
        
        // Check if customer is logged in for this ticket
        if (!isset($_SESSION['altalayi_ticket_' . $ticket_number])) {
            echo '<script>window.location.href = "' . esc_url($this->get_access_ticket_url()) . '";</script>';
            return;
        }
        
        $phone = $_SESSION['altalayi_ticket_' . $ticket_number];
        $ticket = $this->db->get_ticket_by_credentials($ticket_number, $phone);
        
        if (!$ticket) {
            echo '<h1>' . __('Ticket not found', 'altalayi-ticket') . '</h1>';
            return;
        }
        
        $attachments = $this->db->get_ticket_attachments($ticket->id);
        $updates = $this->db->get_ticket_updates($ticket->id, true);
        
        include ALTALAYI_TICKET_PLUGIN_PATH . 'templates/frontend/ticket-view.php';
    }    /**
     * Custom template include
     */
    public function template_include($template) {
        $action = get_query_var('altalayi_ticket_action');
        
        if ($action) {
            // Return a basic template that includes our content
            return ALTALAYI_TICKET_PLUGIN_PATH . 'templates/frontend/page-template.php';
        }
        
        return $template;
    }
    
    /**
     * Display ticket login page
     */
    private function display_ticket_login_page() {
        include ALTALAYI_TICKET_PLUGIN_PATH . 'templates/frontend/ticket-login.php';
    }
    
    /**
     * Display ticket create page
     */
    private function display_ticket_create_page() {
        include ALTALAYI_TICKET_PLUGIN_PATH . 'templates/frontend/ticket-create.php';
    }
    
    /**
     * Display ticket view page
     */
    private function display_ticket_view_page() {
        $ticket_number = get_query_var('ticket_number');
        
        if (!$ticket_number) {
            wp_redirect($this->get_access_ticket_url());
            exit;
        }
        
        // Check if user is logged in to this ticket via session
        session_start();
        if (!isset($_SESSION['altalayi_ticket_' . $ticket_number])) {
            wp_redirect($this->get_access_ticket_url());
            exit;
        }
        
        $phone = $_SESSION['altalayi_ticket_' . $ticket_number];
        $ticket = $this->db->get_ticket_by_credentials($ticket_number, $phone);
        
        if (!$ticket) {
            wp_redirect($this->get_access_ticket_url());
            exit;
        }
        
        $attachments = $this->db->get_ticket_attachments($ticket->id);
        $updates = $this->db->get_ticket_updates($ticket->id, true); // Customer view only
        
        include ALTALAYI_TICKET_PLUGIN_PATH . 'templates/frontend/ticket-view.php';
    }
    
    /**
     * Ticket form shortcode - for creating new tickets
     */
    public function ticket_form_shortcode($atts) {
        $atts = shortcode_atts(array(
            'title' => __('Submit New Ticket', 'altalayi-ticket'),
            'success_message' => __('Your ticket has been submitted successfully. Please save your ticket number and phone number for future reference.', 'altalayi-ticket'),
            'show_title' => 'true',
            'container_class' => 'altalayi-shortcode-container'
        ), $atts);
        
        // Ensure styles are loaded
        wp_enqueue_style('altalayi-ticket-style');
        wp_enqueue_style('altalayi-ticket-theme-compat');
        wp_enqueue_script('altalayi-ticket-script');
        
        ob_start();
        ?>
        <div class="<?php echo esc_attr($atts['container_class']); ?>">
            <?php if ($atts['show_title'] === 'true'): ?>
                <h2 class="altalayi-shortcode-title"><?php echo esc_html($atts['title']); ?></h2>
            <?php endif; ?>
            <div class="altalayi-ticket-content">
                <?php include ALTALAYI_TICKET_PLUGIN_PATH . 'templates/frontend/ticket-create.php'; ?>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
    
    /**
     * Ticket login shortcode - for accessing existing tickets
     */
    public function ticket_login_shortcode($atts) {
        $atts = shortcode_atts(array(
            'title' => __('Access Your Ticket', 'altalayi-ticket'),
            'show_title' => 'true',
            'container_class' => 'altalayi-shortcode-container',
            'redirect_after_login' => 'same_page'
        ), $atts);
        
        // Ensure styles are loaded
        wp_enqueue_style('altalayi-ticket-style');
        wp_enqueue_style('altalayi-ticket-theme-compat');
        wp_enqueue_script('altalayi-ticket-script');
        
        ob_start();
        ?>
        <div class="<?php echo esc_attr($atts['container_class']); ?>">
            <?php if ($atts['show_title'] === 'true'): ?>
                <h2 class="altalayi-shortcode-title"><?php echo esc_html($atts['title']); ?></h2>
            <?php endif; ?>
            <div class="altalayi-ticket-content">
                <?php include ALTALAYI_TICKET_PLUGIN_PATH . 'templates/frontend/ticket-login.php'; ?>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
    
    /**
     * Ticket view shortcode - for displaying specific ticket details
     */
    public function ticket_view_shortcode($atts) {
        $atts = shortcode_atts(array(
            'ticket_number' => '',
            'phone' => '',
            'auto_detect' => 'true',
            'show_title' => 'true',
            'container_class' => 'altalayi-shortcode-container'
        ), $atts);
        
        // Ensure styles are loaded
        wp_enqueue_style('altalayi-ticket-style');
        wp_enqueue_style('altalayi-ticket-theme-compat');
        wp_enqueue_script('altalayi-ticket-script');
        
        // Auto-detect from session or URL parameters if enabled
        if ($atts['auto_detect'] === 'true') {
            if (empty($atts['ticket_number']) && isset($_GET['ticket'])) {
                $atts['ticket_number'] = sanitize_text_field($_GET['ticket']);
            }
            if (empty($atts['phone']) && isset($_GET['phone'])) {
                $atts['phone'] = sanitize_text_field($_GET['phone']);
            }
            
            // Check session for logged-in ticket
            if (!session_id()) {
                session_start();
            }
            if (empty($atts['ticket_number']) && !empty($_SESSION['altalayi_current_ticket'])) {
                $atts['ticket_number'] = $_SESSION['altalayi_current_ticket'];
                $atts['phone'] = $_SESSION['altalayi_current_phone'];
            }
        }
        
        if (empty($atts['ticket_number']) || empty($atts['phone'])) {
            ob_start();
            ?>
            <div class="<?php echo esc_attr($atts['container_class']); ?>">
                <div class="altalayi-error-message">
                    <p><?php _e('Please login to view your ticket or provide ticket credentials.', 'altalayi-ticket'); ?></p>
                    <p><a href="#" class="altalayi-login-link"><?php _e('Click here to login', 'altalayi-ticket'); ?></a></p>
                </div>
            </div>
            <?php
            return ob_get_clean();
        }
        
        $ticket = $this->db->get_ticket_by_credentials($atts['ticket_number'], $atts['phone']);
        
        if (!$ticket) {
            ob_start();
            ?>
            <div class="<?php echo esc_attr($atts['container_class']); ?>">
                <div class="altalayi-error-message">
                    <p><?php _e('Ticket not found. Please check your ticket number and phone number.', 'altalayi-ticket'); ?></p>
                </div>
            </div>
            <?php
            return ob_get_clean();
        }
        
        ob_start();
        ?>
        <div class="<?php echo esc_attr($atts['container_class']); ?>">
            <?php if ($atts['show_title'] === 'true'): ?>
                <h2 class="altalayi-shortcode-title"><?php printf(__('Ticket #%s', 'altalayi-ticket'), esc_html($ticket->ticket_number)); ?></h2>
            <?php endif; ?>
            <div class="altalayi-ticket-content">
                <?php 
                // Set ticket data for the template
                $this->current_ticket = $ticket;
                $this->current_ticket_number = $atts['ticket_number'];
                $this->current_phone = $atts['phone'];
                $this->display_ticket_view_content($atts['ticket_number']); 
                ?>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
    
    /**
     * AJAX: Submit new ticket
     */
    public function ajax_submit_ticket() {
        if (!wp_verify_nonce($_POST['nonce'], 'altalayi_ticket_nonce')) {
            wp_send_json_error(array('message' => __('Security check failed', 'altalayi-ticket')));
        }
        
        // Validate required fields
        $required_fields = array('customer_name', 'customer_phone', 'customer_email', 'complaint_text');
        foreach ($required_fields as $field) {
            if (empty($_POST[$field])) {
                wp_send_json_error(array('message' => sprintf(__('Field %s is required', 'altalayi-ticket'), $field)));
            }
        }
        
        // Prepare ticket data
        $ticket_data = array(
            'customer_name' => sanitize_text_field($_POST['customer_name']),
            'customer_phone' => sanitize_text_field($_POST['customer_phone']),
            'customer_email' => sanitize_email($_POST['customer_email']),
            'complaint_text' => sanitize_textarea_field($_POST['complaint_text']),
            'tire_brand' => sanitize_text_field($_POST['tire_brand']),
            'tire_model' => sanitize_text_field($_POST['tire_model']),
            'tire_size' => sanitize_text_field($_POST['tire_size']),
            'purchase_date' => sanitize_text_field($_POST['purchase_date'])
        );
        
        // Create ticket
        $ticket_id = $this->db->create_ticket($ticket_data);
        
        if (!$ticket_id) {
            wp_send_json_error(array('message' => __('Failed to create ticket', 'altalayi-ticket')));
        }
        
        // Handle file uploads
        if (!empty($_FILES)) {
            $this->handle_file_uploads($ticket_id);
        }
        
        // Get the created ticket
        $ticket = $this->db->get_ticket($ticket_id);
        
        // Send confirmation email
        $email = new AltalayiTicketEmail();
        $email->send_ticket_created_notification($ticket_id);
        
        // Set session for automatic login
        session_start();
        $_SESSION['altalayi_ticket_' . $ticket->ticket_number] = $ticket_data['customer_phone'];
        
        wp_send_json_success(array(
            'message' => __('Ticket created successfully', 'altalayi-ticket'),
            'ticket_number' => $ticket->ticket_number,
            'redirect_url' => home_url('/ticket/' . $ticket->ticket_number)
        ));
    }
    
    /**
     * AJAX: Login to ticket
     */
    public function ajax_login_ticket() {
        if (!wp_verify_nonce($_POST['nonce'], 'altalayi_ticket_nonce')) {
            wp_send_json_error(array('message' => __('Security check failed', 'altalayi-ticket')));
        }
        
        $ticket_number = sanitize_text_field($_POST['ticket_number']);
        $phone = sanitize_text_field($_POST['phone']);
        
        if (empty($ticket_number) || empty($phone)) {
            wp_send_json_error(array('message' => __('Please provide ticket number and phone number', 'altalayi-ticket')));
        }
        
        $ticket = $this->db->get_ticket_by_credentials($ticket_number, $phone);
        
        if (!$ticket) {
            wp_send_json_error(array('message' => __('Invalid ticket number or phone number', 'altalayi-ticket')));
        }
        
        // Set session
        session_start();
        $_SESSION['altalayi_ticket_' . $ticket_number] = $phone;
        
        wp_send_json_success(array(
            'message' => __('Login successful', 'altalayi-ticket'),
            'redirect_url' => home_url('/ticket/' . $ticket_number)
        ));
    }
    
    /**
     * AJAX: Add customer response
     */
    public function ajax_add_customer_response() {
        if (!wp_verify_nonce($_POST['nonce'], 'altalayi_ticket_nonce')) {
            wp_send_json_error(array('message' => __('Security check failed', 'altalayi-ticket')));
        }
        
        $ticket_id = intval($_POST['ticket_id']);
        $response = sanitize_textarea_field($_POST['response']);
        
        if (empty($response)) {
            wp_send_json_error(array('message' => __('Please provide a response', 'altalayi-ticket')));
        }
        
        // Verify customer access to ticket
        $ticket = $this->db->get_ticket($ticket_id);
        session_start();
        
        if (!isset($_SESSION['altalayi_ticket_' . $ticket->ticket_number])) {
            wp_send_json_error(array('message' => __('Access denied', 'altalayi-ticket')));
        }
        
        // Add customer response
        $result = $this->db->add_ticket_update(
            $ticket_id,
            'customer_response',
            '',
            '',
            $response,
            0, // Customer ID = 0
            true
        );
        
        if ($result) {
            // Handle additional file uploads if any
            if (!empty($_FILES)) {
                $this->handle_file_uploads($ticket_id, 0);
            }
            
            // Send notification to assigned employee
            $email = new AltalayiTicketEmail();
            $email->send_customer_response_notification($ticket_id);
            
            wp_send_json_success(array('message' => __('Response added successfully', 'altalayi-ticket')));
        } else {
            wp_send_json_error(array('message' => __('Failed to add response', 'altalayi-ticket')));
        }
    }
    
    /**
     * Handle file uploads
     */
    private function handle_file_uploads($ticket_id, $uploaded_by = 0) {
        if (!function_exists('wp_handle_upload')) {
            require_once(ABSPATH . 'wp-admin/includes/file.php');
        }
        
        $upload_overrides = array('test_form' => false);
        
        // Create upload directory
        $upload_dir = wp_upload_dir();
        $ticket_upload_dir = $upload_dir['basedir'] . '/altalayi-tickets/' . $ticket_id;
        
        if (!file_exists($ticket_upload_dir)) {
            wp_mkdir_p($ticket_upload_dir);
        }
        
        foreach ($_FILES as $file_key => $file) {
            if ($file['error'] === UPLOAD_ERR_OK) {
                // Validate file type
                $allowed_types = array('image/jpeg', 'image/png', 'image/gif', 'application/pdf', 'image/webp');
                if (!in_array($file['type'], $allowed_types)) {
                    continue;
                }
                
                // Validate file size (max 5MB)
                if ($file['size'] > 5 * 1024 * 1024) {
                    continue;
                }
                
                $uploaded_file = wp_handle_upload($file, $upload_overrides);
                
                if ($uploaded_file && !isset($uploaded_file['error'])) {
                    // Determine attachment type
                    $attachment_type = 'additional';
                    if (strpos($file_key, 'tire_image') !== false) {
                        $attachment_type = 'tire_image';
                    } elseif (strpos($file_key, 'receipt') !== false) {
                        $attachment_type = 'receipt';
                    }
                    
                    // Save to database
                    $file_data = array(
                        'file_name' => $file['name'],
                        'file_path' => $uploaded_file['url'],
                        'file_type' => $file['type'],
                        'file_size' => $file['size'],
                        'attachment_type' => $attachment_type,
                        'uploaded_by' => $uploaded_by
                    );
                    
                    $this->db->add_attachment($ticket_id, $file_data);
                }
            }
        }
    }
    
    /**
     * Get attachment download URL
     */
    public function get_attachment_download_url($attachment_id) {
        return add_query_arg(array(
            'action' => 'download_attachment',
            'attachment_id' => $attachment_id,
            'nonce' => wp_create_nonce('download_attachment_' . $attachment_id)
        ), admin_url('admin-ajax.php'));
    }
}
