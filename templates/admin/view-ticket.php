<?php
/**
 * View Ticket Admin Template
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wrap">
    <h1><?php printf(__('Ticket #%s', 'altalayi-ticket'), esc_html($ticket->ticket_number)); ?></h1>
    
    <div class="ticket-details">
        <div class="ticket-header">
            <div class="ticket-info">
                <div class="info-grid">
                    <div class="info-item">
                        <strong><?php _e('Status:', 'altalayi-ticket'); ?></strong>
                        <div class="status-manager" data-ticket-id="<?php echo esc_attr($ticket->id); ?>">
                            <div class="current-status">
                                <?php echo altalayi_get_status_badge($ticket->status_name, $ticket->status_color); ?>
                                <button type="button" class="button button-small edit-status-btn">
                                    <?php _e('Change', 'altalayi-ticket'); ?>
                                </button>
                            </div>
                            <div class="status-editor" style="display: none;">
                                <select class="status-selector">
                                    <?php foreach ($statuses as $status): ?>
                                        <option value="<?php echo esc_attr($status->id); ?>" 
                                                <?php selected($ticket->status_id, $status->id); ?>>
                                            <?php echo esc_html($status->status_name); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <button type="button" class="button button-primary save-status-btn">
                                    <?php _e('Save', 'altalayi-ticket'); ?>
                                </button>
                                <button type="button" class="button cancel-status-btn">
                                    <?php _e('Cancel', 'altalayi-ticket'); ?>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <strong><?php _e('Assigned To:', 'altalayi-ticket'); ?></strong>
                        <div class="assignment-manager" data-ticket-id="<?php echo esc_attr($ticket->id); ?>">
                            <div class="current-assignment">
                                <span class="assigned-display">
                                    <?php echo $ticket->assigned_user_name ? esc_html($ticket->assigned_user_name) : '<em>' . __('Unassigned', 'altalayi-ticket') . '</em>'; ?>
                                </span>
                                <button type="button" class="button button-small edit-assignment-btn">
                                    <?php _e('Change', 'altalayi-ticket'); ?>
                                </button>
                            </div>
                            <div class="assignment-editor" style="display: none;">
                                <select class="assignment-selector">
                                    <option value=""><?php _e('Unassigned', 'altalayi-ticket'); ?></option>
                                    <?php foreach ($employees as $employee): ?>
                                        <option value="<?php echo esc_attr($employee->ID); ?>" 
                                                <?php selected($ticket->assigned_to, $employee->ID); ?>>
                                            <?php echo esc_html($employee->display_name); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <button type="button" class="button button-primary save-assignment-btn">
                                    <?php _e('Save', 'altalayi-ticket'); ?>
                                </button>
                                <button type="button" class="button cancel-assignment-btn">
                                    <?php _e('Cancel', 'altalayi-ticket'); ?>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <strong><?php _e('Created:', 'altalayi-ticket'); ?></strong>
                        <?php echo altalayi_format_date($ticket->created_at, 'F j, Y g:i A'); ?>
                    </div>
                    
                    <div class="info-item">
                        <strong><?php _e('Last Updated:', 'altalayi-ticket'); ?></strong>
                        <?php echo altalayi_format_date($ticket->updated_at, 'F j, Y g:i A'); ?>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="ticket-content">
            <div class="content-sections">
                <!-- Customer Information -->
                <div class="content-section">
                    <h3><?php _e('Customer Information', 'altalayi-ticket'); ?></h3>
                    <div class="customer-details">
                        <div class="detail-row">
                            <strong><?php _e('Name:', 'altalayi-ticket'); ?></strong>
                            <?php echo esc_html($ticket->customer_name); ?>
                        </div>
                        <div class="detail-row">
                            <strong><?php _e('Phone:', 'altalayi-ticket'); ?></strong>
                            <a href="tel:<?php echo esc_attr($ticket->customer_phone); ?>">
                                <?php echo esc_html($ticket->customer_phone); ?>
                            </a>
                        </div>
                        <div class="detail-row">
                            <strong><?php _e('Email:', 'altalayi-ticket'); ?></strong>
                            <a href="mailto:<?php echo esc_attr($ticket->customer_email); ?>">
                                <?php echo esc_html($ticket->customer_email); ?>
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Tire Information -->
                <div class="content-section">
                    <h3><?php _e('Tire Information', 'altalayi-ticket'); ?></h3>
                    <div class="tire-details">
                        <div class="detail-row">
                            <strong><?php _e('Brand:', 'altalayi-ticket'); ?></strong>
                            <?php echo esc_html($ticket->tire_brand); ?>
                        </div>
                        <div class="detail-row">
                            <strong><?php _e('Size:', 'altalayi-ticket'); ?></strong>
                            <?php echo esc_html($ticket->tire_size); ?>
                        </div>
                        <div class="detail-row">
                            <strong><?php _e('Model:', 'altalayi-ticket'); ?></strong>
                            <?php echo esc_html($ticket->tire_model); ?>
                        </div>
                        <div class="detail-row">
                            <strong><?php _e('Purchase Date:', 'altalayi-ticket'); ?></strong>
                            <?php echo altalayi_format_date($ticket->purchase_date); ?>
                        </div>
                        <?php if ($ticket->purchase_location): ?>
                        <div class="detail-row">
                            <strong><?php _e('Purchase Location:', 'altalayi-ticket'); ?></strong>
                            <?php echo esc_html($ticket->purchase_location); ?>
                        </div>
                        <?php endif; ?>
                        <?php if ($ticket->mileage): ?>
                        <div class="detail-row">
                            <strong><?php _e('Mileage:', 'altalayi-ticket'); ?></strong>
                            <?php echo esc_html($ticket->mileage); ?> km
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Complaint Details -->
                <div class="content-section">
                    <h3><?php _e('Complaint Details', 'altalayi-ticket'); ?></h3>
                    <div class="complaint-text">
                        <?php echo nl2br(esc_html($ticket->complaint_text)); ?>
                    </div>
                </div>
                
                <!-- Attachments -->
                <?php if (!empty($attachments)): ?>
                    <?php
                    // Group attachments by type
                    $grouped_attachments = array(
                        'tire_image' => array(),
                        'receipt' => array(),
                        'additional' => array()
                    );
                    
                    foreach ($attachments as $attachment) {
                        $grouped_attachments[$attachment->attachment_type][] = $attachment;
                    }
                    ?>
                    
                    <!-- Tire Images -->
                    <?php if (!empty($grouped_attachments['tire_image'])): ?>
                    <div class="content-section">
                        <h3>
                            <i class="dashicons dashicons-admin-media"></i>
                            <?php _e('Tire Images', 'altalayi-ticket'); ?> 
                            <span class="attachment-count">(<?php echo count($grouped_attachments['tire_image']); ?>)</span>
                        </h3>
                        <div class="attachments-grid">
                            <?php foreach ($grouped_attachments['tire_image'] as $attachment): ?>
                                <?php include 'attachment-item-template.php'; ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Receipt/Invoice -->
                    <?php if (!empty($grouped_attachments['receipt'])): ?>
                    <div class="content-section">
                        <h3>
                            <i class="dashicons dashicons-media-document"></i>
                            <?php _e('Receipt/Invoice', 'altalayi-ticket'); ?> 
                            <span class="attachment-count">(<?php echo count($grouped_attachments['receipt']); ?>)</span>
                        </h3>
                        <div class="attachments-grid">
                            <?php foreach ($grouped_attachments['receipt'] as $attachment): ?>
                                <?php include 'attachment-item-template.php'; ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Additional Documents -->
                    <?php if (!empty($grouped_attachments['additional'])): ?>
                    <div class="content-section">
                        <h3>
                            <i class="dashicons dashicons-paperclip"></i>
                            <?php _e('Additional Documents', 'altalayi-ticket'); ?> 
                            <span class="attachment-count">(<?php echo count($grouped_attachments['additional']); ?>)</span>
                        </h3>
                        <div class="attachments-grid">
                            <?php foreach ($grouped_attachments['additional'] as $attachment): ?>
                                <?php include 'attachment-item-template.php'; ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Add Note Section -->
        <div class="add-note-section">
            <h3><?php _e('Add Internal Note', 'altalayi-ticket'); ?></h3>
            <form id="add-note-form">
                <div class="note-form-fields">
                    <textarea name="note_content" placeholder="<?php _e('Add a note about this ticket...', 'altalayi-ticket'); ?>" 
                              rows="4" required></textarea>
                    
                    <div class="note-options">
                        <label>
                            <input type="checkbox" name="visible_to_customer" value="1">
                            <?php _e('Visible to customer', 'altalayi-ticket'); ?>
                        </label>
                        
                        <button type="submit" class="button button-primary">
                            <?php _e('Add Note', 'altalayi-ticket'); ?>
                        </button>
                    </div>
                </div>
            </form>
        </div>
        
        <!-- Ticket History -->
        <?php if (!empty($updates)): ?>
        <div class="ticket-history">
            <h3><?php _e('Ticket History', 'altalayi-ticket'); ?></h3>
            <div class="history-timeline">
                <?php foreach ($updates as $update): ?>
                    <div class="history-item <?php echo esc_attr($update->update_type); ?>">
                        <div class="history-icon">
                            <?php
                            switch ($update->update_type) {
                                case 'status_change':
                                    echo '<i class="dashicons dashicons-update"></i>';
                                    break;
                                case 'assignment':
                                    echo '<i class="dashicons dashicons-admin-users"></i>';
                                    break;
                                case 'note':
                                    echo '<i class="dashicons dashicons-edit"></i>';
                                    break;
                                case 'customer_response':
                                    echo '<i class="dashicons dashicons-format-chat"></i>';
                                    break;
                                default:
                                    echo '<i class="dashicons dashicons-marker"></i>';
                            }
                            ?>
                        </div>
                        
                        <div class="history-content">
                            <div class="history-header">
                                <span class="history-user">
                                    <?php echo $update->updated_by_name ? esc_html($update->updated_by_name) : __('System', 'altalayi-ticket'); ?>
                                </span>
                                <span class="history-date">
                                    <?php echo altalayi_format_date($update->update_date, 'F j, Y g:i A'); ?>
                                </span>
                            </div>
                            
                            <div class="history-message">
                                <?php if ($update->update_type === 'status_change' && $update->old_value && $update->new_value): ?>
                                    <?php printf(
                                        __('Status changed from "%s" to "%s"', 'altalayi-ticket'),
                                        esc_html($update->old_value),
                                        esc_html($update->new_value)
                                    ); ?>
                                <?php elseif ($update->update_type === 'assignment' && $update->new_value): ?>
                                    <?php printf(__('Ticket assigned to %s', 'altalayi-ticket'), esc_html($update->new_value)); ?>
                                <?php elseif ($update->notes): ?>
                                    <?php echo nl2br(esc_html($update->notes)); ?>
                                <?php endif; ?>
                            </div>
                            
                            <?php if (!$update->is_visible_to_customer): ?>
                                <div class="internal-note-badge">
                                    <?php _e('Internal Note', 'altalayi-ticket'); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Image Modal -->
<div id="imageModal" class="image-modal" style="display: none;">
    <div class="modal-content">
        <span class="modal-close" onclick="closeImageModal()">&times;</span>
        <img id="modalImage" src="" alt="Attachment">
    </div>
</div>

<style>
.ticket-details {
    max-width: 1000px;
}

.ticket-header {
    background: white;
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 20px;
    margin-bottom: 20px;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
}

.info-item {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.status-manager, .assignment-manager {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.current-status, .current-assignment {
    display: flex;
    align-items: center;
    gap: 10px;
}

.status-editor, .assignment-editor {
    display: flex;
    align-items: center;
    gap: 10px;
}

.status-editor select, .assignment-editor select {
    min-width: 150px;
}

.content-sections {
    display: grid;
    gap: 20px;
}

.content-section {
    background: white;
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 20px;
}

.content-section h3 {
    margin-top: 0;
    margin-bottom: 15px;
    padding-bottom: 10px;
    border-bottom: 2px solid #0073aa;
    color: #2c3e50;
    font-size: 18px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 10px;
}

.content-section h3:before {
    content: '';
    width: 4px;
    height: 20px;
    background: linear-gradient(135deg, #0073aa, #005177);
    border-radius: 2px;
}

.content-section h3 .dashicons {
    color: #0073aa;
    font-size: 20px;
}

.attachment-count {
    background: #0073aa;
    color: white;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 500;
    margin-left: auto;
}

.detail-row {
    display: flex;
    margin-bottom: 10px;
}

.detail-row strong {
    min-width: 150px;
    margin-right: 10px;
}

.complaint-text {
    line-height: 1.6;
    color: #333;
}

.attachments-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
    gap: 20px;
    margin-top: 15px;
}

.attachment-item {
    background: #fff;
    border: 2px solid #e1e1e1;
    border-radius: 8px;
    padding: 15px;
    text-align: center;
    transition: all 0.3s ease;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.attachment-item:hover {
    border-color: #0073aa;
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    transform: translateY(-2px);
}

.attachment-image img {
    max-width: 100%;
    height: 120px;
    object-fit: cover;
    border-radius: 6px;
    cursor: pointer;
    transition: transform 0.2s ease;
    border: 1px solid #ddd;
}

.attachment-image img:hover {
    transform: scale(1.05);
}

.attachment-file {
    height: 120px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 64px;
    color: #666;
    background: #f8f9fa;
    border-radius: 6px;
    border: 1px solid #ddd;
}

.attachment-name {
    font-size: 13px;
    margin: 12px 0 8px 0;
    word-break: break-word;
    font-weight: 600;
    color: #2c3e50;
    line-height: 1.3;
    min-height: 34px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.attachment-meta {
    font-size: 11px;
    color: #7c8b9a;
    margin-bottom: 12px;
}

.attachment-actions {
    display: flex;
    gap: 8px;
    justify-content: center;
    flex-wrap: wrap;
}

.attachment-actions .button {
    font-size: 11px;
    padding: 4px 12px;
    height: auto;
    line-height: 1.4;
    border-radius: 4px;
    text-decoration: none;
}

.attachment-actions .button:hover {
    transform: none;
}

.add-note-section {
    background: white;
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 20px;
    margin: 20px 0;
}

.note-form-fields textarea {
    width: 100%;
    resize: vertical;
}

.note-options {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 10px;
}

.ticket-history {
    background: white;
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 20px;
}

.history-timeline {
    position: relative;
    padding-left: 40px;
}

.history-timeline::before {
    content: '';
    position: absolute;
    left: 20px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #ddd;
}

.history-item {
    position: relative;
    margin-bottom: 20px;
    background: #f9f9f9;
    border-radius: 4px;
    padding: 15px;
}

.history-icon {
    position: absolute;
    left: -32px;
    top: 15px;
    width: 24px;
    height: 24px;
    background: white;
    border: 2px solid #ddd;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
}

.history-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
}

.history-user {
    font-weight: bold;
    color: #2c3e50;
}

.history-date {
    color: #7f8c8d;
    font-size: 14px;
}

.internal-note-badge {
    display: inline-block;
    background: #e74c3c;
    color: white;
    padding: 2px 8px;
    border-radius: 3px;
    font-size: 11px;
    margin-top: 10px;
}

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
}

.modal-content img {
    width: 100%;
    height: auto;
}
</style>

<script>
jQuery(document).ready(function($) {
    // Status management
    $('.edit-status-btn').on('click', function() {
        var statusManager = $(this).closest('.status-manager');
        statusManager.find('.current-status').hide();
        statusManager.find('.status-editor').show();
    });
    
    $('.cancel-status-btn').on('click', function() {
        var statusManager = $(this).closest('.status-manager');
        statusManager.find('.status-editor').hide();
        statusManager.find('.current-status').show();
    });
    
    $('.save-status-btn').on('click', function() {
        var statusManager = $(this).closest('.status-manager');
        var ticketId = statusManager.data('ticket-id');
        var statusId = statusManager.find('.status-selector').val();
        
        $.post(ajaxurl, {
            action: 'altalayi_update_ticket_status',
            ticket_id: ticketId,
            status_id: statusId,
            nonce: altalayi_admin_ajax.nonce
        }, function(response) {
            if (response.success) {
                location.reload();
            } else {
                alert('Error updating status: ' + (response.data ? response.data.message : 'Unknown error'));
            }
        });
    });
    
    // Assignment management
    $('.edit-assignment-btn').on('click', function() {
        var assignmentManager = $(this).closest('.assignment-manager');
        assignmentManager.find('.current-assignment').hide();
        assignmentManager.find('.assignment-editor').show();
    });
    
    $('.cancel-assignment-btn').on('click', function() {
        var assignmentManager = $(this).closest('.assignment-manager');
        assignmentManager.find('.assignment-editor').hide();
        assignmentManager.find('.current-assignment').show();
    });
    
    $('.save-assignment-btn').on('click', function() {
        var assignmentManager = $(this).closest('.assignment-manager');
        var ticketId = assignmentManager.data('ticket-id');
        var userId = assignmentManager.find('.assignment-selector').val();
        
        $.post(ajaxurl, {
            action: 'altalayi_assign_ticket',
            ticket_id: ticketId,
            assigned_to: userId,
            nonce: altalayi_admin_ajax.nonce
        }, function(response) {
            if (response.success) {
                location.reload();
            } else {
                alert('Error assigning ticket: ' + (response.data ? response.data.message : 'Unknown error'));
            }
        });
    });
    
    // Add note form
    $('#add-note-form').on('submit', function(e) {
        e.preventDefault();
        
        var noteContent = $('textarea[name="note_content"]').val();
        var visibleToCustomer = $('input[name="visible_to_customer"]').is(':checked') ? 1 : 0;
        
        $.post(ajaxurl, {
            action: 'altalayi_add_ticket_note',
            ticket_id: <?php echo $ticket->id; ?>,
            note: noteContent,
            visible_to_customer: visibleToCustomer,
            nonce: altalayi_admin_ajax.nonce
        }, function(response) {
            if (response.success) {
                location.reload();
            } else {
                alert('Error adding note: ' + (response.data ? response.data.message : 'Unknown error'));
            }
        });
    });
});

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
