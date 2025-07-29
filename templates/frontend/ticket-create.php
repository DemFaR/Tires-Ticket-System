<?php
/**
 * Frontend Ticket Creation Form Template
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="altalayi-ticket-container">
    <div class="ticket-form-wrapper">
        <div class="form-header">
            <h1><?php _e('Submit a Tire Complaint', 'altalayi-ticket'); ?></h1>
            <p><?php _e('Please fill out the form below to submit your tire complaint. We will review your case and get back to you as soon as possible.', 'altalayi-ticket'); ?></p>
        </div>
        
        <div id="ticket-messages"></div>
        
        <form id="altalayi-ticket-form" class="ticket-form" enctype="multipart/form-data">
            <?php wp_nonce_field('altalayi_ticket_nonce', 'nonce'); ?>
            
            <!-- Customer Information -->
            <fieldset class="form-section">
                <legend><?php _e('Customer Information', 'altalayi-ticket'); ?></legend>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="customer_name"><?php _e('Full Name', 'altalayi-ticket'); ?> <span class="required">*</span></label>
                        <input type="text" id="customer_name" name="customer_name" style="width: 90%" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="customer_phone"><?php _e('Phone Number', 'altalayi-ticket'); ?> <span class="required">*</span></label>
                        <input type="tel" id="customer_phone" name="customer_phone" style="width: 90%" required>
                        <small><?php _e('This will be used to access your ticket', 'altalayi-ticket'); ?></small>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="customer_email"><?php _e('Email Address', 'altalayi-ticket'); ?> <span class="required">*</span></label>
                    <input type="email" id="customer_email" name="customer_email" style="width: 90%" required>
                    <small><?php _e('We will send ticket updates to this email', 'altalayi-ticket'); ?></small>
                </div>
            </fieldset>
            
            <!-- Tire Information -->
            <fieldset class="form-section">
                <legend><?php _e('Tire Information', 'altalayi-ticket'); ?></legend>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="tire_brand"><?php _e('Tire Brand', 'altalayi-ticket'); ?></label>
                        <input type="text" id="tire_brand" name="tire_brand" style="width: 90%" placeholder="e.g., Bridgestone, Dayton">
                    </div>
                    
                    <div class="form-group">
                        <label for="tire_model"><?php _e('Tire Model', 'altalayi-ticket'); ?></label>
                        <input type="text" id="tire_model" name="tire_model" style="width: 90%" placeholder="e.g., Turanza T005">
                    </div>
                </div>
                
                <div class="form-row tire-size-row">
                    <div class="form-group">
                        <label for="tire_size_width"><?php _e('Tire Width', 'altalayi-ticket'); ?></label>
                        <input type="number" id="tire_size_width" name="tire_size_width" style="width: 90%" placeholder="225" min="100" max="400">
                        <small><?php _e('Width in millimeters (e.g., 225)', 'altalayi-ticket'); ?></small>
                    </div>
                    
                    <div class="form-group">
                        <label for="tire_size_aspect"><?php _e('Aspect Ratio', 'altalayi-ticket'); ?></label>
                        <input type="number" id="tire_size_aspect" name="tire_size_aspect" style="width: 90%" placeholder="60" min="25" max="100">
                        <small><?php _e('Aspect ratio percentage (e.g., 60)', 'altalayi-ticket'); ?></small>
                    </div>
                    
                    <div class="form-group">
                        <label for="tire_size_diameter"><?php _e('Rim Diameter', 'altalayi-ticket'); ?></label>
                        <input type="number" id="tire_size_diameter" name="tire_size_diameter" style="width: 90%" placeholder="16" min="10" max="30">
                        <small><?php _e('Rim diameter in inches (e.g., 16)', 'altalayi-ticket'); ?></small>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group tire-size-display">
                        <label><?php _e('Complete Tire Size', 'altalayi-ticket'); ?></label>
                        <div class="tire-size-preview">
                            <span id="tire-size-result"><?php _e('Enter values above to see tire size format', 'altalayi-ticket'); ?></span>
                        </div>
                        <small><?php _e('This shows your tire size in standard format (Width/Aspect R Diameter)', 'altalayi-ticket'); ?></small>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="purchase_date"><?php _e('Purchase Date', 'altalayi-ticket'); ?></label>
                        <input type="date" id="purchase_date" name="purchase_date" style="width: 90%">
                    </div>
                    
                    <div class="form-group">
                        <label for="purchase_location"><?php _e('Purchase Location', 'altalayi-ticket'); ?></label>
                        <input type="text" id="purchase_location" name="purchase_location" style="width: 90%" placeholder="<?php _e('Store name or location', 'altalayi-ticket'); ?>">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="mileage"><?php _e('Current Mileage', 'altalayi-ticket'); ?></label>
                        <input type="number" id="mileage" name="mileage" style="width: 90%" placeholder="50000" min="0">
                        <small><?php _e('Vehicle mileage in kilometers', 'altalayi-ticket'); ?></small>
                    </div>
                </div>
            </fieldset>
            
            <!-- Complaint Details -->
            <fieldset class="form-section">
                <legend><?php _e('Complaint Details', 'altalayi-ticket'); ?></legend>
                
                <div class="form-group">
                    <label for="complaint_text"><?php _e('Describe the Issue', 'altalayi-ticket'); ?> <span class="required">*</span></label>
                    <textarea id="complaint_text" name="complaint_text" rows="6" required 
                              placeholder="<?php _e('Please describe the issue with your tire in detail. Include when the problem started, how it affects your driving, and any other relevant information.', 'altalayi-ticket'); ?>"></textarea>
                </div>
            </fieldset>
            
            <!-- File Uploads -->
            <fieldset class="form-section">
                <legend><?php _e('Supporting Documents', 'altalayi-ticket'); ?></legend>
                
                <div class="upload-section">
                    <div class="form-group">
                        <label for="tire_images"><?php _e('Tire Images', 'altalayi-ticket'); ?></label>
                        <div class="file-upload-area">
                            <input type="file" id="tire_images" name="tire_images[]" multiple accept="image/*">
                            <div class="upload-instructions">
                                <i class="dashicons dashicons-camera"></i>
                                <p><?php _e('Upload clear photos of the tire issue', 'altalayi-ticket'); ?></p>
                                <small><?php _e('Maximum 5MB per file. JPG, PNG, WebP formats accepted.', 'altalayi-ticket'); ?></small>
                            </div>
                        </div>
                        <div id="receipt-preview" class="file-preview"></div>
                    </div>
                    
                    <div class="form-group">
                        <label for="receipt_image"><?php _e('Receipt/Invoice', 'altalayi-ticket'); ?></label>
                        <div class="file-upload-area">
                            <input type="file" id="receipt_image" name="receipt_image" accept="image/*,.pdf">
                            <div class="upload-instructions">
                                <i class="dashicons dashicons-media-document"></i>
                                <p><?php _e('Upload your purchase receipt or invoice', 'altalayi-ticket'); ?></p>
                                <small><?php _e('Maximum 5MB. JPG, PNG, PDF formats accepted.', 'altalayi-ticket'); ?></small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="additional_files"><?php _e('Additional Documents', 'altalayi-ticket'); ?></label>
                        <div class="file-upload-area">
                            <input type="file" id="additional_files" name="additional_files[]" multiple accept="image/*,.pdf,.doc,.docx,.txt,.rtf,.odt">
                            <div class="upload-instructions">
                                <i class="dashicons dashicons-paperclip"></i>
                                <p><?php _e('Upload any additional supporting documents', 'altalayi-ticket'); ?></p>
                                <small><?php _e('Maximum 5MB per file. Images, PDF, Word, and text files accepted.', 'altalayi-ticket'); ?></small>
                            </div>
                        </div>
                        <div id="additional-files-preview" class="file-preview"></div>
                    </div>
                </div>
            </fieldset>
            
            <!-- Submit Button -->
            <div class="form-actions">
                <button type="submit" class="submit-btn">
                    <span class="btn-text"><?php _e('Submit Ticket', 'altalayi-ticket'); ?></span>
                    <span class="btn-loading" style="display: none;">
                        <i class="dashicons dashicons-update rotate"></i> <?php _e('Submitting...', 'altalayi-ticket'); ?>
                    </span>
                </button>
            </div>
        </form>
        
        <div class="form-footer">
            <p>
                <?php _e('Already have a ticket?', 'altalayi-ticket'); ?>
                <a href="<?php echo esc_url(altalayi_get_access_ticket_url()); ?>"><?php _e('Access your existing ticket', 'altalayi-ticket'); ?></a>
            </p>
        </div>
    </div>
</div>

<style>
.altalayi-ticket-container {
    max-width: 800px;
    margin: 40px auto;
    padding: 0 20px;
}

.ticket-form-wrapper {
    background: white;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.form-header {
    background: linear-gradient(135deg, #2c3e50, #3498db);
    color: white;
    padding: 30px;
    text-align: center;
}

.form-header h1 {
    margin: 0 0 10px 0;
    font-size: 28px;
    font-weight: 300;
}

.form-header p {
    margin: 0;
    opacity: 0.9;
    font-size: 16px;
}

.ticket-form {
    padding: 30px;
}

.form-section {
    border: 1px solid #e1e5e9;
    border-radius: 6px;
    padding: 20px;
    margin-bottom: 25px;
}

.form-section legend {
    font-weight: 600;
    color: #2c3e50;
    padding: 0 15px;
    font-size: 18px;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

.form-row.tire-size-row {
    grid-template-columns: 1fr 1fr 1fr;
}

.tire-size-display {
    grid-column: 1 / -1;
}

.tire-size-preview {
    background: #f8f9fa;
    border: 2px solid #e1e5e9;
    border-radius: 6px;
    padding: 15px;
    text-align: center;
    font-size: 18px;
    font-weight: 600;
    color: #2c3e50;
    margin: 8px 0;
    min-height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.tire-size-preview.has-values {
    background: #d4edda;
    border-color: #27ae60;
    color: #155724;
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

.required {
    color: #e74c3c;
}

.form-group input,
.form-group textarea,
.form-group select {
    width: 100%;
    padding: 12px;
    border: 2px solid #e1e5e9;
    border-radius: 6px;
    font-size: 16px;
    transition: border-color 0.3s;
}

.form-group input:focus,
.form-group textarea:focus {
    outline: none;
    border-color: #3498db;
}

.form-group small {
    display: block;
    margin-top: 5px;
    color: #7f8c8d;
    font-size: 14px;
}

.file-upload-area {
    border: 2px dashed #bdc3c7;
    border-radius: 6px;
    padding: 20px;
    text-align: center;
    transition: border-color 0.3s;
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

.file-preview {
    margin-top: 15px;
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
    gap: 10px;
}

.file-preview-item {
    background: #f8f9fa;
    border: 1px solid #e1e5e9;
    border-radius: 4px;
    padding: 10px;
    text-align: center;
    position: relative;
}

.file-preview-item img {
    max-width: 100%;
    max-height: 80px;
    border-radius: 4px;
}

.file-preview-item .file-name {
    font-size: 12px;
    margin-top: 5px;
    word-break: break-all;
}

.file-remove {
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
}

.form-actions {
    text-align: center;
    padding-top: 20px;
    border-top: 1px solid #e1e5e9;
}

.submit-btn {
    background: linear-gradient(135deg, #27ae60, #2ecc71);
    color: white;
    border: none;
    padding: 15px 40px;
    border-radius: 6px;
    font-size: 18px;
    font-weight: 600;
    cursor: pointer;
    transition: transform 0.2s;
}

.submit-btn:hover {
    transform: translateY(-2px);
}

.submit-btn:disabled {
    opacity: 0.7;
    cursor: not-allowed;
    transform: none;
}

.form-footer {
    background: #f8f9fa;
    padding: 20px 30px;
    text-align: center;
    border-top: 1px solid #e1e5e9;
}

.form-footer a {
    color: #3498db;
    text-decoration: none;
    font-weight: 500;
}

.form-footer a:hover {
    text-decoration: underline;
}

#ticket-messages {
    margin: 20px 30px 0;
}

.ticket-message {
    padding: 12px 20px;
    border-radius: 6px;
    margin-bottom: 15px;
}

.ticket-message.success {
    background: #d4edda;
    border: 1px solid #c3e6cb;
    color: #155724;
}

.ticket-message.error {
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
    .form-row {
        grid-template-columns: 1fr;
    }
    
    .form-row.tire-size-row {
        grid-template-columns: 1fr;
    }
    
    .altalayi-ticket-container {
        margin: 20px auto;
        padding: 0 10px;
    }
    
    .ticket-form {
        padding: 20px;
    }
    
    .form-header {
        padding: 20px;
    }
    
    .form-header h1 {
        font-size: 24px;
    }
}
</style>

<script>
// Form submission handler only - NO FILE PREVIEW FUNCTIONALITY
jQuery(document).ready(function($) {
    // Tire size calculator
    function updateTireSize() {
        var width = $('#tire_size_width').val();
        var aspect = $('#tire_size_aspect').val();
        var diameter = $('#tire_size_diameter').val();
        var preview = $('#tire-size-result');
        var previewContainer = $('.tire-size-preview');
        
        if (width && aspect && diameter) {
            var tireSize = width + '/' + aspect + 'R' + diameter;
            preview.text(tireSize);
            previewContainer.addClass('has-values');
        } else if (width || aspect || diameter) {
            var partial = (width || '___') + '/' + (aspect || '__') + 'R' + (diameter || '__');
            preview.text(partial);
            previewContainer.removeClass('has-values');
        } else {
            preview.text('<?php _e("Enter values above to see tire size format", "altalayi-ticket"); ?>');
            previewContainer.removeClass('has-values');
        }
    }
    
    // Update tire size on input change
    $('#tire_size_width, #tire_size_aspect, #tire_size_diameter').on('input change', updateTireSize);
    
    // Form submission handler
    $('#altalayi-ticket-form').on('submit', function(e) {
        e.preventDefault();
        
        var form = $(this);
        var submitBtn = form.find('.submit-btn');
        var formData = new FormData(this);
        formData.append('action', 'altalayi_submit_ticket');
        
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
                    $('#ticket-messages').html(
                        '<div class="ticket-message success">' +
                        '<strong><?php _e("Success!", "altalayi-ticket"); ?></strong> ' +
                        response.data.message +
                        '<br><strong><?php _e("Your ticket number:", "altalayi-ticket"); ?></strong> ' + response.data.ticket_number +
                        '</div>'
                    );
                    
                    setTimeout(function() {
                        window.location.href = response.data.redirect_url;
                    }, 3000);
                } else {
                    $('#ticket-messages').html(
                        '<div class="ticket-message error">' +
                        '<strong><?php _e("Error:", "altalayi-ticket"); ?></strong> ' +
                        response.data.message +
                        '</div>'
                    );
                }
            },
            error: function() {
                $('#ticket-messages').html(
                    '<div class="ticket-message error">' +
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
                    scrollTop: $('#ticket-messages').offset().top - 100
                }, 500);
            }
        });
    });
});
</script>
