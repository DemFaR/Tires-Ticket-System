<?php
/**
 * Helper functions for Altalayi Ticket System
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get ticket status badge HTML
 */
function altalayi_get_status_badge($status_name, $status_color) {
    return sprintf(
        '<span class="status-badge" style="background-color: %s; color: white; padding: 4px 8px; border-radius: 3px; font-size: 12px; font-weight: bold;">%s</span>',
        esc_attr($status_color),
        esc_html($status_name)
    );
}

/**
 * Get priority badge HTML
 */
function altalayi_get_priority_badge($priority) {
    $colors = array(
        'low' => '#28a745',
        'medium' => '#ffc107',
        'high' => '#fd7e14',
        'urgent' => '#dc3545'
    );
    
    $color = isset($colors[$priority]) ? $colors[$priority] : $colors['medium'];
    
    return sprintf(
        '<span class="priority-badge" style="background-color: %s; color: white; padding: 4px 8px; border-radius: 3px; font-size: 12px; font-weight: bold;">%s</span>',
        esc_attr($color),
        esc_html(ucfirst($priority))
    );
}

/**
 * Format date for display
 */
function altalayi_format_date($date, $format = 'F j, Y g:i A') {
    if (empty($date) || $date === '0000-00-00 00:00:00') {
        return __('N/A', 'altalayi-ticket');
    }
    
    return date($format, strtotime($date));
}

/**
 * Format file size
 */
function altalayi_format_file_size($bytes) {
    if ($bytes >= 1073741824) {
        $bytes = number_format($bytes / 1073741824, 2) . ' GB';
    } elseif ($bytes >= 1048576) {
        $bytes = number_format($bytes / 1048576, 2) . ' MB';
    } elseif ($bytes >= 1024) {
        $bytes = number_format($bytes / 1024, 2) . ' KB';
    } elseif ($bytes > 1) {
        $bytes = $bytes . ' bytes';
    } elseif ($bytes == 1) {
        $bytes = $bytes . ' byte';
    } else {
        $bytes = '0 bytes';
    }
    
    return $bytes;
}

/**
 * Get attachment type label
 */
function altalayi_get_attachment_type_label($type) {
    $labels = array(
        'tire_image' => __('Tire Image', 'altalayi-ticket'),
        'receipt' => __('Receipt/Invoice', 'altalayi-ticket'),
        'additional' => __('Additional Document', 'altalayi-ticket')
    );
    
    return isset($labels[$type]) ? $labels[$type] : $labels['additional'];
}

/**
 * Get attachment icon
 */
function altalayi_get_attachment_icon($file_type) {
    if (strpos($file_type, 'image/') === 0) {
        return '<i class="dashicons dashicons-format-image"></i>';
    } elseif ($file_type === 'application/pdf') {
        return '<i class="dashicons dashicons-pdf"></i>';
    } else {
        return '<i class="dashicons dashicons-media-default"></i>';
    }
}

/**
 * Check if user can view ticket - Access control removed
 */
function altalayi_can_user_view_ticket($ticket, $user_id = null) {
    // Access control removed - all users can view tickets
    return true;
}

/**
 * Get ticket statistics
 */
function altalayi_get_ticket_stats() {
    $db = new AltalayiTicketDatabase();
    return $db->get_statistics();
}

/**
 * Get user's ticket statistics
 */
function altalayi_get_user_ticket_stats($user_id) {
    global $wpdb;
    
    $tickets_table = $wpdb->prefix . 'altalayi_tickets';
    $statuses_table = $wpdb->prefix . 'altalayi_ticket_statuses';
    
    $stats = array();
    
    // Total assigned tickets
    $stats['total_assigned'] = $wpdb->get_var($wpdb->prepare(
        "SELECT COUNT(*) FROM {$tickets_table} WHERE assigned_to = %d",
        $user_id
    ));
    
    // Open assigned tickets
    $stats['open_assigned'] = $wpdb->get_var($wpdb->prepare(
        "SELECT COUNT(*) FROM {$tickets_table} t 
         LEFT JOIN {$statuses_table} s ON t.status_id = s.id 
         WHERE t.assigned_to = %d AND s.is_final = 0",
        $user_id
    ));
    
    // Closed assigned tickets
    $stats['closed_assigned'] = $wpdb->get_var($wpdb->prepare(
        "SELECT COUNT(*) FROM {$tickets_table} t 
         LEFT JOIN {$statuses_table} s ON t.status_id = s.id 
         WHERE t.assigned_to = %d AND s.is_final = 1",
        $user_id
    ));
    
    return $stats;
}

/**
 * Sanitize phone number
 */
function altalayi_sanitize_phone($phone) {
    // Remove all non-numeric characters except +
    $phone = preg_replace('/[^0-9+]/', '', $phone);
    
    // If phone starts with 0, replace with country code (assuming Saudi Arabia +966)
    if (substr($phone, 0, 1) === '0') {
        $phone = '+966' . substr($phone, 1);
    }
    
    return $phone;
}

/**
 * Validate phone number
 */
function altalayi_validate_phone($phone) {
    $phone = altalayi_sanitize_phone($phone);
    
    // Check if phone number is valid (basic validation)
    if (strlen($phone) < 10 || strlen($phone) > 15) {
        return false;
    }
    
    return $phone;
}

/**
 * Get allowed file types
 */
