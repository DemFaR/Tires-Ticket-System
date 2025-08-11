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
                                <?php if ($ticket->assigned_to != get_current_user_id()): ?>
                                <button type="button" class="button button-small button-primary assign-to-me-btn" 
                                        data-ticket-id="<?php echo esc_attr($ticket->id); ?>">
                                    <?php _e('Assign to Me', 'altalayi-ticket'); ?>
                                </button>
                                <?php endif; ?>
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
                        <?php if ($ticket->customer_city): ?>
                        <div class="detail-row">
                            <strong><?php _e('City:', 'altalayi-ticket'); ?></strong>
                            <?php echo esc_html($ticket->customer_city); ?>
                        </div>
                        <?php endif; ?>
                        <?php if ($ticket->motocare_center_visited): ?>
                        <div class="detail-row">
                            <strong><?php _e('Motocare Center/Distributor:', 'altalayi-ticket'); ?></strong>
                            <?php echo esc_html($ticket->motocare_center_visited); ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Tire Information -->
                <div class="content-section">
                    <h3><?php _e('Tire Information', 'altalayi-ticket'); ?></h3>
                    <div class="tire-details">
                        <?php if ($ticket->tire_brand): ?>
                        <div class="detail-row">
                            <strong><?php _e('Brand:', 'altalayi-ticket'); ?></strong>
                            <?php echo esc_html($ticket->tire_brand); ?>
                        </div>
                        <?php endif; ?>
                        <?php if ($ticket->tire_model): ?>
                        <div class="detail-row">
                            <strong><?php _e('Model:', 'altalayi-ticket'); ?></strong>
                            <?php echo esc_html($ticket->tire_model); ?>
                        </div>
                        <?php endif; ?>
                        <!-- Hide tire size field -->
                        <?php /*
                        <?php if ($ticket->tire_size): ?>
                        <div class="detail-row">
                            <strong><?php _e('Size:', 'altalayi-ticket'); ?></strong>
                            <?php echo esc_html($ticket->tire_size); ?>
                        </div>
                        <?php endif; ?>
                        */ ?>
                        <?php if ($ticket->number_of_tires): ?>
                        <div class="detail-row">
                            <strong><?php _e('Number of Tires:', 'altalayi-ticket'); ?></strong>
                            <?php echo esc_html($ticket->number_of_tires); ?>
                        </div>
                        <?php endif; ?>
                        <?php if ($ticket->tire_position): ?>
                        <div class="detail-row">
                            <strong><?php _e('Tire Position:', 'altalayi-ticket'); ?></strong>
                            <?php echo esc_html($ticket->tire_position); ?>
                        </div>
                        <?php endif; ?>
                        <?php if ($ticket->air_pressure): ?>
                        <div class="detail-row">
                            <strong><?php _e('Air Pressure:', 'altalayi-ticket'); ?></strong>
                            <?php echo esc_html($ticket->air_pressure); ?>
                        </div>
                        <?php endif; ?>
                        <?php if ($ticket->tread_depth): ?>
                        <div class="detail-row">
                            <strong><?php _e('Tread Depth:', 'altalayi-ticket'); ?></strong>
                            <?php echo esc_html($ticket->tread_depth); ?>
                        </div>
                        <?php endif; ?>
                        <?php if ($ticket->purchase_date && $ticket->purchase_date !== '0000-00-00'): ?>
                        <div class="detail-row">
                            <strong><?php _e('Purchase Date:', 'altalayi-ticket'); ?></strong>
                            <?php echo date('F j, Y', strtotime($ticket->purchase_date)); ?>
                        </div>
                        <?php endif; ?>
                        <?php if ($ticket->purchase_location): ?>
                        <div class="detail-row">
                            <strong><?php _e('Purchase Location:', 'altalayi-ticket'); ?></strong>
                            <?php echo esc_html(altalayi_get_purchase_location_label($ticket->purchase_location)); ?>
                        </div>
                        <?php endif; ?>
                        <!-- Hide mileage field -->
                        <?php /*
                        <?php if ($ticket->mileage): ?>
                        <div class="detail-row">
                            <strong><?php _e('Mileage:', 'altalayi-ticket'); ?></strong>
                            <?php echo esc_html($ticket->mileage); ?> km
                        </div>
                        <?php endif; ?>
                        */ ?>
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
                            <i class="dashicons dashicons-admin-media"></i>
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
        
        <!-- Customer Responses -->
        <?php 
        $customer_responses = array_filter($updates, function($update) {
            return $update->update_type === 'customer_response';
        });
        ?>
        <?php if (!empty($customer_responses)): ?>
        <div class="customer-responses-section">
            <h3>
                <i class="dashicons dashicons-format-chat"></i>
                <?php _e('Customer Responses', 'altalayi-ticket'); ?> 
                <span class="response-count">(<?php echo count($customer_responses); ?>)</span>
            </h3>
            <div class="customer-responses-list">
                <?php foreach ($customer_responses as $response): ?>
                    <div class="customer-response-item">
                        <div class="response-header">
                            <div class="response-label">
                                <i class="dashicons dashicons-businessman"></i>
                                <?php _e('Customer Response', 'altalayi-ticket'); ?>
                            </div>
                            <div class="response-date">
                                <?php echo altalayi_format_date($response->update_date, 'F j, Y g:i A'); ?>
                            </div>
                        </div>
                        <div class="response-content">
                            <?php echo nl2br(esc_html($response->notes)); ?>
                        </div>
                        <?php 
                        // Get attachments for this response if any
                        $response_attachments = array_filter($attachments, function($att) use ($response) {
                            return $att->attachment_type === 'additional' && 
                                   abs(strtotime($att->upload_date) - strtotime($response->update_date)) < 300; // Within 5 minutes
                        });
                        ?>
                        <?php if (!empty($response_attachments)): ?>
                        <div class="response-attachments">
                            <small class="attachment-label"><?php _e('Attached files:', 'altalayi-ticket'); ?></small>
                            <div class="response-attachments-grid">
                                <?php foreach ($response_attachments as $attachment): ?>
                                    <div class="response-attachment-item">
                                        <i class="dashicons dashicons-media-default"></i>
                                        <span class="attachment-name"><?php echo esc_html($attachment->file_name); ?></span>
                                        <a href="<?php echo esc_url($attachment->file_path); ?>" target="_blank" class="attachment-link">
                                            <?php _e('View', 'altalayi-ticket'); ?>
                                        </a>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
        
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
        <?php 
        $history_updates = array_filter($updates, function($update) {
            return $update->update_type !== 'customer_response';
        });
        ?>
        <?php if (!empty($history_updates)): ?>
        <div class="ticket-history">
            <h3><?php _e('Ticket History', 'altalayi-ticket'); ?></h3>
            <div class="history-timeline">
                <?php foreach (array_reverse($history_updates) as $update): ?>
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
    flex-wrap: wrap;
}

