<?php
/**
 * Open Tickets Admin Template
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wrap">
    <h1><?php _e('Open Tickets', 'altalayi-ticket'); ?></h1>
    
    <!-- Filters -->
    <div class="ticket-filters">
        <form method="GET" action="">
            <input type="hidden" name="page" value="altalayi-open-tickets">
            
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
                <a href="<?php echo admin_url('admin.php?page=altalayi-open-tickets'); ?>" class="button">
                    <?php _e('Clear', 'altalayi-ticket'); ?>
                </a>
            </div>
        </form>
    </div>
    
    <!-- Tickets Table -->
    <?php if (!empty($tickets)): ?>
        <div class="tickets-container">
            <div class="bulk-actions">
                <select id="bulk-action-selector">
                    <option value=""><?php _e('Bulk Actions', 'altalayi-ticket'); ?></option>
                    <?php foreach ($statuses as $status): ?>
                        <option value="status-<?php echo esc_attr($status->id); ?>">
                            <?php printf(__('Set Status: %s', 'altalayi-ticket'), esc_html($status->name)); ?>
                        </option>
                    <?php endforeach; ?>
                    <?php foreach ($employees as $employee): ?>
                        <option value="assign-<?php echo esc_attr($employee->ID); ?>">
                            <?php printf(__('Assign to: %s', 'altalayi-ticket'), esc_html($employee->display_name)); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="button" id="apply-bulk-action" class="button">
                    <?php _e('Apply', 'altalayi-ticket'); ?>
                </button>
            </div>
            
            <table class="wp-list-table widefat fixed striped tickets-table">
                <thead>
                    <tr>
                        <td class="manage-column check-column">
                            <input type="checkbox" id="select-all-tickets">
                        </td>
                        <th><?php _e('Ticket #', 'altalayi-ticket'); ?></th>
                        <th><?php _e('Customer', 'altalayi-ticket'); ?></th>
                        <th><?php _e('Tire Information', 'altalayi-ticket'); ?></th>
                        <th><?php _e('Complaint', 'altalayi-ticket'); ?></th>
                        <th><?php _e('Status', 'altalayi-ticket'); ?></th>
                        <th><?php _e('Assigned To', 'altalayi-ticket'); ?></th>
                        <th><?php _e('Date', 'altalayi-ticket'); ?></th>
                        <th><?php _e('Actions', 'altalayi-ticket'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tickets as $ticket): ?>
                        <tr>
                            <th class="check-column">
                                <input type="checkbox" name="ticket[]" value="<?php echo esc_attr($ticket->id); ?>">
                            </th>
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
                                <div class="status-selector" data-ticket-id="<?php echo esc_attr($ticket->id); ?>">
                                    <?php echo altalayi_get_status_badge($ticket->status_name, $ticket->status_color); ?>
                                    <select class="status-dropdown" style="display: none;">
                                        <?php foreach ($statuses as $status): ?>
                                            <option value="<?php echo esc_attr($status->id); ?>" 
                                                    <?php selected($ticket->status_id, $status->id); ?>>
                                                <?php echo esc_html($status->name); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </td>
                            <td>
                                <div class="assignment-selector" data-ticket-id="<?php echo esc_attr($ticket->id); ?>">
                                    <?php if ($ticket->assigned_user_name): ?>
                                        <strong><?php echo esc_html($ticket->assigned_user_name); ?></strong>
                                    <?php else: ?>
                                        <em><?php _e('Unassigned', 'altalayi-ticket'); ?></em>
                                    <?php endif; ?>
                                    <select class="assignment-dropdown" style="display: none;">
                                        <option value=""><?php _e('Unassigned', 'altalayi-ticket'); ?></option>
                                        <?php foreach ($employees as $employee): ?>
                                            <option value="<?php echo esc_attr($employee->ID); ?>" 
                                                    <?php selected($ticket->assigned_to, $employee->ID); ?>>
                                                <?php echo esc_html($employee->display_name); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </td>
                            <td>
                                <?php echo altalayi_format_date($ticket->created_at, 'M j, Y g:i A'); ?>
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
            <p><?php _e('No open tickets found.', 'altalayi-ticket'); ?></p>
            <p>
                <a href="<?php echo home_url('/new-ticket'); ?>" target="_blank" class="button button-primary">
                    <?php _e('Customer Portal', 'altalayi-ticket'); ?>
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

.bulk-actions {
    padding: 10px;
    border-bottom: 1px solid #ddd;
    background: #f9f9f9;
}

.bulk-actions select {
    margin-right: 10px;
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

.status-selector,
.assignment-selector {
    cursor: pointer;
}

.status-selector:hover .status-badge,
.assignment-selector:hover {
    background-color: #f0f0f0;
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

<script>
jQuery(document).ready(function($) {
    // Toggle status dropdown
    $('.status-selector').on('click', function() {
        var badge = $(this).find('.status-badge');
        var dropdown = $(this).find('.status-dropdown');
        
        if (dropdown.is(':visible')) {
            dropdown.hide();
            badge.show();
        } else {
            badge.hide();
            dropdown.show().focus();
        }
    });
    
    // Handle status change
    $('.status-dropdown').on('change', function() {
        var ticketId = $(this).closest('.status-selector').data('ticket-id');
        var statusId = $(this).val();
        
        $.post(ajaxurl, {
            action: 'update_ticket_status',
            ticket_id: ticketId,
            status_id: statusId,
            nonce: '<?php echo wp_create_nonce("update_ticket_status"); ?>'
        }, function(response) {
            if (response.success) {
                location.reload();
            } else {
                alert('Error updating status: ' + response.data);
            }
        });
    });
    
    // Toggle assignment dropdown
    $('.assignment-selector').on('click', function() {
        var content = $(this).contents().not('.assignment-dropdown');
        var dropdown = $(this).find('.assignment-dropdown');
        
        if (dropdown.is(':visible')) {
            dropdown.hide();
            content.show();
        } else {
            content.hide();
            dropdown.show().focus();
        }
    });
    
    // Handle assignment change
    $('.assignment-dropdown').on('change', function() {
        var ticketId = $(this).closest('.assignment-selector').data('ticket-id');
        var userId = $(this).val();
        
        $.post(ajaxurl, {
            action: 'assign_ticket',
            ticket_id: ticketId,
            user_id: userId,
            nonce: '<?php echo wp_create_nonce("assign_ticket"); ?>'
        }, function(response) {
            if (response.success) {
                location.reload();
            } else {
                alert('Error assigning ticket: ' + response.data);
            }
        });
    });
    
    // Select all tickets
    $('#select-all-tickets').on('change', function() {
        $('input[name="ticket[]"]').prop('checked', this.checked);
    });
    
    // Bulk actions
    $('#apply-bulk-action').on('click', function() {
        var action = $('#bulk-action-selector').val();
        var selectedTickets = $('input[name="ticket[]"]:checked').map(function() {
            return this.value;
        }).get();
        
        if (!action || selectedTickets.length === 0) {
            alert('<?php _e("Please select an action and at least one ticket.", "altalayi-ticket"); ?>');
            return;
        }
        
        if (action.startsWith('status-')) {
            var statusId = action.replace('status-', '');
            bulkUpdateStatus(selectedTickets, statusId);
        } else if (action.startsWith('assign-')) {
            var userId = action.replace('assign-', '');
            bulkAssignTickets(selectedTickets, userId);
        }
    });
    
    function bulkUpdateStatus(tickets, statusId) {
        $.post(ajaxurl, {
            action: 'bulk_update_status',
            tickets: tickets,
            status_id: statusId,
            nonce: '<?php echo wp_create_nonce("bulk_update_status"); ?>'
        }, function(response) {
            if (response.success) {
                location.reload();
            } else {
                alert('Error updating tickets: ' + response.data);
            }
        });
    }
    
    function bulkAssignTickets(tickets, userId) {
        $.post(ajaxurl, {
            action: 'bulk_assign_tickets',
            tickets: tickets,
            user_id: userId,
            nonce: '<?php echo wp_create_nonce("bulk_assign_tickets"); ?>'
        }, function(response) {
            if (response.success) {
                location.reload();
            } else {
                alert('Error assigning tickets: ' + response.data);
            }
        });
    }
});
</script>
