<?php
/**
 * Email Template: Customer Response Notification
 */

if (!defined('ABSPATH')) {
    exit;
}

echo $this->get_email_header();
?>

<h2><?php _e('Customer Response Received', 'altalayi-ticket'); ?></h2>

<p><?php printf(__('Hello %s,', 'altalayi-ticket'), esc_html($assigned_user->display_name)); ?></p>

<p><?php _e('A customer has responded to a ticket assigned to you. Please review their response and take appropriate action.', 'altalayi-ticket'); ?></p>

<div class="ticket-info">
    <h3><?php _e('Ticket Information', 'altalayi-ticket'); ?></h3>
    <table style="width: 100%; border-collapse: collapse;">
        <tr>
            <td style="padding: 10px; border-bottom: 1px solid #eee; font-weight: bold;">
                <?php _e('Ticket Number:', 'altalayi-ticket'); ?>
            </td>
            <td style="padding: 10px; border-bottom: 1px solid #eee;">
                <strong style="color: #e74c3c; font-size: 18px;"><?php echo esc_html($ticket->ticket_number); ?></strong>
            </td>
        </tr>
        <tr>
            <td style="padding: 10px; border-bottom: 1px solid #eee; font-weight: bold;">
                <?php _e('Customer Name:', 'altalayi-ticket'); ?>
            </td>
            <td style="padding: 10px; border-bottom: 1px solid #eee;">
                <?php echo esc_html($ticket->customer_name); ?>
            </td>
        </tr>
        <tr>
            <td style="padding: 10px; border-bottom: 1px solid #eee; font-weight: bold;">
                <?php _e('Customer Phone:', 'altalayi-ticket'); ?>
            </td>
            <td style="padding: 10px; border-bottom: 1px solid #eee;">
                <?php echo esc_html($ticket->customer_phone); ?>
            </td>
        </tr>
        <tr>
            <td style="padding: 10px; border-bottom: 1px solid #eee; font-weight: bold;">
                <?php _e('Current Status:', 'altalayi-ticket'); ?>
            </td>
            <td style="padding: 10px; border-bottom: 1px solid #eee;">
                <span class="status-badge" style="background-color: <?php echo esc_attr($ticket->status_color); ?>;">
                    <?php echo esc_html($ticket->status_name); ?>
                </span>
            </td>
        </tr>
    </table>
</div>

<div style="text-align: center; margin: 30px 0;">
    <a href="<?php echo esc_url($admin_url); ?>" class="btn"><?php _e('View Ticket & Response', 'altalayi-ticket'); ?></a>
</div>

<p><?php _e('Please respond to the customer promptly to maintain our high service standards.', 'altalayi-ticket'); ?></p>

<?php echo $this->get_email_footer(); ?>
