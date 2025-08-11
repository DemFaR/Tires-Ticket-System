<?php
/**
 * AJAX handlers for Altalayi Ticket System
 */

if (!defined('ABSPATH')) {
    exit;
}

class AltalayiTicketAjax {
    
    private $db;
    
    public function __construct() {
        $this->db = new AltalayiTicketDatabase();
        
        // Admin AJAX actions
        add_action('wp_ajax_altalayi_update_ticket_status', array($this, 'update_ticket_status'));
        add_action('wp_ajax_altalayi_assign_ticket', array($this, 'assign_ticket'));
        add_action('wp_ajax_altalayi_assign_to_me', array($this, 'assign_to_me'));
        add_action('wp_ajax_altalayi_add_ticket_note', array($this, 'add_ticket_note'));
        add_action('wp_ajax_altalayi_delete_attachment', array($this, 'delete_attachment'));
        add_action('wp_ajax_altalayi_bulk_action', array($this, 'bulk_action'));
        add_action('wp_ajax_altalayi_delete_ticket', array($this, 'delete_ticket'));
        add_action('wp_ajax_altalayi_cleanup_orphaned_files', array($this, 'cleanup_orphaned_files'));
        
        // Frontend AJAX actions
        add_action('wp_ajax_nopriv_altalayi_submit_ticket', array($this, 'submit_ticket'));
        add_action('wp_ajax_nopriv_altalayi_login_ticket', array($this, 'login_ticket'));
        add_action('wp_ajax_nopriv_altalayi_add_customer_response', array($this, 'add_customer_response'));
        add_action('wp_ajax_nopriv_altalayi_upload_attachment', array($this, 'upload_attachment'));
        
        // Both logged in and non-logged in users
        add_action('wp_ajax_altalayi_submit_ticket', array($this, 'submit_ticket'));
        add_action('wp_ajax_altalayi_login_ticket', array($this, 'login_ticket'));
        add_action('wp_ajax_altalayi_add_customer_response', array($this, 'add_customer_response'));
        add_action('wp_ajax_altalayi_upload_attachment', array($this, 'upload_attachment'));
        add_action('wp_ajax_altalayi_download_attachment', array($this, 'download_attachment'));
        add_action('wp_ajax_nopriv_altalayi_download_attachment', array($this, 'download_attachment'));
    }
    
    /**
     * Update ticket status
     */
    public function update_ticket_status() {
        if (!wp_verify_nonce($_POST['nonce'], 'altalayi_admin_nonce')) {
            wp_send_json_error(array('message' => __('Security check failed', 'altalayi-ticket')));
        }
        
        $ticket_id = intval($_POST['ticket_id']);
        $status_id = intval($_POST['status_id']);
        $notes = sanitize_textarea_field($_POST['notes']);
        
        $ticket = $this->db->get_ticket($ticket_id);
        
        if (!$ticket) {
            wp_send_json_error(array('message' => __('Ticket not found', 'altalayi-ticket')));
        }
        
        $old_status_name = $ticket->status_name;
        
        // Update status
        $result = $this->db->update_ticket($ticket_id, array('status_id' => $status_id));
        
        if ($result) {
            // Get new status name
            $new_status = $this->get_status_by_id($status_id);
            
            // Add update log
            $this->db->add_ticket_update(
                $ticket_id,
                'status_change',
                $old_status_name,
                $new_status->status_name,
                $notes,
                get_current_user_id(),
                true
            );
            
            // Send email notification
            $email = new AltalayiTicketEmail();
            $email->send_status_update_notification($ticket_id);
            
            wp_send_json_success(array(
                'message' => __('Status updated successfully', 'altalayi-ticket'),
                'new_status' => $new_status->status_name,
                'status_color' => $new_status->status_color
            ));
        } else {
            wp_send_json_error(array('message' => __('Failed to update status', 'altalayi-ticket')));
        }
    }
    
