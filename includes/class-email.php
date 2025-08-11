<?php
/**
 * Email notifications for Altalayi Ticket System
 */

if (!defined('ABSPATH')) {
    exit;
}

class AltalayiTicketEmail {
    
    private $db;
    private $whatsapp;
    private $wasenderapi;
    
    public function __construct() {
        $this->db = new AltalayiTicketDatabase();
        $this->whatsapp = new AltalayiTicketWhatsApp();
        $this->wasenderapi = new AltalayiTicketWaSenderAPI();
        add_filter('wp_mail_content_type', array($this, 'set_html_content_type'));
        
        // Configure SMTP if enabled
        $this->configure_smtp();
        
        // Add AJAX handler for SMTP testing
        add_action('wp_ajax_altalayi_test_smtp', array($this, 'test_smtp'));
    }
    
    /**
     * Configure SMTP settings
     */
    private function configure_smtp() {
        $settings = get_option('altalayi_ticket_settings', array());
        
        if (!empty($settings['enable_smtp']) && !empty($settings['smtp_host'])) {
            add_action('phpmailer_init', array($this, 'configure_phpmailer'));
        }
    }
    
    /**
     * Configure PHPMailer for SMTP
     */
    public function configure_phpmailer($phpmailer) {
        $settings = get_option('altalayi_ticket_settings', array());
        
        $phpmailer->isSMTP();
        $phpmailer->Host = $settings['smtp_host'];
        $phpmailer->Port = intval($settings['smtp_port']);
        $phpmailer->SMTPAuth = true;
        $phpmailer->Username = $settings['smtp_username'];
        $phpmailer->Password = $settings['smtp_password'];
        
        // Set encryption
        if ($settings['smtp_secure'] === 'ssl') {
            $phpmailer->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS;
        } elseif ($settings['smtp_secure'] === 'tls') {
            $phpmailer->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        }
        
        // Set From address if configured
        if (!empty($settings['smtp_from_email'])) {
            $phpmailer->setFrom(
                $settings['smtp_from_email'], 
                !empty($settings['smtp_from_name']) ? $settings['smtp_from_name'] : ''
            );
        }
        
        // Enable debug for development (you can remove this in production)
        // $phpmailer->SMTPDebug = 2;
    }
    
