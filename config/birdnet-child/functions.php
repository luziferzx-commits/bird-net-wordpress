<?php
/**
 * Bird Net - Astra Child Theme Functions
 */

// Enqueue parent and child theme styles
function birdnet_enqueue_styles() {
    wp_enqueue_style('astra-parent-style', get_template_directory_uri() . '/style.css');
    wp_enqueue_style('birdnet-child-style', get_stylesheet_directory_uri() . '/style.css', array('astra-parent-style'), wp_get_theme()->get('Version'));
    
    // Google Fonts
    wp_enqueue_style('google-fonts-noto-thai', 'https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@300;400;500;600;700;800&display=swap', array(), null);
}
add_action('wp_enqueue_scripts', 'birdnet_enqueue_styles');

// Add Line Chat floating button
function birdnet_floating_buttons() {
    $line_id = get_option('birdnet_line_id', '@birdnet');
    $phone = get_option('birdnet_phone', '0800000000');
    ?>
    <style>
    .birdnet-floating {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 9999;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    .birdnet-float-btn {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        text-decoration: none;
        font-size: 24px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .birdnet-float-btn:hover {
        transform: scale(1.1);
        box-shadow: 0 6px 16px rgba(0,0,0,0.3);
        color: #fff;
    }
    .birdnet-float-btn.line {
        background-color: #06C755;
    }
    .birdnet-float-btn.phone {
        background-color: #1e40af;
    }
    @media (max-width: 768px) {
        .birdnet-floating {
            bottom: 15px;
            right: 15px;
        }
        .birdnet-float-btn {
            width: 48px;
            height: 48px;
            font-size: 20px;
        }
    }
    </style>
    <div class="birdnet-floating">
        <a href="https://line.me/ti/p/~<?php echo esc_attr($line_id); ?>" class="birdnet-float-btn line" target="_blank" rel="noopener" aria-label="Line Chat" title="แชท Line">
            💬
        </a>
        <a href="tel:<?php echo esc_attr($phone); ?>" class="birdnet-float-btn phone" aria-label="โทรศัพท์" title="โทรเลย">
            📞
        </a>
    </div>
    <?php
}
add_action('wp_footer', 'birdnet_floating_buttons');

// Custom admin settings for contact info
function birdnet_admin_settings() {
    add_options_page(
        'Bird Net Settings',
        'Bird Net Settings',
        'manage_options',
        'birdnet-settings',
        'birdnet_settings_page'
    );
}
add_action('admin_menu', 'birdnet_admin_settings');

function birdnet_settings_page() {
    if (isset($_POST['birdnet_save_settings']) && check_admin_referer('birdnet_settings_nonce')) {
        update_option('birdnet_phone', sanitize_text_field($_POST['birdnet_phone']));
        update_option('birdnet_line_id', sanitize_text_field($_POST['birdnet_line_id']));
        update_option('birdnet_email', sanitize_email($_POST['birdnet_email']));
        echo '<div class="updated"><p>Settings saved!</p></div>';
    }

    $phone = get_option('birdnet_phone', '0800000000');
    $line_id = get_option('birdnet_line_id', '@birdnet');
    $email = get_option('birdnet_email', 'info@birdnet.co.th');
    ?>
    <div class="wrap">
        <h1>Bird Net Settings</h1>
        <form method="post">
            <?php wp_nonce_field('birdnet_settings_nonce'); ?>
            <table class="form-table">
                <tr>
                    <th>เบอร์โทรศัพท์</th>
                    <td><input type="text" name="birdnet_phone" value="<?php echo esc_attr($phone); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th>Line ID</th>
                    <td><input type="text" name="birdnet_line_id" value="<?php echo esc_attr($line_id); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th>อีเมล</th>
                    <td><input type="email" name="birdnet_email" value="<?php echo esc_attr($email); ?>" class="regular-text"></td>
                </tr>
            </table>
            <p class="submit">
                <input type="submit" name="birdnet_save_settings" class="button-primary" value="Save Settings">
            </p>
        </form>
    </div>
    <?php
}

// Add SEO meta description
function birdnet_meta_description() {
    if (is_front_page()) {
        echo '<meta name="description" content="บริการติดตั้งตาข่ายกันนก หนามกันนก เจลไล่นก แผงกันนกโซลาร์เซลล์ มืออาชีพ ครบวงจร ราคาเป็นธรรม รับประกันผลงาน">' . "\n";
    }
}
add_action('wp_head', 'birdnet_meta_description', 1);

// Disable WordPress emoji for performance
function birdnet_disable_emojis() {
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
}
add_action('init', 'birdnet_disable_emojis');

// Remove unnecessary header items for performance
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wp_shortlink_wp_head');
