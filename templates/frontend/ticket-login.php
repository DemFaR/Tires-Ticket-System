<?php
/**
 * Frontend Ticket Login Template
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="altalayi-login-container">
    <div class="login-wrapper">
        <div class="login-header">
            <h1><?php _e('Access Your Ticket', 'altalayi-ticket'); ?></h1>
            <p><?php _e('Enter your ticket number and phone number to view your ticket status and updates.', 'altalayi-ticket'); ?></p>
        </div>
        
        <div id="login-messages"></div>
        
        <div class="login-forms">
            <!-- Login Form -->
            <div class="login-section">
                <h2><?php _e('Existing Ticket Login', 'altalayi-ticket'); ?></h2>
                
                <form id="ticket-login-form" class="login-form">
                    <?php wp_nonce_field('altalayi_ticket_nonce', 'nonce'); ?>
                    
                    <div class="form-group">
                        <label for="ticket_number">
                            <i class="dashicons dashicons-ticket"></i>
                            <?php _e('Ticket Number', 'altalayi-ticket'); ?>
                        </label>
                        <input type="text" id="ticket_number" name="ticket_number" required 
                               placeholder="ALT-<?php echo date('Y'); ?>-XXXXX">
                    </div>
                    
                    <div class="form-group">
                        <label for="phone">
                            <i class="dashicons dashicons-phone"></i>
                            <?php _e('Phone Number', 'altalayi-ticket'); ?>
                        </label>
                        <input type="tel" id="phone" name="phone" required 
                               placeholder="<?php _e('Enter your phone number', 'altalayi-ticket'); ?>">
                    </div>
                    
                    <button type="submit" class="login-btn">
                        <span class="btn-text">
                            <i class="dashicons dashicons-unlock"></i>
                            <?php _e('Access Ticket', 'altalayi-ticket'); ?>
                        </span>
                        <span class="btn-loading" style="display: none;">
                            <i class="dashicons dashicons-update rotate"></i>
                            <?php _e('Checking...', 'altalayi-ticket'); ?>
                        </span>
                    </button>
                </form>
            </div>
            
            <!-- Divider -->
            <div class="divider">
                <span><?php _e('OR', 'altalayi-ticket'); ?></span>
            </div>
            
            <!-- New Ticket Section -->
            <div class="new-ticket-section">
                <h2><?php _e('Submit New Complaint', 'altalayi-ticket'); ?></h2>
                <p><?php _e('Don\'t have a ticket yet? Create a new tire complaint.', 'altalayi-ticket'); ?></p>
                
                <a href="<?php echo esc_url(altalayi_get_create_ticket_url()); ?>" class="new-ticket-btn">
                    <i class="dashicons dashicons-plus-alt"></i>
                    <?php _e('Create New Ticket', 'altalayi-ticket'); ?>
                </a>
            </div>
        </div>
        
        <div class="login-footer">
            <div class="help-section">
                <h3><?php _e('Need Help?', 'altalayi-ticket'); ?></h3>
                <ul>
                    <li>
                        <i class="dashicons dashicons-info"></i>
                        <?php _e('Your ticket number was provided when you first submitted your complaint', 'altalayi-ticket'); ?>
                    </li>
                    <li>
                        <i class="dashicons dashicons-email"></i>
                        <?php _e('Check your email for the ticket confirmation message', 'altalayi-ticket'); ?>
                    </li>
                    <li>
                        <i class="dashicons dashicons-phone"></i>
                        <?php _e('Use the same phone number you provided when creating the ticket', 'altalayi-ticket'); ?>
                    </li>
                </ul>
            </div>
            
            <div class="contact-section">
                <h3><?php _e('Contact Support', 'altalayi-ticket'); ?></h3>
                <p><?php _e('If you\'re having trouble accessing your ticket, please contact our support team:', 'altalayi-ticket'); ?></p>
                <div class="contact-info">
                    <span class="contact-item">
                        <i class="dashicons dashicons-email"></i>
                        support@altalayi.com
                    </span>
                    <span class="contact-item">
                        <i class="dashicons dashicons-phone"></i>
                        +966-XXX-XXXX
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.altalayi-login-container {
    max-width: 600px;
    margin: 40px auto;
    padding: 0 20px;
}

.login-wrapper {
    background: white;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.login-header {
    background: linear-gradient(135deg, #2c3e50, #3498db);
    color: white;
    padding: 30px;
    text-align: center;
}

.login-header h1 {
    margin: 0 0 10px 0;
    font-size: 28px;
    font-weight: 300;
}

.login-header p {
    margin: 0;
    opacity: 0.9;
    font-size: 16px;
}

.login-forms {
    padding: 30px;
}

.login-section,
.new-ticket-section {
    margin-bottom: 30px;
}

.login-section h2,
.new-ticket-section h2 {
    color: #2c3e50;
    margin: 0 0 20px 0;
    font-size: 20px;
    font-weight: 600;
}

.login-form {
    background: #f8f9fa;
    padding: 25px;
    border-radius: 6px;
    border: 1px solid #e1e5e9;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #34495e;
}

.form-group label i {
    margin-right: 8px;
    color: #7f8c8d;
}

.form-group input {
    width: 100%;
    padding: 12px;
    border: 2px solid #e1e5e9;
    border-radius: 6px;
    font-size: 16px;
    transition: border-color 0.3s;
}

.form-group input:focus {
    outline: none;
    border-color: #3498db;
}

.login-btn,
.new-ticket-btn {
    width: 100%;
    padding: 15px;
    border: none;
    border-radius: 6px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    transition: transform 0.2s;
}

.login-btn {
    background: linear-gradient(135deg, #3498db, #2980b9);
    color: white;
}

.new-ticket-btn {
    background: linear-gradient(135deg, #27ae60, #2ecc71);
    color: white;
}

.login-btn:hover,
.new-ticket-btn:hover {
    transform: translateY(-2px);
}

.login-btn:disabled {
    opacity: 0.7;
    cursor: not-allowed;
    transform: none;
}

.login-btn i,
.new-ticket-btn i {
    margin-right: 8px;
}

.divider {
    text-align: center;
    position: relative;
    margin: 30px 0;
}

.divider::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 0;
    right: 0;
    height: 1px;
    background: #e1e5e9;
}

.divider span {
    background: white;
    padding: 0 20px;
    color: #7f8c8d;
    font-weight: 600;
    position: relative;
}

.new-ticket-section {
    text-align: center;
}

.new-ticket-section p {
    color: #7f8c8d;
    margin-bottom: 20px;
}

.login-footer {
    background: #f8f9fa;
    padding: 30px;
    border-top: 1px solid #e1e5e9;
}

.help-section,
.contact-section {
    margin-bottom: 25px;
}

.help-section h3,
.contact-section h3 {
    color: #2c3e50;
    margin: 0 0 15px 0;
    font-size: 18px;
    font-weight: 600;
}

.help-section ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.help-section li {
    margin-bottom: 10px;
    color: #5a6c7d;
    display: flex;
    align-items: flex-start;
}

.help-section li i {
    margin-right: 10px;
    margin-top: 2px;
    color: #3498db;
}

.contact-section p {
    color: #5a6c7d;
    margin-bottom: 15px;
}

.contact-info {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
}

.contact-item {
    display: flex;
    align-items: center;
    color: #2c3e50;
    font-weight: 500;
}

.contact-item i {
    margin-right: 8px;
    color: #3498db;
}

#login-messages {
    margin: 20px 30px 0;
}

.login-message {
    padding: 12px 20px;
    border-radius: 6px;
    margin-bottom: 15px;
}

.login-message.success {
    background: #d4edda;
    border: 1px solid #c3e6cb;
    color: #155724;
}

.login-message.error {
    background: #f8d7da;
    border: 1px solid #f5c6cb;
    color: #721c24;
}

.rotate {
    animation: rotate 1s linear infinite;
}

@keyframes rotate {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

@media (max-width: 768px) {
    .altalayi-login-container {
        margin: 20px auto;
        padding: 0 10px;
    }
    
    .login-forms {
        padding: 20px;
    }
    
    .login-footer {
        padding: 20px;
    }
    
    .login-header {
        padding: 20px;
    }
    
    .login-header h1 {
        font-size: 24px;
    }
    
    .contact-info {
        flex-direction: column;
        gap: 10px;
    }
}
</style>

<script>
jQuery(document).ready(function($) {
    $('#ticket-login-form').on('submit', function(e) {
        e.preventDefault();
        
        var form = $(this);
        var submitBtn = form.find('.login-btn');
        var formData = {
            action: 'altalayi_login_ticket',
            ticket_number: $('#ticket_number').val(),
            phone: $('#phone').val(),
            nonce: $('input[name="nonce"]').val()
        };
        
        // Disable submit button
        submitBtn.prop('disabled', true);
        submitBtn.find('.btn-text').hide();
        submitBtn.find('.btn-loading').show();
        
        $.ajax({
            url: altalayi_ajax.ajax_url,
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    $('#login-messages').html(
                        '<div class="login-message success">' +
                        '<strong><?php _e("Success!", "altalayi-ticket"); ?></strong> ' +
                        response.data.message +
                        '</div>'
                    );
                    
                    setTimeout(function() {
                        window.location.href = response.data.redirect_url;
                    }, 1500);
                } else {
                    $('#login-messages').html(
                        '<div class="login-message error">' +
                        '<strong><?php _e("Error:", "altalayi-ticket"); ?></strong> ' +
                        response.data.message +
                        '</div>'
                    );
                }
            },
            error: function() {
                $('#login-messages').html(
                    '<div class="login-message error">' +
                    '<strong><?php _e("Error:", "altalayi-ticket"); ?></strong> ' +
                    '<?php _e("An unexpected error occurred. Please try again.", "altalayi-ticket"); ?>' +
                    '</div>'
                );
            },
            complete: function() {
                // Re-enable submit button
                submitBtn.prop('disabled', false);
                submitBtn.find('.btn-text').show();
                submitBtn.find('.btn-loading').hide();
                
                $('html, body').animate({
                    scrollTop: $('#login-messages').offset().top - 100
                }, 500);
            }
        });
    });
});
</script>
