<?php
/**
 * Bird Net - Astra Child Theme Functions
 */

// Enqueue parent and child theme styles
function birdnet_enqueue_styles() {
    wp_enqueue_style('astra-parent-style', get_template_directory_uri() . '/style.css');
    wp_enqueue_style('birdnet-child-style', get_stylesheet_directory_uri() . '/style.css', array('astra-parent-style'), wp_get_theme()->get('Version'));
    
    // Google Fonts - Prompt + Inter + Noto Sans Thai
    wp_enqueue_style('google-fonts-brand', 'https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600&family=Noto+Sans+Thai:wght@300;400;500;600;700&display=swap', array(), null);
    
    // AOS - Animate on Scroll library
    wp_enqueue_style('aos-css', 'https://unpkg.com/aos@2.3.4/dist/aos.css', array(), '2.3.4');
    wp_enqueue_script('aos-js', 'https://unpkg.com/aos@2.3.4/dist/aos.js', array(), '2.3.4', true);
}
add_action('wp_enqueue_scripts', 'birdnet_enqueue_styles');

// Hide page title on front page
function birdnet_hide_front_page_title() {
    if (is_front_page()) {
        echo '<style>.entry-header, .page-header, article > header { display: none !important; }</style>';
    }
}
add_action('wp_head', 'birdnet_hide_front_page_title');

// Add preconnect for faster font loading
function birdnet_preconnect_fonts() {
    echo '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
}
add_action('wp_head', 'birdnet_preconnect_fonts', 0);

