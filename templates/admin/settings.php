<?php
/**
 * Settings Admin Template
 */

if (!defined('ABSPATH')) {
    exit;
}

// Handle form submission
if (isset($_POST['submit']) && wp_verify_nonce($_POST['altalayi_settings_nonce'], 'altalayi_settings_action')) {
    $settings = array(
        'company_name' => sanitize_text_field($_POST['company_name']),
        'company_email' => sanitize_email($_POST['company_email']),
        'company_phone' => sanitize_text_field($_POST['company_phone']),
        'company_address' => sanitize_textarea_field($_POST['company_address']),
        'company_website' => esc_url_raw($_POST['company_website']),
        'frontend_create_page' => intval($_POST['frontend_create_page']),
        'frontend_access_page' => intval($_POST['frontend_access_page']),
        'frontend_view_page' => intval($_POST['frontend_view_page']),
        'frontend_create_page_ar' => intval($_POST['frontend_create_page_ar']),
        'frontend_access_page_ar' => intval($_POST['frontend_access_page_ar']),
        'frontend_view_page_ar' => intval($_POST['frontend_view_page_ar']),
        'enable_email_notifications' => isset($_POST['enable_email_notifications']) ? 1 : 0,
        'admin_notification_email' => sanitize_email($_POST['admin_notification_email']),
        'notify_on_new_ticket' => isset($_POST['notify_on_new_ticket']) ? 1 : 0,
        'notify_on_status_change' => isset($_POST['notify_on_status_change']) ? 1 : 0,
        'notify_on_customer_response' => isset($_POST['notify_on_customer_response']) ? 1 : 0,
        'notification_roles' => isset($_POST['notification_roles']) ? array_map('sanitize_text_field', $_POST['notification_roles']) : array(),
        'additional_notification_emails' => sanitize_textarea_field($_POST['additional_notification_emails']),
        'tickets_per_page' => intval($_POST['tickets_per_page']),
        'allow_file_uploads' => isset($_POST['allow_file_uploads']) ? 1 : 0,
        'max_file_size' => intval($_POST['max_file_size']),
        'allowed_file_types' => sanitize_text_field($_POST['allowed_file_types']),
        'show_delete_button' => isset($_POST['show_delete_button']) ? 1 : 0,
        'enable_whatsapp_notifications' => isset($_POST['enable_whatsapp_notifications']) ? 1 : 0,
        'whatsapp_access_token' => sanitize_text_field($_POST['whatsapp_access_token']),
        'whatsapp_phone_number_id' => sanitize_text_field($_POST['whatsapp_phone_number_id']),
        'whatsapp_business_phone' => sanitize_text_field($_POST['whatsapp_business_phone']),
        'whatsapp_notify_on_new_ticket' => isset($_POST['whatsapp_notify_on_new_ticket']) ? 1 : 0,
        'whatsapp_notify_on_status_change' => isset($_POST['whatsapp_notify_on_status_change']) ? 1 : 0,
        'whatsapp_notify_on_employee_response' => isset($_POST['whatsapp_notify_on_employee_response']) ? 1 : 0,
        'enable_wasenderapi_notifications' => isset($_POST['enable_wasenderapi_notifications']) ? 1 : 0,
        'wasenderapi_api_key' => sanitize_text_field($_POST['wasenderapi_api_key']),
        'wasenderapi_session_id' => sanitize_text_field($_POST['wasenderapi_session_id']),
        'wasenderapi_notify_on_new_ticket' => isset($_POST['wasenderapi_notify_on_new_ticket']) ? 1 : 0,
        'wasenderapi_notify_on_status_change' => isset($_POST['wasenderapi_notify_on_status_change']) ? 1 : 0,
        'wasenderapi_notify_on_employee_response' => isset($_POST['wasenderapi_notify_on_employee_response']) ? 1 : 0,
        'enable_smtp' => isset($_POST['enable_smtp']) ? 1 : 0,
        'smtp_host' => sanitize_text_field($_POST['smtp_host']),
        'smtp_port' => intval($_POST['smtp_port']),
        'smtp_secure' => sanitize_text_field($_POST['smtp_secure']),
        'smtp_username' => sanitize_text_field($_POST['smtp_username']),
        'smtp_password' => sanitize_text_field($_POST['smtp_password']),
        'smtp_from_email' => sanitize_email($_POST['smtp_from_email']),
        'smtp_from_name' => sanitize_text_field($_POST['smtp_from_name'])
    );
    
    update_option('altalayi_ticket_settings', $settings);
    echo '<div class="notice notice-success"><p>' . __('Settings saved successfully!', 'altalayi-ticket') . '</p></div>';
}

// Get current settings
$settings = get_option('altalayi_ticket_settings', array());

// Default values
$defaults = array(
    'company_name' => 'Altalayi Company',
    'company_email' => 'support@al-talayi.com.sa',
    'company_phone' => '8002444447',
    'company_address' => '',
    'company_website' => home_url(),
    'frontend_create_page' => '',
    'frontend_access_page' => '',
    'frontend_view_page' => '',
    'frontend_create_page_ar' => '',
    'frontend_access_page_ar' => '',
    'frontend_view_page_ar' => '',
    'enable_email_notifications' => 1,
    'admin_notification_email' => get_option('admin_email'),
    'notify_on_new_ticket' => 1,
    'notify_on_status_change' => 1,
    'notify_on_customer_response' => 1,
    'notification_roles' => array('administrator'),
    'additional_notification_emails' => '',
    'tickets_per_page' => 20,
    'allow_file_uploads' => 1,
    'max_file_size' => 5,
    'allowed_file_types' => 'jpg,jpeg,png,gif,pdf,doc,docx',
    'show_delete_button' => 0,
    'enable_whatsapp_notifications' => 0,
    'whatsapp_access_token' => '',
    'whatsapp_phone_number_id' => '',
    'whatsapp_business_phone' => '',
    'whatsapp_notify_on_new_ticket' => 1,
    'whatsapp_notify_on_status_change' => 1,
    'whatsapp_notify_on_employee_response' => 1,
    'enable_wasenderapi_notifications' => 0,
    'wasenderapi_api_key' => '',
    'wasenderapi_session_id' => '',
    'wasenderapi_notify_on_new_ticket' => 1,
    'wasenderapi_notify_on_status_change' => 1,
    'wasenderapi_notify_on_employee_response' => 1,
    'enable_smtp' => 0,
    'smtp_host' => '',
    'smtp_port' => 587,
    'smtp_secure' => 'tls',
    'smtp_username' => '',
    'smtp_password' => '',
    'smtp_from_email' => '',
    'smtp_from_name' => ''
);

// Merge with defaults
$settings = wp_parse_args($settings, $defaults);

// Ensure notification_roles is always an array
if (!isset($settings['notification_roles']) || !is_array($settings['notification_roles'])) {
    $settings['notification_roles'] = array('administrator');
}

// Get all pages for dropdown
$pages = get_pages();

// Get all WordPress roles for notifications
global $wp_roles;
$all_roles = $wp_roles->roles;
?>

