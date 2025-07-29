<?php
/**
 * Ticket Statuses Admin Template
 */

if (!defined('ABSPATH')) {
    exit;
}

$statuses = $this->db->get_statuses();
?>

<div class="wrap">
    <h1><?php _e('Ticket Statuses', 'altalayi-ticket'); ?></h1>
    
    <div class="status-management">
        <!-- Add New Status Form -->
        <div class="add-status-form">
            <h2><?php _e('Add New Status', 'altalayi-ticket'); ?></h2>
            <form method="POST" action="">
                <?php wp_nonce_field('add_status'); ?>
                <input type="hidden" name="action" value="add_status">
                
                <table class="form-table">
                    <tr>
                        <th><label for="status_name"><?php _e('Status Name', 'altalayi-ticket'); ?></label></th>
                        <td>
                            <input type="text" id="status_name" name="status_name" class="regular-text" required>
                            <p class="description"><?php _e('Enter the status name (e.g., "In Review", "Escalated")', 'altalayi-ticket'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="status_color"><?php _e('Status Color', 'altalayi-ticket'); ?></label></th>
                        <td>
                            <input type="color" id="status_color" name="status_color" value="#3498db">
                            <p class="description"><?php _e('Choose a color for this status badge', 'altalayi-ticket'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="status_order"><?php _e('Display Order', 'altalayi-ticket'); ?></label></th>
                        <td>
                            <input type="number" id="status_order" name="status_order" min="0" value="<?php echo count($statuses) + 1; ?>">
                            <p class="description"><?php _e('Lower numbers appear first', 'altalayi-ticket'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="is_final"><?php _e('Final Status', 'altalayi-ticket'); ?></label></th>
                        <td>
                            <label>
                                <input type="checkbox" id="is_final" name="is_final" value="1">
                                <?php _e('This is a final status (tickets cannot be changed after this)', 'altalayi-ticket'); ?>
                            </label>
                        </td>
                    </tr>
                </table>
                
                <?php submit_button(__('Add Status', 'altalayi-ticket')); ?>
            </form>
        </div>
        
        <!-- Existing Statuses -->
        <div class="existing-statuses">
            <h2><?php _e('Existing Statuses', 'altalayi-ticket'); ?></h2>
            
            <?php if (!empty($statuses)): ?>
                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th><?php _e('Order', 'altalayi-ticket'); ?></th>
                            <th><?php _e('Status Name', 'altalayi-ticket'); ?></th>
                            <th><?php _e('Color', 'altalayi-ticket'); ?></th>
                            <th><?php _e('Final Status', 'altalayi-ticket'); ?></th>
                            <th><?php _e('Tickets Count', 'altalayi-ticket'); ?></th>
                            <!--<th><?php _e('Actions', 'altalayi-ticket'); ?></th>-->
                        </tr>
                    </thead>
                    <tbody id="status-list">
                        <?php foreach ($statuses as $status): ?>
                            <tr data-status-id="<?php echo esc_attr($status->id); ?>">
                                <td class="status-order">
                                    <span class="order-display"><?php echo esc_html($status->status_order); ?></span>
                                    <input type="number" class="order-input" value="<?php echo esc_attr($status->status_order); ?>" style="display: none; width: 60px;">
                                </td>
                                <td>
                                    <div class="status-name-editor">
                                        <strong class="status-name-display"><?php echo esc_html($status->status_name); ?></strong>
                                        <input type="text" class="status-name-input" value="<?php echo esc_attr($status->status_name); ?>" style="display: none;">
                                    </div>
                                </td>
                                <td>
                                    <div class="color-preview">
                                        <span class="color-swatch" style="background-color: <?php echo esc_attr($status->status_color); ?>"></span>
                                        <span class="color-code"><?php echo esc_html($status->status_color); ?></span>
                                    </div>
                                </td>
                                <td>
                                    <?php if ($status->is_final): ?>
                                        <span class="final-status-badge"><?php _e('Final', 'altalayi-ticket'); ?></span>
                                    <?php else: ?>
                                        <span class="regular-status-badge"><?php _e('Regular', 'altalayi-ticket'); ?></span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php 
                                    $ticket_count = $this->db->get_tickets_count_by_status($status->id);
                                    echo esc_html($ticket_count);
                                    ?>
                                </td>
                                <!--
                                <td>                                    
                                    <button type="button" class="button button-small edit-status" data-status-id="<?php echo esc_attr($status->id); ?>">
                                        <?php _e('Edit Order', 'altalayi-ticket'); ?>
                                    </button>
                                    <button type="button" class="button button-small edit-status-name" data-status-id="<?php echo esc_attr($status->id); ?>">
                                        <?php _e('Edit Status', 'altalayi-ticket'); ?>
                                    </button>
                                    
                                    <?php if ($ticket_count == 0): ?>
                                        <button type="button" class="button button-small button-link-delete delete-status" data-status-id="<?php echo esc_attr($status->id); ?>">
                                            <?php _e('Delete', 'altalayi-ticket'); ?>
                                        </button>
                                    <?php endif; ?>
                                </td>
                                -->
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="no-statuses">
                    <p><?php _e('No custom statuses found. Default statuses will be created automatically.', 'altalayi-ticket'); ?></p>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Default Statuses Info -->
        <div class="default-statuses-info">
            <h3><?php _e('Default Statuses', 'altalayi-ticket'); ?></h3>
            <p><?php _e('The following default statuses are automatically available:', 'altalayi-ticket'); ?></p>
            <ul>
                <li><strong><?php _e('Open', 'altalayi-ticket'); ?></strong> - <?php _e('New tickets awaiting review', 'altalayi-ticket'); ?></li>
                <li><strong><?php _e('Assigned', 'altalayi-ticket'); ?></strong> - <?php _e('Tickets assigned to an employee', 'altalayi-ticket'); ?></li>
                <li><strong><?php _e('More Information Required', 'altalayi-ticket'); ?></strong> - <?php _e('Waiting for customer response', 'altalayi-ticket'); ?></li>
                <li><strong><?php _e('In Progress', 'altalayi-ticket'); ?></strong> - <?php _e('Actively being worked on', 'altalayi-ticket'); ?></li>
                <li><strong><?php _e('Escalated to Management', 'altalayi-ticket'); ?></strong> - <?php _e('Escalated for management review', 'altalayi-ticket'); ?></li>
                <li><strong><?php _e('Escalated to Bridgestone', 'altalayi-ticket'); ?></strong> - <?php _e('Escalated to manufacturer', 'altalayi-ticket'); ?></li>
                <li><strong><?php _e('Resolved', 'altalayi-ticket'); ?></strong> - <?php _e('Issue resolved successfully (Final)', 'altalayi-ticket'); ?></li>
                <li><strong><?php _e('Closed', 'altalayi-ticket'); ?></strong> - <?php _e('Ticket closed (Final)', 'altalayi-ticket'); ?></li>
                <li><strong><?php _e('Rejected', 'altalayi-ticket'); ?></strong> - <?php _e('Not eligible for compensation (Final)', 'altalayi-ticket'); ?></li>
            </ul>
        </div>
    </div>
</div>

<style>
.status-management {
    max-width: 1000px;
}

.add-status-form {
    background: white;
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 20px;
    margin-bottom: 30px;
}

.existing-statuses {
    background: white;
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 20px;
    margin-bottom: 30px;
}

.color-preview {
    display: flex;
    align-items: center;
    gap: 10px;
}

.color-swatch {
    display: inline-block;
    width: 20px;
    height: 20px;
    border-radius: 3px;
    border: 1px solid #ddd;
}

.final-status-badge {
    background: #e74c3c;
    color: white;
    padding: 2px 8px;
    border-radius: 3px;
    font-size: 11px;
    text-transform: uppercase;
}

.regular-status-badge {
    background: #95a5a6;
    color: white;
    padding: 2px 8px;
    border-radius: 3px;
    font-size: 11px;
    text-transform: uppercase;
}

.status-order {
    width: 80px;
}

.no-statuses {
    text-align: center;
    padding: 40px 20px;
    color: #666;
}

.default-statuses-info {
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 4px;
    padding: 20px;
}

.default-statuses-info ul {
    list-style: disc;
    margin-left: 20px;
}

.default-statuses-info li {
    margin-bottom: 5px;
}

#status-list tr {
    cursor: move;
}

.ui-sortable-helper {
    background: #fff;
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
}
</style>

<script>
jQuery(document).ready(function($) {
    // Make status list sortable
    $('#status-list').sortable({
        handle: '.status-order',
        update: function(event, ui) {
            var statusOrder = [];
            $('#status-list tr').each(function(index) {
                statusOrder.push({
                    id: $(this).data('status-id'),
                    order: index + 1
                });
            });
            
            // Update order in database
            $.post(ajaxurl, {
                action: 'update_status_order',
                statuses: statusOrder,
                nonce: '<?php echo wp_create_nonce("update_status_order"); ?>'
            }, function(response) {
                if (response.success) {
                    // Update display order numbers
                    $('#status-list tr').each(function(index) {
                        $(this).find('.order-display').text(index + 1);
                        $(this).find('.order-input').val(index + 1);
                    });
                } else {
                    alert('Error updating status order: ' + response.data);
                    location.reload();
                }
            });
        }
    });
    
    // Edit status order
    $('.edit-status').on('click', function() {
        var row = $(this).closest('tr');
        var orderDisplay = row.find('.order-display');
        var orderInput = row.find('.order-input');
        
        if (orderInput.is(':visible')) {
            // Save changes
            var newOrder = orderInput.val();
            var statusId = $(this).data('status-id');
            
            $.post(ajaxurl, {
                action: 'update_single_status_order',
                status_id: statusId,
                order: newOrder,
                nonce: '<?php echo wp_create_nonce("update_single_status_order"); ?>'
            }, function(response) {
                if (response.success) {
                    orderDisplay.text(newOrder).show();
                    orderInput.hide();
                    location.reload(); // Reload to show proper sorting
                } else {
                    alert('Error updating status order: ' + response.data);
                }
            });
        } else {
            // Enter edit mode
            orderDisplay.hide();
            orderInput.show().focus();
        }
    });
    
    // Edit status name
    $('.edit-status-name').on('click', function() {
        var row = $(this).closest('tr');
        var nameDisplay = row.find('.status-name-display');
        var nameInput = row.find('.status-name-input');
        
        if (nameInput.is(':visible')) {
            // Save changes
            var newName = nameInput.val();
            var statusId = $(this).data('status-id');
            
            if (!newName.trim()) {
                alert('<?php _e("Status name cannot be empty", "altalayi-ticket"); ?>');
                return;
            }
            
            $.post(ajaxurl, {
                action: 'update_status_name',
                status_id: statusId,
                name: newName,
                nonce: '<?php echo wp_create_nonce("update_status_name"); ?>'
            }, function(response) {
                if (response.success) {
                    nameDisplay.text(newName).show();
                    nameInput.hide();
                } else {
                    alert('Error updating status name: ' + response.data);
                }
            });
        } else {
            // Enter edit mode
            nameDisplay.hide();
            nameInput.show().focus().select();
        }
    });
    
    // Handle Enter key for inputs
    $('.order-input, .status-name-input').on('keypress', function(e) {
        if (e.which == 13) { // Enter key
            if ($(this).hasClass('order-input')) {
                $(this).closest('tr').find('.edit-status').click();
            } else {
                $(this).closest('tr').find('.edit-status-name').click();
            }
        }
    });
    
    // Handle Escape key for inputs
    $('.order-input, .status-name-input').on('keyup', function(e) {
        if (e.which == 27) { // Escape key
            if ($(this).hasClass('order-input')) {
                var row = $(this).closest('tr');
                var orderDisplay = row.find('.order-display');
                $(this).hide();
                orderDisplay.show();
            } else {
                var row = $(this).closest('tr');
                var nameDisplay = row.find('.status-name-display');
                $(this).hide();
                nameDisplay.show();
            }
        }
    });
    
    // Delete status
    $('.delete-status').on('click', function() {
        if (!confirm('<?php _e("Are you sure you want to delete this status?", "altalayi-ticket"); ?>')) {
            return;
        }
        
        var statusId = $(this).data('status-id');
        var row = $(this).closest('tr');
        
        $.post(ajaxurl, {
            action: 'delete_status',
            status_id: statusId,
            nonce: '<?php echo wp_create_nonce("delete_status"); ?>'
        }, function(response) {
            if (response.success) {
                row.fadeOut(function() {
                    $(this).remove();
                });
            } else {
                alert('Error deleting status: ' + response.data);
            }
        });
    });
});
</script>
