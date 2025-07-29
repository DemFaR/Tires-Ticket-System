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
        'show_delete_button' => isset($_POST['show_delete_button']) ? 1 : 0
    );
    
    update_option('altalayi_ticket_settings', $settings);
    echo '<div class="notice notice-success"><p>' . __('Settings saved successfully!', 'altalayi-ticket') . '</p></div>';
}

// Get current settings
$settings = get_option('altalayi_ticket_settings', array());

// Default values
$defaults = array(
    'company_name' => 'Altalayi Company',
    'company_email' => 'support@altalayi.com',
    'company_phone' => '+966-XXX-XXXX',
    'company_address' => '',
    'company_website' => home_url(),
    'frontend_create_page' => '',
    'frontend_access_page' => '',
    'frontend_view_page' => '',
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
    'show_delete_button' => 0
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
});
</script>
