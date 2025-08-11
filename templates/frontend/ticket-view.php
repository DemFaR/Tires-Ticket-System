<?php
/**
 * Frontend Ticket View Template
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="altalayi-ticket-view-container">
    <!--< ?php include plugin_dir_path(__FILE__) . 'language-switcher.php'; ?>-->

    <div class="ticket-view-wrapper">
        <!-- Ticket Header -->
        <div class="ticket-header">
            <div class="ticket-title">
                <h1><?php printf(__('Ticket #%s', 'altalayi-ticket'), esc_html($ticket->ticket_number)); ?></h1>
                <div class="ticket-meta">
                    <span class="ticket-date">
                        <i class="dashicons dashicons-calendar-alt"></i>
                        <?php printf(__('Created: %s', 'altalayi-ticket'), altalayi_format_date($ticket->created_at)); ?>
                    </span>
                    <span class="ticket-status">
                        <?php echo altalayi_get_status_badge($ticket->status_name, $ticket->status_color); ?>
                    </span>
                </div>
            </div>
            
            <div class="ticket-actions">
                <a href="<?php echo esc_url(altalayi_get_access_ticket_url()); ?>" class="btn btn-secondary">
                    <i class="dashicons dashicons-arrow-left-alt"></i>
                    <?php _e('Back to Login', 'altalayi-ticket'); ?>
                </a>
                <button onclick="window.print()" class="btn btn-outline">
                    <i class="dashicons dashicons-printer"></i>
                    <?php _e('Print', 'altalayi-ticket'); ?>
                </button>
            </div>
        </div>
        
        <!-- Two Column Layout -->
        <div class="ticket-content-grid">
            <!-- Left Column -->
            <div class="left-column">
                <!-- Customer Information -->
                <div class="info-section">
                    <h2><?php _e('Customer Information', 'altalayi-ticket'); ?></h2>
                    <div class="info-list">
                        <div class="info-item">
                            <label><?php _e('Name:', 'altalayi-ticket'); ?></label>
                            <span><?php echo esc_html($ticket->customer_name); ?></span>
                        </div>
                        <div class="info-item">
                            <label><?php _e('Phone:', 'altalayi-ticket'); ?></label>
                            <span><?php echo esc_html($ticket->customer_phone); ?></span>
                        </div>
                        <div class="info-item">
                            <label><?php _e('Email:', 'altalayi-ticket'); ?></label>
                            <span><?php echo esc_html($ticket->customer_email); ?></span>
                        </div>
                        <?php if ($ticket->customer_city): ?>
                        <div class="info-item">
                            <label><?php _e('City:', 'altalayi-ticket'); ?></label>
                            <span><?php echo esc_html($ticket->customer_city); ?></span>
                        </div>
                        <?php endif; ?>
                        <?php if ($ticket->motocare_center_visited): ?>
                        <div class="info-item">
                            <label><?php _e('Motocare Center/Distributor:', 'altalayi-ticket'); ?></label>
                            <span><?php echo esc_html($ticket->motocare_center_visited); ?></span>
                        </div>
                        <?php endif; ?>
                        <?php if ($ticket->assigned_user_name): ?>
                        <div class="info-item">
                            <label><?php _e('Assigned To:', 'altalayi-ticket'); ?></label>
                            <span><?php echo esc_html($ticket->assigned_user_name); ?></span>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Tire Information -->
                <?php if ($ticket->tire_brand || $ticket->tire_model || $ticket->number_of_tires || $ticket->tire_position || $ticket->air_pressure || $ticket->tread_depth || $ticket->purchase_date || $ticket->purchase_location): ?>
                <div class="info-section">
                    <h2><?php _e('Tire Information', 'altalayi-ticket'); ?></h2>
                    <div class="info-list">
                        <?php if ($ticket->tire_brand): ?>
                        <div class="info-item">
                            <label><?php _e('Brand:', 'altalayi-ticket'); ?></label>
                            <span><?php echo esc_html($ticket->tire_brand); ?></span>
                        </div>
                        <?php endif; ?>
                        
                        <?php if ($ticket->tire_model): ?>
                        <div class="info-item">
                            <label><?php _e('Model:', 'altalayi-ticket'); ?></label>
                            <span><?php echo esc_html($ticket->tire_model); ?></span>
                        </div>
                        <?php endif; ?>
                        
                        <!-- Hide tire size field -->
                        <?php /*
                        <?php if ($ticket->tire_size): ?>
                        <div class="info-item">
                            <label><?php _e('Size:', 'altalayi-ticket'); ?></label>
                            <span><?php echo esc_html($ticket->tire_size); ?></span>
                        </div>
                        <?php endif; ?>
                        */ ?>
                        
                        <?php if ($ticket->number_of_tires): ?>
                        <div class="info-item">
                            <label><?php _e('Number of Tires:', 'altalayi-ticket'); ?></label>
                            <span><?php echo esc_html($ticket->number_of_tires); ?></span>
                        </div>
                        <?php endif; ?>
                        
                        <?php if ($ticket->tire_position): ?>
                        <div class="info-item">
                            <label><?php _e('Tire Position:', 'altalayi-ticket'); ?></label>
                            <span><?php echo esc_html($ticket->tire_position); ?></span>
                        </div>
                        <?php endif; ?>
                        
                        <?php if ($ticket->air_pressure): ?>
                        <div class="info-item">
                            <label><?php _e('Air Pressure:', 'altalayi-ticket'); ?></label>
                            <span><?php echo esc_html($ticket->air_pressure); ?></span>
                        </div>
                        <?php endif; ?>
                        
                        <?php if ($ticket->tread_depth): ?>
                        <div class="info-item">
                            <label><?php _e('Tread Depth:', 'altalayi-ticket'); ?></label>
                            <span><?php echo esc_html($ticket->tread_depth); ?></span>
                        </div>
                        <?php endif; ?>
                        
                        <?php if ($ticket->purchase_date && $ticket->purchase_date !== '0000-00-00'): ?>
                        <div class="info-item">
                            <label><?php _e('Purchase Date:', 'altalayi-ticket'); ?></label>
                            <span><?php echo altalayi_format_date($ticket->purchase_date, 'F j, Y'); ?></span>
                        </div>
                        <?php endif; ?>
                        
                        <?php if ($ticket->purchase_location): ?>
                        <div class="info-item">
                            <label><?php _e('Purchase Location:', 'altalayi-ticket'); ?></label>
                            <span><?php echo esc_html(altalayi_get_purchase_location_label($ticket->purchase_location)); ?></span>
                        </div>
                        <?php endif; ?>
                        
                        <!-- Hide current mileage -->
                        <?php /*
                        <?php if ($ticket->mileage): ?>
                        <div class="info-item">
                            <label><?php _e('Current Mileage:', 'altalayi-ticket'); ?></label>
                            <span><?php echo number_format($ticket->mileage); ?> <?php _e('km', 'altalayi-ticket'); ?></span>
                        </div>
                        <?php endif; ?>
                        */ ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            
            <!-- Right Column -->
            <div class="right-column">
                <!-- Complaint Details -->
                <div class="info-section">
                    <h2><?php _e('Complaint Details', 'altalayi-ticket'); ?></h2>
                    <div class="complaint-text">
                        <?php echo nl2br(esc_html($ticket->complaint_text)); ?>
                    </div>
                </div>
                
                <!-- Attachments -->
                <?php if (!empty($attachments)): ?>
                <div class="info-section">
                    <h2><?php _e('Attachments', 'altalayi-ticket'); ?></h2>
                    <div class="attachments-grid">
                        <?php foreach ($attachments as $attachment): ?>
                            <div class="attachment-item">
                                <?php
                                $file_extension = pathinfo($attachment->file_name, PATHINFO_EXTENSION);
                                $is_image = in_array(strtolower($file_extension), array('jpg', 'jpeg', 'png', 'gif', 'webp'));
                                // file_path is already a full URL from wp_handle_upload
                                $file_url = $attachment->file_path;
                                ?>
                                
                                <?php if ($is_image): ?>
                                    <div class="attachment-image">
                                        <img src="<?php echo esc_url($file_url); ?>" 
                                             alt="<?php echo esc_attr($attachment->file_name); ?>"
                                             onclick="openImageModal(this.src)">
                                    </div>
                                <?php else: ?>
                                    <div class="attachment-file">
                                        <?php echo altalayi_get_attachment_icon($attachment->file_type); ?>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="attachment-info">
                                    <div class="attachment-name"><?php echo esc_html($attachment->file_name); ?></div>
                                    <div class="attachment-meta">
                                        <span class="attachment-type"><?php echo altalayi_get_attachment_type_label($attachment->attachment_type); ?></span>
                                        <span class="attachment-size"><?php echo altalayi_format_file_size($attachment->file_size); ?></span>
                                    </div>
                                </div>
                                <div class="attachment-actions">
                                    <a href="<?php echo esc_url($file_url); ?>" 
                                       target="_blank" class="btn btn-small">
                                        <?php _e('View', 'altalayi-ticket'); ?>
                                    </a>
                                    <a href="<?php echo esc_url(altalayi_get_attachment_download_url($attachment->id)); ?>" 
                                       class="btn btn-small">
                                        <?php _e('Download', 'altalayi-ticket'); ?>
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- Customer Response Section -->
                <?php
                // Check if current ticket status is "More Information Required"
                $needs_customer_response = (strtolower($ticket->status_name) === 'more information required');
                ?>
                
                <?php if ($needs_customer_response): ?>
                <div class="info-section customer-response-section">
                    <h2><?php _e('Additional Information Required', 'altalayi-ticket'); ?></h2>
                    <p class="response-instruction">
                        <?php _e('Our team needs additional information to process your ticket. Please provide the requested details below:', 'altalayi-ticket'); ?>
                    </p>
                    
                    <?php if (isset($_GET['message']) && $_GET['message'] === 'success'): ?>
                    <div class="response-message success">
                        <strong><?php _e('Success!', 'altalayi-ticket'); ?></strong> <?php _e('Your response has been submitted successfully.', 'altalayi-ticket'); ?>
                    </div>
                    <?php endif; ?>
                    
                    <form id="customer-response-form" class="response-form" method="post" enctype="multipart/form-data" action="<?php echo admin_url('admin-ajax.php'); ?>">
                        <?php wp_nonce_field('altalayi_ticket_nonce', 'nonce'); ?>
                        <input type="hidden" name="ticket_id" value="<?php echo esc_attr($ticket->id); ?>">
                        <input type="hidden" name="action" value="altalayi_add_customer_response">
                        
                        <div class="form-group">
                            <label for="customer_response"><?php _e('Your Response:', 'altalayi-ticket'); ?></label>
                            <textarea id="customer_response" name="response" rows="5" required
                                      placeholder="<?php _e('Please provide the additional information requested by our team...', 'altalayi-ticket'); ?>"></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="additional_files"><?php _e('Additional Files (Optional):', 'altalayi-ticket'); ?></label>
                            <div class="file-upload-area">
                                <input type="file" id="additional_files" name="additional_files[]" multiple accept="image/*,.pdf">
                                <div class="upload-instructions">
                                    <i class="dashicons dashicons-paperclip"></i>
                                    <p><?php _e('Upload additional documents or images if needed', 'altalayi-ticket'); ?></p>
                                    <small><?php _e('Maximum 5MB per file. Images and PDF files accepted.', 'altalayi-ticket'); ?></small>
                                </div>
                            </div>
                            <div id="additional-files-preview" class="file-preview"></div>
                        </div>
                        
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary submit-btn">
                                <span class="btn-text">
                                    <i class="dashicons dashicons-yes"></i>
                                    <?php _e('Submit Response', 'altalayi-ticket'); ?>
                                </span>
                                <span class="btn-loading" style="display: none;">
                                    <i class="dashicons dashicons-update rotate"></i>
                                    <?php _e('Submitting...', 'altalayi-ticket'); ?>
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
                <?php endif; ?>
                
                <!-- Employee Responses -->
                <?php
                // Filter notes from employees (oldest first)
                $employee_notes = array_filter($updates, function($update) {
                    return $update->update_type === 'note' && !empty($update->notes);
                });
                // Sort oldest first
                usort($employee_notes, function($a, $b) {
                    return strtotime($a->update_date) - strtotime($b->update_date);
                });
                ?>
                
                <?php if (!empty($employee_notes)): ?>
                <div class="info-section">
                    <h2><?php _e('Employee Responses', 'altalayi-ticket'); ?></h2>
                    
                    <div class="employee-responses">
                        <?php foreach ($employee_notes as $note): ?>
                            <div class="employee-response-item">
                                <div class="response-header">
                                    <span class="response-author">
                                        <i class="dashicons dashicons-admin-users"></i>
                                        <?php echo esc_html($note->updated_by_name ?: __('Support Team', 'altalayi-ticket')); ?>
                                    </span>
                                    <span class="response-date"><?php echo altalayi_format_date($note->update_date); ?></span>
                                </div>
                                <div class="response-content">
                                    <?php echo nl2br(esc_html($note->notes)); ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- Ticket Updates -->
                <div class="info-section">
                    <h2><?php _e('Ticket Updates', 'altalayi-ticket'); ?></h2>
                    
                    <?php if (!empty($updates)): ?>
                        <?php
                        // Filter out notes and sort newest first for ticket updates
                        $ticket_updates = array_filter($updates, function($update) {
                            return $update->update_type !== 'note' || empty($update->notes);
                        });
                        // Sort newest first
                        usort($ticket_updates, function($a, $b) {
                            return strtotime($b->update_date) - strtotime($a->update_date);
                        });
                        ?>
                        
                        <div class="updates-timeline">
                            <?php foreach ($ticket_updates as $update): ?>
                                <div class="update-item <?php echo $update->update_type; ?>">
                                    <div class="update-marker"></div>
                                    <div class="update-content">
                                        <div class="update-header">
                                            <span class="update-type">
                                                <?php
                                                switch ($update->update_type) {
                                                    case 'status_change':
                                                        echo '<i class="dashicons dashicons-update"></i>' . __('Status Update', 'altalayi-ticket');
                                                        break;
                                                    case 'assignment':
                                                        echo '<i class="dashicons dashicons-admin-users"></i>' . __('Assignment', 'altalayi-ticket');
                                                        break;
                                                    case 'note':
                                                        echo '<i class="dashicons dashicons-edit"></i>' . __('Note', 'altalayi-ticket');
                                                        break;
                                                    case 'customer_response':
                                                        echo '<i class="dashicons dashicons-businessman"></i>' . __('Customer Response', 'altalayi-ticket');
                                                        break;
                                                }
                                                ?>
                                            </span>
                                            <span class="update-date"><?php echo altalayi_format_date($update->update_date); ?></span>
                                        </div>
                                        
                                        <?php if ($update->update_type === 'status_change'): ?>
                                            <div class="update-text">
                                                <?php printf(__('Status changed from "%s" to "%s"', 'altalayi-ticket'), 
                                                           esc_html($update->old_value), esc_html($update->new_value)); ?>
                                            </div>
                                        <?php elseif ($update->update_type === 'assignment'): ?>
                                            <div class="update-text">
                                                <?php printf(__('Ticket assigned to %s', 'altalayi-ticket'), esc_html($update->new_value)); ?>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <?php if ($update->notes): ?>
                                            <div class="update-notes">
                                                <?php echo nl2br(esc_html($update->notes)); ?>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <?php if ($update->updated_by_name): ?>
                                            <div class="update-author">
                                                <?php printf(__('by %s', 'altalayi-ticket'), esc_html($update->updated_by_name)); ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="no-updates">
                            <p><?php _e('No updates available yet.', 'altalayi-ticket'); ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div id="response-messages"></div>
    </div>
