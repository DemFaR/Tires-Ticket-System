/**
 * Admin JavaScript for Altalayi Ticket System
 */

(function($) {
    'use strict';
    
    // Initialize when DOM is ready
    $(document).ready(function() {
        AltalayiAdmin.init();
    });
    
    window.AltalayiAdmin = {
        
        /**
         * Initialize the admin interface
         */
        init: function() {
            this.initTicketActions();
            this.initFilters();
            this.initBulkActions();
            this.initStatusManagement();
            this.initFileHandling();
            this.initTooltips();
        },
        
        /**
         * Initialize ticket actions
         */
        initTicketActions: function() {
            var self = this;
            
            // Status update form
            $(document).on('submit', '#update-status-form', function(e) {
                e.preventDefault();
                self.updateTicketStatus($(this));
            });
            
            // Assignment form
            $(document).on('submit', '#assign-ticket-form', function(e) {
                e.preventDefault();
                self.assignTicket($(this));
            });
            
            // Add note form
            $(document).on('submit', '#add-note-form', function(e) {
                e.preventDefault();
                self.addTicketNote($(this));
            });
            
            // Quick status update buttons
            $(document).on('click', '.quick-status-btn', function() {
                var statusId = $(this).data('status-id');
                var ticketId = $(this).data('ticket-id');
                self.quickStatusUpdate(ticketId, statusId);
            });
            
            // Quick assignment buttons
            $(document).on('click', '.quick-assign-btn', function() {
                var userId = $(this).data('user-id');
                var ticketId = $(this).data('ticket-id');
                self.quickAssignment(ticketId, userId);
            });
        },
        
        /**
         * Update ticket status
         */
        updateTicketStatus: function(form) {
            var self = this;
            var submitBtn = form.find('button[type="submit"]');
            
            this.showButtonLoading(submitBtn);
            
            $.ajax({
                url: altalayi_admin_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'altalayi_update_ticket_status',
                    ticket_id: form.find('[name="ticket_id"]').val(),
                    status_id: form.find('[name="status_id"]').val(),
                    notes: form.find('[name="notes"]').val(),
                    nonce: altalayi_admin_ajax.nonce
                },
                success: function(response) {
                    if (response.success) {
                        self.showMessage(response.data.message, 'success');
                        
                        // Update status display
                        $('.current-status').html(
                            '<span class="status-badge" style="background-color: ' + 
                            response.data.status_color + '">' + 
                            response.data.new_status + '</span>'
                        );
                        
                        // Reset form
                        form[0].reset();
                        
                        // Refresh updates
                        setTimeout(function() {
                            location.reload();
                        }, 2000);
                    } else {
                        self.showMessage(response.data.message, 'error');
                    }
                },
                error: function() {
                    self.showMessage('An error occurred. Please try again.', 'error');
                },
                complete: function() {
                    self.hideButtonLoading(submitBtn);
                }
            });
        },
        
        /**
         * Assign ticket
         */
        assignTicket: function(form) {
            var self = this;
            var submitBtn = form.find('button[type="submit"]');
            
            this.showButtonLoading(submitBtn);
            
            $.ajax({
                url: altalayi_admin_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'altalayi_assign_ticket',
                    ticket_id: form.find('[name="ticket_id"]').val(),
                    assigned_to: form.find('[name="assigned_to"]').val(),
                    nonce: altalayi_admin_ajax.nonce
                },
                success: function(response) {
                    if (response.success) {
                        self.showMessage(response.data.message, 'success');
                        
                        // Update assignment display
                        $('.assigned-user').text(response.data.assigned_user);
                        
                        // Reset form
                        form[0].reset();
                        
                        // Refresh updates
                        setTimeout(function() {
                            location.reload();
                        }, 2000);
                    } else {
                        self.showMessage(response.data.message, 'error');
                    }
                },
                error: function() {
                    self.showMessage('An error occurred. Please try again.', 'error');
                },
                complete: function() {
                    self.hideButtonLoading(submitBtn);
                }
            });
        },
        
        /**
         * Add ticket note
         */
        addTicketNote: function(form) {
            var self = this;
            var submitBtn = form.find('button[type="submit"]');
            
            this.showButtonLoading(submitBtn);
            
            $.ajax({
                url: altalayi_admin_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'altalayi_add_ticket_note',
                    ticket_id: form.find('[name="ticket_id"]').val(),
                    note: form.find('[name="note"]').val(),
                    visible_to_customer: form.find('[name="visible_to_customer"]').is(':checked') ? 1 : 0,
                    nonce: altalayi_admin_ajax.nonce
                },
                success: function(response) {
                    if (response.success) {
                        self.showMessage(response.data.message, 'success');
                        
                        // Add new note to timeline
                        if (response.data.note_html) {
                            $('.updates-timeline').append(response.data.note_html);
                        }
                        
                        // Reset form
                        form[0].reset();
                    } else {
                        self.showMessage(response.data.message, 'error');
                    }
                },
                error: function() {
                    self.showMessage('An error occurred. Please try again.', 'error');
                },
                complete: function() {
                    self.hideButtonLoading(submitBtn);
                }
            });
        },
        
        /**
         * Quick status update
         */
        quickStatusUpdate: function(ticketId, statusId) {
            var self = this;
            
            if (!confirm('Are you sure you want to update the status of this ticket?')) {
                return;
            }
            
            $.ajax({
                url: altalayi_admin_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'altalayi_update_ticket_status',
                    ticket_id: ticketId,
                    status_id: statusId,
                    notes: '',
                    nonce: altalayi_admin_ajax.nonce
                },
                success: function(response) {
                    if (response.success) {
                        self.showMessage(response.data.message, 'success');
                        location.reload();
                    } else {
                        self.showMessage(response.data.message, 'error');
                    }
                },
                error: function() {
                    self.showMessage('An error occurred. Please try again.', 'error');
                }
            });
        },
        
        /**
         * Quick assignment
         */
        quickAssignment: function(ticketId, userId) {
            var self = this;
            
            if (!confirm('Are you sure you want to assign this ticket?')) {
                return;
            }
            
            $.ajax({
                url: altalayi_admin_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'altalayi_assign_ticket',
                    ticket_id: ticketId,
                    assigned_to: userId,
                    nonce: altalayi_admin_ajax.nonce
                },
                success: function(response) {
                    if (response.success) {
                        self.showMessage(response.data.message, 'success');
                        location.reload();
                    } else {
                        self.showMessage(response.data.message, 'error');
                    }
                },
                error: function() {
                    self.showMessage('An error occurred. Please try again.', 'error');
                }
            });
        },
        
        /**
         * Initialize filters
         */
        initFilters: function() {
            var self = this;
            
            // Filter form submission
            $(document).on('submit', '.altalayi-filters form', function(e) {
                e.preventDefault();
                self.applyFilters($(this));
            });
            
            // Clear filters
            $(document).on('click', '.clear-filters', function() {
                $('.altalayi-filters form')[0].reset();
                $('.altalayi-filters form').submit();
            });
            
            // Date range picker
            if ($.fn.datepicker) {
                $('.date-picker').datepicker({
                    dateFormat: 'yy-mm-dd',
                    changeMonth: true,
                    changeYear: true
                });
            }
        },
        
        /**
         * Apply filters
         */
        applyFilters: function(form) {
            var filterData = form.serialize();
            var currentUrl = window.location.href.split('?')[0];
            
            window.location.href = currentUrl + '?' + filterData;
        },
        
        /**
         * Initialize bulk actions
         */
        initBulkActions: function() {
            var self = this;
            
            // Select all checkboxes
            $(document).on('change', '.select-all', function() {
                $('.ticket-checkbox').prop('checked', $(this).is(':checked'));
                self.updateBulkActionsVisibility();
            });
            
            // Individual checkbox change
            $(document).on('change', '.ticket-checkbox', function() {
                self.updateBulkActionsVisibility();
            });
            
            // Bulk action execution
            $(document).on('submit', '#bulk-actions-form', function(e) {
                e.preventDefault();
                self.executeBulkAction($(this));
            });
        },
        
        /**
         * Update bulk actions visibility
         */
        updateBulkActionsVisibility: function() {
            var selectedCount = $('.ticket-checkbox:checked').length;
            var bulkActions = $('.bulk-actions');
            
            if (selectedCount > 0) {
                bulkActions.show();
                bulkActions.find('.selected-count').text(selectedCount);
            } else {
                bulkActions.hide();
            }
        },
        
        /**
         * Execute bulk action
         */
        executeBulkAction: function(form) {
            var self = this;
            var action = form.find('select[name="bulk_action"]').val();
            var selectedTickets = $('.ticket-checkbox:checked').map(function() {
                return $(this).val();
            }).get();
            
            if (!action || selectedTickets.length === 0) {
                self.showMessage('Please select an action and at least one ticket.', 'error');
                return;
            }
            
            if (!confirm('Are you sure you want to perform this action on ' + selectedTickets.length + ' tickets?')) {
                return;
            }
            
            var submitBtn = form.find('button[type="submit"]');
            self.showButtonLoading(submitBtn);
            
            $.ajax({
                url: altalayi_admin_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'altalayi_bulk_action',
                    bulk_action: action,
                    ticket_ids: selectedTickets,
                    nonce: altalayi_admin_ajax.nonce
                },
                success: function(response) {
                    if (response.success) {
                        self.showMessage(response.data.message, 'success');
                        location.reload();
                    } else {
                        self.showMessage(response.data.message, 'error');
                    }
                },
                error: function() {
                    self.showMessage('An error occurred. Please try again.', 'error');
                },
                complete: function() {
                    self.hideButtonLoading(submitBtn);
                }
            });
        },
        
        /**
         * Initialize status management
         */
        initStatusManagement: function() {
            var self = this;
            
            // Add new status
            $(document).on('submit', '#add-status-form', function(e) {
                e.preventDefault();
                self.addStatus($(this));
            });
            
            // Edit status
            $(document).on('click', '.edit-status', function() {
                var statusId = $(this).data('status-id');
                self.editStatus(statusId);
            });
            
            // Delete status
            $(document).on('click', '.delete-status', function() {
                var statusId = $(this).data('status-id');
                self.deleteStatus(statusId);
            });
            
            // Color picker
            $(document).on('change', '.color-picker', function() {
                $(this).siblings('.color-preview').css('background-color', $(this).val());
            });
        },
        
        /**
         * Initialize file handling
         */
        initFileHandling: function() {
            // Download attachment
            $(document).on('click', '.download-attachment', function(e) {
                // Let the browser handle the download
                return true;
            });
            
            // Delete attachment
            $(document).on('click', '.delete-attachment', function() {
                if (confirm('Are you sure you want to delete this attachment?')) {
                    var attachmentId = $(this).data('attachment-id');
                    // Implement attachment deletion
                }
            });
        },
        
        /**
         * Initialize tooltips
         */
        initTooltips: function() {
            // Initialize WordPress-style tooltips
            $('.tooltip').each(function() {
                var $this = $(this);
                var title = $this.attr('title');
                
                if (title) {
                    $this.removeAttr('title');
                    
                    $this.hover(
                        function() {
                            var tooltip = $('<div class="admin-tooltip">').text(title);
                            $('body').append(tooltip);
                            
                            var offset = $this.offset();
                            tooltip.css({
                                top: offset.top - tooltip.outerHeight() - 5,
                                left: offset.left + ($this.outerWidth() / 2) - (tooltip.outerWidth() / 2)
                            });
                        },
                        function() {
                            $('.admin-tooltip').remove();
                        }
                    );
                }
            });
        },
        
        /**
         * Show message
         */
        showMessage: function(message, type) {
            type = type || 'info';
            
            var messageElement = $('<div class="altalayi-message ' + type + '">')
                .html(message)
                .hide();
            
            // Remove existing messages
            $('.altalayi-message').remove();
            
            // Add new message
            $('.wrap h1').after(messageElement);
            messageElement.slideDown();
            
            // Auto-hide success messages
            if (type === 'success') {
                setTimeout(function() {
                    messageElement.slideUp(function() {
                        $(this).remove();
                    });
                }, 5000);
            }
            
            // Scroll to message
            $('html, body').animate({
                scrollTop: messageElement.offset().top - 100
            }, 300);
        },
        
        /**
         * Show loading state on button
         */
        showButtonLoading: function(button) {
            var btn = $(button);
            btn.prop('disabled', true);
            
            var originalText = btn.text();
            btn.data('original-text', originalText);
            btn.html('<span class="spinner is-active" style="float: none; margin: 0 5px 0 0;"></span>Loading...');
        },
        
        /**
         * Hide loading state on button
         */
        hideButtonLoading: function(button) {
            var btn = $(button);
            btn.prop('disabled', false);
            
            var originalText = btn.data('original-text') || 'Submit';
            btn.html(originalText);
        },
        
        /**
         * Confirm dialog
         */
        confirm: function(message, callback) {
            if (window.confirm(message)) {
                if (typeof callback === 'function') {
                    callback();
                }
                return true;
            }
            return false;
        },
        
        /**
         * Format number with thousands separator
         */
        formatNumber: function(num) {
            return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
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
    
    // Auto-refresh dashboard statistics every 5 minutes
    if (window.location.href.indexOf('altalayi-tickets') !== -1) {
        setInterval(function() {
            $('.stat-card h3').each(function() {
                var $this = $(this);
                var currentValue = parseInt($this.text());
                
                // Add slight animation to indicate refresh
                $this.animate({opacity: 0.5}, 200).animate({opacity: 1}, 200);
            });
        }, 300000); // 5 minutes
    }
    
})(jQuery);
