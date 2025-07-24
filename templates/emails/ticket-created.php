<?php
/**
 * Email Template: Ticket Created
 */

if (!defined('ABSPATH')) {
    exit;
}

echo $this->get_email_header();
?>

<h2><?php _e('Your Tire Complaint Has Been Received', 'altalayi-ticket'); ?></h2>

<p><?php printf(__('Dear %s,', 'altalayi-ticket'), esc_html($ticket->customer_name)); ?></p>

<p><?php _e('Thank you for contacting Altalayi regarding your tire concern. We have received your complaint and created a support ticket for you.', 'altalayi-ticket'); ?></p>

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
                <?php _e('Status:', 'altalayi-ticket'); ?>
            </td>
            <td style="padding: 10px; border-bottom: 1px solid #eee;">
                <span class="status-badge" style="background-color: <?php echo esc_attr($ticket->status_color); ?>;">
                    <?php echo esc_html($ticket->status_name); ?>
                </span>
            </td>
        </tr>
        <tr>
            <td style="padding: 10px; border-bottom: 1px solid #eee; font-weight: bold;">
                <?php _e('Date Created:', 'altalayi-ticket'); ?>
            </td>
            <td style="padding: 10px; border-bottom: 1px solid #eee;">
                <?php echo altalayi_format_date($ticket->created_at); ?>
            </td>
        </tr>
        <?php if ($ticket->tire_brand): ?>
        <tr>
            <td style="padding: 10px; border-bottom: 1px solid #eee; font-weight: bold;">
                <?php _e('Tire Brand:', 'altalayi-ticket'); ?>
            </td>
            <td style="padding: 10px; border-bottom: 1px solid #eee;">
                <?php echo esc_html($ticket->tire_brand); ?>
            </td>
        </tr>
        <?php endif; ?>
    </table>
</div>

<h3><?php _e('Access Your Ticket', 'altalayi-ticket'); ?></h3>
<p><?php _e('To check the status of your ticket or provide additional information, please use the following credentials:', 'altalayi-ticket'); ?></p>

<div style="background: #f0f8ff; padding: 20px; border-radius: 8px; margin: 20px 0;">
    <p style="margin: 0 0 10px 0;"><strong><?php _e('Ticket Number:', 'altalayi-ticket'); ?></strong> <code style="background: #e3f2fd; padding: 4px 8px; border-radius: 4px; font-size: 16px;"><?php echo esc_html($ticket->ticket_number); ?></code></p>
    <p style="margin: 0;"><strong><?php _e('Phone Number:', 'altalayi-ticket'); ?></strong> <code style="background: #e3f2fd; padding: 4px 8px; border-radius: 4px; font-size: 16px;"><?php echo esc_html($ticket->customer_phone); ?></code></p>
</div>

<div style="text-align: center; margin: 30px 0;">
    <a href="<?php echo esc_url($view_url); ?>" class="btn"><?php _e('View Your Ticket', 'altalayi-ticket'); ?></a>
</div>

<h3><?php _e('What Happens Next?', 'altalayi-ticket'); ?></h3>
<ol>
    <li><?php _e('Our technical team will review your complaint within 24-48 hours', 'altalayi-ticket'); ?></li>
    <li><?php _e('We may contact you for additional information if needed', 'altalayi-ticket'); ?></li>
    <li><?php _e('You will receive email updates whenever your ticket status changes', 'altalayi-ticket'); ?></li>
    <li><?php _e('We will work to resolve your concern as quickly as possible', 'altalayi-ticket'); ?></li>
</ol>

<div style="background: #fff3cd; border: 1px solid #ffeaa7; padding: 15px; border-radius: 8px; margin: 20px 0;">
    <p style="margin: 0;"><strong><?php _e('Important:', 'altalayi-ticket'); ?></strong> <?php _e('Please save your ticket number and keep this email for your records. You will need these credentials to access your ticket online.', 'altalayi-ticket'); ?></p>
</div>

<p><?php _e('If you have any urgent questions, please don\'t hesitate to contact our support team directly.', 'altalayi-ticket'); ?></p>

<p><?php _e('Thank you for choosing Altalayi.', 'altalayi-ticket'); ?></p>

<?php echo $this->get_email_footer(); ?>