</div>

<style>
/* Modern Professional Ticket View Styles */
.altalayi-ticket-view-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
    background-color: #f8fafc;
    min-height: 100vh;
}

.ticket-view-wrapper {
    background: #ffffff;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    border: 1px solid #e2e8f0;
}

/* Header Styles */
.ticket-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 32px 40px;
    position: relative;
    overflow: hidden;
}

.ticket-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(10px);
}

.ticket-header > * {
    position: relative;
    z-index: 1;
}

.ticket-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    flex-wrap: wrap;
    gap: 20px;
}

.ticket-title h1 {
    margin: 0 0 12px 0;
    font-size: 28px;
    font-weight: 700;
    color: white;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.ticket-meta {
    display: flex;
    gap: 20px;
    align-items: center;
    flex-wrap: wrap;
}

.ticket-date {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    color: rgba(255, 255, 255, 0.9);
}

.ticket-status .status-badge {
    padding: 6px 16px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    background: rgba(255, 255, 255, 0.2);
    color: white;
    border: 1px solid rgba(255, 255, 255, 0.3);
}

.ticket-actions {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    border: none;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    text-decoration: none;
    transition: all 0.2s ease;
    white-space: nowrap;
}

.btn-secondary {
    background: rgba(255, 255, 255, 0.15);
    color: white;
    border: 1px solid rgba(255, 255, 255, 0.3);
}

.btn-outline {
    background: transparent;
    color: white;
    border: 1px solid rgba(255, 255, 255, 0.5);
}

.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

/* Two Column Layout */
.ticket-content-grid {
    display: grid;
    grid-template-columns: 400px 1fr;
    min-height: 600px;
}

.left-column {
    background: #f8fafc;
    border-right: 1px solid #e2e8f0;
}

.right-column {
    background: white;
}

/* Info Section Styles */
.info-section {
    padding: 24px;
    border-bottom: 1px solid #e2e8f0;
}

.info-section:last-child {
    border-bottom: none;
}

.info-section h2 {
    margin: 0 0 20px 0;
    font-size: 18px;
    font-weight: 600;
    color: #1a202c;
    display: flex;
    align-items: center;
    gap: 8px;
}

.info-section h2::before {
    content: '';
    width: 4px;
    height: 18px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-radius: 2px;
}

/* Info List Styles */
.info-list {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.info-item {
    display: flex;
    flex-direction: column;
    gap: 4px;
    padding: 12px 0;
    border-bottom: 1px solid #f0f0f0;
}

.info-item:last-child {
    border-bottom: none;
}

.info-item label {
    font-size: 12px;
    font-weight: 600;
    color: #718096;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.info-item span {
    font-size: 15px;
    color: #2d3748;
    font-weight: 500;
}

/* Complaint Text */
.complaint-text {
    background: white;
    padding: 20px;
    border-radius: 8px;
    border-left: 4px solid #667eea;
    color: #2d3748;
    line-height: 1.6;
    font-size: 15px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

/* Attachments Grid */
.attachments-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 16px;
}

.attachment-item {
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    overflow: hidden;
    transition: all 0.2s ease;
}

.attachment-item:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    transform: translateY(-2px);
}

.attachment-image {
    height: 140px;
    overflow: hidden;
}

.attachment-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    cursor: pointer;
    transition: transform 0.2s ease;
}

