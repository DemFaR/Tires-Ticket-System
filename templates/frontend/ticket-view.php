<?php
/**
 * Frontend Ticket View Template
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="altalayi-ticket-view-container">
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
        
        <!-- Customer Information -->
        <div class="info-section">
            <h2><?php _e('Customer Information', 'altalayi-ticket'); ?></h2>
            <div class="info-grid">
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
                <?php if ($ticket->assigned_user_name): ?>
                <div class="info-item">
                    <label><?php _e('Assigned To:', 'altalayi-ticket'); ?></label>
                    <span><?php echo esc_html($ticket->assigned_user_name); ?></span>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Tire Information -->
        <?php if ($ticket->tire_brand || $ticket->tire_model || $ticket->tire_size): ?>
        <div class="info-section">
            <h2><?php _e('Tire Information', 'altalayi-ticket'); ?></h2>
            <div class="info-grid">
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
                
                <?php if ($ticket->tire_size): ?>
                <div class="info-item">
                    <label><?php _e('Size:', 'altalayi-ticket'); ?></label>
                    <span><?php echo esc_html($ticket->tire_size); ?></span>
                </div>
                <?php endif; ?>
                
                <?php if ($ticket->purchase_date && $ticket->purchase_date !== '0000-00-00'): ?>
                <div class="info-item">
                    <label><?php _e('Purchase Date:', 'altalayi-ticket'); ?></label>
                    <span><?php echo altalayi_format_date($ticket->purchase_date, 'F j, Y'); ?></span>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
        
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
        
        <div id="response-messages"></div>
    </div>
</div>

<style>
.altalayi-ticket-view-container {
    max-width: 900px;
    margin: 40px auto;
    padding: 0 20px;
}

.ticket-view-wrapper {
    background: white;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.ticket-header {
    background: linear-gradient(135deg, #2c3e50, #3498db);
    color: white;
    padding: 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.ticket-title h1 {
    margin: 0 0 10px 0;
    font-size: 28px;
    font-weight: 300;
}

.ticket-meta {
    display: flex;
    gap: 20px;
    align-items: center;
}

.ticket-date {
    opacity: 0.9;
}

.ticket-date i {
    margin-right: 5px;
}

.ticket-actions {
    display: flex;
    gap: 10px;
}

.btn {
    padding: 10px 20px;
    border: none;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    transition: all 0.2s;
}

.btn-primary {
    background: linear-gradient(135deg, #27ae60, #2ecc71);
    color: white;
}

.btn-secondary {
    background: rgba(255, 255, 255, 0.2);
    color: white;
    border: 1px solid rgba(255, 255, 255, 0.3);
}

.btn-outline {
    background: transparent;
    color: white;
    border: 1px solid rgba(255, 255, 255, 0.5);
}

.btn-small {
    padding: 6px 12px;
    font-size: 12px;
}

.btn:hover {
    transform: translateY(-1px);
}

.info-section {
    padding: 30px;
    border-bottom: 1px solid #e1e5e9;
}

.info-section:last-child {
    border-bottom: none;
}

.info-section h2 {
    margin: 0 0 20px 0;
    color: #2c3e50;
    font-size: 20px;
    font-weight: 600;
    padding-bottom: 10px;
    border-bottom: 2px solid #3498db;
}

.info-grid {
    display: flex;
    justify-content: space-between;
}

.info-item {
    display: flex;
    flex-direction: column;
}

.info-item label {
    font-weight: 600;
    color: #34495e;
    margin-bottom: 5px;
    font-size: 14px;
}

.info-item span {
    color: #2c3e50;
    font-size: 16px;
}

.complaint-text {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 6px;
    border-left: 4px solid #3498db;
    color: #2c3e50;
    line-height: 1.6;
}

.attachments-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 15px;
}

.attachment-item {
    background: #f8f9fa;
    border: 1px solid #e1e5e9;
    border-radius: 6px;
    padding: 15px;
    text-align: center;
}

.attachment-image img {
    max-width: 100%;
    height: 120px;
    object-fit: cover;
    border-radius: 4px;
    cursor: pointer;
    transition: transform 0.2s;
}

.attachment-image img:hover {
    transform: scale(1.05);
}

.attachment-file {
    height: 120px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 48px;
    color: #3498db;
}

.attachment-info {
    margin-top: 10px;
}

.attachment-name {
    font-weight: bold;
    color: #2c3e50;
    margin-bottom: 5px;
    word-break: break-word;
    font-size: 14px;
}

.attachment-meta {
    font-size: 12px;
    color: #6c757d;
    margin-bottom: 10px;
}

.attachment-meta span {
    display: block;
    margin-bottom: 2px;
}

.attachment-actions {
    display: flex;
    gap: 5px;
    justify-content: center;
}

.attachment-actions .btn {
    padding: 5px 10px;
    font-size: 12px;
}

/* Image Modal */
.image-modal {
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.9);
}

.modal-content {
    position: relative;
    margin: auto;
    padding: 0;
    width: 90%;
    max-width: 800px;
    top: 50%;
    transform: translateY(-50%);
}

.modal-close {
    position: absolute;
    top: 15px;
    right: 35px;
    color: #f1f1f1;
    font-size: 40px;
    font-weight: bold;
    cursor: pointer;
    z-index: 1001;
}

.modal-content img {
    width: 100%;
    height: auto;
    border-radius: 4px;
}
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 5px;
}