    /**
     * Assign ticket
     */
    public function assign_ticket() {
        if (!wp_verify_nonce($_POST['nonce'], 'altalayi_admin_nonce')) {
            wp_send_json_error(array('message' => __('Security check failed', 'altalayi-ticket')));
        }
        
        $ticket_id = intval($_POST['ticket_id']);
        $assigned_to = intval($_POST['assigned_to']);
        
        $ticket = $this->db->get_ticket($ticket_id);
        
        if (!$ticket) {
            wp_send_json_error(array('message' => __('Ticket not found', 'altalayi-ticket')));
        }
        
        $old_assigned = $ticket->assigned_user_name;
        
        // Only find "Assigned" status if current status is "Open" and assigning to someone (not unassigning)
        $assigned_status = null;
        if ($assigned_to > 0 && strtolower($ticket->status_name) === 'open') {
            // First try to get it by name
            $assigned_status = $this->db->get_status_by_name('Assigned');
            if (!$assigned_status) {
                // If "Assigned" doesn't exist, try other common names
                $assigned_status = $this->db->get_status_by_name('In Progress');
                if (!$assigned_status) {
                    $assigned_status = $this->db->get_status_by_name('Working');
                }
            }
        }
        
        // Update assignment
        $assignment_result = $this->db->update_ticket($ticket_id, array('assigned_to' => $assigned_to));
        
        // Update status only if we found an "Assigned" status and current status is "Open"
        $status_result = true;
        if ($assigned_status && strtolower($ticket->status_name) === 'open') {
            $status_result = $this->db->update_ticket($ticket_id, array('status_id' => $assigned_status->id));
        }
        
        if ($assignment_result) {
            $assigned_user = $assigned_to ? get_user_by('ID', $assigned_to) : null;
            $assigned_user_name = $assigned_user ? $assigned_user->display_name : '';
            
            // Add assignment update log
            $this->db->add_ticket_update(
                $ticket_id,
                'assignment',
                $old_assigned,
                $assigned_user_name,
                'Ticket assigned',
                get_current_user_id(),
                true
            );
            
            // Add status change log if status was changed (only for Open tickets)
            if ($assigned_status && strtolower($ticket->status_name) === 'open' && $status_result) {
                $this->db->add_ticket_update(
                    $ticket_id,
                    'status_change',
                    $ticket->status_name,
                    $assigned_status->status_name,
                    'Status automatically changed due to assignment',
                    get_current_user_id(),
                    true
                );
            }
            
            // Send assignment notification
            if ($assigned_to > 0) {
                $email = new AltalayiTicketEmail();
                $email->send_assignment_notification($ticket_id, $assigned_to);
            }
            
            wp_send_json_success(array(
                'message' => __('Ticket assigned successfully', 'altalayi-ticket'),
                'assigned_user' => $assigned_user_name,
                'status_changed' => $assigned_status && strtolower($ticket->status_name) === 'open' ? true : false,
                'new_status' => $assigned_status && strtolower($ticket->status_name) === 'open' ? $assigned_status->status_name : null
            ));
        } else {
            wp_send_json_error(array('message' => __('Failed to assign ticket', 'altalayi-ticket')));
        }
    }
    
