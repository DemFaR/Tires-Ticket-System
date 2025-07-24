<script src="<?php echo plugin_dir_url(dirname(__DIR__)) . 'assets/js/file-upload-v4.js?v=' . time(); ?>"></script>

<script>
// Form submission handler
jQuery(document).ready(function($) {
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