<div class="wrap">
    <h1><?php _e('Altalayi Ticket System Settings', 'altalayi-ticket'); ?></h1>
    
    <form method="post" action="">
        <?php wp_nonce_field('altalayi_settings_action', 'altalayi_settings_nonce'); ?>
        
        <nav class="nav-tab-wrapper">
            <a href="#general" class="nav-tab nav-tab-active"><?php _e('General', 'altalayi-ticket'); ?></a>
            <a href="#company" class="nav-tab"><?php _e('Company Info', 'altalayi-ticket'); ?></a>
            <a href="#frontend" class="nav-tab"><?php _e('Frontend Pages', 'altalayi-ticket'); ?></a>
            <a href="#notifications" class="nav-tab"><?php _e('Notifications', 'altalayi-ticket'); ?></a>
            <a href="#email-smtp" class="nav-tab"><?php _e('Email & SMTP', 'altalayi-ticket'); ?></a>
            <a href="#whatsapp" class="nav-tab"><?php _e('WhatsApp', 'altalayi-ticket'); ?></a>
            <a href="#files" class="nav-tab"><?php _e('File Uploads', 'altalayi-ticket'); ?></a>
        </nav>
        
        <!-- General Settings -->
        <div id="general" class="tab-content active">
            <h2><?php _e('General Settings', 'altalayi-ticket'); ?></h2>
            <table class="form-table">
                <tr>
                    <th scope="row">
                        <label for="tickets_per_page"><?php _e('Tickets Per Page', 'altalayi-ticket'); ?></label>
                    </th>
                    <td>
                        <input type="number" id="tickets_per_page" name="tickets_per_page" 
                               value="<?php echo esc_attr($settings['tickets_per_page']); ?>" 
                               min="5" max="100" class="small-text" />
                        <p class="description"><?php _e('Number of tickets to display per page in admin.', 'altalayi-ticket'); ?></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="show_delete_button"><?php _e('Show Delete Buttons', 'altalayi-ticket'); ?></label>
                    </th>
                    <td>
                        <fieldset>
                            <label for="show_delete_button">
                                <input type="checkbox" id="show_delete_button" name="show_delete_button" value="1" 
                                       <?php checked($settings['show_delete_button'], 1); ?> />
                                <?php _e('Show delete buttons in ticket lists', 'altalayi-ticket'); ?>
                            </label>
                            <p class="description"><?php _e('When enabled, delete buttons will appear in dashboard, open tickets, and closed tickets lists. Deleting a ticket will permanently remove all associated files from the server.', 'altalayi-ticket'); ?></p>
                        </fieldset>
                    </td>
                </tr>
            </table>
        </div>
        
        <!-- Company Information -->
        <div id="company" class="tab-content">
            <h2><?php _e('Company Information', 'altalayi-ticket'); ?></h2>
            <table class="form-table">
                <tr>
                    <th scope="row">
                        <label for="company_name"><?php _e('Company Name', 'altalayi-ticket'); ?></label>
                    </th>
                    <td>
                        <input type="text" id="company_name" name="company_name" 
                               value="<?php echo esc_attr($settings['company_name']); ?>" 
                               class="regular-text" />
                        <p class="description"><?php _e('Your company name (used in emails and documents).', 'altalayi-ticket'); ?></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="company_email"><?php _e('Company Email', 'altalayi-ticket'); ?></label>
                    </th>
                    <td>
                        <input type="email" id="company_email" name="company_email" 
                               value="<?php echo esc_attr($settings['company_email']); ?>" 
                               class="regular-text" />
                        <p class="description"><?php _e('Main company email address.', 'altalayi-ticket'); ?></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="company_phone"><?php _e('Company Phone', 'altalayi-ticket'); ?></label>
                    </th>
                    <td>
                        <input type="text" id="company_phone" name="company_phone" 
                               value="<?php echo esc_attr($settings['company_phone']); ?>" 
                               class="regular-text" />
                        <p class="description"><?php _e('Company phone number.', 'altalayi-ticket'); ?></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="company_address"><?php _e('Company Address', 'altalayi-ticket'); ?></label>
                    </th>
                    <td>
                        <textarea id="company_address" name="company_address" 
                                  class="large-text" rows="4"><?php echo esc_textarea($settings['company_address']); ?></textarea>
                        <p class="description"><?php _e('Company physical address.', 'altalayi-ticket'); ?></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="company_website"><?php _e('Company Website', 'altalayi-ticket'); ?></label>
                    </th>
                    <td>
                        <input type="url" id="company_website" name="company_website" 
                               value="<?php echo esc_url($settings['company_website']); ?>" 
                               class="regular-text" />
                        <p class="description"><?php _e('Company website URL.', 'altalayi-ticket'); ?></p>
                    </td>
                </tr>
            </table>
        </div>
        
        <!-- Frontend Pages -->
        <div id="frontend" class="tab-content">
            <h2><?php _e('Frontend Pages', 'altalayi-ticket'); ?></h2>
            <table class="form-table">
                <tr>
                    <th scope="row">
                        <label for="frontend_create_page"><?php _e('Ticket Creation Page', 'altalayi-ticket'); ?></label>
                    </th>
                    <td>
                        <select id="frontend_create_page" name="frontend_create_page">
                            <option value=""><?php _e('Select a page...', 'altalayi-ticket'); ?></option>
                            <?php foreach ($pages as $page): ?>
                                <option value="<?php echo esc_attr($page->ID); ?>" 
                                        <?php selected($settings['frontend_create_page'], $page->ID); ?>>
                                    <?php echo esc_html($page->post_title); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <p class="description">
                            <?php _e('Page where customers can create new tickets. Add the shortcode [altalayi_ticket_form] to this page.', 'altalayi-ticket'); ?>
                        </p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="frontend_access_page"><?php _e('Ticket Access Page', 'altalayi-ticket'); ?></label>
                    </th>
                    <td>
                        <select id="frontend_access_page" name="frontend_access_page">
                            <option value=""><?php _e('Select a page...', 'altalayi-ticket'); ?></option>
                            <?php foreach ($pages as $page): ?>
                                <option value="<?php echo esc_attr($page->ID); ?>" 
                                        <?php selected($settings['frontend_access_page'], $page->ID); ?>>
                                    <?php echo esc_html($page->post_title); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <p class="description">
                            <?php _e('Page where customers can access their tickets. Add the shortcode [altalayi_ticket_login] to this page.', 'altalayi-ticket'); ?>
                        </p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="frontend_view_page"><?php _e('Ticket View Page', 'altalayi-ticket'); ?></label>
                    </th>
                    <td>
                        <select id="frontend_view_page" name="frontend_view_page">
                            <option value=""><?php _e('Use default /ticket/ URL', 'altalayi-ticket'); ?></option>
                            <?php foreach ($pages as $page): ?>
                                <option value="<?php echo esc_attr($page->ID); ?>" 
                                        <?php selected($settings['frontend_view_page'], $page->ID); ?>>
                                    <?php echo esc_html($page->post_title); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <p class="description">
                            <?php _e('Page where customers will view their ticket details. Add the shortcode [altalayi_ticket_view auto_detect="true"] to this page. If not selected, the default /ticket/ URL will be used.', 'altalayi-ticket'); ?>
                        </p>
                    </td>
                </tr>
            </table>
            
            <h3><?php _e('Arabic Pages (Optional)', 'altalayi-ticket'); ?></h3>
            <p class="description">
                <?php _e('Set separate pages for Arabic language support. If not set, language parameter will be added to the main pages.', 'altalayi-ticket'); ?>
            </p>
            <table class="form-table">
                <tr>
                    <th scope="row">
                        <label for="frontend_create_page_ar"><?php _e('Arabic Ticket Creation Page', 'altalayi-ticket'); ?></label>
                    </th>
                    <td>
                        <select id="frontend_create_page_ar" name="frontend_create_page_ar">
                            <option value=""><?php _e('Use main page with language parameter', 'altalayi-ticket'); ?></option>
                            <?php foreach ($pages as $page): ?>
                                <option value="<?php echo esc_attr($page->ID); ?>" 
                                        <?php selected($settings['frontend_create_page_ar'], $page->ID); ?>>
                                    <?php echo esc_html($page->post_title); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <p class="description">
                            <?php _e('Optional: Separate page for Arabic ticket creation. Add the shortcode [altalayi_ticket_form] to this page.', 'altalayi-ticket'); ?>
                        </p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="frontend_access_page_ar"><?php _e('Arabic Ticket Access Page', 'altalayi-ticket'); ?></label>
                    </th>
                    <td>
                        <select id="frontend_access_page_ar" name="frontend_access_page_ar">
                            <option value=""><?php _e('Use main page with language parameter', 'altalayi-ticket'); ?></option>
                            <?php foreach ($pages as $page): ?>
                                <option value="<?php echo esc_attr($page->ID); ?>" 
                                        <?php selected($settings['frontend_access_page_ar'], $page->ID); ?>>
                                    <?php echo esc_html($page->post_title); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <p class="description">
                            <?php _e('Optional: Separate page for Arabic ticket access. Add the shortcode [altalayi_ticket_login] to this page.', 'altalayi-ticket'); ?>
                        </p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="frontend_view_page_ar"><?php _e('Arabic Ticket View Page', 'altalayi-ticket'); ?></label>
                    </th>
                    <td>
                        <select id="frontend_view_page_ar" name="frontend_view_page_ar">
                            <option value=""><?php _e('Use main page with language parameter', 'altalayi-ticket'); ?></option>
                            <?php foreach ($pages as $page): ?>
                                <option value="<?php echo esc_attr($page->ID); ?>" 
                                        <?php selected($settings['frontend_view_page_ar'], $page->ID); ?>>
                                    <?php echo esc_html($page->post_title); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <p class="description">
                            <?php _e('Optional: Separate page for Arabic ticket viewing. Add the shortcode [altalayi_ticket_view auto_detect="true"] to this page.', 'altalayi-ticket'); ?>
                        </p>
                    </td>
                </tr>
            </table>
            
            <h3><?php _e('Available Shortcodes', 'altalayi-ticket'); ?></h3>
            <table class="form-table">
                <tr>
                    <td>
                        <div class="shortcode-info">
                            <h4><?php _e('Ticket Creation Form', 'altalayi-ticket'); ?></h4>
                            <code>[altalayi_ticket_form]</code>
                            <p><?php _e('Displays the ticket creation form for customers.', 'altalayi-ticket'); ?></p>
                        </div>
                        
                        <div class="shortcode-info">
                            <h4><?php _e('Ticket Login/Access', 'altalayi-ticket'); ?></h4>
                            <code>[altalayi_ticket_login]</code>
                            <p><?php _e('Displays the ticket login form where customers can access their tickets.', 'altalayi-ticket'); ?></p>
                        </div>
                        
                        <div class="shortcode-info">
                            <h4><?php _e('Ticket View', 'altalayi-ticket'); ?></h4>
                            <code>[altalayi_ticket_view auto_detect="true"]</code>
                            <p><?php _e('Displays ticket details with automatic detection. Use this shortcode on your custom ticket view page.', 'altalayi-ticket'); ?></p>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
        
        <!-- Email Notifications -->
        <div id="notifications" class="tab-content">
            <h2><?php _e('Email Notifications', 'altalayi-ticket'); ?></h2>
            <table class="form-table">
                <tr>
                    <th scope="row"><?php _e('Enable Notifications', 'altalayi-ticket'); ?></th>
                    <td>
                        <fieldset>
                            <label for="enable_email_notifications">
                                <input type="checkbox" id="enable_email_notifications" name="enable_email_notifications" 
                                       value="1" <?php checked($settings['enable_email_notifications'], 1); ?> />
                                <?php _e('Enable email notifications', 'altalayi-ticket'); ?>
                            </label>
                        </fieldset>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="admin_notification_email"><?php _e('Admin Email', 'altalayi-ticket'); ?></label>
                    </th>
                    <td>
                        <input type="email" id="admin_notification_email" name="admin_notification_email" 
                               value="<?php echo esc_attr($settings['admin_notification_email']); ?>" 
                               class="regular-text" />
                        <p class="description"><?php _e('Email address to receive admin notifications.', 'altalayi-ticket'); ?></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Notification Types', 'altalayi-ticket'); ?></th>
                    <td>
                        <fieldset>
                            <label for="notify_on_new_ticket">
                                <input type="checkbox" id="notify_on_new_ticket" name="notify_on_new_ticket" 
                                       value="1" <?php checked($settings['notify_on_new_ticket'], 1); ?> />
                                <?php _e('Notify admin when new ticket is created', 'altalayi-ticket'); ?>
                            </label><br>
                            
                            <label for="notify_on_status_change">
                                <input type="checkbox" id="notify_on_status_change" name="notify_on_status_change" 
                                       value="1" <?php checked($settings['notify_on_status_change'], 1); ?> />
                                <?php _e('Notify customer when ticket status changes', 'altalayi-ticket'); ?>
                            </label><br>
                            
                            <label for="notify_on_customer_response">
                                <input type="checkbox" id="notify_on_customer_response" name="notify_on_customer_response" 
                                       value="1" <?php checked($settings['notify_on_customer_response'], 1); ?> />
                                <?php _e('Notify admin when customer responds', 'altalayi-ticket'); ?>
                            </label>
                        </fieldset>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Notify User Roles', 'altalayi-ticket'); ?></th>
                    <td>
                        <fieldset>
                            <div class="role-tags-container">
                                <label><?php _e('Select roles to receive notifications:', 'altalayi-ticket'); ?></label>
                                <div class="role-tags-wrapper">
                                    <?php foreach ($all_roles as $role_key => $role_info): ?>
                                        <?php 
                                        $checked = in_array($role_key, $settings['notification_roles']);
                                        $role_name = translate_user_role($role_info['name']);
                                        ?>
                                        <label class="role-tag <?php echo $checked ? 'selected' : ''; ?>">
                                            <input type="checkbox" name="notification_roles[]" 
                                                   value="<?php echo esc_attr($role_key); ?>" 
                                                   <?php checked($checked); ?> />
                                            <span class="role-tag-label">
                                                <?php echo esc_html($role_name); ?>
                                                <span class="user-count">
                                                    (<?php echo count(get_users(array('role' => $role_key))); ?>)
                                                </span>
                                            </span>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                                <p class="description">
                                    <?php _e('All users with selected roles will receive email notifications. User count is shown in parentheses.', 'altalayi-ticket'); ?>
                                </p>
                            </div>
                        </fieldset>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Additional Emails', 'altalayi-ticket'); ?></th>
                    <td>
                        <textarea name="additional_notification_emails" class="large-text" rows="3" 
                                  placeholder="<?php _e('Enter additional email addresses, one per line...', 'altalayi-ticket'); ?>"><?php 
                            echo esc_textarea(isset($settings['additional_notification_emails']) ? $settings['additional_notification_emails'] : ''); 
                        ?></textarea>
                        <p class="description">
                            <?php _e('Additional email addresses to receive notifications (one per line). These will be added to role-based notifications.', 'altalayi-ticket'); ?>
                        </p>
                    </td>
                </tr>
            </table>
            
                        
            <h3><?php _e('WhatsApp Business Notifications', 'altalayi-ticket'); ?></h3>
        </div>
        
        <!-- Email & SMTP Settings -->
        <div id="email-smtp" class="tab-content">
            <h2><?php _e('Email & SMTP Configuration', 'altalayi-ticket'); ?></h2>
            <p class="description">
                <?php _e('Configure SMTP settings to improve email deliverability and avoid spam filters. Using SMTP is recommended over PHP mail() function.', 'altalayi-ticket'); ?>
            </p>
            
            <table class="form-table">
                <tr>
                    <th scope="row"><?php _e('Enable SMTP', 'altalayi-ticket'); ?></th>
                    <td>
                        <fieldset>
                            <label for="enable_smtp">
                                <input type="checkbox" id="enable_smtp" name="enable_smtp" 
                                       value="1" <?php checked($settings['enable_smtp'], 1); ?> />
                                <?php _e('Use SMTP for sending emails (Recommended)', 'altalayi-ticket'); ?>
                            </label>
                            <p class="description">
                                <?php _e('Enable this to use SMTP instead of PHP mail() function. This helps avoid spam filters and improves email authentication.', 'altalayi-ticket'); ?>
                            </p>
                        </fieldset>
                    </td>
                </tr>
                <tr class="smtp-setting">
                    <th scope="row">
                        <label for="smtp_host"><?php _e('SMTP Host', 'altalayi-ticket'); ?></label>
                    </th>
                    <td>
                        <input type="text" id="smtp_host" name="smtp_host" 
                               value="<?php echo esc_attr($settings['smtp_host']); ?>" 
                               class="regular-text" placeholder="smtp.gmail.com" />
                        <p class="description">
                            <?php _e('Your SMTP server hostname. Examples: smtp.gmail.com, mail.yourdomain.com', 'altalayi-ticket'); ?>
                        </p>
                    </td>
                </tr>
                <tr class="smtp-setting">
                    <th scope="row">
                        <label for="smtp_port"><?php _e('SMTP Port', 'altalayi-ticket'); ?></label>
                    </th>
                    <td>
                        <input type="number" id="smtp_port" name="smtp_port" 
                               value="<?php echo esc_attr($settings['smtp_port']); ?>" 
                               class="small-text" min="1" max="65535" />
                        <p class="description">
                            <?php _e('Common ports: 587 (TLS), 465 (SSL), 25 (Plain). Use 587 for most providers.', 'altalayi-ticket'); ?>
                        </p>
                    </td>
                </tr>
                <tr class="smtp-setting">
                    <th scope="row">
                        <label for="smtp_secure"><?php _e('Encryption', 'altalayi-ticket'); ?></label>
                    </th>
                    <td>
                        <select id="smtp_secure" name="smtp_secure">
                            <option value="none" <?php selected($settings['smtp_secure'], 'none'); ?>><?php _e('None', 'altalayi-ticket'); ?></option>
                            <option value="tls" <?php selected($settings['smtp_secure'], 'tls'); ?>><?php _e('TLS (Recommended)', 'altalayi-ticket'); ?></option>
                            <option value="ssl" <?php selected($settings['smtp_secure'], 'ssl'); ?>><?php _e('SSL', 'altalayi-ticket'); ?></option>
                        </select>
                        <p class="description">
                            <?php _e('Select the encryption method. TLS is recommended for most providers.', 'altalayi-ticket'); ?>
                        </p>
                    </td>
                </tr>
                <tr class="smtp-setting">
                    <th scope="row">
                        <label for="smtp_username"><?php _e('SMTP Username', 'altalayi-ticket'); ?></label>
                    </th>
                    <td>
                        <input type="text" id="smtp_username" name="smtp_username" 
                               value="<?php echo esc_attr($settings['smtp_username']); ?>" 
                               class="regular-text" placeholder="your-email@yourdomain.com" />
                        <p class="description">
                            <?php _e('Your SMTP username (usually your email address).', 'altalayi-ticket'); ?>
                        </p>
                    </td>
                </tr>
                <tr class="smtp-setting">
                    <th scope="row">
                        <label for="smtp_password"><?php _e('SMTP Password', 'altalayi-ticket'); ?></label>
                    </th>
                    <td>
                        <input type="password" id="smtp_password" name="smtp_password" 
                               value="<?php echo esc_attr($settings['smtp_password']); ?>" 
                               class="regular-text" placeholder="••••••••" />
                        <p class="description">
                            <?php _e('Your SMTP password or app-specific password. Keep this secure!', 'altalayi-ticket'); ?>
                            <br>
                            <strong><?php _e('Gmail Users:', 'altalayi-ticket'); ?></strong>
                            <a href="https://support.google.com/accounts/answer/185833" target="_blank">
                                <?php _e('Use App Password instead of your regular password', 'altalayi-ticket'); ?> ↗
                            </a>
                        </p>
                    </td>
                </tr>
                <tr class="smtp-setting">
                    <th scope="row">
                        <label for="smtp_from_email"><?php _e('From Email', 'altalayi-ticket'); ?></label>
                    </th>
                    <td>
                        <input type="email" id="smtp_from_email" name="smtp_from_email" 
                               value="<?php echo esc_attr($settings['smtp_from_email']); ?>" 
                               class="regular-text" placeholder="support@yourdomain.com" />
                        <p class="description">
                            <?php _e('Email address to send from. Should match your SMTP username for best deliverability.', 'altalayi-ticket'); ?>
                        </p>
                    </td>
                </tr>
                <tr class="smtp-setting">
                    <th scope="row">
                        <label for="smtp_from_name"><?php _e('From Name', 'altalayi-ticket'); ?></label>
                    </th>
                    <td>
                        <input type="text" id="smtp_from_name" name="smtp_from_name" 
                               value="<?php echo esc_attr($settings['smtp_from_name']); ?>" 
                               class="regular-text" placeholder="Altalayi Support" />
                        <p class="description">
                            <?php _e('Display name for outgoing emails.', 'altalayi-ticket'); ?>
                        </p>
                    </td>
                </tr>
                <tr class="smtp-setting">
                    <td colspan="2">
                        <div class="smtp-test-section">
                            <h4><?php _e('Test SMTP Configuration', 'altalayi-ticket'); ?></h4>
                            <p class="description">
                                <?php _e('After saving your settings, test the SMTP configuration by sending a test email.', 'altalayi-ticket'); ?>
                            </p>
                            <div class="smtp-test-form" style="margin-top: 15px;">
                                <input type="email" id="test_smtp_email" placeholder="test@example.com" class="regular-text" />
                                <button type="button" id="test_smtp_btn" class="button" disabled>
                                    <?php _e('Send Test Email', 'altalayi-ticket'); ?>
                                </button>
                                <div id="smtp_test_result" style="margin-top: 10px;"></div>
                            </div>
                        </div>
                    </td>
                </tr>
            </table>
            
            <h3><?php _e('Email Deliverability Tips', 'altalayi-ticket'); ?></h3>
            <div class="deliverability-tips">
                <div class="tip-section">
                    <h4><?php _e('1. DNS Authentication Records', 'altalayi-ticket'); ?></h4>
                    <p><?php _e('Add these DNS records to your domain to improve email authentication:', 'altalayi-ticket'); ?></p>
                    <ul>
                        <li><strong>SPF:</strong> <code>v=spf1 include:_spf.google.com ~all</code> <?php _e('(adjust for your email provider)', 'altalayi-ticket'); ?></li>
                        <li><strong>DKIM:</strong> <?php _e('Contact your hosting provider to set up DKIM signing', 'altalayi-ticket'); ?></li>
                        <li><strong>DMARC:</strong> <code>v=DMARC1; p=quarantine; rua=mailto:dmarc@yourdomain.com</code></li>
                    </ul>
                </div>
                
                <div class="tip-section">
                    <h4><?php _e('2. Recommended Email Providers', 'altalayi-ticket'); ?></h4>
                    <ul>
                        <li><strong>Gmail/Google Workspace:</strong> smtp.gmail.com, Port 587, TLS</li>
                        <li><strong>Microsoft 365:</strong> smtp.office365.com, Port 587, TLS</li>
                        <li><strong>SendGrid:</strong> smtp.sendgrid.net, Port 587, TLS</li>
                        <li><strong>Mailgun:</strong> smtp.mailgun.org, Port 587, TLS</li>
                    </ul>
                </div>
                
                <div class="tip-section">
                    <h4><?php _e('3. Common Issues', 'altalayi-ticket'); ?></h4>
                    <ul>
                        <li><?php _e('Make sure your "From Email" matches your SMTP username', 'altalayi-ticket'); ?></li>
                        <li><?php _e('Use App Passwords for Gmail (not your regular password)', 'altalayi-ticket'); ?></li>
                        <li><?php _e('Ensure your hosting provider allows SMTP connections', 'altalayi-ticket'); ?></li>
                        <li><?php _e('Check that your domain has proper SPF, DKIM, and DMARC records', 'altalayi-ticket'); ?></li>
                    </ul>
                </div>
            </div>
        </div>
        
        <!-- WhatsApp Settings -->
        <div id="whatsapp" class="tab-content">
            <h2><?php _e('WhatsApp Notifications', 'altalayi-ticket'); ?></h2>
            <p class="description">
                <?php _e('Configure WhatsApp notifications to customers. You can choose between WhatsApp Business API (official) or WaSenderAPI (alternative solution).', 'altalayi-ticket'); ?>
            </p>
            
            <h3><?php _e('WhatsApp Business API (Official)', 'altalayi-ticket'); ?></h3>
            <p class="description">
                <?php _e('Configure WhatsApp Business API to send automated notifications to customers. You need a verified WhatsApp Business API account and access token from Meta Business.', 'altalayi-ticket'); ?>
            </p>
            <table class="form-table">
                <tr>
                    <th scope="row"><?php _e('Enable WhatsApp Business Notifications', 'altalayi-ticket'); ?></th>
                    <td>
                        <fieldset>
                            <label for="enable_whatsapp_notifications">
                                <input type="checkbox" id="enable_whatsapp_notifications" name="enable_whatsapp_notifications" 
                                       value="1" <?php checked($settings['enable_whatsapp_notifications'], 1); ?> />
                                <?php _e('Enable WhatsApp Business notifications to customers', 'altalayi-ticket'); ?>
                            </label>
                            <p class="description">
                                <?php _e('When enabled, customers will receive WhatsApp notifications in addition to email notifications.', 'altalayi-ticket'); ?>
                            </p>
                        </fieldset>
                    </td>
                </tr>
                <tr class="whatsapp-setting">
                    <th scope="row">
                        <label for="whatsapp_access_token"><?php _e('WhatsApp Access Token', 'altalayi-ticket'); ?></label>
                    </th>
                    <td>
                        <input type="text" id="whatsapp_access_token" name="whatsapp_access_token" 
                               value="<?php echo esc_attr($settings['whatsapp_access_token']); ?>" 
                               class="large-text" placeholder="EAAxxxxxxxxxx..." />
                        <p class="description">
                            <?php _e('Your WhatsApp Business API access token from Meta Business Manager. Keep this secure!', 'altalayi-ticket'); ?>
                            <br>
                            <a href="https://developers.facebook.com/docs/whatsapp/business-management-api/get-started" target="_blank">
                                <?php _e('How to get WhatsApp Business API access token', 'altalayi-ticket'); ?> ↗
                            </a>
                        </p>
                    </td>
                </tr>
                <tr class="whatsapp-setting">
                    <th scope="row">
                        <label for="whatsapp_phone_number_id"><?php _e('WhatsApp Phone Number ID', 'altalayi-ticket'); ?></label>
                    </th>
                    <td>
                        <input type="text" id="whatsapp_phone_number_id" name="whatsapp_phone_number_id" 
                               value="<?php echo esc_attr($settings['whatsapp_phone_number_id']); ?>" 
                               class="regular-text" placeholder="102xxxxxxxxxx" />
                        <p class="description">
                            <?php _e('The Phone Number ID from your WhatsApp Business API configuration (not the actual phone number).', 'altalayi-ticket'); ?>
                            <br>
                            <strong><?php _e('Note:', 'altalayi-ticket'); ?></strong>
                            <?php _e('This is different from your company phone number and is used specifically for WhatsApp API calls.', 'altalayi-ticket'); ?>
                        </p>
                    </td>
                </tr>
                <tr class="whatsapp-setting">
                    <th scope="row">
                        <label for="whatsapp_business_phone"><?php _e('WhatsApp Business Phone', 'altalayi-ticket'); ?></label>
                    </th>
                    <td>
                        <input type="text" id="whatsapp_business_phone" name="whatsapp_business_phone" 
                               value="<?php echo esc_attr($settings['whatsapp_business_phone']); ?>" 
                               class="regular-text" placeholder="+966XXXXXXXXX" />
                        <p class="description">
                            <?php _e('The actual WhatsApp Business phone number used for sending messages (with country code, e.g., +966501234567).', 'altalayi-ticket'); ?>
                            <br>
                            <strong><?php _e('Important:', 'altalayi-ticket'); ?></strong>
                            <?php _e('This should be different from your call center number and must be verified with WhatsApp Business.', 'altalayi-ticket'); ?>
                        </p>
                    </td>
                </tr>
                <tr class="whatsapp-setting">
                    <th scope="row"><?php _e('WhatsApp Notification Types', 'altalayi-ticket'); ?></th>
                    <td>
                        <fieldset>
                            <label for="whatsapp_notify_on_new_ticket">
                                <input type="checkbox" id="whatsapp_notify_on_new_ticket" name="whatsapp_notify_on_new_ticket" 
                                       value="1" <?php checked($settings['whatsapp_notify_on_new_ticket'], 1); ?> />
                                <?php _e('Send WhatsApp notification when ticket is created', 'altalayi-ticket'); ?>
                            </label><br>
                            
                            <label for="whatsapp_notify_on_status_change">
                                <input type="checkbox" id="whatsapp_notify_on_status_change" name="whatsapp_notify_on_status_change" 
                                       value="1" <?php checked($settings['whatsapp_notify_on_status_change'], 1); ?> />
                                <?php _e('Send WhatsApp notification when ticket status changes', 'altalayi-ticket'); ?>
                            </label><br>
                            
                            <label for="whatsapp_notify_on_employee_response">
                                <input type="checkbox" id="whatsapp_notify_on_employee_response" name="whatsapp_notify_on_employee_response" 
                                       value="1" <?php checked($settings['whatsapp_notify_on_employee_response'], 1); ?> />
                                <?php _e('Send WhatsApp notification when employee responds to ticket', 'altalayi-ticket'); ?>
                            </label>
                        </fieldset>
                        <p class="description">
                            <?php _e('Select which events should trigger WhatsApp notifications to customers.', 'altalayi-ticket'); ?>
                        </p>
                    </td>
                </tr>
                <tr class="whatsapp-setting">
                    <td colspan="2">
                        <div class="whatsapp-test-section">
                            <h4><?php _e('Test WhatsApp Configuration', 'altalayi-ticket'); ?></h4>
                            <p class="description">
                                <?php _e('After saving your settings, you can test the WhatsApp integration by sending a test message.', 'altalayi-ticket'); ?>
                            </p>
                            <div class="whatsapp-test-form" style="margin-top: 15px;">
                                <input type="text" id="test_whatsapp_phone" placeholder="+966501234567" class="regular-text" />
                                <button type="button" id="test_whatsapp_btn" class="button" disabled>
                                    <?php _e('Send Test Message', 'altalayi-ticket'); ?>
                                </button>
                                <div id="whatsapp_test_result" style="margin-top: 10px;"></div>
                            </div>
                        </div>
                    </td>
                </tr>
            </table>
            
            <h3><?php _e('WaSenderAPI (Alternative Solution)', 'altalayi-ticket'); ?></h3>
            <p class="description">
                <?php _e('Configure WaSenderAPI as an alternative to WhatsApp Business API. This service allows you to send WhatsApp messages through their API without needing official WhatsApp Business approval.', 'altalayi-ticket'); ?>
                <br>
                <strong><?php _e('Note:', 'altalayi-ticket'); ?></strong>
                <?php _e('You need to create an account at wasenderapi.com and set up a WhatsApp session.', 'altalayi-ticket'); ?>
            </p>
            <table class="form-table">
                <tr>
                    <th scope="row"><?php _e('Enable WaSenderAPI Notifications', 'altalayi-ticket'); ?></th>
                    <td>
                        <fieldset>
                            <label for="enable_wasenderapi_notifications">
                                <input type="checkbox" id="enable_wasenderapi_notifications" name="enable_wasenderapi_notifications" 
                                       value="1" <?php checked($settings['enable_wasenderapi_notifications'], 1); ?> />
                                <?php _e('Enable WaSenderAPI notifications to customers', 'altalayi-ticket'); ?>
                            </label>
                            <p class="description">
                                <?php _e('When enabled, customers will receive WhatsApp notifications via WaSenderAPI. This will override WhatsApp Business API if both are enabled.', 'altalayi-ticket'); ?>
                            </p>
                        </fieldset>
                    </td>
                </tr>
                <tr class="wasenderapi-setting">
                    <th scope="row">
                        <label for="wasenderapi_api_key"><?php _e('WaSenderAPI Key', 'altalayi-ticket'); ?></label>
                    </th>
                    <td>
                        <input type="text" id="wasenderapi_api_key" name="wasenderapi_api_key" 
                               value="<?php echo esc_attr($settings['wasenderapi_api_key']); ?>" 
                               class="large-text" placeholder="your-api-key-here" />
                        <p class="description">
                            <?php _e('Your WaSenderAPI key from your dashboard. Keep this secure!', 'altalayi-ticket'); ?>
                            <br>
                            <a href="https://wasenderapi.com/dashboard" target="_blank">
                                <?php _e('Get your API key from WaSenderAPI Dashboard', 'altalayi-ticket'); ?> ↗
                            </a>
                        </p>
                    </td>
                </tr>
                <tr class="wasenderapi-setting">
                    <th scope="row">
                        <label for="wasenderapi_session_id"><?php _e('Session ID (Optional)', 'altalayi-ticket'); ?></label>
                    </th>
                    <td>
                        <input type="text" id="wasenderapi_session_id" name="wasenderapi_session_id" 
                               value="<?php echo esc_attr($settings['wasenderapi_session_id']); ?>" 
                               class="regular-text" placeholder="session-id" />
                        <p class="description">
                            <?php _e('Optional: Specific session ID if you have multiple WhatsApp sessions. Leave empty to use the default session.', 'altalayi-ticket'); ?>
                            <br>
                            <a href="https://wasenderapi.com/help/getting-started/creating-first-session" target="_blank">
                                <?php _e('Learn how to create and manage sessions', 'altalayi-ticket'); ?> ↗
                            </a>
                        </p>
                    </td>
                </tr>
                <tr class="wasenderapi-setting">
                    <th scope="row"><?php _e('WaSenderAPI Notification Types', 'altalayi-ticket'); ?></th>
                    <td>
                        <fieldset>
                            <label for="wasenderapi_notify_on_new_ticket">
                                <input type="checkbox" id="wasenderapi_notify_on_new_ticket" name="wasenderapi_notify_on_new_ticket" 
                                       value="1" <?php checked($settings['wasenderapi_notify_on_new_ticket'], 1); ?> />
                                <?php _e('Send WhatsApp notification when ticket is created', 'altalayi-ticket'); ?>
                            </label><br>
                            
                            <label for="wasenderapi_notify_on_status_change">
                                <input type="checkbox" id="wasenderapi_notify_on_status_change" name="wasenderapi_notify_on_status_change" 
                                       value="1" <?php checked($settings['wasenderapi_notify_on_status_change'], 1); ?> />
                                <?php _e('Send WhatsApp notification when ticket status changes', 'altalayi-ticket'); ?>
                            </label><br>
                            
                            <label for="wasenderapi_notify_on_employee_response">
                                <input type="checkbox" id="wasenderapi_notify_on_employee_response" name="wasenderapi_notify_on_employee_response" 
                                       value="1" <?php checked($settings['wasenderapi_notify_on_employee_response'], 1); ?> />
                                <?php _e('Send WhatsApp notification when employee responds to ticket', 'altalayi-ticket'); ?>
                            </label>
                        </fieldset>
                        <p class="description">
                            <?php _e('Select which events should trigger WhatsApp notifications to customers via WaSenderAPI.', 'altalayi-ticket'); ?>
                        </p>
                    </td>
                </tr>
                <tr class="wasenderapi-setting">
                    <td colspan="2">
                        <div class="wasenderapi-test-section">
                            <h4><?php _e('Test WaSenderAPI Configuration', 'altalayi-ticket'); ?></h4>
                            <p class="description">
                                <?php _e('After saving your settings, you can test the WaSenderAPI integration by sending a test message.', 'altalayi-ticket'); ?>
                            </p>
                            <div class="wasenderapi-test-form" style="margin-top: 15px;">
                                <input type="text" id="test_wasenderapi_phone" placeholder="+966501234567" class="regular-text" />
                                <button type="button" id="test_wasenderapi_btn" class="button" disabled>
                                    <?php _e('Send Test Message', 'altalayi-ticket'); ?>
                                </button>
                                <div id="wasenderapi_test_result" style="margin-top: 10px;"></div>
                            </div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
            
        
        <!-- File Upload Settings -->
        <div id="files" class="tab-content">
            <h2><?php _e('File Upload Settings', 'altalayi-ticket'); ?></h2>
            <table class="form-table">
                <tr>
                    <th scope="row"><?php _e('Allow File Uploads', 'altalayi-ticket'); ?></th>
                    <td>
                        <fieldset>
                            <label for="allow_file_uploads">
                                <input type="checkbox" id="allow_file_uploads" name="allow_file_uploads" 
                                       value="1" <?php checked($settings['allow_file_uploads'], 1); ?> />
                                <?php _e('Allow customers to upload files with tickets', 'altalayi-ticket'); ?>
                            </label>
                        </fieldset>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="max_file_size"><?php _e('Maximum File Size', 'altalayi-ticket'); ?></label>
                    </th>
                    <td>
                        <input type="number" id="max_file_size" name="max_file_size" 
                               value="<?php echo esc_attr($settings['max_file_size']); ?>" 
                               min="1" max="50" class="small-text" />
                        <span><?php _e('MB', 'altalayi-ticket'); ?></span>
                        <p class="description">
                            <?php printf(__('Maximum file size in megabytes. Server limit: %s', 'altalayi-ticket'), 
                                        size_format(wp_max_upload_size())); ?>
                        </p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="allowed_file_types"><?php _e('Allowed File Types', 'altalayi-ticket'); ?></label>
                    </th>
                    <td>
                        <input type="text" id="allowed_file_types" name="allowed_file_types" 
                               value="<?php echo esc_attr($settings['allowed_file_types']); ?>" 
                               class="large-text" />
                        <p class="description">
                            <?php _e('Comma-separated list of allowed file extensions (e.g., jpg,png,pdf,doc).', 'altalayi-ticket'); ?>
                        </p>
                    </td>
                </tr>
            </table>
        </div>
        
        <?php submit_button(); ?>
    </form>