    /**
     * Assign ticket to current user and change status to "Assigned"
     */
    public function assign_to_me() {
        if (!wp_verify_nonce($_POST['nonce'], 'altalayi_admin_nonce')) {
            wp_send_json_error(array('message' => __('Security check failed', 'altalayi-ticket')));
        }
        
        $ticket_id = intval($_POST['ticket_id']);
        $current_user_id = get_current_user_id();
        
        if (!$current_user_id) {
            wp_send_json_error(array('message' => __('You must be logged in to assign tickets', 'altalayi-ticket')));
        }
        
        $ticket = $this->db->get_ticket($ticket_id);
        if (!$ticket) {
            wp_send_json_error(array('message' => __('Ticket not found', 'altalayi-ticket')));
        }
        
        // Check if ticket is already assigned to current user
        if ($ticket->assigned_to == $current_user_id) {
            wp_send_json_error(array('message' => __('Ticket is already assigned to you', 'altalayi-ticket')));
        }
        
        $old_assigned = $ticket->assigned_user_name;
        $current_user = get_user_by('ID', $current_user_id);
        
        // Only find "Assigned" status if current status is "Open"
        $assigned_status = null;
        if (strtolower($ticket->status_name) === 'open') {
            // First try to get it by name
            $assigned_status = $this->db->get_status_by_name('Assigned');
            if (!$assigned_status) {
                // If "Assigned" doesn't exist, try other common names
                $assigned_status = $this->db->get_status_by_name('In Progress');
                if (!$assigned_status) {
                    $assigned_status = $this->db->get_status_by_name('Working');
                }
            }
        }
        
        // Update assignment
        $assignment_result = $this->db->update_ticket($ticket_id, array('assigned_to' => $current_user_id));
        
        // Update status only if we found an "Assigned" status and current status is "Open"
        $status_result = true;
        if ($assigned_status && strtolower($ticket->status_name) === 'open') {
            $status_result = $this->db->update_ticket($ticket_id, array('status_id' => $assigned_status->id));
        }
        
        if ($assignment_result) {
            // Add assignment update log
            $this->db->add_ticket_update(
                $ticket_id,
                'assignment',
                $old_assigned,
                $current_user->display_name,
                'Ticket self-assigned',
                $current_user_id,
                true
            );
            
            // Add status change log if status was changed (only for Open tickets)
            if ($assigned_status && strtolower($ticket->status_name) === 'open' && $status_result) {
                $this->db->add_ticket_update(
                    $ticket_id,
                    'status_change',
                    $ticket->status_name,
                    $assigned_status->status_name,
                    'Status automatically changed due to assignment',
                    $current_user_id,
                    true
                );
            }
            
            // Send assignment notification
            $email = new AltalayiTicketEmail();
            $email->send_assignment_notification($ticket_id, $current_user_id);
            
            wp_send_json_success(array(
                'message' => __('Ticket successfully assigned to you', 'altalayi-ticket'),
                'assigned_user' => $current_user->display_name,
                'status_changed' => $assigned_status ? true : false,
                'new_status' => $assigned_status ? $assigned_status->status_name : null
            ));
        } else {
            wp_send_json_error(array('message' => __('Failed to assign ticket', 'altalayi-ticket')));
        }
    }
    
    /**
     * Add ticket note
     */
    public function add_ticket_note() {
        if (!wp_verify_nonce($_POST['nonce'], 'altalayi_admin_nonce')) {
            wp_send_json_error(array('message' => __('Security check failed', 'altalayi-ticket')));
        }
        
        $ticket_id = intval($_POST['ticket_id']);
        $note = sanitize_textarea_field($_POST['note']);
        
        // Fix: Properly handle checkbox value
        $visible_to_customer = isset($_POST['visible_to_customer']) && $_POST['visible_to_customer'] == '1' ? 1 : 0;
        
        if (empty($note)) {
            wp_send_json_error(array('message' => __('Please provide a note', 'altalayi-ticket')));
        }
        
        $result = $this->db->add_ticket_update(
            $ticket_id,
            'note',
            '',
            '',
            $note,
            get_current_user_id(),
            $visible_to_customer
        );
        
        if ($result) {
            $user = get_user_by('ID', get_current_user_id());
            
            // Send email notification to customer if note is visible to them
            if ($visible_to_customer) {
                $email = new AltalayiTicketEmail();
                $email->send_employee_response_notification($ticket_id);
            }
            
            wp_send_json_success(array(
                'message' => __('Note added successfully', 'altalayi-ticket'),
                'note_html' => $this->format_note_html($note, $user->display_name, current_time('mysql'), $visible_to_customer)
            ));
        } else {
            wp_send_json_error(array('message' => __('Failed to add note', 'altalayi-ticket')));
        }
    }
    