    /**
     * Test SMTP configuration
     */
    public function test_smtp() {
        // Verify nonce
        if (!wp_verify_nonce($_POST['nonce'], 'altalayi_test_smtp')) {
            wp_send_json_error(array('message' => __('Security check failed', 'altalayi-ticket')));
        }
        
        $test_email = sanitize_email($_POST['email']);
        if (!is_email($test_email)) {
            wp_send_json_error(array('message' => __('Invalid email address', 'altalayi-ticket')));
        }
        
        $settings = get_option('altalayi_ticket_settings', array());
        $subject = __('[Altalayi] SMTP Test Email', 'altalayi-ticket');
        $message = sprintf(
            __('This is a test email to verify your SMTP configuration is working correctly.

Sent from: %s
Test time: %s
SMTP Host: %s
SMTP Port: %s

If you received this email, your SMTP configuration is working properly!

Best regards,
Altalayi Ticket System', 'altalayi-ticket'),
            home_url(),
            current_time('Y-m-d H:i:s'),
            $settings['smtp_host'],
            $settings['smtp_port']
        );
        
        $headers = $this->get_email_headers();
        
        $sent = wp_mail($test_email, $subject, $message, $headers);
        
        if ($sent) {
            wp_send_json_success(array('message' => __('Test email sent successfully!', 'altalayi-ticket')));
        } else {
            wp_send_json_error(array('message' => __('Failed to send test email. Please check your SMTP settings.', 'altalayi-ticket')));
        }
    }
    
    /**
     * Set email content type to HTML
     */
    public function set_html_content_type() {
        return 'text/html';
    }
    
    /**
     * Get email headers based on settings
     */
    private function get_email_headers() {
        $settings = get_option('altalayi_ticket_settings', array());
        
        // Use SMTP settings if available, otherwise fall back to company settings
        if (!empty($settings['enable_smtp']) && !empty($settings['smtp_from_email'])) {
            $from_email = $settings['smtp_from_email'];
            $from_name = !empty($settings['smtp_from_name']) ? $settings['smtp_from_name'] : 'Altalayi Support';
        } else {
            $from_email = !empty($settings['company_email']) ? $settings['company_email'] : 'support@altalayi.com';
            $from_name = !empty($settings['company_name']) ? $settings['company_name'] : 'Altalayi Support';
        }
        
        return array(
            'From: ' . $from_name . ' <' . $from_email . '>',
            'Reply-To: ' . $from_email
        );
    }
    
    /**
     * Get admin notification email
     */
    private function get_admin_email() {
        $settings = get_option('altalayi_ticket_settings', array());
        return !empty($settings['admin_notification_email']) ? $settings['admin_notification_email'] : get_option('admin_email');
    }
    
    /**
     * Send ticket created notification
     */
    public function send_ticket_created_notification($ticket_id) {
        $ticket = $this->db->get_ticket($ticket_id);
        
        if (!$ticket) {
            return false;
        }
        
        // Check if notifications are enabled
        if (!altalayi_notifications_enabled()) {
            return false;
        }
        
        $subject = sprintf(__('[Altalayi] Ticket Created: %s', 'altalayi-ticket'), $ticket->ticket_number);
        
        $message = $this->get_email_template('ticket-created', array(
            'ticket' => $ticket,
            'login_url' => altalayi_get_access_ticket_url(),
            'view_url' => altalayi_get_access_ticket_url()
        ));
        
        $headers = $this->get_email_headers();
        
        // Send to customer
        $sent = wp_mail($ticket->customer_email, $subject, $message, $headers);
        
        // Send notification to admin only if new ticket notifications are enabled
        if (altalayi_notification_type_enabled('new_ticket')) {
            $notification_emails = altalayi_get_notification_emails();
            
            if (!empty($notification_emails)) {
                $admin_subject = sprintf(__('[Altalayi] New Ticket: %s', 'altalayi-ticket'), $ticket->ticket_number);
                $admin_message = $this->get_email_template('new-ticket-admin', array(
                    'ticket' => $ticket,
                    'admin_url' => admin_url('admin.php?page=altalayi-view-ticket&ticket_id=' . $ticket_id)
                ));
                
                // Send to all notification recipients
                foreach ($notification_emails as $admin_email) {
                    wp_mail($admin_email, $admin_subject, $admin_message, $headers);
                }
            }
        }
        
        // Send WhatsApp notification if enabled
        if ($this->whatsapp->is_enabled()) {
            $this->whatsapp->send_ticket_created_notification($ticket_id);
        }
        
        // Send WaSenderAPI notification if enabled (priority over WhatsApp Business)
        if ($this->wasenderapi->is_enabled()) {
            $this->wasenderapi->send_ticket_created_notification($ticket_id);
        }
        
        return $sent;
    }
    
    /**
     * Send status update notification
     */
    public function send_status_update_notification($ticket_id) {
        $ticket = $this->db->get_ticket($ticket_id);
        
        if (!$ticket) {
            return false;
        }
        
        $subject = sprintf(__('[Altalayi] Ticket Status Updated: %s', 'altalayi-ticket'), $ticket->ticket_number);
        
        $message = $this->get_email_template('status-update', array(
            'ticket' => $ticket,
            'login_url' => altalayi_get_access_ticket_url()
        ));
        
        $headers = $this->get_email_headers();
        
        $email_sent = wp_mail($ticket->customer_email, $subject, $message, $headers);
        
        // Send WhatsApp notification if enabled
        if ($this->whatsapp->is_enabled()) {
            $this->whatsapp->send_status_update_notification($ticket_id);
        }
        
        // Send WaSenderAPI notification if enabled (priority over WhatsApp Business)
        if ($this->wasenderapi->is_enabled()) {
            $this->wasenderapi->send_status_update_notification($ticket_id);
        }
        
        return $email_sent;
    }
    
    /**
     * Send customer response notification
     */
    public function send_customer_response_notification($ticket_id) {
        $ticket = $this->db->get_ticket($ticket_id);
        
        if (!$ticket || !$ticket->assigned_to) {
            return false;
        }
        
        $assigned_user = get_user_by('ID', $ticket->assigned_to);
        
        if (!$assigned_user) {
            return false;
        }
        
        $subject = sprintf(__('[Altalayi] Customer Response: %s', 'altalayi-ticket'), $ticket->ticket_number);
        
        $message = $this->get_email_template('customer-response', array(
            'ticket' => $ticket,
            'assigned_user' => $assigned_user,
            'admin_url' => admin_url('admin.php?page=altalayi-view-ticket&ticket_id=' . $ticket_id)
        ));
        
        $headers = $this->get_email_headers();
        
        return wp_mail($assigned_user->user_email, $subject, $message, $headers);
    }
    
    /**
     * Send assignment notification
     */
    public function send_assignment_notification($ticket_id, $assigned_user_id) {
        $ticket = $this->db->get_ticket($ticket_id);
        $assigned_user = get_user_by('ID', $assigned_user_id);
        
        if (!$ticket || !$assigned_user) {
            return false;
        }
        
        $subject = sprintf(__('[Altalayi] Ticket Assigned: %s', 'altalayi-ticket'), $ticket->ticket_number);
        
        $message = $this->get_email_template('ticket-assigned', array(
            'ticket' => $ticket,
            'assigned_user' => $assigned_user,
            'admin_url' => admin_url('admin.php?page=altalayi-view-ticket&ticket_id=' . $ticket_id)
        ));
        
        $headers = $this->get_email_headers();
        
        return wp_mail($assigned_user->user_email, $subject, $message, $headers);
    }
    
    /**
     * Send employee response notification to customer
     */
    public function send_employee_response_notification($ticket_id) {
        $ticket = $this->db->get_ticket($ticket_id);
        
        if (!$ticket) {
            return false;
        }
        
        $subject = sprintf(__('[Altalayi] New Response for Ticket: %s', 'altalayi-ticket'), $ticket->ticket_number);
        
        $message = $this->get_email_template('employee-response', array(
            'ticket' => $ticket,
            'login_url' => altalayi_get_access_ticket_url()
        ));
        
        $headers = $this->get_email_headers();
        
        $email_sent = wp_mail($ticket->customer_email, $subject, $message, $headers);
        
        // Send WhatsApp notification if enabled
        if ($this->whatsapp->is_enabled()) {
            $this->whatsapp->send_employee_response_notification($ticket_id);
        }
        
        // Send WaSenderAPI notification if enabled (priority over WhatsApp Business)
        if ($this->wasenderapi->is_enabled()) {
            $this->wasenderapi->send_employee_response_notification($ticket_id);
        }
        
        return $email_sent;
    }
    
    /**
     * Get email template
     */
    private function get_email_template($template, $vars = array()) {
        extract($vars);
        
        ob_start();
        
        $template_file = ALTALAYI_TICKET_PLUGIN_PATH . 'templates/emails/' . $template . '.php';
        
        if (file_exists($template_file)) {
            include $template_file;
        } else {
            // Fallback to basic template
            $basic_template = ALTALAYI_TICKET_PLUGIN_PATH . 'templates/emails/basic.php';
            if (file_exists($basic_template)) {
                include $basic_template;
            } else {
                echo '<h2>Email Template Error</h2><p>Unable to load email template.</p>';
            }
        }
        
        return ob_get_clean();
    }
    
    /**
     * Get email header
     */
    public function get_email_header() {
        $settings = get_option('altalayi_ticket_settings', array());
        $company_name = !empty($settings['company_name']) ? $settings['company_name'] : 'Altalayi';
        
        return '
        <html>
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>' . esc_html($company_name) . ' Support</title>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; }
                .email-container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .email-header { background-color: #2c3e50; color: white; padding: 20px; text-align: center; }
                .email-body { background-color: #f9f9f9; padding: 30px; }
                .email-footer { background-color: #34495e; color: white; padding: 20px; text-align: center; font-size: 12px; }
                .ticket-info { background-color: white; padding: 20px; border-radius: 5px; margin: 20px 0; }
                .status-badge { display: inline-block; padding: 5px 10px; border-radius: 3px; color: white; font-weight: bold; }
                .btn { display: inline-block; background-color: #3498db; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; margin: 10px 0; }
                .btn:hover { background-color: #2980b9; }
            </style>
        </head>
        <body>
            <div class="email-container">
                <div class="email-header">
                    <h1>' . esc_html($company_name) . ' Support</h1>
                    <p>Professional Tire Support Services</p>
                </div>
                <div class="email-body">
        ';
    }
    
    /**
     * Get email footer
     */
    public function get_email_footer() {
        $settings = get_option('altalayi_ticket_settings', array());
        $company_name = !empty($settings['company_name']) ? $settings['company_name'] : 'Altalayi Company';
        $support_email = !empty($settings['company_email']) ? $settings['company_email'] : 'support@altalayi.com';
        
        return '
                </div>
                <div class="email-footer">
                    <p>&copy; ' . date('Y') . ' ' . esc_html($company_name) . '. All rights reserved.</p>
                    <p>This is an automated message, please do not reply to this email.</p>
                    <p>For support, contact us at: ' . esc_html($support_email) . '</p>
                </div>
            </div>
        </body>
        </html>
        ';
    }
}
