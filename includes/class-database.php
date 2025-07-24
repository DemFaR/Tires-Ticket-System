<?php
/**
 * Database operations for Altalayi Ticket System
 */

if (!defined('ABSPATH')) {
    exit;
}

class AltalayiTicketDatabase {
    
    private $tickets_table;
    private $attachments_table;
    private $statuses_table;
    private $ticket_updates_table;
    
    public function __construct() {
        global $wpdb;
        $this->tickets_table = $wpdb->prefix . 'altalayi_tickets';
        $this->attachments_table = $wpdb->prefix . 'altalayi_ticket_attachments';
        $this->statuses_table = $wpdb->prefix . 'altalayi_ticket_statuses';
        $this->ticket_updates_table = $wpdb->prefix . 'altalayi_ticket_updates';
    }
    
    /**
     * Create database tables
     */
    public function create_tables() {
        global $wpdb;
        
        $charset_collate = $wpdb->get_charset_collate();
        
        // Tickets table
        $sql_tickets = "CREATE TABLE {$this->tickets_table} (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            ticket_number varchar(20) NOT NULL UNIQUE,
            customer_name varchar(255) NOT NULL,
            customer_phone varchar(20) NOT NULL,
            customer_email varchar(255) NOT NULL,
            complaint_text longtext NOT NULL,
            tire_brand varchar(255),
            tire_model varchar(255),
            tire_size varchar(255),
            purchase_date date,
            complaint_date datetime DEFAULT CURRENT_TIMESTAMP,
            status_id int(11) NOT NULL DEFAULT 1,
            assigned_to bigint(20) NULL,
            priority enum('low', 'medium', 'high', 'urgent') DEFAULT 'medium',
            resolution_notes longtext,
            is_eligible_for_compensation tinyint(1) DEFAULT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY idx_ticket_number (ticket_number),
            KEY idx_customer_phone (customer_phone),
            KEY idx_status (status_id),
            KEY idx_assigned_to (assigned_to)
        ) $charset_collate;";
        
