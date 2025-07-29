<?php
/**
 * Basic Email Template - Fallback
 */

if (!defined('ABSPATH')) {
    exit;
}

echo $this->get_email_header();
?>

<h2><?php _e('Notification', 'altalayi-ticket'); ?></h2>

<p><?php _e('This is a basic notification template.', 'altalayi-ticket'); ?></p>

<div style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0;">
    <p><?php _e('Template not found. Please contact the system administrator.', 'altalayi-ticket'); ?></p>
</div>

<?php echo $this->get_email_footer(); ?>
