/**
 * Altalayi Ticket System - File Upload Handler v4.0
 * DUPLICATE PREVENTION VERSION
 */
(function($) {
    'use strict';
    
    // Prevent multiple initialization
    if (window.altalayi_file_upload_initialized) {
        console.warn('⚠️ Altalayi File Upload already initialized - preventing duplicate');
        return;
    }
    
    console.log('🚀 Altalayi Ticket System - File Upload v4.0 Loaded (DUPLICATE PREVENTION)');
    
    // Mark as initialized
    window.altalayi_file_upload_initialized = true;
    
    $(document).ready(function() {
        console.log('📋 DOM Ready - Initializing file upload handlers');
        
        // ANTI-DUPLICATE file preview function
        function setupFilePreview(inputId, previewId) {
            var input = $('#' + inputId);
            var preview = $('#' + previewId);
            
            if (input.length === 0) {
                console.warn('❌ Input not found:', inputId);
                return;
            }
            
            // Check if already initialized
            if (input.data('altalayi-initialized')) {
                console.warn('⚠️ Input already initialized:', inputId, '- skipping');
                return;
            }
            
            // Mark as initialized
            input.data('altalayi-initialized', true);
            
            // Remove ALL existing event handlers for this input
            input.off('change');
            
            // Bind our handler with namespace
            input.on('change.altalayi', function() {
                var files = this.files;
                preview.empty();
                
                console.log('📁 PROCESSING', files.length, 'files for', inputId, '(v4.0)');
                
                if (files.length === 0) {
                    console.log('📭 No files selected');
                    return;
                }
                
                // Create file data array with unique processing
                var fileDataArray = [];
                for (let i = 0; i < files.length; i++) {
                    fileDataArray.push({
                        file: files[i],
                        index: i,
                        name: files[i].name,
                        type: files[i].type,
                        uniqueId: 'file_' + Date.now() + '_' + i
                    });
                }
                
                console.log('📋 File array:', fileDataArray.map(f => f.name));
                
                // Process each file
                fileDataArray.forEach(function(fileData) {
                    console.log('🔄 Processing:', fileData.name, 'ID:', fileData.uniqueId);
                    
                    var reader = new FileReader();
                    
                    reader.onload = function(e) {
                        console.log('✅ FileReader done:', fileData.name);
                        
                        var previewItem = $('<div class="file-preview-item" data-file-id="' + fileData.uniqueId + '">');
                        
                        if (fileData.type.startsWith('image/')) {
                            previewItem.append('<img src="' + e.target.result + '" alt="Preview">');
                        } else {
                            previewItem.append('<i class="dashicons dashicons-media-document"></i>');
                        }
                        
                        previewItem.append('<div class="file-name">' + fileData.name + '</div>');
                        previewItem.append('<button type="button" class="file-remove" data-filename="' + fileData.name + '" data-input-id="' + inputId + '" data-file-id="' + fileData.uniqueId + '">&times;</button>');
                        
                        preview.append(previewItem);
                        console.log('🎯 Preview created:', fileData.name);
                    };
                    
                    reader.onerror = function() {
                        console.error('❌ FileReader error:', fileData.name);
                    };
                    
                    reader.readAsDataURL(fileData.file);
                });
            });
            
            console.log('✅ Handler bound for:', inputId);
        }
        
        // Initialize all file upload handlers
        setupFilePreview('tire_images', 'tire-images-preview');
        setupFilePreview('receipt_image', 'receipt-preview');
        setupFilePreview('additional_files', 'additional-files-preview');
        
        console.log('✅ ALL handlers initialized (v4.0 - DUPLICATE PREVENTION)');
        
        // Remove file preview - Use event delegation for dynamically created elements
        $(document).on('click', '.file-remove', function() {
            var previewItem = $(this).parent();
            var filename = $(this).data('filename');
            var inputId = $(this).data('input-id');
            var fileId = $(this).data('file-id');
            var previewContainer = previewItem.closest('.file-preview');
            
            console.log('🗑️ Removing file:', filename, 'ID:', fileId);
            
            // Remove the preview item
            previewItem.remove();
            
            // If this was the only preview item, clear the input
            if (previewContainer.children().length === 0 && inputId) {
                var input = $('#' + inputId)[0];
                if (input) {
                    input.value = '';
                    console.log('🧹 Cleared input:', inputId);
                }
            }
        });
    });
    
})(jQuery);
