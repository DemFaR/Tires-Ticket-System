/**
 * Frontend JavaScript for Altalayi Ticket System
 */

(function($) {
    'use strict';
    
    // Initialize when DOM is ready
    $(document).ready(function() {
        AltalayiTicket.init();
    });
    
    window.AltalayiTicket = {
        
        /**
         * Initialize the application
         */
        init: function() {
            this.initFileUploads();
            this.initFormValidation();
            this.initMessageSystem();
            this.initProgressIndicators();
        },
        
        /**
         * Initialize file upload functionality
         */
        initFileUploads: function() {
            var self = this;
            
            // File input change handler
            $(document).on('change', 'input[type="file"]', function() {
                var input = $(this);
                var previewContainer = input.closest('.form-group').find('.file-preview, .file-preview-grid');
                
                if (previewContainer.length === 0) {
                    previewContainer = $('<div class="file-preview-grid"></div>');
                    input.closest('.form-group').append(previewContainer);
                }
                
                self.handleFileSelection(this.files, previewContainer, input);
            });
            
            // Drag and drop handlers
            $(document).on('dragover dragenter', '.file-upload-area', function(e) {
                e.preventDefault();
                e.stopPropagation();
                $(this).addClass('dragover');
            });
            
            $(document).on('dragleave', '.file-upload-area', function(e) {
                e.preventDefault();
                e.stopPropagation();
                $(this).removeClass('dragover');
            });
            
            $(document).on('drop', '.file-upload-area', function(e) {
                e.preventDefault();
                e.stopPropagation();
                $(this).removeClass('dragover');
                
                var files = e.originalEvent.dataTransfer.files;
                var input = $(this).find('input[type="file"]')[0];
                var previewContainer = $(this).closest('.form-group').find('.file-preview, .file-preview-grid');
                
                if (previewContainer.length === 0) {
                    previewContainer = $('<div class="file-preview-grid"></div>');
                    $(this).closest('.form-group').append(previewContainer);
                }
                
                // Set files to input
                input.files = files;
                
                self.handleFileSelection(files, previewContainer, $(input));
            });
            
            // File remove handler
            $(document).on('click', '.file-remove-btn', function() {
                $(this).closest('.file-preview-item').remove();
            });
        },
        
        /**
         * Handle file selection and preview
         */
        handleFileSelection: function(files, container, input) {
            var self = this;
            
            // Clear existing previews if single file input
            if (!input.prop('multiple')) {
                container.empty();
            }
            
            for (var i = 0; i < files.length; i++) {
                var file = files[i];
                
                // Validate file
                if (!this.validateFile(file)) {
                    continue;
                }
                
                // Create preview item
                var previewItem = this.createFilePreviewItem(file);
                container.append(previewItem);
                
                // Generate preview for images
                if (file.type.startsWith('image/')) {
                    this.generateImagePreview(file, previewItem);
                }
            }
        },
        
        /**
         * Validate file
         */
        validateFile: function(file) {
            var maxSize = 5 * 1024 * 1024; // 5MB
            var allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'application/pdf'];
            
            if (file.size > maxSize) {
                this.showMessage('File "' + file.name + '" is too large. Maximum size is 5MB.', 'error');
                return false;
            }
            
            if (!allowedTypes.includes(file.type)) {
                this.showMessage('File "' + file.name + '" is not a supported format.', 'error');
                return false;
            }
            
            return true;
        },
        
        /**
         * Create file preview item
         */
        createFilePreviewItem: function(file) {
            var item = $('<div class="file-preview-item">');
            var removeBtn = $('<button type="button" class="file-remove-btn">&times;</button>');
            var fileName = $('<div class="file-name">').text(file.name);
            var fileSize = $('<div class="file-size">').text(this.formatFileSize(file.size));
            
            item.append(removeBtn);
            item.append(fileName);
            item.append(fileSize);
            
            return item;
        },
        
        /**
         * Generate image preview
         */
        generateImagePreview: function(file, previewItem) {
            var reader = new FileReader();
            
            reader.onload = function(e) {
                var img = $('<img>').attr('src', e.target.result);
                previewItem.prepend(img);
            };
            
            reader.readAsDataURL(file);
        },
        
        /**
         * Format file size
         */
        formatFileSize: function(bytes) {
            if (bytes === 0) return '0 Bytes';
            
            var k = 1024;
            var sizes = ['Bytes', 'KB', 'MB', 'GB'];
            var i = Math.floor(Math.log(bytes) / Math.log(k));
            
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        },
        
        /**
         * Initialize form validation
         */
        initFormValidation: function() {
            var self = this;
            
            // Real-time validation
            $(document).on('blur', '.altalayi-form-input, .altalayi-form-textarea', function() {
                self.validateField($(this));
            });
            
            // Form submission validation
            $(document).on('submit', 'form[data-altalayi-form]', function(e) {
                var form = $(this);
                var isValid = self.validateForm(form);
                
                if (!isValid) {
                    e.preventDefault();
                    self.showMessage('Please correct the errors in the form.', 'error');
                }
            });
        },
        
        /**
         * Validate individual field
         */
        validateField: function(field) {
            var value = field.val().trim();
            var isRequired = field.prop('required');
            var fieldType = field.attr('type') || field.prop('tagName').toLowerCase();
            var errorContainer = field.siblings('.altalayi-form-error');
            
            // Remove existing error
            errorContainer.remove();
            field.removeClass('error');
            
            // Required field validation
            if (isRequired && !value) {
                this.showFieldError(field, 'This field is required.');
                return false;
            }
            
            // Email validation
            if (fieldType === 'email' && value) {
                var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailPattern.test(value)) {
                    this.showFieldError(field, 'Please enter a valid email address.');
                    return false;
                }
            }
            
            // Phone validation
            if (fieldType === 'tel' && value) {
                var phonePattern = /^[\+]?[0-9\s\-\(\)]{10,}$/;
                if (!phonePattern.test(value)) {
                    this.showFieldError(field, 'Please enter a valid phone number.');
                    return false;
                }
            }
            
            return true;
        },
        
        /**
         * Validate entire form
         */
        validateForm: function(form) {
            var self = this;
            var isValid = true;
            
            form.find('.altalayi-form-input, .altalayi-form-textarea').each(function() {
                if (!self.validateField($(this))) {
                    isValid = false;
                }
            });
            
            return isValid;
        },
        
        /**
         * Show field error
         */
        showFieldError: function(field, message) {
            field.addClass('error');
            var errorElement = $('<span class="altalayi-form-error">').text(message);
            field.after(errorElement);
        },
        
        /**
         * Initialize message system
         */
        initMessageSystem: function() {
            // Auto-hide success messages
            setTimeout(function() {
                $('.altalayi-message.success').slideUp();
            }, 5000);
            
            // Message close handlers
            $(document).on('click', '.altalayi-message [data-dismiss]', function() {
                $(this).closest('.altalayi-message').slideUp();
            });
        },
        
        /**
         * Show message
         */
        showMessage: function(message, type, container) {
            type = type || 'info';
            container = container || '#altalayi-messages, .altalayi-messages';
            
            var messageElement = $('<div class="altalayi-message ' + type + '">')
                .html('<div class="altalayi-message-content">' + message + '</div>');
            
            $(container).empty().append(messageElement);
            
            // Scroll to message
            $('html, body').animate({
                scrollTop: messageElement.offset().top - 100
            }, 500);
            
            // Auto-hide after 5 seconds for success messages
            if (type === 'success') {
                setTimeout(function() {
                    messageElement.slideUp();
                }, 5000);
            }
        },
        
        /**
         * Initialize progress indicators
         */
        initProgressIndicators: function() {
            // Animate progress bars on page load
            $('.altalayi-progress-bar').each(function() {
                var bar = $(this);
                var width = bar.data('width') || bar.attr('style').match(/width:\s*(\d+)%/);
                
                if (width) {
                    bar.css('width', '0%');
                    setTimeout(function() {
                        bar.css('width', width[1] + '%');
                    }, 500);
                }
            });
        },
        
        /**
         * AJAX helper
         */
        ajax: function(action, data, options) {
            options = options || {};
            
            var ajaxData = {
                action: action,
                nonce: altalayi_ajax.nonce
            };
            
            if (data) {
                if (data instanceof FormData) {
                    data.append('action', action);
                    data.append('nonce', altalayi_ajax.nonce);
                    ajaxData = data;
                } else {
                    $.extend(ajaxData, data);
                }
            }
            
            var ajaxOptions = {
                url: altalayi_ajax.ajax_url,
                type: 'POST',
                data: ajaxData,
                success: options.success || function() {},
                error: options.error || function() {},
                complete: options.complete || function() {}
            };
            
            // Handle FormData
            if (data instanceof FormData) {
                ajaxOptions.processData = false;
                ajaxOptions.contentType = false;
            }
            
            return $.ajax(ajaxOptions);
        },
        
        /**
         * Show loading state on button
         */
        showButtonLoading: function(button) {
            var btn = $(button);
            btn.prop('disabled', true);
            btn.find('.btn-text').hide();
            btn.find('.btn-loading').show();
            
            // Add loading class if no loading element exists
            if (btn.find('.btn-loading').length === 0) {
                btn.prepend('<span class="altalayi-loading"></span>');
            }
        },
        
        /**
         * Hide loading state on button
         */
        hideButtonLoading: function(button) {
            var btn = $(button);
            btn.prop('disabled', false);
            btn.find('.btn-text').show();
            btn.find('.btn-loading').hide();
            btn.find('.altalayi-loading').remove();
        },
        
        /**
         * Format date for display
         */
        formatDate: function(dateString, format) {
            var date = new Date(dateString);
            
            if (format === 'relative') {
                var now = new Date();
                var diff = now - date;
                var days = Math.floor(diff / (1000 * 60 * 60 * 24));
                
                if (days === 0) {
                    return 'Today';
                } else if (days === 1) {
                    return 'Yesterday';
                } else if (days < 7) {
                    return days + ' days ago';
                } else {
                    return date.toLocaleDateString();
                }
            }
            
            return date.toLocaleDateString();
        },
        
        /**
         * Debounce function
         */
        debounce: function(func, wait) {
            var timeout;
            return function executedFunction() {
                var context = this;
                var args = arguments;
                var later = function() {
                    timeout = null;
                    func.apply(context, args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }
    };
    
})(jQuery);