    /**
     * Submit new ticket
     */
    public function submit_ticket() {
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
        
        // Validate email
        if (!is_email($_POST['customer_email'])) {
            wp_send_json_error(array('message' => __('Please provide a valid email address', 'altalayi-ticket')));
        }
        
        // Prepare tire size from individual components - COMMENTED OUT since tire size fields are hidden
        /*
        $tire_width = sanitize_text_field($_POST['tire_size_width']);
        $tire_aspect = sanitize_text_field($_POST['tire_size_aspect']);
        $tire_diameter = sanitize_text_field($_POST['tire_size_diameter']);
        $tire_size = '';
        
        if (!empty($tire_width) && !empty($tire_aspect) && !empty($tire_diameter)) {
            $tire_size = $tire_width . '/' . $tire_aspect . 'R' . $tire_diameter;
        }
        */
        
        // Prepare ticket data
        $ticket_data = array(
            'customer_name' => sanitize_text_field($_POST['customer_name']),
            'customer_phone' => sanitize_text_field($_POST['customer_phone']),
            'customer_email' => sanitize_email($_POST['customer_email']),
            'customer_city' => sanitize_text_field($_POST['customer_city'] ?? ''),
            'motocare_center_visited' => sanitize_text_field($_POST['motocare_center_visited'] ?? ''),
            'complaint_text' => sanitize_textarea_field($_POST['complaint_text']),
            'tire_brand' => sanitize_text_field($_POST['tire_brand']),
            'tire_model' => sanitize_text_field($_POST['tire_model']),
            'tire_size' => '', // Keep empty since tire size fields are hidden
            'number_of_tires' => intval($_POST['number_of_tires'] ?? 0),
            'tire_position' => sanitize_text_field($_POST['tire_position'] ?? ''),
            'air_pressure' => sanitize_text_field($_POST['air_pressure'] ?? ''),
            'tread_depth' => sanitize_text_field($_POST['tread_depth'] ?? ''),
            'purchase_date' => sanitize_text_field($_POST['purchase_date']),
            'purchase_location' => sanitize_text_field($_POST['purchase_location'] ?? ''),
            'mileage' => 0 // Keep as 0 since mileage field is hidden
        );
        
        // Create ticket
        $ticket_id = $this->db->create_ticket($ticket_data);
        
        if (!$ticket_id) {
            wp_send_json_error(array('message' => __('Failed to create ticket', 'altalayi-ticket')));
        }
        
        // Handle file uploads
        if (!empty($_FILES)) {
            $this->handle_file_uploads($ticket_id, 0);
        }
        
        // Get the created ticket
        $ticket = $this->db->get_ticket($ticket_id);
        
        // Send confirmation email
        $email = new AltalayiTicketEmail();
        $email->send_ticket_created_notification($ticket_id);
        
        // Set session for automatic login
        if (!session_id()) {
            session_start();
        }
        $_SESSION['altalayi_ticket_' . $ticket->ticket_number] = $ticket_data['customer_phone'];
        
        wp_send_json_success(array(
            'message' => __('Ticket created successfully', 'altalayi-ticket'),
            'ticket_number' => $ticket->ticket_number,
            'redirect_url' => altalayi_get_ticket_url($ticket->ticket_number)
        ));
    }
    
    /**
     * Login to ticket
     */
    public function login_ticket() {
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
        if (!session_id()) {
            session_start();
        }
        $_SESSION['altalayi_ticket_' . $ticket_number] = $phone;
        
        wp_send_json_success(array(
            'message' => __('Login successful', 'altalayi-ticket'),
            'redirect_url' => altalayi_get_ticket_url($ticket_number)
        ));
    }
    
    /**
     * Add customer response
     */
    public function add_customer_response() {
        // Check if this is a real AJAX request or direct form submission
        $is_ajax_request = wp_doing_ajax() && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
        
        if (!wp_verify_nonce($_POST['nonce'], 'altalayi_ticket_nonce')) {
            if ($is_ajax_request) {
                wp_send_json_error(array('message' => __('Security check failed', 'altalayi-ticket')));
            } else {
                wp_die(__('Security check failed', 'altalayi-ticket'));
            }
        }
        
        $ticket_id = intval($_POST['ticket_id']);
        $response = sanitize_textarea_field($_POST['response']);
        
        if (empty($response)) {
            if ($is_ajax_request) {
                wp_send_json_error(array('message' => __('Please provide a response', 'altalayi-ticket')));
            } else {
                wp_die(__('Please provide a response', 'altalayi-ticket'));
            }
        }
        
        // Verify customer access to ticket
        $ticket = $this->db->get_ticket($ticket_id);
        if (!session_id()) {
            session_start();
        }
        
        if (!isset($_SESSION['altalayi_ticket_' . $ticket->ticket_number])) {
            if ($is_ajax_request) {
                wp_send_json_error(array('message' => __('Access denied', 'altalayi-ticket')));
            } else {
                wp_die(__('Access denied', 'altalayi-ticket'));
            }
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
            if (!empty($_FILES['additional_files'])) {
                $this->handle_file_uploads($ticket_id, 0);
            }
            
            // Send notification to assigned employee
            $email = new AltalayiTicketEmail();
            $email->send_customer_response_notification($ticket_id);
            
            // Check if this is a real AJAX request or direct form submission
            $is_ajax_request = wp_doing_ajax() && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
            
            if ($is_ajax_request) {
                wp_send_json_success(array('message' => __('Response added successfully', 'altalayi-ticket')));
            } else {
                // Redirect back to ticket page without URL parameters
                $redirect_url = altalayi_get_ticket_url($ticket->ticket_number);
                wp_redirect($redirect_url);
                exit;
            }
        } else {
            if ($is_ajax_request) {
                wp_send_json_error(array('message' => __('Failed to add response', 'altalayi-ticket')));
            } else {
                wp_die(__('Failed to add response', 'altalayi-ticket'));
            }
        }
    }
    
