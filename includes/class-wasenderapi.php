<?php
/**
 * WaSenderAPI Integration for Altalayi Ticket System
 */

if (!defined('ABSPATH')) {
    exit;
}

class AltalayiTicketWaSenderAPI {
    
    private $api_key;
    private $session_id;
    private $api_url = 'https://www.wasenderapi.com/api/';
    
    public function __construct() {
        $settings = get_option('altalayi_ticket_settings', array());
        
        $this->api_key = isset($settings['wasenderapi_api_key']) ? $settings['wasenderapi_api_key'] : '';
        $this->session_id = isset($settings['wasenderapi_session_id']) ? $settings['wasenderapi_session_id'] : '';
        
        // Add AJAX action for testing WaSenderAPI
        add_action('wp_ajax_altalayi_test_wasenderapi', array($this, 'test_wasenderapi'));
    }
    
    /**
     * Check if WaSenderAPI is configured and enabled
     */
    public function is_enabled() {
        $settings = get_option('altalayi_ticket_settings', array());
        return !empty($settings['enable_wasenderapi_notifications']) &&
               !empty($this->api_key);
    }
    
    /**
     * Send WhatsApp message via WaSenderAPI
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
        
        $url = $this->api_url . 'send-message';
        
        $data = array(
            'to' => $to,
            'text' => $message
        );
        
        // Add session ID if provided
        if (!empty($this->session_id)) {
            $data['sessionId'] = $this->session_id;
        }
        
        $response = wp_remote_post($url, array(
            'headers' => array(
                'Authorization' => 'Bearer ' . $this->api_key,
                'Content-Type' => 'application/json'
            ),
            'body' => json_encode($data),
            'timeout' => 30
        ));
        
        if (is_wp_error($response)) {
            $error_msg = 'WaSenderAPI Error: ' . $response->get_error_message();
            error_log($error_msg);
            return array('error' => $error_msg);
        }
        
        $response_code = wp_remote_retrieve_response_code($response);
        $body = wp_remote_retrieve_body($response);
        
        error_log('WaSenderAPI Response Code: ' . $response_code);
        error_log('WaSenderAPI Response Body: ' . $body);
        
        if ($response_code === 200) {
            $result = json_decode($body, true);
            if (isset($result['success']) && $result['success']) {
                return $result;
            } else {
                $error_msg = 'WaSenderAPI Error Response: ' . $body;
                error_log($error_msg);
                return array('error' => $error_msg, 'response' => $result);
            }
        } else {
            $error_msg = 'WaSenderAPI HTTP Error ' . $response_code . ': ' . $body;
            error_log($error_msg);
            return array('error' => $error_msg, 'http_code' => $response_code);
        }
    }
    
    /**
     * Send ticket created notification
     */
    public function send_ticket_created_notification($ticket_id) {
        $settings = get_option('altalayi_ticket_settings', array());
        if (empty($settings['wasenderapi_notify_on_new_ticket'])) {
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
        
        $result = $this->send_message($ticket->customer_phone, $message);
        return $result && !isset($result['error']);
    }
    
    /**
     * Send status update notification
     */
    public function send_status_update_notification($ticket_id) {
        $settings = get_option('altalayi_ticket_settings', array());
        if (empty($settings['wasenderapi_notify_on_status_change'])) {
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
        
        $result = $this->send_message($ticket->customer_phone, $message);
        return $result && !isset($result['error']);
    }
    
    /**
     * Send employee response notification
     */
    public function send_employee_response_notification($ticket_id) {
        $settings = get_option('altalayi_ticket_settings', array());
        if (empty($settings['wasenderapi_notify_on_employee_response'])) {
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
        
        $result = $this->send_message($ticket->customer_phone, $message);
        return $result && !isset($result['error']);
    }
    
    /**
     * Format phone number for WaSenderAPI
     */
    private function format_phone_number($phone) {
        if (empty($phone)) {
            return false;
        }
        
        // Remove all non-numeric characters except +
        $phone = preg_replace('/[^0-9+]/', '', $phone);
        
        // If phone starts with 0, replace with Saudi Arabia country code (+966)
        if (substr($phone, 0, 1) === '0') {
            $phone = '+966' . substr($phone, 1);
        }
        
        // If phone doesn't start with + or country code, add Saudi Arabia (+966)
        if (!preg_match('/^\+/', $phone)) {
            // Check if it already has country code
            if (!preg_match('/^966/', $phone)) {
                $phone = '+966' . $phone;
            } else {
                $phone = '+' . $phone;
            }
        }
        
        // Validate final format (should be +[country code][number])
        if (!preg_match('/^\+\d{10,15}$/', $phone)) {
            return false;
        }
        
        return $phone;
    }
    
    /**
     * Check session status
     */
    public function check_session_status() {
        if (!$this->is_enabled()) {
            return false;
        }
        
        $url = $this->api_url . 'session/status';
        
        $data = array();
        if (!empty($this->session_id)) {
            $data['sessionId'] = $this->session_id;
        }
        
        $response = wp_remote_post($url, array(
            'headers' => array(
                'Authorization' => 'Bearer ' . $this->api_key,
                'Content-Type' => 'application/json'
            ),
            'body' => json_encode($data),
            'timeout' => 15
        ));
        
        if (is_wp_error($response)) {
            return false;
        }
        
        $response_code = wp_remote_retrieve_response_code($response);
        $body = wp_remote_retrieve_body($response);
        
        if ($response_code === 200) {
            $result = json_decode($body, true);
            return $result;
        }
        
        return false;
    }
    
    /**
     * AJAX handler for testing WaSenderAPI configuration
     */
    public function test_wasenderapi() {
        // Check nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'altalayi_test_wasenderapi')) {
            wp_send_json_error(array('message' => __('Security check failed', 'altalayi-ticket')));
        }
        
        // Check user permissions
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('Insufficient permissions', 'altalayi-ticket')));
        }
        
        $phone = isset($_POST['phone']) ? sanitize_text_field($_POST['phone']) : '';
        
        if (empty($phone)) {
            wp_send_json_error(array('message' => __('Phone number is required', 'altalayi-ticket')));
        }
        
        // Check if WaSenderAPI is configured
        if (!$this->is_enabled()) {
            wp_send_json_error(array('message' => __('WaSenderAPI is not properly configured. Please check your settings.', 'altalayi-ticket')));
        }
        
        $test_message = sprintf(
            "Hello,\n\nThis is a test message from the ticket system via WaSenderAPI.\n\nIf you received this message, your WaSenderAPI configuration is working correctly.\n\nTime: %s\n\nThank you - Technical Support Team",
            current_time('Y-m-d H:i:s')
        );
        
        $result = $this->send_message($phone, $test_message);
        
        if ($result && !isset($result['error'])) {
            wp_send_json_success(array(
                'message' => __('Test message sent successfully via WaSenderAPI!', 'altalayi-ticket'),
                'data' => $result
            ));
        } else {
            $error_message = __('Failed to send test message.', 'altalayi-ticket');
            if (isset($result['error'])) {
                $error_message .= ' ' . $result['error'];
            } else {
                $error_message .= ' ' . __('Please check your WaSenderAPI configuration and ensure your session is active.', 'altalayi-ticket');
            }
            wp_send_json_error(array('message' => $error_message, 'debug' => $result));
        }
    }
}

// Initialize WaSenderAPI class
new AltalayiTicketWaSenderAPI();
