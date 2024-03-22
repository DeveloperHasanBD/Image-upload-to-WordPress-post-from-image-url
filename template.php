<?php 

/**
 * template name: Image upload
 */

// Include WordPress core files for media handling
require_once(ABSPATH . 'wp-admin/includes/media.php');
require_once(ABSPATH . 'wp-admin/includes/file.php');
require_once(ABSPATH . 'wp-admin/includes/image.php');

// Specify the external image URL
$image_url = 'https://safety.it/wp-content/uploads/2022/09/18920_2020_400x400.jpg';

// Get the post ID to which you want to attach the image
$post_id = 8; // Replace with the ID of the post

// Check if the post ID exists and is valid
if ($post_id && get_post_status($post_id)) {
    // Check if an attachment with the same URL already exists in the media library
    $existing_attachment_id = attachment_url_to_postid($image_url);

    // If an existing attachment is found, use its ID
    if ($existing_attachment_id) {
        // Attach the existing image to the post
        set_post_thumbnail($post_id, $existing_attachment_id);
        echo 'Existing image attached to the post successfully!';
    } else {
        // Download the image from the external URL
        $image_data = file_get_contents($image_url);

        // Check if the image data was fetched successfully
        if ($image_data !== false) {
            // Upload the image to the media library and attach to the post
            $attachment_id = media_sideload_image($image_url, $post_id, '', 'id');

            // Check if the attachment ID is not a WP_Error object
            if (!is_wp_error($attachment_id)) {
                // Set the image as the featured image (post thumbnail)
                set_post_thumbnail($post_id, $attachment_id);
                echo 'New image uploaded and attached to the post!';
            } else {
                echo 'Error uploading image: ' . $attachment_id->get_error_message();
            }
        } else {
            echo 'Failed to fetch the image data from the URL.';
        }
    }
} else {
    echo 'Invalid post ID or post does not exist.';
}
?>