        // Ticket attachments table
        $sql_attachments = "CREATE TABLE {$this->attachments_table} (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            ticket_id bigint(20) NOT NULL,
            file_name varchar(255) NOT NULL,
            file_path varchar(500) NOT NULL,
            file_type varchar(100) NOT NULL,
            file_size bigint(20) NOT NULL,
            attachment_type enum('tire_image', 'receipt', 'additional') NOT NULL,
            uploaded_by bigint(20) NOT NULL,
            uploaded_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY idx_ticket_id (ticket_id),
            FOREIGN KEY (ticket_id) REFERENCES {$this->tickets_table}(id) ON DELETE CASCADE
        ) $charset_collate;";
        
        // Ticket statuses table
        $sql_statuses = "CREATE TABLE {$this->statuses_table} (
            id int(11) NOT NULL AUTO_INCREMENT,
            status_name varchar(100) NOT NULL,
            status_color varchar(7) DEFAULT '#000000',
            status_order int(11) DEFAULT 0,
            is_active tinyint(1) DEFAULT 1,
            is_final tinyint(1) DEFAULT 0,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        ) $charset_collate;";
        
        // Ticket updates/history table
        $sql_updates = "CREATE TABLE {$this->ticket_updates_table} (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            ticket_id bigint(20) NOT NULL,
            update_type enum('status_change', 'assignment', 'note', 'customer_response') NOT NULL,
            old_value varchar(255),
            new_value varchar(255),
            notes longtext,
            updated_by bigint(20) NOT NULL,
            update_date datetime DEFAULT CURRENT_TIMESTAMP,
            is_visible_to_customer tinyint(1) DEFAULT 0,
            PRIMARY KEY (id),
            KEY idx_ticket_id (ticket_id),
            FOREIGN KEY (ticket_id) REFERENCES {$this->tickets_table}(id) ON DELETE CASCADE
        ) $charset_collate;";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql_tickets);
        dbDelta($sql_attachments);
        dbDelta($sql_statuses);
        dbDelta($sql_updates);
        
        // Insert default statuses
        $this->insert_default_statuses();
    }
    
    /**
     * Insert default ticket statuses
     */
    private function insert_default_statuses() {
        global $wpdb;
        
        // Check if statuses already exist
        $existing_count = $wpdb->get_var("SELECT COUNT(*) FROM {$this->statuses_table}");
        if ($existing_count > 0) {
            return; // Statuses already exist
        }

        $default_statuses = array(
            array('status_name' => 'Open', 'status_color' => '#28a745', 'status_order' => 1, 'is_final' => 0),
            array('status_name' => 'Assigned', 'status_color' => '#ffc107', 'status_order' => 2, 'is_final' => 0),
            array('status_name' => 'More Information Required', 'status_color' => '#fd7e14', 'status_order' => 3, 'is_final' => 0),
            array('status_name' => 'In Progress', 'status_color' => '#007bff', 'status_order' => 4, 'is_final' => 0),
            array('status_name' => 'Escalated to Management', 'status_color' => '#6f42c1', 'status_order' => 5, 'is_final' => 0),
            array('status_name' => 'Escalated to Bridgestone', 'status_color' => '#e83e8c', 'status_order' => 6, 'is_final' => 0),
            array('status_name' => 'Resolved', 'status_color' => '#20c997', 'status_order' => 7, 'is_final' => 1),
            array('status_name' => 'Closed', 'status_color' => '#6c757d', 'status_order' => 8, 'is_final' => 1),
            array('status_name' => 'Rejected', 'status_color' => '#dc3545', 'status_order' => 9, 'is_final' => 1)
        );
        
        foreach ($default_statuses as $status) {
            $wpdb->insert($this->statuses_table, $status);
        }
    }
    
    /**
     * Generate unique ticket number
     */
    public function generate_ticket_number() {
        global $wpdb;
        
        do {
            $ticket_number = 'ALT-' . date('Y') . '-' . str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT);
            $exists = $wpdb->get_var($wpdb->prepare(
                "SELECT id FROM {$this->tickets_table} WHERE ticket_number = %s",
                $ticket_number
            ));
        } while ($exists);
        
        return $ticket_number;
    }
    
    /**
     * Create new ticket
     */
    public function create_ticket($data) {
        global $wpdb;
        
        // Get the "Open" status ID
        $open_status_id = $wpdb->get_var("SELECT id FROM {$this->statuses_table} WHERE status_name = 'Open' AND is_active = 1");
        if (!$open_status_id) {
            $open_status_id = 1; // Fallback to ID 1
        }
        
        $ticket_data = array(
            'ticket_number' => $this->generate_ticket_number(),
            'customer_name' => sanitize_text_field($data['customer_name']),
            'customer_phone' => sanitize_text_field($data['customer_phone']),
            'customer_email' => sanitize_email($data['customer_email']),
            'complaint_text' => sanitize_textarea_field($data['complaint_text']),
            'tire_brand' => sanitize_text_field($data['tire_brand']),
            'tire_model' => sanitize_text_field($data['tire_model']),
            'tire_size' => sanitize_text_field($data['tire_size']),
            'purchase_date' => $data['purchase_date'],
            'status_id' => $open_status_id,
            'priority' => sanitize_text_field($data['priority'] ?? 'medium')
        );
        
        $result = $wpdb->insert($this->tickets_table, $ticket_data);
        
        if ($result) {
            $ticket_id = $wpdb->insert_id;
            
            // Log ticket creation
            $this->add_ticket_update($ticket_id, 'status_change', '', 'Open', 'Ticket created', 0);
            
            return $ticket_id;
        }
        
        return false;
    }
    
    /**
     * Get ticket by ID
     */
    public function get_ticket($ticket_id) {
        global $wpdb;
        
        $query = "SELECT t.*, s.status_name, s.status_color, u.display_name as assigned_user_name 
                  FROM {$this->tickets_table} t 
                  LEFT JOIN {$this->statuses_table} s ON t.status_id = s.id 
                  LEFT JOIN {$wpdb->users} u ON t.assigned_to = u.ID 
                  WHERE t.id = %d";
        
        return $wpdb->get_row($wpdb->prepare($query, $ticket_id));
    }
    
    /**
     * Get ticket by number and phone
     */
    public function get_ticket_by_credentials($ticket_number, $phone) {
        global $wpdb;
        
        $query = "SELECT t.*, s.status_name, s.status_color, u.display_name as assigned_user_name 
                  FROM {$this->tickets_table} t 
                  LEFT JOIN {$this->statuses_table} s ON t.status_id = s.id 
                  LEFT JOIN {$wpdb->users} u ON t.assigned_to = u.ID 
                  WHERE t.ticket_number = %s AND t.customer_phone = %s";
        
        return $wpdb->get_row($wpdb->prepare($query, $ticket_number, $phone));
    }
    
    /**
     * Get tickets with filters
     */
    public function get_tickets($filters = array()) {
        global $wpdb;
        
        $where_clauses = array('1=1');
        $params = array();
        
        if (!empty($filters['status'])) {
            if ($filters['status'] === 'open') {
                $where_clauses[] = 's.is_final = 0';
            } elseif ($filters['status'] === 'closed') {
                $where_clauses[] = 's.is_final = 1';
            }
        }
        
        if (!empty($filters['customer_name'])) {
            $where_clauses[] = 't.customer_name LIKE %s';
            $params[] = '%' . $filters['customer_name'] . '%';
        }
        
        if (!empty($filters['customer_phone'])) {
            $where_clauses[] = 't.customer_phone LIKE %s';
            $params[] = '%' . $filters['customer_phone'] . '%';
        }
        
        if (!empty($filters['date_from'])) {
            $where_clauses[] = 'DATE(t.created_at) >= %s';
            $params[] = $filters['date_from'];
        }
        
        if (!empty($filters['date_to'])) {
            $where_clauses[] = 'DATE(t.created_at) <= %s';
            $params[] = $filters['date_to'];
        }
        
        if (!empty($filters['assigned_to'])) {
            $where_clauses[] = 't.assigned_to = %d';
            $params[] = $filters['assigned_to'];
        }
        
        $where_sql = implode(' AND ', $where_clauses);
        
        $query = "SELECT t.*, s.status_name, s.status_color, u.display_name as assigned_user_name 
                  FROM {$this->tickets_table} t 
                  LEFT JOIN {$this->statuses_table} s ON t.status_id = s.id 
                  LEFT JOIN {$wpdb->users} u ON t.assigned_to = u.ID 
                  WHERE {$where_sql} 
                  ORDER BY t.created_at DESC";
        
        if (!empty($params)) {
            return $wpdb->get_results($wpdb->prepare($query, ...$params));
        } else {
            return $wpdb->get_results($query);
        }
    }
    
    /**
     * Update ticket
     */
    public function update_ticket($ticket_id, $data) {
        global $wpdb;
        
        $result = $wpdb->update(
            $this->tickets_table,
            $data,
            array('id' => $ticket_id)
        );
        
        return $result !== false;
    }
    
    /**
     * Add ticket update/history
     */
    public function add_ticket_update($ticket_id, $type, $old_value, $new_value, $notes, $updated_by, $visible_to_customer = false) {
        global $wpdb;
        
        $update_data = array(
            'ticket_id' => $ticket_id,
            'update_type' => $type,
            'old_value' => $old_value,
            'new_value' => $new_value,
            'notes' => $notes,
            'updated_by' => $updated_by,
            'is_visible_to_customer' => $visible_to_customer ? 1 : 0
        );
        
        return $wpdb->insert($this->ticket_updates_table, $update_data);
    }
    
    /**
     * Get ticket updates
     */
    public function get_ticket_updates($ticket_id, $customer_view = false) {
        global $wpdb;
        
        $where_clause = $customer_view ? 'AND tu.is_visible_to_customer = 1' : '';
        
        $query = "SELECT tu.*, u.display_name as updated_by_name 
                  FROM {$this->ticket_updates_table} tu 
                  LEFT JOIN {$wpdb->users} u ON tu.updated_by = u.ID 
                  WHERE tu.ticket_id = %d {$where_clause} 
                  ORDER BY tu.update_date ASC";
        
        return $wpdb->get_results($wpdb->prepare($query, $ticket_id));
    }
    
    /**
     * Add attachment
     */
    public function add_attachment($ticket_id, $file_data) {
        global $wpdb;
        
        $attachment_data = array(
            'ticket_id' => $ticket_id,
            'file_name' => $file_data['file_name'],
            'file_path' => $file_data['file_path'],
            'file_type' => $file_data['file_type'],
            'file_size' => $file_data['file_size'],
            'attachment_type' => $file_data['attachment_type'],
            'uploaded_by' => $file_data['uploaded_by']
        );
        
        $result = $wpdb->insert($this->attachments_table, $attachment_data);
        
        if ($result === false) {
            return false;
        }
        
        return $wpdb->insert_id;
    }
    
    /**
     * Get ticket attachments
     */
    public function get_ticket_attachments($ticket_id) {
        global $wpdb;
        
        $query = "SELECT * FROM {$this->attachments_table} WHERE ticket_id = %d ORDER BY uploaded_at ASC";
        
        return $wpdb->get_results($wpdb->prepare($query, $ticket_id));
    }
    
    /**
     * Get all statuses
     */
    public function get_statuses() {
        global $wpdb;
        
        return $wpdb->get_results("SELECT * FROM {$this->statuses_table} WHERE is_active = 1 ORDER BY status_order ASC");
    }
    
    /**
     * Get statistics
     */
    public function get_statistics() {
        global $wpdb;
        
        $stats = array();
        
        // Total tickets
        $stats['total_tickets'] = $wpdb->get_var("SELECT COUNT(*) FROM {$this->tickets_table}");
        
        // Open tickets
        $stats['open_tickets'] = $wpdb->get_var("
            SELECT COUNT(*) FROM {$this->tickets_table} t 
            LEFT JOIN {$this->statuses_table} s ON t.status_id = s.id 
            WHERE s.is_final = 0
        ");
        
        // Closed tickets
        $stats['closed_tickets'] = $wpdb->get_var("
            SELECT COUNT(*) FROM {$this->tickets_table} t 
            LEFT JOIN {$this->statuses_table} s ON t.status_id = s.id 
            WHERE s.is_final = 1
        ");
        
        // Today's tickets
        $stats['today_tickets'] = $wpdb->get_var("
            SELECT COUNT(*) FROM {$this->tickets_table} 
            WHERE DATE(created_at) = CURDATE()
        ");
        
        return $stats;
    }
    
    /**
     * Get ticket count by status
     */
    public function get_tickets_count_by_status($status_id) {
        global $wpdb;
        
        return $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM {$this->tickets_table} WHERE status_id = %d",
            $status_id
        ));
    }
    
    /**
     * Get recent tickets
     */
    public function get_recent_tickets($limit = 10) {
        global $wpdb;
        
        return $wpdb->get_results($wpdb->prepare("
            SELECT 
                t.*,
                s.status_name as status_name,
                s.status_color as status_color,
                u.display_name as assigned_user_name
            FROM {$this->tickets_table} t
            LEFT JOIN {$this->statuses_table} s ON t.status_id = s.id
            LEFT JOIN {$wpdb->users} u ON t.assigned_to = u.ID
            ORDER BY t.created_at DESC
            LIMIT %d
        ", $limit));
    }
    
    /**
     * Get tickets by status
     */
    public function get_tickets_by_status($status_type, $filters = array()) {
        global $wpdb;
        
        $where_conditions = array();
        $where_values = array();
        
        // Status type filter (open/closed)
        if ($status_type === 'open') {
            $where_conditions[] = "s.is_final = 0";
        } elseif ($status_type === 'closed') {
            $where_conditions[] = "s.is_final = 1";
        }
        
        // Apply filters
        if (!empty($filters['customer_name'])) {
            $where_conditions[] = "t.customer_name LIKE %s";
            $where_values[] = '%' . $wpdb->esc_like($filters['customer_name']) . '%';
        }
        
        if (!empty($filters['customer_phone'])) {
            $where_conditions[] = "t.customer_phone LIKE %s";
            $where_values[] = '%' . $wpdb->esc_like($filters['customer_phone']) . '%';
        }
        
        if (!empty($filters['date_from'])) {
            $where_conditions[] = "DATE(t.created_at) >= %s";
            $where_values[] = $filters['date_from'];
        }
        
        if (!empty($filters['date_to'])) {
            $where_conditions[] = "DATE(t.created_at) <= %s";
            $where_values[] = $filters['date_to'];
        }
        
        if (!empty($filters['status_id'])) {
            $where_conditions[] = "t.status_id = %d";
            $where_values[] = $filters['status_id'];
        }
        
        if (!empty($filters['assigned_to'])) {
            $where_conditions[] = "t.assigned_to = %d";
            $where_values[] = $filters['assigned_to'];
        }
        
        $where_clause = !empty($where_conditions) ? 'WHERE ' . implode(' AND ', $where_conditions) : '';
        
        $sql = "
            SELECT 
                t.*,
                s.status_name as status_name,
                s.status_color as status_color,
                u.display_name as assigned_user_name
            FROM {$this->tickets_table} t
            LEFT JOIN {$this->statuses_table} s ON t.status_id = s.id
            LEFT JOIN {$wpdb->users} u ON t.assigned_to = u.ID
            {$where_clause}
            ORDER BY t.created_at DESC
        ";
        
        if (!empty($where_values)) {
            return $wpdb->get_results($wpdb->prepare($sql, $where_values));
        } else {
            return $wpdb->get_results($sql);
        }
    }
}
