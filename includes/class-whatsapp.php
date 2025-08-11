<?php
/**
 * WhatsApp Integration for Altalayi Ticket System
 */

if (!defined('ABSPATH')) {
    exit;
}

class AltalayiTicketWhatsApp {
    
    private $access_token;
    private $phone_number_id;
    private $business_phone;
    private $api_url = 'https://graph.facebook.com/v18.0/';
    
    public function __construct() {
        $settings = get_option('altalayi_ticket_settings', array());
        
        $this->access_token = isset($settings['whatsapp_access_token']) ? $settings['whatsapp_access_token'] : '';
        $this->phone_number_id = isset($settings['whatsapp_phone_number_id']) ? $settings['whatsapp_phone_number_id'] : '';
        $this->business_phone = isset($settings['whatsapp_business_phone']) ? $settings['whatsapp_business_phone'] : '';
        
        // Add AJAX action for testing WhatsApp
        add_action('wp_ajax_altalayi_test_whatsapp', array($this, 'test_whatsapp'));
    }
    
    /**
     * Check if WhatsApp is configured and enabled
     */
    public function is_enabled() {
        $settings = get_option('altalayi_ticket_settings', array());
        return !empty($settings['enable_whatsapp_notifications']) &&
               !empty($this->access_token) &&
               !empty($this->phone_number_id);
    }
    
    /**
     * Send WhatsApp message
     */
    public function send_message($to, $message) {
        if (!$this->is_enabled()) {
            return false;
        }
        
        // Format phone number
        $to = $this->format_phone_number($to);
        if (!$to) {
            return false;
        }
        
        $url = $this->api_url . $this->phone_number_id . '/messages';
        
        $data = array(
            'messaging_product' => 'whatsapp',
            'to' => $to,
            'type' => 'text',
            'text' => array(
                'body' => $message
            )
        );
        
        $response = wp_remote_post($url, array(
            'headers' => array(
                'Authorization' => 'Bearer ' . $this->access_token,
                'Content-Type' => 'application/json'
            ),
            'body' => json_encode($data),
            'timeout' => 30
        ));
        
        if (is_wp_error($response)) {
            error_log('WhatsApp API Error: ' . $response->get_error_message());
            return false;
        }
        
        $response_code = wp_remote_retrieve_response_code($response);
        $body = wp_remote_retrieve_body($response);
        
        if ($response_code === 200) {
            $result = json_decode($body, true);
            return $result;
        } else {
            error_log('WhatsApp API Error Response: ' . $body);
            return false;
        }
    }
    
    /**
     * Send ticket created notification
     */
    public function send_ticket_created_notification($ticket_id) {
        $settings = get_option('altalayi_ticket_settings', array());
        if (empty($settings['whatsapp_notify_on_new_ticket'])) {
            return false;
        }
        
        $db = new AltalayiTicketDatabase();
        $ticket = $db->get_ticket($ticket_id);
        
        if (!$ticket) {
            return false;
        }
        
        $message = sprintf(
            "Hello %s,\n\nYour tire complaint ticket has been created successfully!\n\nTicket Number: %s\nPhone Number: %s\n\nTo view your ticket details, please login here:\n%s\n\nUse your ticket number and phone number to access your ticket.\n\nThank you - Altalayi Customer Service",
            $ticket->customer_name,
            $ticket->ticket_number,
            $ticket->customer_phone,
            altalayi_get_access_ticket_url()
        );
        
        return $this->send_message($ticket->customer_phone, $message);
    }
    
    /**
     * Send status update notification
     */
    public function send_status_update_notification($ticket_id) {
        $settings = get_option('altalayi_ticket_settings', array());
        if (empty($settings['whatsapp_notify_on_status_change'])) {
            return false;
        }
        
        $db = new AltalayiTicketDatabase();
        $ticket = $db->get_ticket($ticket_id);
        
        if (!$ticket) {
            return false;
        }
        
        $message = sprintf(
            "Hello %s,\n\nYour ticket status has been updated to: %s\n\nTicket Number: %s\nPhone Number: %s\n\nTo view the update details, please login here:\n%s\n\nUse your ticket number and phone number to access your ticket.\n\nThank you - Altalayi Customer Service",
            $ticket->customer_name,
            $ticket->status_name,
            $ticket->ticket_number,
            $ticket->customer_phone,
            altalayi_get_access_ticket_url()
        );
        
        return $this->send_message($ticket->customer_phone, $message);
    }
    
    /**
     * Send employee response notification
     */
    public function send_employee_response_notification($ticket_id) {
        $settings = get_option('altalayi_ticket_settings', array());
        if (empty($settings['whatsapp_notify_on_employee_response'])) {
            return false;
        }
        
        $db = new AltalayiTicketDatabase();
        $ticket = $db->get_ticket($ticket_id);
        
        if (!$ticket) {
            return false;
        }
        
        $message = sprintf(
            "Hello %s,\n\nA new response has been added to your ticket!\n\nTicket Number: %s\nPhone Number: %s\n\nTo view the response, please login here:\n%s\n\nUse your ticket number and phone number to access your ticket.\n\nThank you - Altalayi Customer Service",
            $ticket->customer_name,
            $ticket->ticket_number,
            $ticket->customer_phone,
            altalayi_get_access_ticket_url()
        );
        
        return $this->send_message($ticket->customer_phone, $message);
    }
    
    /**
     * Format phone number for WhatsApp API
     */
    private function format_phone_number($phone) {
        if (empty($phone)) {
            return false;
        }
        
        // Remove all non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        // If phone starts with 0, replace with Saudi Arabia country code (966)
        if (substr($phone, 0, 1) === '0') {
            $phone = '966' . substr($phone, 1);
        }
        
        // If phone doesn't start with country code, add Saudi Arabia (966)
        if (!preg_match('/^966/', $phone) && !preg_match('/^\d{3}/', $phone)) {
            $phone = '966' . $phone;
        }
        
        // Validate final format (should be 12-15 digits)
        if (strlen($phone) < 10 || strlen($phone) > 15) {
            return false;
        }
        
        return $phone;
    }
    
    /**
     * AJAX handler for testing WhatsApp configuration
     */
    public function test_whatsapp() {
        // Check nonce
        if (!wp_verify_nonce($_POST['nonce'], 'altalayi_test_whatsapp')) {
            wp_send_json_error(array('message' => __('Security check failed', 'altalayi-ticket')));
        }
        
        // Check user permissions
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('Insufficient permissions', 'altalayi-ticket')));
        }
        
        $phone = sanitize_text_field($_POST['phone']);
        
        if (empty($phone)) {
            wp_send_json_error(array('message' => __('Phone number is required', 'altalayi-ticket')));
        }
        
        // Check if WhatsApp is configured
        if (!$this->is_enabled()) {
            wp_send_json_error(array('message' => __('WhatsApp is not properly configured. Please check your settings.', 'altalayi-ticket')));
        }
        
        $test_message = sprintf(
            "Hello,\n\nThis is a test message from the ticket system.\n\nIf you received this message, your WhatsApp configuration is working correctly.\n\nTime: %s\n\nThank you - Technical Support Team",
            current_time('Y-m-d H:i:s')
        );
        
        $result = $this->send_message($phone, $test_message);
        
        if ($result) {
            wp_send_json_success(array('message' => __('Test message sent successfully!', 'altalayi-ticket')));
        } else {
            wp_send_json_error(array('message' => __('Failed to send test message. Please check your WhatsApp configuration.', 'altalayi-ticket')));
        }
    }
}

// Initialize WhatsApp class
new AltalayiTicketWhatsApp();
