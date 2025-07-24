<?php
/**
 * Email Template: Status Update
 */

if (!defined('ABSPATH')) {
    exit;
}

echo $this->get_email_header();
?>

<h2><?php _e('Ticket Status Update', 'altalayi-ticket'); ?></h2>

<p><?php printf(__('Dear %s,', 'altalayi-ticket'), esc_html($ticket->customer_name)); ?></p>

<p><?php _e('Your tire complaint ticket has been updated. Here are the current details:', 'altalayi-ticket'); ?></p>

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
                <?php _e('Current Status:', 'altalayi-ticket'); ?>
            </td>
            <td style="padding: 10px; border-bottom: 1px solid #eee;">
                <span class="status-badge" style="background-color: <?php echo esc_attr($ticket->status_color); ?>;">
                    <?php echo esc_html($ticket->status_name); ?>
                </span>
            </td>
        </tr>
        <tr>
            <td style="padding: 10px; border-bottom: 1px solid #eee; font-weight: bold;">
                <?php _e('Last Updated:', 'altalayi-ticket'); ?>
            </td>
            <td style="padding: 10px; border-bottom: 1px solid #eee;">
                <?php echo altalayi_format_date($ticket->updated_at); ?>
            </td>
        </tr>
        <?php if ($ticket->assigned_user_name): ?>
        <tr>
            <td style="padding: 10px; border-bottom: 1px solid #eee; font-weight: bold;">
                <?php _e('Assigned To:', 'altalayi-ticket'); ?>
            </td>
            <td style="padding: 10px; border-bottom: 1px solid #eee;">
                <?php echo esc_html($ticket->assigned_user_name); ?>
            </td>
        </tr>
        <?php endif; ?>
    </table>
</div>

<?php
// Check if more information is required
$status_lower = strtolower($ticket->status_name);
if (strpos($status_lower, 'information') !== false || strpos($status_lower, 'required') !== false):
?>
    <div style="background: #fff3cd; border: 1px solid #ffeaa7; padding: 20px; border-radius: 8px; margin: 20px 0;">
        <h3 style="margin-top: 0; color: #856404;"><?php _e('Action Required', 'altalayi-ticket'); ?></h3>
        <p style="margin-bottom: 0;"><?php _e('Our team needs additional information from you to proceed with your ticket. Please log in to your ticket to provide the requested details.', 'altalayi-ticket'); ?></p>
    </div>
<?php endif; ?>

<div style="text-align: center; margin: 30px 0;">
    <a href="<?php echo esc_url($view_url); ?>" class="btn"><?php _e('View Your Ticket', 'altalayi-ticket'); ?></a>
</div>

<div style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0;">
    <h3 style="margin-top: 0;"><?php _e('Quick Access Information', 'altalayi-ticket'); ?></h3>
    <p style="margin: 0 0 10px 0;"><strong><?php _e('Ticket Number:', 'altalayi-ticket'); ?></strong> <code style="background: #e9ecef; padding: 4px 8px; border-radius: 4px;"><?php echo esc_html($ticket->ticket_number); ?></code></p>
    <p style="margin: 0;"><strong><?php _e('Phone Number:', 'altalayi-ticket'); ?></strong> <code style="background: #e9ecef; padding: 4px 8px; border-radius: 4px;"><?php echo esc_html($ticket->customer_phone); ?></code></p>
</div>

<p><?php _e('We appreciate your patience as we work to resolve your concern. You will continue to receive email notifications for any future updates to your ticket.', 'altalayi-ticket'); ?></p>

<p><?php _e('If you have any questions about this update, please contact our support team.', 'altalayi-ticket'); ?></p>

<p><?php _e('Thank you for choosing Altalayi.', 'altalayi-ticket'); ?></p>

<?php echo $this->get_email_footer(); ?>
