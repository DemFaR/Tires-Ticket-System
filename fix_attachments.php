<?php
// Quick fix for misclassified attachments
if (!defined('ABSPATH')) {
    // Adjust this path to your WordPress installation
    define('ABSPATH', '/var/www/html/wordpress/');
    require_once ABSPATH . 'wp-config.php';
}

global $wpdb;
$table = $wpdb->prefix . 'altalayi_ticket_attachments';

echo "Fixing attachment classifications...\n";

// Fix tire images (contains tire-related keywords in filename)
$tire_patterns = array('tire', 'dueler', 'wheel', 'tyre');
$updates = 0;

foreach ($tire_patterns as $pattern) {
    $result = $wpdb->query(
        $wpdb->prepare(
            "UPDATE {$table} SET attachment_type = %s WHERE file_name LIKE %s AND attachment_type != %s",
            'tire_image',
            "%{$pattern}%",
            'tire_image'
        )
    );
    if ($result) {
        echo "Updated {$result} files containing '{$pattern}' to tire_image type\n";
        $updates += $result;
    }
}

// Check current state
$attachments = $wpdb->get_results("SELECT id, file_name, attachment_type FROM {$table}");
echo "\nCurrent attachments:\n";
foreach ($attachments as $att) {
    echo "ID: {$att->id}, File: {$att->file_name}, Type: {$att->attachment_type}\n";
}

echo "\nTotal updates made: {$updates}\n";
?>
