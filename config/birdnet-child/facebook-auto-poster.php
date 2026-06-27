<?php
/**
 * Facebook Auto Poster for Birds Go Away
 */

// 1. Create Settings Page
add_action('admin_menu', 'birdnet_fb_poster_menu');
function birdnet_fb_poster_menu() {
    add_options_page(
        'Facebook Auto Poster',
        'Facebook Sync',
        'manage_options',
        'birdnet-fb-poster',
        'birdnet_fb_poster_settings_page'
    );
}

add_action('admin_init', 'birdnet_fb_poster_settings');
function birdnet_fb_poster_settings() {
    register_setting('birdnet_fb_settings', 'birdnet_fb_page_id');
    register_setting('birdnet_fb_settings', 'birdnet_fb_access_token');
}

function birdnet_fb_poster_settings_page() {
    ?>
    <div class="wrap">
        <h1>ตั้งค่าระบบดึงข้อมูลจาก Facebook (Facebook Auto Poster)</h1>
        
        <?php
        if (isset($_POST['sync_now']) && check_admin_referer('birdnet_fb_sync_now')) {
            $result = birdnet_sync_facebook_posts();
            echo '<div class="notice notice-success is-dismissible"><p><strong>ซิงค์ข้อมูลสำเร็จ:</strong> ดึงมาได้ ' . intval($result['imported']) . ' โพสต์ใหม่ (ข้ามโพสต์ซ้ำ ' . intval($result['skipped']) . ' โพสต์)</p></div>';
        }
        ?>

        <form method="post" action="options.php">
            <?php settings_fields('birdnet_fb_settings'); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Facebook Page ID</th>
                    <td><input type="text" name="birdnet_fb_page_id" value="<?php echo esc_attr(get_option('birdnet_fb_page_id')); ?>" class="regular-text" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Page Access Token (Long-lived)</th>
                    <td><input type="password" name="birdnet_fb_access_token" value="<?php echo esc_attr(get_option('birdnet_fb_access_token')); ?>" class="regular-text" /></td>
                </tr>
            </table>
            <?php submit_button('บันทึกการตั้งค่า'); ?>
        </form>

        <hr>
        <h2>ทดสอบดึงข้อมูล (Manual Sync)</h2>
        <p>คุณสามารถกดปุ่มนี้เพื่อดึงโพสต์ล่าสุดจากเฟสบุ๊คเข้ามาทันที โดยไม่ต้องรอรอบอัตโนมัติ (รอบอัตโนมัติคือ 1 ชั่วโมง)</p>
        <form method="post" action="">
            <?php wp_nonce_field('birdnet_fb_sync_now'); ?>
            <input type="submit" name="sync_now" class="button button-primary" value="ดึงข้อมูลจาก Facebook ทันที">
        </form>
    </div>
    <?php
}

// 2. WP-Cron Setup
add_action('wp', 'birdnet_setup_fb_cron');
function birdnet_setup_fb_cron() {
    if (!wp_next_scheduled('birdnet_fb_hourly_sync')) {
        wp_schedule_event(time(), 'hourly', 'birdnet_fb_hourly_sync');
    }
}

add_action('birdnet_fb_hourly_sync', 'birdnet_sync_facebook_posts');

// 3. Main Sync Function
function birdnet_sync_facebook_posts() {
    $page_id = get_option('birdnet_fb_page_id');
    $token = get_option('birdnet_fb_access_token');

    if (empty($page_id) || empty($token)) {
        return ['imported' => 0, 'skipped' => 0];
    }

    $api_url = "https://graph.facebook.com/v19.0/{$page_id}/posts?fields=id,message,full_picture,created_time,permalink_url&limit=10&access_token={$token}";
    
    $response = wp_remote_get($api_url);
    if (is_wp_error($response)) {
        return ['imported' => 0, 'skipped' => 0];
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    if (empty($data['data'])) {
        return ['imported' => 0, 'skipped' => 0];
    }

    $imported = 0;
    $skipped = 0;

    foreach ($data['data'] as $post) {
        $fb_post_id = $post['id'];
        $message = isset($post['message']) ? $post['message'] : '';
        $picture = isset($post['full_picture']) ? $post['full_picture'] : '';
        $permalink = isset($post['permalink_url']) ? $post['permalink_url'] : '';
        
        // Skip if no text
        if (empty($message)) {
            $skipped++;
            continue;
        }

        // Check if post already exists
        $existing = get_posts([
            'meta_key' => 'fb_post_id',
            'meta_value' => $fb_post_id,
            'post_type' => 'post',
            'post_status' => 'any',
            'posts_per_page' => 1
        ]);

        if (!empty($existing)) {
            $skipped++;
            continue; // Post exists
        }

        // Create Title from first 50 chars of message
        $title = wp_trim_words($message, 10, '...');
        if (mb_strlen($title) > 60) {
            $title = mb_substr($title, 0, 57) . '...';
        }

        // Create Content
        $content = nl2br(esc_html($message));
        $content .= "\n\n<p><a href='" . esc_url($permalink) . "' target='_blank'>ดูโพสต์ต้นฉบับบน Facebook</a></p>";

        // Insert Post
        $post_data = [
            'post_title'    => $title,
            'post_content'  => $content,
            'post_status'   => 'publish',
            'post_author'   => 1,
            'post_type'     => 'post',
            'meta_input'    => [
                'fb_post_id' => $fb_post_id
            ]
        ];

        // Use created_time if available
        if (!empty($post['created_time'])) {
            $post_data['post_date'] = date('Y-m-d H:i:s', strtotime($post['created_time']));
        }

        $new_post_id = wp_insert_post($post_data);

        if ($new_post_id && !is_wp_error($new_post_id)) {
            $imported++;
            
            // Handle Featured Image
            if (!empty($picture)) {
                birdnet_sideload_image($picture, $new_post_id, "รูปภาพจาก Facebook");
            }
        } else {
            $skipped++;
        }
    }

    return ['imported' => $imported, 'skipped' => $skipped];
}

// 4. Image Sideload Helper
function birdnet_sideload_image($url, $post_id, $desc = null) {
    if (!function_exists('media_handle_sideload')) {
        require_once(ABSPATH . 'wp-admin/includes/media.php');
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/image.php');
    }

    // Give it a fake name based on post ID
    $tmp = download_url($url);
    if (is_wp_error($tmp)) return false;

    $file_array = [
        'name'     => 'fb-post-' . $post_id . '.jpg',
        'tmp_name' => $tmp
    ];

    $id = media_handle_sideload($file_array, $post_id, $desc);
    if (is_wp_error($id)) {
        @unlink($file_array['tmp_name']);
        return false;
    }

    set_post_thumbnail($post_id, $id);
    return $id;
}
