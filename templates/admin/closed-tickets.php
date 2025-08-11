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
                                    <?php if ($ticket->customer_city): ?>
                                    <br><small><?php _e('City:', 'altalayi-ticket'); ?> <?php echo esc_html($ticket->customer_city); ?></small>
                                    <?php endif; ?>
                                    <?php if ($ticket->motocare_center_visited): ?>
                                    <br><small><?php _e('Center:', 'altalayi-ticket'); ?> <?php echo esc_html($ticket->motocare_center_visited); ?></small>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td>
                                <div class="tire-info">
                                    <?php if ($ticket->tire_brand): ?>
                                    <strong><?php echo esc_html($ticket->tire_brand); ?></strong><br>
                                    <?php endif; ?>
                                    <!-- Hide tire size -->
                                    <?php /*
                                    <?php if ($ticket->tire_size): ?>
                                    <small><?php _e('Size:', 'altalayi-ticket'); ?> <?php echo esc_html($ticket->tire_size); ?></small><br>
                                    <?php endif; ?>
                                    */ ?>
                                    <?php if ($ticket->tire_model): ?>
                                    <small><?php _e('Model:', 'altalayi-ticket'); ?> <?php echo esc_html($ticket->tire_model); ?></small><br>
                                    <?php endif; ?>
                                    <?php if ($ticket->number_of_tires): ?>
                                    <small><?php _e('Qty:', 'altalayi-ticket'); ?> <?php echo esc_html($ticket->number_of_tires); ?></small><br>
                                    <?php endif; ?>
                                    <?php if ($ticket->tire_position): ?>
                                    <small><?php _e('Position:', 'altalayi-ticket'); ?> <?php echo esc_html($ticket->tire_position); ?></small><br>
                                    <?php endif; ?>
                                    <?php if ($ticket->purchase_date && $ticket->purchase_date !== '0000-00-00'): ?>
                                    <small><?php _e('Purchase:', 'altalayi-ticket'); ?> <?php echo altalayi_format_date($ticket->purchase_date); ?></small>
                                    <?php endif; ?>
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
                                <?php
                                $settings = get_option('altalayi_ticket_settings', array());
                                if (!empty($settings['show_delete_button'])):
                                ?>
                                <button type="button" class="button button-small button-link-delete delete-ticket-btn" 
                                        data-ticket-id="<?php echo esc_attr($ticket->id); ?>"
                                        data-ticket-number="<?php echo esc_attr($ticket->ticket_number); ?>"
                                        style="margin-left: 5px;">
                                    <?php _e('Delete', 'altalayi-ticket'); ?>
                                </button>
                                <?php endif; ?>
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

.delete-ticket-btn {
    color: #a00 !important;
    border-color: #a00 !important;
}

.delete-ticket-btn:hover {
    color: #fff !important;
    background: #a00 !important;
    border-color: #a00 !important;
}
</style>

<script>
jQuery(document).ready(function($) {
    // Delete ticket functionality
    $('.delete-ticket-btn').on('click', function(e) {
        e.preventDefault();
        
        var $btn = $(this);
        var ticketId = $btn.data('ticket-id');
        var ticketNumber = $btn.data('ticket-number');
        
        if (confirm('<?php _e('Are you sure you want to delete ticket', 'altalayi-ticket'); ?> #' + ticketNumber + '? <?php _e('This action cannot be undone and will permanently delete all associated files.', 'altalayi-ticket'); ?>')) {
            
            $btn.prop('disabled', true).text('<?php _e('Deleting...', 'altalayi-ticket'); ?>');
            
            $.post(ajaxurl, {
                action: 'altalayi_delete_ticket',
                ticket_id: ticketId,
                nonce: '<?php echo wp_create_nonce('altalayi_admin_nonce'); ?>'
            })
            .done(function(response) {
                if (response.success) {
                    // Remove the row from the table
                    $btn.closest('tr').fadeOut(function() {
                        $(this).remove();
                    });
                    
                    // Show success message
                    $('<div class="notice notice-success is-dismissible"><p>' + response.data.message + '</p></div>')
                        .insertAfter('.wrap h1');
                } else {
                    alert('<?php _e('Error:', 'altalayi-ticket'); ?> ' + response.data.message);
                    $btn.prop('disabled', false).text('<?php _e('Delete', 'altalayi-ticket'); ?>');
                }
            })
            .fail(function() {
                alert('<?php _e('Network error occurred', 'altalayi-ticket'); ?>');
                $btn.prop('disabled', false).text('<?php _e('Delete', 'altalayi-ticket'); ?>');
            });
        }
    });
});
</script>
