<?php
/**
 * Frontend Ticket View Template
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
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
        
        <!-- Ticket Updates -->
        <div class="info-section">
            <h2><?php _e('Ticket Updates', 'altalayi-ticket'); ?></h2>
            
            <?php if (!empty($updates)): ?>
                <div class="updates-timeline">
                    <?php foreach ($updates as $update): ?>
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
        
        <!-- Customer Response Section -->
        <?php
        // Check if ticket is waiting for customer response
        $needs_customer_response = false;
        foreach ($updates as $update) {
            if ($update->update_type === 'status_change' && 
                stripos($update->new_value, 'information required') !== false) {
                $needs_customer_response = true;
                break;
            }
        }
        ?>
        
        <?php if ($needs_customer_response): ?>
        <div class="info-section customer-response-section">
            <h2><?php _e('Additional Information Required', 'altalayi-ticket'); ?></h2>
            <p class="response-instruction">
                <?php _e('Our team needs additional information to process your ticket. Please provide the requested details below:', 'altalayi-ticket'); ?>
            </p>
            
            <form id="customer-response-form" class="response-form">
                <?php wp_nonce_field('altalayi_ticket_nonce', 'nonce'); ?>
                <input type="hidden" name="ticket_id" value="<?php echo esc_attr($ticket->id); ?>">
                
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
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
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
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
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
jQuery(document).ready(function($) {
    $('#customer-response-form').on('submit', function(e) {
        e.preventDefault();
        
        var form = $(this);
        var submitBtn = form.find('.btn-primary');
        var formData = new FormData(this);
        formData.append('action', 'altalayi_add_customer_response');
        
        // Disable submit button
        submitBtn.prop('disabled', true);
        submitBtn.find('.btn-text').hide();
        submitBtn.find('.btn-loading').show();
        
        $.ajax({
            url: altalayi_ajax.ajax_url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    $('#response-messages').html(
                        '<div class="response-message success">' +
                        '<strong><?php _e("Success!", "altalayi-ticket"); ?></strong> ' +
                        response.data.message +
                        '</div>'
                    );
                    
                    // Reset form
                    form[0].reset();
                    
                    // Reload page after 3 seconds to show the new response
                    setTimeout(function() {
                        location.reload();
                    }, 3000);
                } else {
                    $('#response-messages').html(
                        '<div class="response-message error">' +
                        '<strong><?php _e("Error:", "altalayi-ticket"); ?></strong> ' +
                        response.data.message +
                        '</div>'
                    );
                }
            },
            error: function() {
                $('#response-messages').html(
                    '<div class="response-message error">' +
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
                    scrollTop: $('#response-messages').offset().top - 100
                }, 500);
            }
        });
    });
});

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
    if (event.target == modal) {
        modal.style.display = 'none';
    }
}
</script>

<!-- Image Modal -->
<div id="imageModal" class="image-modal" style="display: none;">
    <div class="modal-content">
        <span class="modal-close" onclick="closeImageModal()">&times;</span>
        <img id="modalImage" src="" alt="Attachment">
    </div>
</div>

<?php get_footer(); ?>