function altalayi_get_allowed_file_types() {
    return array(
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png' => 'image/png',
        'gif' => 'image/gif',
        'webp' => 'image/webp',
        'pdf' => 'application/pdf',
        'doc' => 'application/msword',
        'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'txt' => 'text/plain',
        'rtf' => 'application/rtf',
        'odt' => 'application/vnd.oasis.opendocument.text'
    );
}

/**
 * Check if file type is allowed
 */
function altalayi_is_file_type_allowed($file_type) {
    $allowed_types = altalayi_get_allowed_file_types();
    return in_array($file_type, $allowed_types);
}

/**
 * Get max upload size
 */
function altalayi_get_max_upload_size() {
    $max_upload = wp_max_upload_size();
    $plugin_max = 5 * 1024 * 1024; // 5MB
    
    return min($max_upload, $plugin_max);
}

/**
 * Generate secure download URL for attachment
 */
function altalayi_get_attachment_download_url($attachment_id) {
    return add_query_arg(array(
        'action' => 'altalayi_download_attachment',
        'attachment_id' => $attachment_id,
        'nonce' => wp_create_nonce('download_attachment_' . $attachment_id)
    ), admin_url('admin-ajax.php'));
}

/**
 * Log ticket activity
 */
function altalayi_log_activity($message, $level = 'info') {
    if (defined('WP_DEBUG') && WP_DEBUG) {
        error_log(sprintf('[Altalayi Ticket] %s: %s', strtoupper($level), $message));
    }
}

/**
 * Get current page URL
 */
function altalayi_get_current_url() {
    $protocol = is_ssl() ? 'https://' : 'http://';
    return $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
}

/**
 * Clean and format text for display
 */
function altalayi_clean_text($text) {
    $text = wp_kses_post($text);
    $text = wpautop($text);
    return $text;
}

/**
 * Get ticket URL for customer
 */
function altalayi_get_ticket_url($ticket_number) {
    return altalayi_get_ticket_view_url($ticket_number);
}

/**
 * Get ticket login URL
 */
function altalayi_get_ticket_login_url() {
    return home_url('/ticket-login');
}

/**
 * Get new ticket URL
 */
function altalayi_get_new_ticket_url() {
    return home_url('/new-ticket');
}

/**
 * Check if customer is logged in to ticket
 */
function altalayi_is_customer_logged_in($ticket_number) {
    if (!session_id()) {
        session_start();
    }
    
    return isset($_SESSION['altalayi_ticket_' . $ticket_number]);
}

/**
 * Logout customer from ticket
 */
function altalayi_logout_customer($ticket_number) {
    if (!session_id()) {
        session_start();
    }
    
    unset($_SESSION['altalayi_ticket_' . $ticket_number]);
}

/**
 * Get plugin option with default
 */
function altalayi_get_option($option_name, $default = '') {
    $options = get_option('altalayi_ticket_settings', array());
    return isset($options[$option_name]) ? $options[$option_name] : $default;
}

/**
 * Update plugin option
 */
function altalayi_update_option($option_name, $value) {
    $options = get_option('altalayi_ticket_settings', array());
    $options[$option_name] = $value;
    return update_option('altalayi_ticket_settings', $options);
}

/**
 * Get company information
 */
function altalayi_get_company_info() {
    return array(
        'name' => altalayi_get_option('company_name', 'Altalayi Company'),
        'email' => altalayi_get_option('company_email', 'support@altalayi.com'),
        'phone' => altalayi_get_option('company_phone', '+966-XXX-XXXX'),
        'address' => altalayi_get_option('company_address', ''),
        'website' => altalayi_get_option('company_website', home_url())
    );
}

/**
 * Get all notification email addresses based on settings
 */
function altalayi_get_notification_emails() {
    $emails = array();
    
    // Get the main admin notification email
    $admin_email = altalayi_get_option('admin_notification_email', get_option('admin_email'));
    if (!empty($admin_email) && is_email($admin_email)) {
        $emails[] = $admin_email;
    }
    
    // Get emails from selected roles
    $notification_roles = altalayi_get_option('notification_roles', array('administrator'));
    
    // Ensure notification_roles is an array
    if (!is_array($notification_roles)) {
        $notification_roles = array('administrator');
    }
    
    if (!empty($notification_roles) && is_array($notification_roles)) {
        foreach ($notification_roles as $role) {
            $users = get_users(array('role' => $role, 'fields' => 'user_email'));
            
            foreach ($users as $user_email) {
                if (!empty($user_email) && is_email($user_email) && !in_array($user_email, $emails)) {
                    $emails[] = $user_email;
                }
            }
        }
    }
    
    // Get additional notification emails
    $additional_emails = altalayi_get_option('additional_notification_emails', '');
    if (!empty($additional_emails)) {
        $additional_emails_array = array_map('trim', explode("\n", $additional_emails));
        foreach ($additional_emails_array as $email) {
            if (!empty($email) && is_email($email) && !in_array($email, $emails)) {
                $emails[] = $email;
            }
        }
    }
    
    return array_unique($emails);
}

/**
 * Check if notifications are enabled
 */
function altalayi_notifications_enabled() {
    return altalayi_get_option('enable_email_notifications', 1) == 1;
}

/**
 * Check if specific notification type is enabled
 */
function altalayi_notification_type_enabled($type) {
    $enabled_types = array(
        'new_ticket' => altalayi_get_option('notify_on_new_ticket', 1),
        'status_change' => altalayi_get_option('notify_on_status_change', 1),
        'customer_response' => altalayi_get_option('notify_on_customer_response', 1)
    );
    
    return isset($enabled_types[$type]) && $enabled_types[$type] == 1;
}