.attachment-meta {
    display: flex;
    gap: 10px;
    font-size: 12px;
    color: #7f8c8d;
}

.updates-timeline {
    position: relative;
    padding-left: 30px;
}

.updates-timeline::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e1e5e9;
}

.update-item {
    position: relative;
    margin-bottom: 25px;
    background: #f8f9fa;
    border-radius: 6px;
    padding: 20px;
}

.update-marker {
    position: absolute;
    left: -37px;
    top: 20px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: #3498db;
    border: 3px solid white;
}

.update-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
}

.update-type {
    font-weight: 600;
    color: #2c3e50;
    display: flex;
    align-items: center;
    gap: 5px;
}

.update-date {
    font-size: 12px;
    color: #7f8c8d;
}

.update-text,
.update-notes {
    color: #34495e;
    line-height: 1.6;
    margin-bottom: 10px;
}

.update-notes {
    background: white;
    padding: 15px;
    border-radius: 4px;
    border-left: 3px solid #3498db;
}

.update-author {
    font-size: 12px;
    color: #7f8c8d;
    font-style: italic;
}

.customer-response.update-item {
    border-left: 4px solid #27ae60;
}

.customer-response .update-marker {
    background: #27ae60;
}

.employee-responses {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.employee-response-item {
    background: #f8f9fa;
    border: 1px solid #e1e5e9;
    border-left: 4px solid #3498db;
    border-radius: 6px;
    padding: 20px;
}

.employee-response-item .response-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
    padding-bottom: 10px;
    border-bottom: 1px solid #e1e5e9;
}

.employee-response-item .response-author {
    font-weight: 600;
    color: #2c3e50;
    display: flex;
    align-items: center;
    gap: 8px;
}

.employee-response-item .response-author i {
    color: #3498db;
}

.employee-response-item .response-date {
    font-size: 12px;
    color: #7f8c8d;
}

.employee-response-item .response-content {
    color: #34495e;
    line-height: 1.6;
    font-size: 15px;
}

.no-updates {
    text-align: center;
    padding: 40px 20px;
    color: #7f8c8d;
}

.customer-response-section {
    background: #f0f8ff;
    border-top: 3px solid #3498db;
}

.response-instruction {
    background: #e3f2fd;
    padding: 15px;
    border-radius: 6px;
    color: #1565c0;
    margin-bottom: 20px;
}

.response-form .form-group {
    margin-bottom: 20px;
}

.response-form label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #34495e;
}

.response-form textarea {
    width: 100%;
    padding: 12px;
    border: 2px solid #e1e5e9;
    border-radius: 6px;
    font-size: 16px;
    resize: vertical;
}

.response-form textarea:focus {
    outline: none;
    border-color: #3498db;
}

.file-upload-area {
    border: 2px dashed #bdc3c7;
    border-radius: 6px;
    padding: 20px;
    text-align: center;
    cursor: pointer;
    position: relative;
}

.file-upload-area:hover {
    border-color: #3498db;
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
    font-size: 24px;
    color: #7f8c8d;
    margin-bottom: 10px;
}

.upload-instructions p {
    margin: 0 0 5px 0;
    color: #2c3e50;
    font-weight: 500;
}

.upload-instructions small {
    color: #7f8c8d;
}

.form-actions {
    text-align: center;
}

#response-messages {
    margin: 20px 30px;
}

.response-message {
    padding: 12px 20px;
    border-radius: 6px;
    margin-bottom: 15px;
}

.response-message.success {
    background: #d4edda;
    border: 1px solid #c3e6cb;
    color: #155724;
}

.response-message.error {
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

.file-upload-area.dragover {
    border-color: #3498db;
    background-color: #f0f8ff;
}

.file-preview {
    margin-top: 15px;
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
    gap: 10px;
}

.file-preview-item {
    background: #f8f9fa;
    border: 1px solid #e1e5e9;
    border-radius: 6px;
    padding: 10px;
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
    color: #2c3e50;
    font-weight: 500;
}

.file-preview-item .file-size {
    font-size: 11px;
    color: #7f8c8d;
}

.file-remove-btn {
    position: absolute;
    top: -5px;
    right: -5px;
    background: #e74c3c;
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
    z-index: 10;
}

.file-remove-btn:hover {
    background: #c0392b;
}

@media print {
    .ticket-actions,
    .customer-response-section {
        display: none !important;
    }
    
    .ticket-header {
        background: #2c3e50 !important;
        -webkit-print-color-adjust: exact;
    }
}

@media (max-width: 768px) {
    .altalayi-ticket-view-container {
        margin: 20px auto;
        padding: 0 10px;
    }
    
    .ticket-header {
        flex-direction: column;
        gap: 20px;
        text-align: center;
    }
    
    .ticket-meta {
        flex-direction: column;
        gap: 10px;
    }
    
    .info-section {
        padding: 20px;
    }
    
    .info-grid {
        grid-template-columns: 1fr;
    }
    
    .attachments-grid {
        grid-template-columns: 1fr;
    }
    
    .updates-timeline {
        padding-left: 20px;
    }
    
    .update-marker {
        left: -27px;
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
    fileInput.addEventListener('change', function() {
        if (this.files && this.files.length > 0) {
            handleFileSelection(this.files, previewContainer);
        } else {
            previewContainer.innerHTML = '';
        }
    });
    
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
                fileIcon.textContent = '📄';
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