.assign-to-me-btn {
    background: #27ae60 !important;
    border-color: #27ae60 !important;
    color: white !important;
}

.assign-to-me-btn:hover {
    background: #219a52 !important;
    border-color: #219a52 !important;
}

.assign-to-me-btn:disabled {
    opacity: 0.6 !important;
    cursor: not-allowed !important;
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
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 20px;
    margin-top: 20px;
}

.attachment-item {
    background: #ffffff;
    border: 1px solid #e1e5e9;
    border-radius: 8px;
    padding: 16px;
    text-align: center;
    transition: all 0.2s ease;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    display: flex;
    flex-direction: column;
    height: auto;
    min-height: 260px;
}

.attachment-item:hover {
    border-color: #0073aa;
    box-shadow: 0 4px 12px rgba(0,115,170,0.1);
    transform: translateY(-2px);
}

.attachment-image img {
    max-width: 100%;
    width: 100%;
    height: 140px;
    object-fit: cover;
    border-radius: 6px;
    cursor: pointer;
    transition: transform 0.2s ease;
    border: 1px solid #e1e5e9;
    margin-bottom: 12px;
}

.attachment-image img:hover {
    transform: scale(1.02);
}

.attachment-file {
    height: 140px;
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 56px;
    color: #0073aa;
    background: #f8f9fa;
    border-radius: 6px;
    border: 1px solid #e1e5e9;
    margin-bottom: 12px;
}