    /**
     * Upload attachment
     */
    public function upload_attachment() {
        if (!wp_verify_nonce($_POST['nonce'], 'altalayi_ticket_nonce')) {
            wp_send_json_error(array('message' => __('Security check failed', 'altalayi-ticket')));
        }
        
        $ticket_id = intval($_POST['ticket_id']);
        
        if (empty($_FILES['file'])) {
            wp_send_json_error(array('message' => __('No file provided', 'altalayi-ticket')));
        }
        
        $uploaded_by = is_user_logged_in() ? get_current_user_id() : 0;
        
        // Verify access
        if ($uploaded_by === 0) {
            $ticket = $this->db->get_ticket($ticket_id);
            if (!session_id()) {
                session_start();
            }
            
            if (!isset($_SESSION['altalayi_ticket_' . $ticket->ticket_number])) {
                wp_send_json_error(array('message' => __('Access denied', 'altalayi-ticket')));
            }
        }
        
        $result = $this->handle_single_file_upload($ticket_id, $_FILES['file'], $uploaded_by);
        
        if ($result) {
            wp_send_json_success(array(
                'message' => __('File uploaded successfully', 'altalayi-ticket'),
                'attachment' => $result
            ));
        } else {
            wp_send_json_error(array('message' => __('Failed to upload file', 'altalayi-ticket')));
        }
    }
    
    /**
     * Download attachment
     */
    public function download_attachment() {
        $attachment_id = intval($_GET['attachment_id']);
        $nonce = $_GET['nonce'];
        
        if (!wp_verify_nonce($nonce, 'download_attachment_' . $attachment_id)) {
            wp_die(__('Security check failed', 'altalayi-ticket'));
        }
        
        global $wpdb;
        $table = $wpdb->prefix . 'altalayi_ticket_attachments';
        $attachment = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$table} WHERE id = %d", $attachment_id));
        
        if (!$attachment) {
            wp_die(__('Attachment not found', 'altalayi-ticket'));
        }
        
        // Access control removed - file access allowed
        
        // Serve file
        $file_path = str_replace(wp_upload_dir()['baseurl'], wp_upload_dir()['basedir'], $attachment->file_path);
        
        if (!file_exists($file_path)) {
            wp_die(__('File not found', 'altalayi-ticket'));
        }
        
        header('Content-Type: ' . $attachment->file_type);
        header('Content-Disposition: attachment; filename="' . $attachment->file_name . '"');
        header('Content-Length: ' . $attachment->file_size);
        