.attachment-image img:hover {
    transform: scale(1.05);
}

.attachment-file {
    height: 140px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f8fafc;
    font-size: 48px;
    color: #667eea;
}

.attachment-info {
    padding: 16px;
}

.attachment-name {
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 8px;
    font-size: 14px;
    word-break: break-word;
}

.attachment-meta {
    font-size: 12px;
    color: #718096;
    margin-bottom: 12px;
}

.attachment-meta span {
    display: block;
    margin-bottom: 2px;
}

.attachment-actions {
    display: flex;
    gap: 8px;
}

.btn-small {
    padding: 6px 12px;
    font-size: 12px;
    border-radius: 6px;
}

.btn-primary {
    background: #667eea;
    color: white;
}

.btn-primary:hover {
    background: #5a67d8;
}

/* Updates Timeline */
.updates-timeline {
    position: relative;
    padding-left: 32px;
}

.updates-timeline::before {
    content: '';
    position: absolute;
    left: 16px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e2e8f0;
}

.update-item {
    position: relative;
    margin-bottom: 24px;
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 20px;
}

.update-marker {
    position: absolute;
    left: -40px;
    top: 24px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: #667eea;
    border: 3px solid white;
    box-shadow: 0 0 0 1px #e2e8f0;
}

.update-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
    padding-bottom: 8px;
    border-bottom: 1px solid #f7fafc;
}