.attachment-info {
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.attachment-name {
    font-size: 13px;
    font-weight: 600;
    color: #2c3e50;
    line-height: 1.3;
    margin-bottom: 8px;
    word-break: break-word;
    text-align: center;
    display: block;
    writing-mode: horizontal-tb;
    text-orientation: mixed;
    overflow-wrap: break-word;
    hyphens: auto;
}

.attachment-meta {
    font-size: 11px;
    color: #7c8b9a;
    margin-bottom: 12px;
    text-align: center;
}

.attachment-actions {
    display: flex;
    gap: 8px;
    justify-content: center;
    flex-wrap: wrap;
    margin-top: auto;
}

.attachment-actions .button {
    font-size: 11px;
    padding: 6px 12px;
    height: auto;
    line-height: 1.3;
    border-radius: 4px;
    text-decoration: none;
    flex: 1;
    max-width: 80px;
    white-space: nowrap;
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

.customer-responses-section {
    background: white;
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 20px;
    margin-bottom: 20px;
}

.customer-responses-section h3 {
    margin-top: 0;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid #1565c0;
    color: #2c3e50;
    font-size: 18px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 10px;
}

.customer-responses-section h3:before {
    content: '';
    width: 4px;
    height: 20px;
    background: linear-gradient(135deg, #1565c0, #0d47a1);
    border-radius: 2px;
}

.customer-responses-section h3 .dashicons {
    color: #1565c0;
    font-size: 20px;
}

.response-count {
    background: #1565c0;
    color: white;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 500;
    margin-left: auto;
}

.customer-response-item {
    background: #f8f9fa;
    border: 1px solid #e3f2fd;
    border-left: 4px solid #1565c0;
    border-radius: 6px;
    padding: 16px;
    margin-bottom: 15px;
}

.response-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
}

.response-label {
    font-weight: 600;
    color: #1565c0;
    display: flex;
    align-items: center;
    gap: 8px;
}

.response-date {
    color: #7f8c8d;
    font-size: 14px;
}

.response-content {
    color: #2c3e50;
    line-height: 1.6;
    margin-bottom: 12px;
    background: white;
    padding: 12px;
    border-radius: 4px;
    border: 1px solid #e1e5e9;
}

.response-attachments {
    margin-top: 12px;
    padding-top: 12px;
    border-top: 1px solid #e1e5e9;
}

.attachment-label {
    color: #7f8c8d;
    font-weight: 500;
    display: block;
    margin-bottom: 8px;
}

.response-attachments-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}

.response-attachment-item {
    background: white;
    border: 1px solid #e1e5e9;
    border-radius: 4px;
    padding: 8px 12px;
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 12px;
}

.response-attachment-item .dashicons {
    color: #1565c0;
    font-size: 16px;
}

.response-attachment-item .attachment-name {
    color: #2c3e50;
    font-weight: 500;
}

.response-attachment-item .attachment-link {
    color: #1565c0;
    text-decoration: none;
    font-weight: 500;
}

.response-attachment-item .attachment-link:hover {
    text-decoration: underline;
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
        
        $.post(altalayi_admin_ajax.ajax_url, {
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
        
        $.post(altalayi_admin_ajax.ajax_url, {
            action: 'altalayi_assign_ticket',
            ticket_id: ticketId,
            assigned_to: userId,
            nonce: altalayi_admin_ajax.nonce
        }, function(response) {
            if (response.success) {
                // Show success message with status change info if applicable
                let message = response.data.message;
                if (response.data.status_changed) {
                    message += ' and status changed to "' + response.data.new_status + '"';
                }
                
                // Create and show success notification
                if ($('.notice-success').length === 0) {
                    $('.ticket-details').prepend('<div class="notice notice-success is-dismissible"><p>' + message + '</p></div>');
                }
                
                // Refresh page after 1 second to show all updates
                setTimeout(function() {
                    location.reload();
                }, 1000);
            } else {
                alert('Error assigning ticket: ' + (response.data ? response.data.message : 'Unknown error'));
            }
        });
    });
    
    // Assign to me functionality
    $('.assign-to-me-btn').on('click', function() {
        var $btn = $(this);
        var ticketId = $btn.data('ticket-id');
        
        // Disable button and show loading state
        $btn.prop('disabled', true).text('Assigning...');
        
        $.post(altalayi_admin_ajax.ajax_url, {
            action: 'altalayi_assign_to_me',
            ticket_id: ticketId,
            nonce: altalayi_admin_ajax.nonce
        })
        .done(function(response) {
            if (response.success) {
                // Update assigned user display
                $('.assigned-display').html(response.data.assigned_user);
                
                // Hide the "Assign to Me" button
                $btn.hide();
                
                // Show success message
                let message = response.data.message;
                if (response.data.status_changed) {
                    message += ' and status changed to "' + response.data.new_status + '"';
                }
                
                // Create and show success notification
                if ($('.notice-success').length === 0) {
                    $('.ticket-details').prepend('<div class="notice notice-success is-dismissible"><p>' + message + '</p></div>');
                }
                
                // Refresh page after 2 seconds to show all updates
                setTimeout(function() {
                    location.reload();
                }, 2000);
            } else {
                alert('Error: ' + response.data.message);
                // Re-enable button
                $btn.prop('disabled', false).text('Assign to Me');
            }
        })
        .fail(function() {
            alert('Network error occurred');
            // Re-enable button
            $btn.prop('disabled', false).text('Assign to Me');
        });
    });
    
    // Add note form
    $('#add-note-form').on('submit', function(e) {
        e.preventDefault();
        
        var noteContent = $('textarea[name="note_content"]').val();
        var visibleToCustomer = $('input[name="visible_to_customer"]').is(':checked') ? 1 : 0;
        
        $.post(altalayi_admin_ajax.ajax_url, {
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