</div>

<style>
.nav-tab-wrapper {
    margin-bottom: 20px;
}

.tab-content {
    display: none;
    background: #fff;
    padding: 20px;
    border: 1px solid #ccd0d4;
    border-top: none;
}

.tab-content.active {
    display: block;
}

.shortcode-info {
    background: #f9f9f9;
    padding: 15px;
    margin: 10px 0;
    border-left: 4px solid #0073aa;
}

.shortcode-info h4 {
    margin: 0 0 10px 0;
    color: #0073aa;
}

.shortcode-info code {
    background: #0073aa;
    color: white;
    padding: 5px 10px;
    border-radius: 3px;
    display: inline-block;
    margin: 5px 0;
    font-weight: bold;
}

.form-table th {
    width: 200px;
}

.notice {
    margin: 15px 0;
}

/* Role Tags Styling */
.role-tags-container {
    max-width: 600px;
}

.role-tags-wrapper {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin: 10px 0;
    padding: 15px;
    border: 1px solid #ddd;
    border-radius: 4px;
    background: #f9f9f9;
}

.role-tag {
    display: inline-flex;
    align-items: center;
    padding: 8px 12px;
    background: #fff;
    border: 2px solid #ddd;
    border-radius: 20px;
    cursor: pointer;
    transition: all 0.3s ease;
    margin: 0;
    font-size: 13px;
    user-select: none;
}