// Add Line Chat floating button
function birdnet_floating_buttons() {
    $line_id = get_option('birdnet_line_id', 'oil_phanu');
    $phone = get_option('birdnet_phone', '0629964994');
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
        width: auto;
        height: 48px;
        border-radius: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 0 16px;
        color: #fff;
        text-decoration: none;
        font-size: 14px;
        font-weight: 600;
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .birdnet-float-btn:hover {
        transform: scale(1.05);
        box-shadow: 0 6px 16px rgba(0,0,0,0.3);
        color: #fff;
    }
    .birdnet-float-btn.line {
        background: #06C755;
    }
    .birdnet-float-btn.phone {
        background: #E8792E;
    }
    .birdnet-float-btn svg {
        width: 22px;
        height: 22px;
        fill: currentColor;
    }
    @media (max-width: 768px) {
        .birdnet-floating {
            bottom: 15px;
            right: 15px;
        }
        .birdnet-float-btn {
            height: 44px;
            padding: 0 14px;
            font-size: 13px;
        }
    }
    </style>
    <div class="birdnet-floating">
        <a href="https://line.me/ti/p/~<?php echo esc_attr($line_id); ?>" class="birdnet-float-btn line" target="_blank" rel="noopener" aria-label="Line Chat" title="แชท Line">
            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12 2C6.48 2 2 5.82 2 10.5c0 2.95 1.95 5.55 4.87 7.13-.19.66-.68 2.37-.78 2.73-.13.47.17.46.36.34.15-.1 2.37-1.61 3.33-2.26.73.1 1.47.16 2.22.16 5.52 0 10-3.82 10-8.5S17.52 2 12 2z"/></svg>
            LINE
        </a>
        <a href="tel:<?php echo esc_attr($phone); ?>" class="birdnet-float-btn phone" aria-label="โทรศัพท์" title="โทรเลย">
            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M6.62 10.79a15.053 15.053 0 006.59 6.59l2.2-2.2a1.003 1.003 0 011.01-.24c1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/></svg>
            โทรเลย
        </a>
    </div>
    <div class="birdnet-sticky-mobile">
        <a href="/contact" class="sticky-cta-quote">ประเมินราคาฟรี</a>
        <a href="https://line.me/ti/p/~<?php echo esc_attr($line_id); ?>" class="sticky-cta-line" target="_blank" rel="noopener">Line</a>
        <a href="tel:<?php echo esc_attr($phone); ?>" class="sticky-cta-call">โทร</a>
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

    $phone = get_option('birdnet_phone', '0629964994');
    $line_id = get_option('birdnet_line_id', 'oil_phanu');
    $email = get_option('birdnet_email', 'admin@birdsgoaway.com');
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

// Output schema.org JSON-LD on front page
function birdnet_schema_jsonld() {
    if (is_front_page()) {
        $schema = get_post_meta(get_the_ID(), '_schema_json_ld', true);
        if ($schema) {
            echo '<script type="application/ld+json">' . $schema . '</script>' . "\n";
        }
    }
    // FAQ Schema on FAQ page
    if (is_page('faq')) {
        $faq_schema = json_encode(array(
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => array(
                array('@type' => 'Question', 'name' => 'ตาข่ายกันนก HDPE มีอายุการใช้งานกี่ปี?', 'acceptedAnswer' => array('@type' => 'Answer', 'text' => 'ตาข่าย HDPE มีอายุการใช้งาน 6-7 ปี ผ่านการ UV Treatment ทนแดด ทนฝน พร้อมรับประกัน 3 ปี')),
                array('@type' => 'Question', 'name' => 'ค่าบริการติดตั้งเริ่มต้นเท่าไหร่?', 'acceptedAnswer' => array('@type' => 'Answer', 'text' => 'ค่าบริการขึ้นอยู่กับพื้นที่และความยากง่าย เราให้บริการประเมินราคาฟรี โทร 062-996-4994')),
                array('@type' => 'Question', 'name' => 'ให้บริการพื้นที่ไหนบ้าง?', 'acceptedAnswer' => array('@type' => 'Answer', 'text' => 'ขอนแก่น เชียงใหม่ ชลบุรี และจังหวัดใกล้เคียง')),
                array('@type' => 'Question', 'name' => 'มีรับประกันหลังติดตั้งไหม?', 'acceptedAnswer' => array('@type' => 'Answer', 'text' => 'มีรับประกันงานติดตั้ง 3 ปี หากพบปัญหา ทีมงานเข้าแก้ไขฟรี')),
                array('@type' => 'Question', 'name' => 'ติดตั้งแล้วนกจะเจ็บไหม?', 'acceptedAnswer' => array('@type' => 'Answer', 'text' => 'ไม่เจ็บ ทุกวิธีเป็นสันติวิธี 100% ไม่ทำร้ายและไม่ฆ่านก ปลอดภัยต่อนก คน และสัตว์เลี้ยง')),
                array('@type' => 'Question', 'name' => 'ติดตั้งในกรุงเทพหรือต่างจังหวัดได้ไหม?', 'acceptedAnswer' => array('@type' => 'Answer', 'text' => 'บริการทั่วประเทศ มีสำนักงานที่ขอนแก่น เชียงใหม่ ชลบุรี ครอบคลุมภาคอีสาน ภาคเหนือ ภาคตะวันออก และกรุงเทพฯ')),
            ),
        ), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        echo '<script type="application/ld+json">' . $faq_schema . '</script>' . "\n";
    }
}
add_action('wp_head', 'birdnet_schema_jsonld', 1);

// Add custom logo support
function birdnet_theme_support() {
    add_theme_support('custom-logo', array(
        'height'      => 80,
        'width'       => 240,
        'flex-height' => true,
        'flex-width'  => true,
    ));
}
add_action('after_setup_theme', 'birdnet_theme_support');

// Initialize AOS (Animate on Scroll)
function birdnet_aos_init() {
    echo '<script>document.addEventListener("DOMContentLoaded", function() { if (typeof AOS !== "undefined") { AOS.init({ duration: 800, easing: "ease-out-cubic", once: true, offset: 80 }); } });</script>' . "\n";
}
add_action('wp_footer', 'birdnet_aos_init', 99);

// Custom Footer Content
function birdnet_custom_footer() {
    ?>
    <div class="birdnet-footer-content">
        <div class="footer-grid">
            <div class="footer-col">
                <h4>BIRDS GO AWAY</h4>
                <p>บริษัท รีเช็ค บิ้วดิ้ง จำกัด<br>
                ทะเบียน 0405567000088<br>
                75/59 ม.17 ต.ศิลา อ.เมือง<br>
                จ.ขอนแก่น 40000</p>
                <p style="margin-top:8px;font-size:0.75rem;opacity:0.6;">ใบอนุญาตโรยตัว / กว. สภาวิศวกร / จป.หัวหน้างาน</p>
            </div>
            <div class="footer-col">
                <h4>ติดต่อเรา</h4>
                <p><strong>ขอนแก่น:</strong> 062-996-4994<br>
                <strong>วีวี่:</strong> 088-951-4924<br>
                <strong>เชียงใหม่:</strong> 093-641-5623<br>
                <strong>ชลบุรี:</strong> 095-629-2488</p>
                <p style="margin-top:8px;">LINE: <a href="https://line.me/ti/p/~oil_phanu">oil_phanu</a></p>
                <p>เวลาทำการ: จ-ส 08:00-18:00</p>
            </div>
            <div class="footer-col">
                <h4>เมนู</h4>
                <ul>
                    <li><a href="/">หน้าแรก</a></li>
                    <li><a href="/services">บริการของเรา</a></li>
                    <li><a href="/portfolio">ผลงาน</a></li>
                    <li><a href="/about">เกี่ยวกับเรา</a></li>
                    <li><a href="/contact">ติดต่อเรา</a></li>
                    <li><a href="/faq">FAQ</a></li>
                    <li><a href="/blog">บทความ</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>บริการของเรา</h4>
                <ul>
                    <li>ตาข่ายกันนก HDPE</li>
                    <li>หนามกันนก สแตนเลส</li>
                    <li>เจลไล่นก</li>
                    <li>แผงกันนกโซลาร์เซลล์</li>
                </ul>
                <p style="margin-top:12px;"><strong>พื้นที่ให้บริการ:</strong><br>ขอนแก่น, เชียงใหม่, ชลบุรี, ภาคอีสาน ทั่วประเทศ</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2025 BIRDS GO AWAY — บริษัท รีเช็ค บิ้วดิ้ง จำกัด | บริการติดตั้งตาข่ายกันนก ขอนแก่น เชียงใหม่ ชลบุรี ภาคอีสาน</p>
        </div>
    </div>
    <?php
}
add_action('astra_footer_before', 'birdnet_custom_footer');

// Hide default Astra footer copyright
function birdnet_hide_default_footer() {
    echo '<style>.ast-small-footer, .site-footer .ast-footer-copyright, .ast-footer-overlay { display: none !important; }</style>';
}
add_action('wp_head', 'birdnet_hide_default_footer');

// Hide page title on all pages (not just front)
function birdnet_hide_all_page_titles() {
    echo '<style>.entry-header, .page-header, article > header, .ast-archive-description { display: none !important; }</style>';
}
add_action('wp_head', 'birdnet_hide_all_page_titles');

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

// Enable native lazy loading for all images
function birdnet_lazy_load_images($content) {
    if (is_admin()) return $content;
    $content = preg_replace('/<img((?!loading=)[^>]*)>/i', '<img$1 loading="lazy">', $content);
    return $content;
}
add_filter('the_content', 'birdnet_lazy_load_images', 99);

// Allow iframe and SVG in post content (WordPress strips them by default)
function birdnet_allow_iframes_svg($tags, $context) {
    if ($context === 'post') {
        $tags['iframe'] = array(
            'src' => true, 'width' => true, 'height' => true,
            'style' => true, 'allowfullscreen' => true, 'loading' => true,
            'referrerpolicy' => true, 'frameborder' => true,
        );
        $tags['svg'] = array(
            'width' => true, 'height' => true, 'viewbox' => true,
            'fill' => true, 'xmlns' => true, 'class' => true,
        );
        $tags['path'] = array('d' => true, 'fill' => true);
    }
    return $tags;
}
add_filter('wp_kses_allowed_html', 'birdnet_allow_iframes_svg', 10, 2);

// Add WebP support for uploads
function birdnet_webp_support($mimes) {
    $mimes['webp'] = 'image/webp';
    return $mimes;
}
add_filter('upload_mimes', 'birdnet_webp_support');

// Preload critical assets
function birdnet_preload_assets() {
    echo '<link rel="preload" as="image" href="/wp-content/uploads/birdnet-assets/fb-cover.webp">' . "\n";
    echo '<link rel="icon" type="image/x-icon" href="/wp-content/uploads/birdnet-assets/favicon.ico">' . "\n";
    echo '<link rel="apple-touch-icon" href="/wp-content/uploads/birdnet-assets/apple-touch-icon.png">' . "\n";
    echo '<link rel="dns-prefetch" href="//www.google.com">' . "\n";
    echo '<link rel="dns-prefetch" href="//www.googletagmanager.com">' . "\n";
}
add_action('wp_head', 'birdnet_preload_assets', 1);

// FAQ removed from nav — causes overflow on desktop. Accessible via /faq/ URL and footer link.

// Force all nav menus (including mobile hamburger) to use "Main Menu"
function birdnet_fix_mobile_menu($args) {
    if (!empty($args['theme_location']) && $args['theme_location'] !== 'primary') {
        $args['menu'] = 'Main Menu';
        $args['theme_location'] = '';
    } elseif (empty($args['menu']) && empty($args['theme_location'])) {
        $args['menu'] = 'Main Menu';
    }
    return $args;
}
add_filter('wp_nav_menu_args', 'birdnet_fix_mobile_menu');

// JS fallback: sync mobile menu with desktop primary nav
function birdnet_mobile_menu_sync_js() {
    echo '<script>
    document.addEventListener("DOMContentLoaded", function() {
        var primary = document.querySelector("nav[aria-label=\'Primary Site Navigation\'] ul");
        var mobile = document.querySelector("nav[aria-label=\'Site Navigation\'] ul");
        if (primary && mobile && mobile.children.length !== primary.children.length) {
            mobile.innerHTML = primary.innerHTML;
        }
        var observer = new MutationObserver(function() {
            var m = document.querySelector("nav[aria-label=\'Site Navigation\'] ul");
            var p = document.querySelector("nav[aria-label=\'Primary Site Navigation\'] ul");
            if (p && m && m.children.length !== p.children.length) m.innerHTML = p.innerHTML;
        });
        observer.observe(document.body, {childList:true, subtree:true});
        setTimeout(function(){observer.disconnect();}, 5000);
    });
    </script>';
}
add_action('wp_footer', 'birdnet_mobile_menu_sync_js');

// Ensure logo displays — JS fallback for broken image (ephemeral Docker uploads)
function birdnet_logo_fallback() {
    $fallback_url = home_url('/wp-content/uploads/birdnet-assets/logo-white.jpg');
    echo '<script>
    document.addEventListener("DOMContentLoaded", function() {
        var logos = document.querySelectorAll(".custom-logo, .ast-site-identity img");
        logos.forEach(function(img) {
            img.onerror = function() {
                this.onerror = null;
                this.src = "' . esc_url($fallback_url) . '";
            };
            if (img.complete && img.naturalWidth === 0) {
                img.src = "' . esc_url($fallback_url) . '";
            }
        });
    });
    </script>';
}
add_action('wp_head', 'birdnet_logo_fallback');