.update-type {
    font-weight: 600;
    color: #2d3748;
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
}

.update-date {
    font-size: 12px;
    color: #718096;
}

.update-text,
.update-notes {
    color: #4a5568;
    line-height: 1.6;
    margin-bottom: 8px;
}

.update-notes {
    background: #f8fafc;
    padding: 16px;
    border-radius: 6px;
    border-left: 3px solid #667eea;
}

.update-author {
    font-size: 12px;
    color: #718096;
    font-style: italic;
}

/* Employee Responses */
.employee-responses {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.employee-response-item {
    background: white;
    border: 1px solid #e2e8f0;
    border-left: 4px solid #667eea;
    border-radius: 8px;
    padding: 20px;
}

.employee-response-item .response-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
    padding-bottom: 8px;
    border-bottom: 1px solid #f7fafc;
}

.employee-response-item .response-author {
    font-weight: 600;
    color: #2d3748;
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
}

.employee-response-item .response-date {
    font-size: 12px;
    color: #718096;
}

.employee-response-item .response-content {
    color: #4a5568;
    line-height: 1.6;
    font-size: 15px;
}

/* Customer Response Section */
.customer-response-section {
    background: linear-gradient(135deg, #f0f8ff, #e6f3ff);
    border-top: 3px solid #667eea;
}

.response-instruction {
    background: rgba(102, 126, 234, 0.1);
    padding: 16px;
    border-radius: 8px;
    color: #2d3748;
    margin-bottom: 20px;
    border-left: 4px solid #667eea;
}

.response-form .form-group {
    margin-bottom: 20px;
}

.response-form label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #2d3748;
    font-size: 14px;
}