.role-tag:hover {
    border-color: #0073aa;
    box-shadow: 0 2px 4px rgba(0,115,170,0.2);
    transform: translateY(-1px);
}

.role-tag.selected {
    background: #0073aa;
    color: white;
    border-color: #005177;
}

.role-tag.selected:hover {
    background: #005177;
    border-color: #004461;
}

.role-tag input[type="checkbox"] {
    display: none;
}

.role-tag-label {
    display: flex;
    align-items: center;
    gap: 5px;
    font-weight: 500;
}

.user-count {
    font-size: 11px;
    opacity: 0.8;
    font-weight: normal;
}

.role-tag.selected .user-count {
    opacity: 0.9;
}

.role-tags-container .description {
    margin-top: 10px;
    font-style: italic;
}

/* WhatsApp Settings Styling */
.whatsapp-setting {
    background: #f8f9ff;
}

.whatsapp-test-section {
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 6px;
    padding: 20px;
    margin-top: 15px;
}

.whatsapp-test-section h4 {
    margin: 0 0 10px 0;
    color: #0073aa;
}

.whatsapp-test-form {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
}

#whatsapp_test_result {
    width: 100%;
    padding: 10px;
    border-radius: 4px;
    display: none;
}

#whatsapp_test_result.success {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
    display: block;
}

