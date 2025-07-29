<?php
/**
 * Admin Dashboard Template
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wrap">
    <h1><?php _e('Tire Ticket System Dashboard', 'altalayi-ticket'); ?></h1>
    
    <div class="altalayi-dashboard">
        <!-- Statistics Cards -->
        <div class="stats-grid">
            <div class="stat-card total-tickets">
                <div class="stat-icon">
                    <i class="dashicons dashicons-tickets-alt"></i>
                </div>
                <div class="stat-content">
                    <h3><?php echo esc_html($stats['total_tickets']); ?></h3>
                    <p><?php _e('Total Tickets', 'altalayi-ticket'); ?></p>
                </div>
            </div>
            
            <div class="stat-card open-tickets">
                <div class="stat-icon">
                    <i class="dashicons dashicons-unlock"></i>
                </div>
                <div class="stat-content">
                    <h3><?php echo esc_html($stats['open_tickets']); ?></h3>
                    <p><?php _e('Open Tickets', 'altalayi-ticket'); ?></p>
                </div>
            </div>
            
            <div class="stat-card closed-tickets">
                <div class="stat-icon">
                    <i class="dashicons dashicons-lock"></i>
                </div>
                <div class="stat-content">
                    <h3><?php echo esc_html($stats['closed_tickets']); ?></h3>
                    <p><?php _e('Closed Tickets', 'altalayi-ticket'); ?></p>
                </div>
            </div>
            
            <div class="stat-card today-tickets">
                <div class="stat-icon">
                    <i class="dashicons dashicons-calendar-alt"></i>
                </div>
                <div class="stat-content">
                    <h3><?php echo esc_html($stats['today_tickets']); ?></h3>
                    <p><?php _e('Today\'s Tickets', 'altalayi-ticket'); ?></p>
                </div>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="quick-actions">
            <h2><?php _e('Quick Actions', 'altalayi-ticket'); ?></h2>
            <div class="action-buttons">
                <a href="<?php echo admin_url('admin.php?page=altalayi-open-tickets'); ?>" class="button button-primary">
                    <i class="dashicons dashicons-list-view"></i>
                    <?php _e('View Open Tickets', 'altalayi-ticket'); ?>
                </a>
                <a href="<?php echo admin_url('admin.php?page=altalayi-closed-tickets'); ?>" class="button">
                    <i class="dashicons dashicons-archive"></i>
                    <?php _e('View Closed Tickets', 'altalayi-ticket'); ?>
                </a>
                <a href="<?php echo admin_url('admin.php?page=altalayi-ticket-statuses'); ?>" class="button">
                    <i class="dashicons dashicons-admin-settings"></i>
                    <?php _e('Manage Statuses', 'altalayi-ticket'); ?>
                </a>
                <a href="<?php echo esc_url(altalayi_get_create_ticket_url()); ?>" class="button" target="_blank">
                    <i class="dashicons dashicons-plus-alt"></i>
                    <?php _e('Customer Portal', 'altalayi-ticket'); ?>
                </a>
            </div>
        </div>
        
        <!-- Recent Tickets -->
        <div class="recent-tickets">
            <h2><?php _e('Recent Tickets', 'altalayi-ticket'); ?></h2>
            
            <?php if (!empty($recent_tickets)): ?>
                <div class="tickets-table-container">
                    <table class="wp-list-table widefat fixed striped">
                        <thead>
                            <tr>
                                <th><?php _e('Ticket #', 'altalayi-ticket'); ?></th>
                                <th><?php _e('Customer', 'altalayi-ticket'); ?></th>
                                <th><?php _e('Subject', 'altalayi-ticket'); ?></th>
                                <th><?php _e('Status', 'altalayi-ticket'); ?></th>
                                <th><?php _e('Assigned To', 'altalayi-ticket'); ?></th>
                                <th><?php _e('Date', 'altalayi-ticket'); ?></th>
                                <th><?php _e('Actions', 'altalayi-ticket'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recent_tickets as $ticket): ?>
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
                                            <small><?php echo esc_html($ticket->customer_phone); ?></small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="complaint-preview">
                                            <?php echo esc_html(wp_trim_words($ticket->complaint_text, 10, '...')); ?>
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
                
                <p class="view-all-link">
                    <a href="<?php echo admin_url('admin.php?page=altalayi-open-tickets'); ?>">
                        <?php _e('View All Tickets', 'altalayi-ticket'); ?> →
                    </a>
                </p>
            <?php else: ?>
                <div class="no-tickets">
                    <p><?php _e('No tickets found.', 'altalayi-ticket'); ?></p>
                    <p>
                        <a href="<?php echo esc_url(altalayi_get_create_ticket_url()); ?>" target="_blank" class="button button-primary">
                            <?php _e('Create Test Ticket', 'altalayi-ticket'); ?>
                        </a>
                    </p>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- System Information -->
        <div class="system-info">
            <h2><?php _e('System Information', 'altalayi-ticket'); ?></h2>
            <div class="info-grid">
                <div class="info-item">
                    <strong><?php _e('Plugin Version:', 'altalayi-ticket'); ?></strong>
                    <?php echo ALTALAYI_TICKET_VERSION; ?>
                </div>
                <div class="info-item">
                    <strong><?php _e('WordPress Version:', 'altalayi-ticket'); ?></strong>
                    <?php echo get_bloginfo('version'); ?>
                </div>
                <div class="info-item">
                    <strong><?php _e('PHP Version:', 'altalayi-ticket'); ?></strong>
                    <?php echo PHP_VERSION; ?>
                </div>
                <div class="info-item">
                    <strong><?php _e('Customer Portal URL:', 'altalayi-ticket'); ?></strong>
                    <a href="<?php echo esc_url(altalayi_get_create_ticket_url()); ?>" target="_blank">
                        <?php echo esc_url(altalayi_get_create_ticket_url()); ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.altalayi-dashboard {
    max-width: 1200px;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.stat-card {
    background: white;
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 20px;
    display: flex;
    align-items: center;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.stat-icon {
    font-size: 24px;
    margin-right: 15px;
    padding: 15px;
    border-radius: 50%;
    color: white;
}

.total-tickets .stat-icon { background: #3498db; }
.open-tickets .stat-icon { background: #e74c3c; }
.closed-tickets .stat-icon { background: #27ae60; }
.today-tickets .stat-icon { background: #f39c12; }

.stat-content h3 {
    margin: 0;
    font-size: 28px;
    font-weight: bold;
    color: #2c3e50;
}

.stat-content p {
    margin: 5px 0 0 0;
    color: #7f8c8d;
    font-size: 14px;
}

.quick-actions {
    background: white;
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 30px;
}

.action-buttons {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.action-buttons .button {
    display: flex;
    align-items: center;
    gap: 5px;
}

.recent-tickets, .system-info {
    background: white;
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 30px;
}

.tickets-table-container {
    overflow-x: auto;
}

.customer-info strong {
    display: block;
}

.complaint-preview {
    max-width: 200px;
}

.no-tickets {
    text-align: center;
    padding: 40px 20px;
    color: #666;
}

.view-all-link {
    text-align: right;
    margin-top: 15px;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 15px;
}

.info-item {
    padding: 10px;
    background: #f8f9fa;
    border-radius: 4px;
}

.info-item strong {
    display: block;
    margin-bottom: 5px;
    color: #2c3e50;
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
