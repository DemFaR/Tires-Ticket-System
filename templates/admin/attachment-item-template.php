<?php
/**
 * Attachment Item Template
 */
$file_extension = pathinfo($attachment->file_name, PATHINFO_EXTENSION);
$is_image = in_array(strtolower($file_extension), array('jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'tiff', 'tif'));
$file_url = $attachment->file_path;
?>

<div class="attachment-item">
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
        <div class="attachment-name">
            <?php echo esc_html($attachment->file_name); ?>
        </div>
        <div class="attachment-meta">
            <small><?php echo esc_html(altalayi_format_file_size($attachment->file_size)); ?></small>
        </div>
        <div class="attachment-actions">
            <a href="<?php echo esc_url($file_url); ?>" 
               target="_blank" class="button button-small">
                <?php _e('View', 'altalayi-ticket'); ?>
            </a>
            <a href="<?php echo esc_url(altalayi_get_attachment_download_url($attachment->id)); ?>" 
               class="button button-small">
                <?php _e('Download', 'altalayi-ticket'); ?>
            </a>
        </div>
    </div>
</div>