#whatsapp_test_result.error {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
    display: block;
}

.whatsapp-setting input[type="text"] {
    font-family: 'Courier New', monospace;
}

.whatsapp-setting .description a {
    color: #0073aa;
    text-decoration: none;
}

.whatsapp-setting .description a:hover {
    text-decoration: underline;
}

/* WaSenderAPI Settings Styling */
.wasenderapi-setting {
    background: #f0f8f0;
}

.wasenderapi-test-section {
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 6px;
    padding: 20px;
    margin-top: 15px;
}

.wasenderapi-test-section h4 {
    margin: 0 0 10px 0;
    color: #28a745;
}

.wasenderapi-test-form {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
}

#wasenderapi_test_result {
    width: 100%;
    padding: 10px;
    border-radius: 4px;
    display: none;
}

#wasenderapi_test_result.success {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
    display: block;
}

#wasenderapi_test_result.error {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
    display: block;
}

.wasenderapi-setting input[type="text"] {
    font-family: 'Courier New', monospace;
}

.wasenderapi-setting .description a {
    color: #28a745;
    text-decoration: none;
}

.wasenderapi-setting .description a:hover {
    text-decoration: underline;
}

/* SMTP Settings Styling */
.smtp-setting {
    background: #f8f9fa;
}

.smtp-test-section {
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 6px;
    padding: 20px;
    margin-top: 15px;
}

