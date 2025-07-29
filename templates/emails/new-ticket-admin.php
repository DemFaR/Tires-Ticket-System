<?php
/**
 * Email Template: New Ticket Admin Notification
 */

if (!defined('ABSPATH')) {
    exit;
}

echo $this->get_email_header();
?>

<h2><?php _e('New Ticket Submitted', 'altalayi-ticket'); ?></h2>

<p><?php _e('A new tire complaint ticket has been submitted and requires your attention.', 'altalayi-ticket'); ?></p>

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
                <?php _e('Customer Email:', 'altalayi-ticket'); ?>
            </td>
            <td style="padding: 10px; border-bottom: 1px solid #eee;">
                <?php echo esc_html($ticket->customer_email); ?>
            </td>
        </tr>
        <tr>
            <td style="padding: 10px; border-bottom: 1px solid #eee; font-weight: bold;">
                <?php _e('Tire Brand:', 'altalayi-ticket'); ?>
            </td>
            <td style="padding: 10px; border-bottom: 1px solid #eee;">
                <?php echo esc_html($ticket->tire_brand); ?>
            </td>
        </tr>
        <tr>
            <td style="padding: 10px; border-bottom: 1px solid #eee; font-weight: bold;">
                <?php _e('Tire Model:', 'altalayi-ticket'); ?>
            </td>
            <td style="padding: 10px; border-bottom: 1px solid #eee;">
                <?php echo esc_html($ticket->tire_model); ?>
            </td>
        </tr>
        <tr>
            <td style="padding: 10px; border-bottom: 1px solid #eee; font-weight: bold;">
                <?php _e('Submitted:', 'altalayi-ticket'); ?>
            </td>
            <td style="padding: 10px; border-bottom: 1px solid #eee;">
                <?php echo altalayi_format_date($ticket->created_at); ?>
            </td>
        </tr>
    </table>
</div>

<div style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0;">
    <h3 style="margin-top: 0;"><?php _e('Complaint Details', 'altalayi-ticket'); ?></h3>
    <p style="margin-bottom: 0;"><?php echo nl2br(esc_html($ticket->complaint_text)); ?></p>
</div>

<div style="text-align: center; margin: 30px 0;">
    <a href="<?php echo esc_url($admin_url); ?>" class="btn"><?php _e('View Ticket in Admin', 'altalayi-ticket'); ?></a>
</div>

<p><?php _e('Please review this ticket and assign it to the appropriate team member.', 'altalayi-ticket'); ?></p>

<?php echo $this->get_email_footer(); ?>