.response-form textarea {
    width: 100%;
    padding: 12px 16px;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    font-size: 14px;
    resize: vertical;
    font-family: inherit;
    transition: border-color 0.2s ease;
}

.response-form textarea:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

/* File Upload */
.file-upload-area {
    border: 2px dashed #cbd5e0;
    border-radius: 8px;
    padding: 24px;
    text-align: center;
    cursor: pointer;
    position: relative;
    transition: all 0.2s ease;
}

.file-upload-area:hover,
.file-upload-area.dragover {
    border-color: #667eea;
    background-color: rgba(102, 126, 234, 0.05);
}

.file-upload-area input[type="file"] {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    opacity: 0;
    cursor: pointer;
}

.upload-instructions i {
    font-size: 32px;
    color: #718096;
    margin-bottom: 12px;
    display: block;
}

.upload-instructions p {
    margin: 0 0 8px 0;
    color: #2d3748;
    font-weight: 500;
    font-size: 16px;
}

.upload-instructions small {
    color: #718096;
    font-size: 14px;
}

/* Form Actions */
.form-actions {
    text-align: center;
    margin-top: 24px;
}

.submit-btn {
    padding: 12px 32px;
    font-size: 16px;
    font-weight: 600;
}

/* Messages */
.response-message {
    padding: 16px 20px;
    border-radius: 8px;
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.response-message.success {
    background: #f0fff4;
    border: 1px solid #9ae6b4;
    color: #276749;
}

.response-message.error {
    background: #fed7d7;
    border: 1px solid #feb2b2;
    color: #c53030;
}

/* No Updates */
.no-updates {
    text-align: center;
    padding: 40px 20px;
    color: #718096;
}

.no-updates p {
    font-size: 16px;
    margin: 0;
}

/* Animations */
.rotate {
    animation: rotate 1s linear infinite;
}

@keyframes rotate {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

/* File Preview */
.file-preview {
    margin-top: 16px;
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
    gap: 12px;
}

.file-preview-item {
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 12px;
    text-align: center;
    position: relative;
}

.file-preview-item img {
    max-width: 100%;
    max-height: 80px;
    border-radius: 4px;
    margin-bottom: 8px;
}

.file-preview-item .file-name {
    font-size: 12px;
    margin-bottom: 4px;
    word-break: break-all;
    color: #2d3748;
    font-weight: 500;
}

.file-preview-item .file-size {
    font-size: 11px;
    color: #718096;
}

.file-remove-btn {
    position: absolute;
    top: -8px;
    right: -8px;
    background: #e53e3e;
    color: white;
    border: none;
    border-radius: 50%;
    width: 20px;
    height: 20px;
    cursor: pointer;
    font-size: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.file-remove-btn:hover {
    background: #c53030;
}

/* Image Modal */
.image-modal {
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.9);
    backdrop-filter: blur(5px);
}

.modal-content {
    position: relative;
    margin: auto;
    padding: 20px;
    width: 90%;
    max-width: 800px;
    top: 50%;
    transform: translateY(-50%);
}

.modal-close {
    position: absolute;
    top: -10px;
    right: 10px;
    color: #f1f1f1;
    font-size: 40px;
    font-weight: bold;
    cursor: pointer;
    z-index: 1001;
    background: rgba(0, 0, 0, 0.5);
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background-color 0.2s ease;
}

.modal-close:hover {
    background: rgba(0, 0, 0, 0.7);
}

.modal-content img {
    width: 100%;
    height: auto;
    border-radius: 8px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
}

/* Print Styles */
@media print {
    .ticket-actions,
    .customer-response-section {
        display: none !important;
    }
    
    .ticket-header {
        background: #667eea !important;
        -webkit-print-color-adjust: exact;
        color-adjust: exact;
    }
    
    .altalayi-ticket-view-container {
        background: white;
    }
    
    .ticket-view-wrapper {
        box-shadow: none;
    }
}

/* Responsive Design */
@media (max-width: 768px) {
    .altalayi-ticket-view-container {
        padding: 16px;
    }
    
    .ticket-header {
        padding: 24px 20px;
        flex-direction: column;
        align-items: flex-start;
        gap: 16px;
    }
    
    .ticket-title h1 {
        font-size: 24px;
    }
    
    .ticket-meta {
        flex-direction: column;
        align-items: flex-start;
        gap: 12px;
    }
    
    .ticket-actions {
        width: 100%;
        justify-content: stretch;
    }
    
    .ticket-actions .btn {
        flex: 1;
        justify-content: center;
    }
    
    /* Stack columns on mobile */
    .ticket-content-grid {
        grid-template-columns: 1fr;
    }
    
    .left-column {
        border-right: none;
        border-bottom: 1px solid #e2e8f0;
    }
    
    .info-section {
        padding: 20px 16px;
    }
    
    .info-section h2 {
        font-size: 16px;
    }
    
    .attachments-grid {
        grid-template-columns: 1fr;
    }
    
    .updates-timeline {
        padding-left: 24px;
    }
    
    .update-marker {
        left: -32px;
    }
    
    .file-preview {
        grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
    }
}

@media (max-width: 480px) {
    .ticket-header {
        padding: 20px 16px;
    }
    
    .ticket-title h1 {
        font-size: 20px;
    }
    
    .info-section {
        padding: 16px 12px;
    }
}
</style>

<script>
// Image modal functions
function openImageModal(src) {
    document.getElementById('modalImage').src = src;
    document.getElementById('imageModal').style.display = 'block';
}

function closeImageModal() {
    document.getElementById('imageModal').style.display = 'none';
}

// Close modal when clicking outside
window.onclick = function(event) {
    var modal = document.getElementById('imageModal');
    if (modal && event.target == modal) {
        modal.style.display = 'none';
    }
}

// File preview functionality for additional files - Vanilla JavaScript
document.addEventListener('DOMContentLoaded', function() {
    var fileInput = document.getElementById('additional_files');
    var previewContainer = document.getElementById('additional-files-preview');
    
    if (!fileInput || !previewContainer) {
        return;
    }
    
    // File input change handler
    /*fileInput.addEventListener('change', function() {
        if (this.files && this.files.length > 0) {
            handleFileSelection(this.files, previewContainer);
        } else {
            previewContainer.innerHTML = '';
        }
    });*/
    
    // Drag and drop handlers
    var uploadArea = document.querySelector('.file-upload-area');
    if (uploadArea) {
        uploadArea.addEventListener('dragover', function(e) {
            e.preventDefault();
            e.stopPropagation();
            this.classList.add('dragover');
        });
        
        uploadArea.addEventListener('dragleave', function(e) {
            e.preventDefault();
            e.stopPropagation();
            this.classList.remove('dragover');
        });
        
        uploadArea.addEventListener('drop', function(e) {
            e.preventDefault();
            e.stopPropagation();
            this.classList.remove('dragover');
            
            var files = e.dataTransfer.files;
            if (files.length > 0) {
                fileInput.files = files;
                handleFileSelection(files, previewContainer);
            }
        });
    }
    
    // File remove handler using event delegation
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('file-remove-btn')) {
            e.preventDefault();
            e.stopPropagation();
            
            var item = e.target.closest('.file-preview-item');
            item.remove();
            
            // Clear the input if no files remain
            if (previewContainer.querySelectorAll('.file-preview-item').length === 0) {
                fileInput.value = '';
            }
        }
    });
    
    /**
     * Handle file selection and preview
     */
    function handleFileSelection(files, container) {
        // Clear existing previews
        container.innerHTML = '';
        container.style.display = 'grid';
        
        for (var i = 0; i < files.length; i++) {
            var file = files[i];
            
            // Validate file
            if (!validateFile(file)) {
                continue;
            }
            
            // Create preview item
            var previewItem = createFilePreviewItem(file);
            container.appendChild(previewItem);
            
            // Generate preview for images
            if (file.type.startsWith('image/')) {
                generateImagePreview(file, previewItem);
            } else {
                // Add a generic file icon for non-images
                var fileIcon = document.createElement('div');
                fileIcon.className = 'file-icon';
                fileIcon.textContent = 'ðŸ“„';
                fileIcon.style.fontSize = '24px';
                fileIcon.style.marginBottom = '8px';
                previewItem.insertBefore(fileIcon, previewItem.firstChild);
            }
        }
    }
    
    /**
     * Validate file
     */
    function validateFile(file) {
        var maxSize = 5 * 1024 * 1024; // 5MB
        var allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp', 'application/pdf'];
        
        if (file.size > maxSize) {
            showMessage('File "' + file.name + '" is too large. Maximum size is 5MB.', 'error');
            return false;
        }
        
        if (!allowedTypes.includes(file.type)) {
            showMessage('File "' + file.name + '" is not a supported format. Allowed: ' + allowedTypes.join(', '), 'error');
            return false;
        }
        
        return true;
    }
    
    /**
     * Create file preview item
     */
    function createFilePreviewItem(file) {
        var item = document.createElement('div');
        item.className = 'file-preview-item';
        item.style.cssText = 'background: #f8f9fa; border: 1px solid #e1e5e9; border-radius: 6px; padding: 10px; text-align: center; position: relative; margin: 5px;';
        
        var removeBtn = document.createElement('button');
        removeBtn.type = 'button';
        removeBtn.className = 'file-remove-btn';
        removeBtn.innerHTML = '&times;';
        removeBtn.style.cssText = 'position: absolute; top: -5px; right: -5px; background: #e74c3c; color: white; border: none; border-radius: 50%; width: 20px; height: 20px; cursor: pointer; font-size: 12px; display: flex; align-items: center; justify-content: center; z-index: 10;';
        
        var fileName = document.createElement('div');
        fileName.className = 'file-name';
        fileName.textContent = file.name;
        fileName.style.cssText = 'font-size: 12px; margin-bottom: 4px; word-break: break-all; color: #2c3e50; font-weight: 500;';
        
        var fileSize = document.createElement('div');
        fileSize.className = 'file-size';
        fileSize.textContent = formatFileSize(file.size);
        fileSize.style.cssText = 'font-size: 11px; color: #7f8c8d;';
        
        item.appendChild(removeBtn);
        item.appendChild(fileName);
        item.appendChild(fileSize);
        
        return item;
    }
    
    /**
     * Generate image preview
     */
    function generateImagePreview(file, previewItem) {
        var reader = new FileReader();
        
        reader.onload = function(e) {
            var img = document.createElement('img');
            img.src = e.target.result;
            img.style.cssText = 'max-width: 100%; max-height: 80px; border-radius: 4px; margin-bottom: 8px;';
            previewItem.insertBefore(img, previewItem.firstChild);
        };
        
        reader.onerror = function(e) {
            console.error('Error reading file:', file.name, e);
        };
        
        try {
            reader.readAsDataURL(file);
        } catch (error) {
            console.error('Error starting FileReader for:', file.name, error);
        }
    }
    
    /**
     * Format file size
     */
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        
        var k = 1024;
        var sizes = ['Bytes', 'KB', 'MB', 'GB'];
        var i = Math.floor(Math.log(bytes) / Math.log(k));
        
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }
    
    /**
     * Show message
     */
    function showMessage(message, type) {
        var messageContainer = document.getElementById('response-messages');
        if (!messageContainer) {
            messageContainer = document.createElement('div');
            messageContainer.id = 'response-messages';
            messageContainer.style.cssText = 'margin: 20px 0;';
            
            var customerSection = document.querySelector('.customer-response-section');
            if (customerSection) {
                customerSection.insertBefore(messageContainer, customerSection.firstChild);
            }
        }
        
        var messageElement = document.createElement('div');
        messageElement.className = type === 'error' ? 'response-message error' : 'response-message success';
        messageElement.style.cssText = 'padding: 12px 20px; border-radius: 6px; margin-bottom: 15px; ' + 
                              (type === 'error' ? 'background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24;' : 'background: #d4edda; border: 1px solid #c3e6cb; color: #155724;');
        messageElement.innerHTML = '<strong>' + (type === 'error' ? 'Error:' : 'Success:') + '</strong> ' + message;
        
        messageContainer.innerHTML = '';
        messageContainer.appendChild(messageElement);
        
        // Auto-hide after 5 seconds for success messages
        if (type === 'success') {
            setTimeout(function() {
                messageElement.style.display = 'none';
            }, 5000);
        }
    }
});
</script>

<!-- Image Modal -->
<div id="imageModal" class="image-modal" style="display: none;">
    <div class="modal-content">
        <span class="modal-close" onclick="closeImageModal()">&times;</span>
        <img id="modalImage" src="" alt="Attachment">
    </div>
</div>
