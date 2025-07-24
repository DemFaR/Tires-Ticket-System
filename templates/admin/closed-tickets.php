<?php
/**
 * Closed Tickets Admin Template
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wrap">
    <h1><?php _e('Closed Tickets', 'altalayi-ticket'); ?></h1>
    
    <!-- Filters -->
    <div class="ticket-filters">
        <form method="GET" action="">
            <input type="hidden" name="page" value="altalayi-closed-tickets">
            
            <div class="filter-row">
                <input type="text" 
                       name="customer_name" 
                       placeholder="<?php _e('Customer Name', 'altalayi-ticket'); ?>"
                       value="<?php echo esc_attr($_GET['customer_name'] ?? ''); ?>">
                
                <input type="text" 
                       name="customer_phone" 
                       placeholder="<?php _e('Phone Number', 'altalayi-ticket'); ?>"
                       value="<?php echo esc_attr($_GET['customer_phone'] ?? ''); ?>">
                
                <input type="date" 
                       name="date_from" 
                       placeholder="<?php _e('From Date', 'altalayi-ticket'); ?>"
                       value="<?php echo esc_attr($_GET['date_from'] ?? ''); ?>">
                
                <input type="date" 
                       name="date_to" 
                       placeholder="<?php _e('To Date', 'altalayi-ticket'); ?>"
                       value="<?php echo esc_attr($_GET['date_to'] ?? ''); ?>">
                
                <button type="submit" class="button"><?php _e('Filter', 'altalayi-ticket'); ?></button>
                <a href="<?php echo admin_url('admin.php?page=altalayi-closed-tickets'); ?>" class="button">
                    <?php _e('Clear', 'altalayi-ticket'); ?>
                </a>
            </div>
        </form>
    </div>
    
    <!-- Tickets Table -->
    <?php if (!empty($tickets)): ?>
        <div class="tickets-container">
            <table class="wp-list-table widefat fixed striped tickets-table">
                <thead>
                    <tr>
                        <th><?php _e('Ticket #', 'altalayi-ticket'); ?></th>
                        <th><?php _e('Customer', 'altalayi-ticket'); ?></th>
                        <th><?php _e('Tire Information', 'altalayi-ticket'); ?></th>
                        <th><?php _e('Complaint', 'altalayi-ticket'); ?></th>
                        <th><?php _e('Final Status', 'altalayi-ticket'); ?></th>
                        <th><?php _e('Assigned To', 'altalayi-ticket'); ?></th>
                        <th><?php _e('Created', 'altalayi-ticket'); ?></th>
                        <th><?php _e('Closed', 'altalayi-ticket'); ?></th>
                        <th><?php _e('Actions', 'altalayi-ticket'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tickets as $ticket): ?>
                        <tr>
                            <td>
                                <strong>
                                    <a href="<?php echo admin_url('admin.php?page=altalayi-view-ticket&ticket_id=' . $ticket->id); ?>">
                                        <?php echo esc_html($ticket->ticket_number); ?>
                                    </a>
                                </strong>
                            </td>
                            <td>
                                <div class="customer-info">
                                    <strong><?php echo esc_html($ticket->customer_name); ?></strong><br>
                                    <small><?php echo esc_html($ticket->customer_phone); ?></small><br>
                                    <small><?php echo esc_html($ticket->customer_email); ?></small>
                                </div>
                            </td>
                            <td>
                                <div class="tire-info">
                                    <strong><?php echo esc_html($ticket->tire_brand); ?></strong><br>
                                    <small><?php _e('Size:', 'altalayi-ticket'); ?> <?php echo esc_html($ticket->tire_size); ?></small><br>
                                    <small><?php _e('Model:', 'altalayi-ticket'); ?> <?php echo esc_html($ticket->tire_model); ?></small><br>
                                    <small><?php _e('Purchase:', 'altalayi-ticket'); ?> <?php echo altalayi_format_date($ticket->purchase_date); ?></small>
                                </div>
                            </td>
                            <td>
                                <div class="complaint-preview">
                                    <?php echo esc_html(wp_trim_words($ticket->complaint_text, 15, '...')); ?>
                                </div>
                            </td>
                            <td>
                                <?php echo altalayi_get_status_badge($ticket->status_name, $ticket->status_color); ?>
                            </td>
                            <td>
                                <?php echo $ticket->assigned_user_name ? esc_html($ticket->assigned_user_name) : '<em>' . __('Unassigned', 'altalayi-ticket') . '</em>'; ?>
                            </td>
                            <td>
                                <?php echo altalayi_format_date($ticket->created_at, 'M j, Y'); ?>
                            </td>
                            <td>
                                <?php echo altalayi_format_date($ticket->updated_at, 'M j, Y'); ?>
                            </td>
                            <td>
                                <a href="<?php echo admin_url('admin.php?page=altalayi-view-ticket&ticket_id=' . $ticket->id); ?>" 
                                   class="button button-small">
                                    <?php _e('View', 'altalayi-ticket'); ?>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="no-tickets">
            <p><?php _e('No closed tickets found.', 'altalayi-ticket'); ?></p>
            <p>
                <a href="<?php echo admin_url('admin.php?page=altalayi-open-tickets'); ?>" class="button button-primary">
                    <?php _e('View Open Tickets', 'altalayi-ticket'); ?>
                </a>
            </p>
        </div>
    <?php endif; ?>
</div>

<style>
.ticket-filters {
    background: white;
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 15px;
    margin-bottom: 20px;
}

.filter-row {
    display: flex;
    gap: 10px;
    align-items: center;
    flex-wrap: wrap;
}

.filter-row input[type="text"],
.filter-row input[type="date"] {
    min-width: 150px;
}

.tickets-container {
    background: white;
}

.customer-info strong {
    display: block;
}

.tire-info strong {
    display: block;
}

.complaint-preview {
    max-width: 200px;
}

.no-tickets {
    text-align: center;
    padding: 40px 20px;
    color: #666;
    background: white;
    border: 1px solid #ddd;
    border-radius: 4px;
}
</style>