.smtp-test-section h4 {
    margin: 0 0 10px 0;
    color: #0073aa;
}

.smtp-test-form {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
}

#smtp_test_result {
    width: 100%;
    padding: 10px;
    border-radius: 4px;
    display: none;
}

#smtp_test_result.success {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
    display: block;
}

#smtp_test_result.error {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
    display: block;
}

.deliverability-tips {
    background: #f0f8ff;
    border: 1px solid #b3d9ff;
    border-radius: 6px;
    padding: 20px;
    margin-top: 20px;
}

.tip-section {
    margin-bottom: 20px;
}

.tip-section:last-child {
    margin-bottom: 0;
}

.tip-section h4 {
    color: #0073aa;
    margin: 0 0 10px 0;
}

.tip-section ul {
    margin-left: 20px;
}

.tip-section code {
    background: #e3f2fd;
    padding: 2px 6px;
    border-radius: 3px;
    font-family: 'Courier New', monospace;
    font-size: 12px;
}
</style>

<script>
jQuery(document).ready(function($) {
    // Tab switching
    $('.nav-tab').on('click', function(e) {
        e.preventDefault();
        
        var target = $(this).attr('href');
        
        // Update active tab
        $('.nav-tab').removeClass('nav-tab-active');
        $(this).addClass('nav-tab-active');
        
        // Show target content
        $('.tab-content').removeClass('active');
        $(target).addClass('active');
    });
    
    // Initialize first tab as active
    $('.nav-tab:first').addClass('nav-tab-active');
    $('.tab-content:first').addClass('active');
    
    // Role tag functionality
    $('.role-tag').on('click', function(e) {
        e.preventDefault();
        
        var checkbox = $(this).find('input[type="checkbox"]');
        var isChecked = checkbox.prop('checked');
        
        // Toggle checkbox
        checkbox.prop('checked', !isChecked);
        
        // Toggle visual state
        if (!isChecked) {
            $(this).addClass('selected');
        } else {
            $(this).removeClass('selected');
        }
    });
    
    // Initialize role tag states
    $('.role-tag input[type="checkbox"]:checked').each(function() {
        $(this).closest('.role-tag').addClass('selected');
    });
    
    // WaSenderAPI settings toggle
    function toggleWaSenderAPISettings() {
        var isEnabled = $('#enable_wasenderapi_notifications').is(':checked');
        $('.wasenderapi-setting').toggle(isEnabled);
        $('#test_wasenderapi_btn').prop('disabled', !isEnabled);
        
        if (isEnabled) {
            $('.wasenderapi-setting').fadeIn(300);
        } else {
            $('.wasenderapi-setting').fadeOut(300);
        }
    }
    
    // Initialize WaSenderAPI settings visibility
    toggleWaSenderAPISettings();
    
    // Handle WaSenderAPI enable/disable
    $('#enable_wasenderapi_notifications').on('change', function() {
        toggleWaSenderAPISettings();
    });
    
    // WaSenderAPI test functionality
    $('#test_wasenderapi_btn').on('click', function() {
        var $btn = $(this);
        var $result = $('#wasenderapi_test_result');
        var phone = $('#test_wasenderapi_phone').val().trim();
        
        if (!phone) {
            $result.removeClass('success').addClass('error')
                   .text('<?php _e("Please enter a phone number to test", "altalayi-ticket"); ?>')
                   .show();
            return;
        }
        
        // Basic phone validation
        if (!/^\+\d{10,15}$/.test(phone)) {
            $result.removeClass('success').addClass('error')
                   .text('<?php _e("Please enter a valid phone number with country code (e.g., +966501234567)", "altalayi-ticket"); ?>')
                   .show();
            return;
        }
        
        $btn.prop('disabled', true).text('<?php _e("Sending...", "altalayi-ticket"); ?>');
        $result.hide();
        
        // Send AJAX request to test WaSenderAPI
        $.post(ajaxurl, {
            action: 'altalayi_test_wasenderapi',
            phone: phone,
            nonce: '<?php echo wp_create_nonce("altalayi_test_wasenderapi"); ?>'
        })
        .done(function(response) {
            if (response.success) {
                $result.removeClass('error').addClass('success')
                       .text('<?php _e("Test message sent successfully!", "altalayi-ticket"); ?>');
            } else {
                $result.removeClass('success').addClass('error')
                       .text('<?php _e("Failed to send test message: ", "altalayi-ticket"); ?>' + (response.data.message || '<?php _e("Unknown error", "altalayi-ticket"); ?>'));
            }
        })
        .fail(function() {
            $result.removeClass('success').addClass('error')
                   .text('<?php _e("Network error. Please try again.", "altalayi-ticket"); ?>');
        })
        .always(function() {
            $btn.prop('disabled', false).text('<?php _e("Send Test Message", "altalayi-ticket"); ?>');
            $result.show();
        });
    });
    
    // Real-time validation for WaSenderAPI key
    $('#wasenderapi_api_key').on('input', function() {
        var apiKey = $(this).val();
        var $field = $(this);
        
        if (apiKey && apiKey.length < 10) {
            $field.css('border-color', '#ff6b6b');
        } else {
            $field.css('border-color', '');
        }
    });
    
    // WhatsApp settings toggle
    function toggleWhatsAppSettings() {
        var isEnabled = $('#enable_whatsapp_notifications').is(':checked');
        $('.whatsapp-setting').toggle(isEnabled);
        $('#test_whatsapp_btn').prop('disabled', !isEnabled);
        
        if (isEnabled) {
            $('.whatsapp-setting').fadeIn(300);
        } else {
            $('.whatsapp-setting').fadeOut(300);
        }
    }
    
    // Initialize WhatsApp settings visibility
    toggleWhatsAppSettings();
    
    // Handle WhatsApp enable/disable
    $('#enable_whatsapp_notifications').on('change', function() {
        toggleWhatsAppSettings();
    });
    
    // WhatsApp test functionality
    $('#test_whatsapp_btn').on('click', function() {
        var $btn = $(this);
        var $result = $('#whatsapp_test_result');
        var phone = $('#test_whatsapp_phone').val().trim();
        
        if (!phone) {
            $result.removeClass('success').addClass('error')
                   .text('<?php _e("Please enter a phone number to test", "altalayi-ticket"); ?>')
                   .show();
            return;
        }
        
        // Basic phone validation
        if (!/^\+\d{10,15}$/.test(phone)) {
            $result.removeClass('success').addClass('error')
                   .text('<?php _e("Please enter a valid phone number with country code (e.g., +966501234567)", "altalayi-ticket"); ?>')
                   .show();
            return;
        }
        
        $btn.prop('disabled', true).text('<?php _e("Sending...", "altalayi-ticket"); ?>');
        $result.hide();
        
        // Send AJAX request to test WhatsApp
        $.post(ajaxurl, {
            action: 'altalayi_test_whatsapp',
            phone: phone,
            nonce: '<?php echo wp_create_nonce("altalayi_test_whatsapp"); ?>'
        })
        .done(function(response) {
            if (response.success) {
                $result.removeClass('error').addClass('success')
                       .text('<?php _e("Test message sent successfully!", "altalayi-ticket"); ?>');
            } else {
                $result.removeClass('success').addClass('error')
                       .text('<?php _e("Failed to send test message: ", "altalayi-ticket"); ?>' + (response.data.message || '<?php _e("Unknown error", "altalayi-ticket"); ?>'));
            }
        })
        .fail(function() {
            $result.removeClass('success').addClass('error')
                   .text('<?php _e("Network error. Please try again.", "altalayi-ticket"); ?>');
        })
        .always(function() {
            $btn.prop('disabled', false).text('<?php _e("Send Test Message", "altalayi-ticket"); ?>');
            $result.show();
        });
    });
    
    // Real-time validation for access token
    $('#whatsapp_access_token').on('input', function() {
        var token = $(this).val();
        var $field = $(this);
        
        if (token && !token.startsWith('EAA')) {
            $field.css('border-color', '#ff6b6b');
        } else {
            $field.css('border-color', '');
        }
    });
    
    // Real-time validation for phone number ID
    $('#whatsapp_phone_number_id').on('input', function() {
        var numberId = $(this).val();
        var $field = $(this);
        
        if (numberId && !/^\d{10,20}$/.test(numberId)) {
            $field.css('border-color', '#ff6b6b');
        } else {
            $field.css('border-color', '');
        }
    });
    
    // Real-time validation for business phone
    $('#whatsapp_business_phone').on('input', function() {
        var phone = $(this).val();
        var $field = $(this);
        
        if (phone && !/^\+\d{10,15}$/.test(phone)) {
            $field.css('border-color', '#ff6b6b');
        } else {
            $field.css('border-color', '');
        }
    });
    
    // SMTP settings toggle
    function toggleSMTPSettings() {
        var isEnabled = $('#enable_smtp').is(':checked');
        $('.smtp-setting').toggle(isEnabled);
        $('#test_smtp_btn').prop('disabled', !isEnabled);
        
        if (isEnabled) {
            $('.smtp-setting').fadeIn(300);
        } else {
            $('.smtp-setting').fadeOut(300);
        }
    }
    
    // Initialize SMTP settings visibility
    toggleSMTPSettings();
    
    // Handle SMTP enable/disable
    $('#enable_smtp').on('change', function() {
        toggleSMTPSettings();
    });
    
    // SMTP test functionality
    $('#test_smtp_btn').on('click', function() {
        var $btn = $(this);
        var $result = $('#smtp_test_result');
        var email = $('#test_smtp_email').val().trim();
        
        if (!email) {
            $result.removeClass('success').addClass('error')
                   .text('<?php _e("Please enter an email address to test", "altalayi-ticket"); ?>')
                   .show();
            return;
        }
        
        // Basic email validation
        var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            $result.removeClass('success').addClass('error')
                   .text('<?php _e("Please enter a valid email address", "altalayi-ticket"); ?>')
                   .show();
            return;
        }
        
        $btn.prop('disabled', true).text('<?php _e("Sending...", "altalayi-ticket"); ?>');
        $result.hide();
        
        // Send AJAX request to test SMTP
        $.post(ajaxurl, {
            action: 'altalayi_test_smtp',
            email: email,
            nonce: '<?php echo wp_create_nonce("altalayi_test_smtp"); ?>'
        })
        .done(function(response) {
            if (response.success) {
                $result.removeClass('error').addClass('success')
                       .text('<?php _e("Test email sent successfully! Check your inbox.", "altalayi-ticket"); ?>');
            } else {
                $result.removeClass('success').addClass('error')
                       .text('<?php _e("Failed to send test email: ", "altalayi-ticket"); ?>' + (response.data.message || '<?php _e("Unknown error", "altalayi-ticket"); ?>'));
            }
        })
        .fail(function() {
            $result.removeClass('success').addClass('error')
                   .text('<?php _e("Network error. Please try again.", "altalayi-ticket"); ?>');
        })
        .always(function() {
            $btn.prop('disabled', false).text('<?php _e("Send Test Email", "altalayi-ticket"); ?>');
            $result.show();
        });
    });
});
</script>