        readfile($file_path);
        exit;
    }
    
    /**
     * Handle file uploads
     */
    private function handle_file_uploads($ticket_id, $uploaded_by = 0) {
        if (!function_exists('wp_handle_upload')) {
            require_once(ABSPATH . 'wp-admin/includes/file.php');
        }
        
        foreach ($_FILES as $file_key => $file_data) {
            // Check if this is a single file or multiple files
            if (is_array($file_data['name'])) {
                // Multiple files (e.g., tire_images[], additional_files[])
                $file_count = count($file_data['name']);
                for ($i = 0; $i < $file_count; $i++) {
                    if ($file_data['error'][$i] === UPLOAD_ERR_OK) {
                        $single_file = array(
                            'name' => $file_data['name'][$i],
                            'type' => $file_data['type'][$i],
                            'tmp_name' => $file_data['tmp_name'][$i],
                            'error' => $file_data['error'][$i],
                            'size' => $file_data['size'][$i]
                        );
                        $this->handle_single_file_upload($ticket_id, $single_file, $uploaded_by, $file_key);
                    }
                }
            } else {
                // Single file (e.g., receipt_image)
                if ($file_data['error'] === UPLOAD_ERR_OK) {
                    $this->handle_single_file_upload($ticket_id, $file_data, $uploaded_by, $file_key);
                }
            }
        }
    }
    
    /**
     * Handle single file upload
     */
    private function handle_single_file_upload($ticket_id, $file, $uploaded_by = 0, $file_key = '') {
        if (!function_exists('wp_handle_upload')) {
            require_once(ABSPATH . 'wp-admin/includes/file.php');
        }
        
        // Validate file type
        $allowed_types = array(
            'image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp', 
            'image/bmp', 'image/tiff', 'image/tif',
            'application/pdf',
            'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'text/plain', 'application/rtf'
        );
        if (!in_array($file['type'], $allowed_types)) {
            return false;
        }
        
        // Validate file size (max 5MB)
        if ($file['size'] > 5 * 1024 * 1024) {
            return false;
        }
        
        $upload_overrides = array('test_form' => false);
        $uploaded_file = wp_handle_upload($file, $upload_overrides);
        
        if ($uploaded_file && !isset($uploaded_file['error'])) {
            // Determine attachment type based on form field name
            $attachment_type = 'additional'; // Default fallback
            
            if (strpos($file_key, 'tire_images') !== false) {
                $attachment_type = 'tire_image';
            } elseif (strpos($file_key, 'receipt_image') !== false) {
                $attachment_type = 'receipt';
            } elseif (strpos($file_key, 'additional_files') !== false) {
                $attachment_type = 'additional';
            }
            
            // Legacy support: if file_key contains old patterns
            if (strpos($file_key, 'tire_image') !== false && $attachment_type === 'additional') {
                $attachment_type = 'tire_image';
            }
            if (strpos($file_key, 'receipt') !== false && $attachment_type === 'additional') {
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
            
            $attachment_id = $this->db->add_attachment($ticket_id, $file_data);
            
            if ($attachment_id) {
                return array_merge($file_data, array('id' => $attachment_id));
            }
        }
        
        return false;
    }
    
    /**
     * Get status by ID
     */
    private function get_status_by_id($status_id) {
        global $wpdb;
        $table = $wpdb->prefix . 'altalayi_ticket_statuses';
        return $wpdb->get_row($wpdb->prepare("SELECT * FROM {$table} WHERE id = %d", $status_id));
    }
    
    /**
     * Format note HTML
     */
    private function format_note_html($note, $author, $date, $visible_to_customer) {
        $visibility = $visible_to_customer ? __('Visible to customer', 'altalayi-ticket') : __('Internal note', 'altalayi-ticket');
        $visibility_class = $visible_to_customer ? 'customer-visible' : 'internal';
        
        return sprintf(
            '<div class="ticket-update %s">
                <div class="update-header">
                    <strong>%s</strong>
                    <span class="update-date">%s</span>
                    <span class="visibility-badge %s">%s</span>
                </div>
                <div class="update-content">%s</div>
            </div>',
            $visibility_class,
            esc_html($author),
            esc_html(date('F j, Y g:i A', strtotime($date))),
            $visibility_class,
            esc_html($visibility),
            nl2br(esc_html($note))
        );
    }
    
    /**
     * Delete attachment
     */
    public function delete_attachment() {
        if (!wp_verify_nonce($_POST['nonce'], 'altalayi_admin_nonce')) {
            wp_send_json_error(array('message' => __('Security check failed', 'altalayi-ticket')));
        }
        
        $attachment_id = intval($_POST['attachment_id']);
        
        if (!$attachment_id) {
            wp_send_json_error(array('message' => __('Invalid attachment ID', 'altalayi-ticket')));
        }
        
        // Get attachment details
        global $wpdb;
        $table = $wpdb->prefix . 'altalayi_ticket_attachments';
        $attachment = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$table} WHERE id = %d", $attachment_id));
        
        if (!$attachment) {
            wp_send_json_error(array('message' => __('Attachment not found', 'altalayi-ticket')));
        }
        
        // Convert URL to file path and delete file from server
        $upload_dir = wp_upload_dir();
        $file_path = str_replace($upload_dir['baseurl'], $upload_dir['basedir'], $attachment->file_path);
        
        if (file_exists($file_path)) {
            wp_delete_file($file_path);
        }
        
        // Delete attachment record from database
        $result = $wpdb->delete($table, array('id' => $attachment_id), array('%d'));
        
        if ($result !== false) {
            wp_send_json_success(array(
                'message' => __('Attachment deleted successfully', 'altalayi-ticket'),
                'attachment_id' => $attachment_id
            ));
        } else {
            wp_send_json_error(array('message' => __('Failed to delete attachment', 'altalayi-ticket')));
        }
    }
    
    /**
     * Handle bulk actions
     */
    public function bulk_action() {
        if (!wp_verify_nonce($_POST['nonce'], 'altalayi_admin_nonce')) {
            wp_send_json_error(array('message' => __('Security check failed', 'altalayi-ticket')));
        }
        
        $action = sanitize_text_field($_POST['bulk_action']);
        $ticket_ids = array_map('intval', $_POST['ticket_ids']);
        
        if (empty($ticket_ids)) {
            wp_send_json_error(array('message' => __('No tickets selected', 'altalayi-ticket')));
        }
        
        $success_count = 0;
        $errors = array();
        
        switch ($action) {
            case 'delete':
                foreach ($ticket_ids as $ticket_id) {
                    // Get ticket to verify it exists
                    $ticket = $this->db->get_ticket($ticket_id);
                    if (!$ticket) {
                        $errors[] = sprintf(__('Ticket #%d not found', 'altalayi-ticket'), $ticket_id);
                        continue;
                    }
                    
                    // Get all attachments for this ticket to delete files
                    $attachments = $this->db->get_ticket_attachments($ticket_id);
                    
                    // Delete all files from server
                    foreach ($attachments as $attachment) {
                        // Convert URL to file path
                        $upload_dir = wp_upload_dir();
                        $file_path = str_replace($upload_dir['baseurl'], $upload_dir['basedir'], $attachment->file_path);
                        
                        // Delete file if it exists
                        if (file_exists($file_path)) {
                            wp_delete_file($file_path);
                        }
                    }
                    
                    // Delete ticket and all related data from database
                    $result = $this->db->delete_ticket($ticket_id);
                    
                    if ($result) {
                        $success_count++;
                    } else {
                        $errors[] = sprintf(__('Failed to delete ticket #%d', 'altalayi-ticket'), $ticket_id);
                    }
                }
                
                if ($success_count > 0) {
                    $message = sprintf(__('%d tickets deleted successfully', 'altalayi-ticket'), $success_count);
                    if (!empty($errors)) {
                        $message .= '. ' . __('Some errors occurred:', 'altalayi-ticket') . ' ' . implode(', ', $errors);
                    }
                    wp_send_json_success(array('message' => $message, 'deleted_count' => $success_count));
                } else {
                    wp_send_json_error(array('message' => __('Failed to delete tickets: ', 'altalayi-ticket') . implode(', ', $errors)));
                }
                break;
                
            default:
                wp_send_json_error(array('message' => __('Invalid bulk action', 'altalayi-ticket')));
        }
    }
    
    /**
     * Delete ticket and all associated files
     */
    public function delete_ticket() {
        if (!wp_verify_nonce($_POST['nonce'], 'altalayi_admin_nonce')) {
            wp_send_json_error(array('message' => __('Security check failed', 'altalayi-ticket')));
        }
        
        $ticket_id = intval($_POST['ticket_id']);
        
        if (!$ticket_id) {
            wp_send_json_error(array('message' => __('Invalid ticket ID', 'altalayi-ticket')));
        }
        
        // Get ticket to verify it exists
        $ticket = $this->db->get_ticket($ticket_id);
        if (!$ticket) {
            wp_send_json_error(array('message' => __('Ticket not found', 'altalayi-ticket')));
        }
        
        // Get all attachments for this ticket to delete files
        $attachments = $this->db->get_ticket_attachments($ticket_id);
        
        // Delete all files from server
        foreach ($attachments as $attachment) {
            // Convert URL to file path
            $upload_dir = wp_upload_dir();
            $file_path = str_replace($upload_dir['baseurl'], $upload_dir['basedir'], $attachment->file_path);
            
            // Delete file if it exists
            if (file_exists($file_path)) {
                wp_delete_file($file_path);
            }
        }
        
        // Delete ticket and all related data from database
        $result = $this->db->delete_ticket($ticket_id);
        
        if ($result) {
            wp_send_json_success(array(
                'message' => __('Ticket deleted successfully', 'altalayi-ticket'),
                'ticket_number' => $ticket->ticket_number
            ));
        } else {
            wp_send_json_error(array('message' => __('Failed to delete ticket', 'altalayi-ticket')));
        }
    }
}
