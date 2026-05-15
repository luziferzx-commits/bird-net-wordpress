<?php
/**
 * Bird Net - Astra Child Theme Functions
 */

// Allow display/flex CSS properties in wp_kses inline styles
function birdnet_allow_css_properties($styles) {
    $styles[] = 'display';
    $styles[] = 'flex-direction';
    $styles[] = 'gap';
    $styles[] = 'flex-shrink';
    $styles[] = 'flex';
    $styles[] = 'justify-content';
    $styles[] = 'align-items';
    $styles[] = 'flex-wrap';
    $styles[] = 'grid-template-columns';
    return $styles;
}
add_filter('safe_style_css', 'birdnet_allow_css_properties');

// Enqueue parent and child theme styles
function birdnet_enqueue_styles() {
    wp_enqueue_style('astra-parent-style', get_template_directory_uri() . '/style.css');
    wp_enqueue_style('birdnet-child-style', get_stylesheet_directory_uri() . '/style.css', array('astra-parent-style'), wp_get_theme()->get('Version'));
    
    // Google Fonts - Prompt + Inter + Noto Sans Thai
    wp_enqueue_style('google-fonts-brand', 'https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600&family=Noto+Sans+Thai:wght@300;400;500;600;700&display=swap', array(), null);
    
    // AOS - Animate on Scroll library
    wp_enqueue_style('aos-css', 'https://unpkg.com/aos@2.3.4/dist/aos.css', array(), '2.3.4');
    wp_enqueue_script('aos-js', 'https://unpkg.com/aos@2.3.4/dist/aos.js', array(), '2.3.4', true);

    // Page-flip library for portfolio flipbook (only on portfolio pages)
    if (is_page('portfolio') || is_page('en/portfolio') || is_page(array('portfolio')) ) {
        wp_enqueue_script('page-flip-js', 'https://cdn.jsdelivr.net/npm/page-flip@2.0.7/dist/js/page-flip.browser.js', array(), '2.0.7', true);
    }
}
add_action('wp_enqueue_scripts', 'birdnet_enqueue_styles');

// Hide page title on front page
function birdnet_hide_front_page_title() {
    if (is_front_page()) {
        echo '<style>.entry-header, .page-header, article > header { display: none !important; }</style>';
    }
}
add_action('wp_head', 'birdnet_hide_front_page_title');

// Language detection helper
function birdnet_is_english() {
    $uri = $_SERVER['REQUEST_URI'] ?? '';
    return (strpos($uri, '/en/') === 0 || $uri === '/en');
}

// Language page mapping
function birdnet_get_lang_urls() {
    $uri = $_SERVER['REQUEST_URI'] ?? '';
    $is_en = birdnet_is_english();
    $map = array(
        '/'           => '/en/home',
        '/services/'  => '/en/services',
        '/portfolio/' => '/en/portfolio',
        '/about/'     => '/en/about',
        '/contact/'   => '/en/contact',
        '/faq/'       => '/en/faq',
        '/en/home'    => '/',
        '/en/services' => '/services/',
        '/en/portfolio' => '/portfolio/',
        '/en/about'   => '/about/',
        '/en/contact' => '/contact/',
        '/en/faq'     => '/faq/',
    );
    $clean = rtrim(strtok($uri, '?'), '/');
    if ($clean === '') $clean = '/';
    else $clean .= '/';
    $clean_no_slash = rtrim($clean, '/');
    $th_url = $is_en ? ($map[$clean_no_slash] ?? '/') : $uri;
    $en_url = $is_en ? $uri : ($map[$clean] ?? ($map[$clean_no_slash] ?? '/en/home'));
    return array('th' => $th_url, 'en' => $en_url, 'is_en' => $is_en);
}

// Language switcher UI
function birdnet_lang_switcher() {
    $lang = birdnet_get_lang_urls();
    $is_en = $lang['is_en'];
    ?>
    <div class="lang-switcher">
        <a href="<?php echo esc_url($lang['th']); ?>" class="lang-btn<?php echo $is_en ? '' : ' active'; ?>" title="ภาษาไทย">TH</a>
        <span class="lang-sep">|</span>
        <a href="<?php echo esc_url($lang['en']); ?>" class="lang-btn<?php echo $is_en ? ' active' : ''; ?>" title="English">EN</a>
    </div>
    <?php
}

// Add hreflang tags for SEO
function birdnet_hreflang_tags() {
    $lang = birdnet_get_lang_urls();
    $base = 'https://birdsgoaway.com';
    echo '<link rel="alternate" hreflang="th" href="' . esc_url($base . $lang['th']) . '" />' . "\n";
    echo '<link rel="alternate" hreflang="en" href="' . esc_url($base . $lang['en']) . '" />' . "\n";
    echo '<link rel="alternate" hreflang="x-default" href="' . esc_url($base . $lang['th']) . '" />' . "\n";
}
add_action('wp_head', 'birdnet_hreflang_tags', 1);

// Top Contact Bar above header
function birdnet_top_contact_bar() {
    $is_en = birdnet_is_english();
    ?>
    <div class="birdnet-top-bar">
        <div class="top-bar-inner">
            <div class="top-bar-left">
                <a href="tel:0629964994" class="top-bar-item"><span class="top-bar-icon">📞</span> 062-996-4994</a>
                <a href="mailto:birdsgoaway.th@gmail.com" class="top-bar-item"><span class="top-bar-icon">📧</span> birdsgoaway.th@gmail.com</a>
                <span class="top-bar-item top-bar-hours"><span class="top-bar-icon">🕐</span> <?php echo $is_en ? 'Mon-Sat 08:00-17:00' : 'จ-ส 08:00-17:00'; ?></span>
            </div>
            <div class="top-bar-right">
                <div class="top-bar-social">
                    <a href="https://www.facebook.com/share/1ZAXHsxCft/?mibextid=wwXIfr" target="_blank" rel="noopener" title="Facebook">📘</a>
                    <a href="https://line.me/ti/p/~oil_phanu" target="_blank" rel="noopener" title="LINE">💬</a>
                </div>
                <a href="<?php echo $is_en ? '/en/contact' : '/contact'; ?>" class="top-bar-cta"><?php echo $is_en ? 'Free Consult!' : 'ปรึกษาฟรี!'; ?></a>
                <?php birdnet_lang_switcher(); ?>
            </div>
        </div>
    </div>
    <?php
}
add_action('astra_header_before', 'birdnet_top_contact_bar');

// Add preconnect for faster font loading
function birdnet_preconnect_fonts() {
    echo '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
}
add_action('wp_head', 'birdnet_preconnect_fonts', 0);

// Add Line Chat floating button
function birdnet_floating_buttons() {
    $is_en = birdnet_is_english();
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
        <a href="https://line.me/ti/p/~<?php echo esc_attr($line_id); ?>" class="birdnet-float-btn line" target="_blank" rel="noopener" aria-label="<?php echo $is_en ? 'Line Chat' : 'แชท Line'; ?>" title="<?php echo $is_en ? 'Chat on LINE' : 'แชท Line'; ?>">
            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12 2C6.48 2 2 5.82 2 10.5c0 2.95 1.95 5.55 4.87 7.13-.19.66-.68 2.37-.78 2.73-.13.47.17.46.36.34.15-.1 2.37-1.61 3.33-2.26.73.1 1.47.16 2.22.16 5.52 0 10-3.82 10-8.5S17.52 2 12 2z"/></svg>
            LINE
        </a>
        <a href="tel:<?php echo esc_attr($phone); ?>" class="birdnet-float-btn phone" aria-label="<?php echo $is_en ? 'Phone' : 'โทรศัพท์'; ?>" title="<?php echo $is_en ? 'Call Now' : 'โทรเลย'; ?>">
            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M6.62 10.79a15.053 15.053 0 006.59 6.59l2.2-2.2a1.003 1.003 0 011.01-.24c1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/></svg>
            <?php echo $is_en ? 'Call Now' : 'โทรเลย'; ?>
        </a>
    </div>
    <div class="birdnet-sticky-mobile">
        <a href="tel:<?php echo esc_attr($phone); ?>" class="sticky-cta-call"><?php echo $is_en ? '📞 Call Now' : '📞 โทรเลย'; ?></a>
        <a href="https://line.me/ti/p/~<?php echo esc_attr($line_id); ?>" class="sticky-cta-line" target="_blank" rel="noopener">💬 Line</a>
        <a href="<?php echo $is_en ? '/en/contact' : '/contact'; ?>" class="sticky-cta-quote"><?php echo $is_en ? '📋 Free Quote' : '📋 ประเมินฟรี'; ?></a>
    </div>
    <?php
}
add_action('wp_footer', 'birdnet_floating_buttons');

// Testimonial carousel JavaScript (injected via wp_footer because WP strips <script> from post content)
function birdnet_testimonial_carousel_js() {
    if (!is_front_page()) return;
    ?>
    <script>
    (function(){
        var track=document.querySelector(".testimonial-track");
        if(!track) return;
        var idx=0,slides=document.querySelectorAll(".testimonial-slide"),total=slides.length;
        var dotsC=document.querySelector(".testimonial-dots");
        if(!dotsC) return;
        for(var i=0;i<total;i++){var d=document.createElement("span");d.className="t-dot";d.setAttribute("data-i",i);d.style.cssText="width:10px;height:10px;border-radius:50%;background:"+(i===0?"#E8792E":"#ddd")+";cursor:pointer;transition:background 0.3s;";d.onclick=function(){idx=parseInt(this.getAttribute("data-i"));go()};dotsC.appendChild(d)}
        function go(){track.style.transform="translateX(-"+idx*100+"%)";var dots=dotsC.querySelectorAll(".t-dot");for(var j=0;j<dots.length;j++)dots[j].style.background=j===idx?"#E8792E":"#ddd"}
        window.moveTestimonial=function(dir){idx=(idx+dir+total)%total;go()};
        setInterval(function(){idx=(idx+1)%total;go()},5000);
    })();
    </script>
    <?php
}
add_action('wp_footer', 'birdnet_testimonial_carousel_js');

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
    $site_url = get_site_url();
    $site_url = str_replace('http://', 'https://', $site_url);
    $is_en = birdnet_is_english();

    // Organization + WebSite + BreadcrumbList schema on ALL pages
    $org_schema = json_encode(array(
        '@context' => 'https://schema.org',
        '@graph' => array(
            array(
                '@type' => 'Organization',
                '@id' => $site_url . '/#organization',
                'name' => $is_en ? 'Birds Go Away — Recheck Building Co., Ltd.' : 'Birds Go Away — บริษัท รีเช็ค บิ้วดิ้ง จำกัด',
                'url' => $site_url . '/',
                'logo' => array(
                    '@type' => 'ImageObject',
                    'url' => $site_url . '/wp-content/uploads/birdnet-assets/logo-dark.jpg',
                    'width' => 240,
                    'height' => 80,
                ),
                'telephone' => '0629964994',
                'email' => 'birdsgoaway.th@gmail.com',
                'address' => array(
                    '@type' => 'PostalAddress',
                    'streetAddress' => $is_en ? '88/38 Klever Village Soi 5, Ban Pet' : '88/38 หมู่บ้าน Klever ซอย5 ต.บ้านเป็ด',
                    'addressLocality' => $is_en ? 'Khon Kaen' : 'ขอนแก่น',
                    'addressRegion' => $is_en ? 'Khon Kaen' : 'ขอนแก่น',
                    'postalCode' => '40000',
                    'addressCountry' => 'TH',
                ),
                'sameAs' => array(
                    'https://www.facebook.com/share/1ZAXHsxCft/?mibextid=wwXIfr',
                    'https://line.me/ti/p/~oil_phanu',
                ),
                'areaServed' => array(
                    array('@type' => 'City', 'name' => $is_en ? 'Khon Kaen' : 'ขอนแก่น'),
                    array('@type' => 'City', 'name' => $is_en ? 'Chiang Mai' : 'เชียงใหม่'),
                    array('@type' => 'City', 'name' => $is_en ? 'Chonburi' : 'ชลบุรี'),
                ),
            ),
            array(
                '@type' => 'WebSite',
                '@id' => $site_url . '/#website',
                'url' => $site_url . '/',
                'name' => 'Birds Go Away',
                'publisher' => array('@id' => $site_url . '/#organization'),
                'inLanguage' => $is_en ? 'en' : 'th',
            ),
        ),
    ), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    echo '<script type="application/ld+json">' . $org_schema . '</script>' . "\n";

    // Breadcrumb schema
    $breadcrumb_items = array(
        array('@type' => 'ListItem', 'position' => 1, 'name' => $is_en ? 'Home' : 'หน้าแรก', 'item' => $is_en ? $site_url . '/en/home' : $site_url . '/'),
    );
    if (!is_front_page()) {
        $page_title = get_the_title();
        $page_url = get_permalink();
        $breadcrumb_items[] = array('@type' => 'ListItem', 'position' => 2, 'name' => $page_title, 'item' => $page_url);
    }
    $breadcrumb_schema = json_encode(array(
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => $breadcrumb_items,
    ), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    echo '<script type="application/ld+json">' . $breadcrumb_schema . '</script>' . "\n";

    if (is_front_page()) {
        $schema = get_post_meta(get_the_ID(), '_schema_json_ld', true);
        if ($schema) {
            echo '<script type="application/ld+json">' . $schema . '</script>' . "\n";
        }
    }
    // FAQ Schema on FAQ page (both TH and EN)
    if (is_page('faq')) {
        if ($is_en) {
            $faq_items = array(
                array('@type' => 'Question', 'name' => 'How long does HDPE bird netting last?', 'acceptedAnswer' => array('@type' => 'Answer', 'text' => 'HDPE nets last 6-7 years with UV treatment, resistant to sun and rain. 3-year warranty included.')),
                array('@type' => 'Question', 'name' => 'How much does installation cost?', 'acceptedAnswer' => array('@type' => 'Answer', 'text' => 'Cost depends on area and difficulty. We provide free on-site assessment. Engineer visits and quotes within 1-2 days. Call 062-996-4994.')),
                array('@type' => 'Question', 'name' => 'What is the installation process?', 'acceptedAnswer' => array('@type' => 'Answer', 'text' => '1) Site survey by engineer 2) Quote within 1-2 days 3) Schedule installation 4) Professional team with full safety equipment 5) Customer inspection before handover with warranty.')),
                array('@type' => 'Question', 'name' => 'What areas do you serve?', 'acceptedAnswer' => array('@type' => 'Answer', 'text' => 'We serve 3 main regions: Khon Kaen and Isan, Chiang Mai and Northern Thailand, Chonburi and Eastern Thailand. Other areas available on request.')),
                array('@type' => 'Question', 'name' => 'Will bird netting make my building look ugly?', 'acceptedAnswer' => array('@type' => 'Answer', 'text' => 'Our HDPE nets are Transparent Black — virtually invisible from a distance. They do not affect building aesthetics.')),
                array('@type' => 'Question', 'name' => 'How weather-resistant are the nets?', 'acceptedAnswer' => array('@type' => 'Answer', 'text' => 'HDPE 2500D/1ply nets with knot breaking strength of 13 kg, UV treated, resistant to heat and rain — ideal for tropical climate.')),
                array('@type' => 'Question', 'name' => 'How are stainless steel spikes different from plastic?', 'acceptedAnswer' => array('@type' => 'Answer', 'text' => 'Our SUS304 stainless steel spikes have 90 pins per meter, rust-proof, adjustable angles, and outlast plastic alternatives significantly.')),
                array('@type' => 'Question', 'name' => 'What certifications do you have?', 'acceptedAnswer' => array('@type' => 'Answer', 'text' => 'Registered company (0405567000088), SAFESIRI rope access certification, Engineering Council license, Safety Officer certification.')),
                array('@type' => 'Question', 'name' => 'Will birds get hurt?', 'acceptedAnswer' => array('@type' => 'Answer', 'text' => 'No — all methods are 100% humane. Nets block entry, spikes have blunt tips, and bird gel is natural and safe for birds, people, and pets.')),
                array('@type' => 'Question', 'name' => 'What does the warranty cover?', 'acceptedAnswer' => array('@type' => 'Answer', 'text' => '3-year warranty covering net tears, detachment, spikes loosening, and installation defects. Free repairs throughout warranty period.')),
                array('@type' => 'Question', 'name' => 'Do you serve Bangkok and other provinces?', 'acceptedAnswer' => array('@type' => 'Answer', 'text' => 'Nationwide service with 3 offices: Khon Kaen (Isan), Chiang Mai (North), Chonburi (East). Bangkok and other provinces available.')),
            );
        } else {
            $faq_items = array(
                array('@type' => 'Question', 'name' => 'ตาข่ายกันนก HDPE มีอายุการใช้งานกี่ปี?', 'acceptedAnswer' => array('@type' => 'Answer', 'text' => 'ตาข่าย HDPE มีอายุการใช้งาน 6-7 ปี ผ่านการ UV Treatment ทนแดด ทนฝน เหมาะกับสภาพอากาศเมืองไทย พร้อมรับประกัน 3 ปี')),
                array('@type' => 'Question', 'name' => 'ค่าบริการติดตั้งเริ่มต้นเท่าไหร่?', 'acceptedAnswer' => array('@type' => 'Answer', 'text' => 'ค่าบริการขึ้นอยู่กับพื้นที่และความยากง่ายของงาน เราให้บริการประเมินราคาฟรี โดยวิศวกรจะไปสำรวจหน้างานและเสนอราคาให้ภายใน 1-2 วัน สามารถติดต่อสอบถามได้ที่ 062-996-4994')),
                array('@type' => 'Question', 'name' => 'ขั้นตอนการทำงานเป็นอย่างไร?', 'acceptedAnswer' => array('@type' => 'Answer', 'text' => '1) สำรวจหน้างาน วิศวกรไปดูพื้นที่จริง 2) เสนอราคาภายใน 1-2 วัน 3) นัดวันติดตั้ง 4) ทีมช่างพร้อมวิศวกรคุมงาน ใช้อุปกรณ์ความปลอดภัยครบ 5) ลูกค้าตรวจรับงานก่อนรับมอบ พร้อมรับประกันผลงาน')),
                array('@type' => 'Question', 'name' => 'ให้บริการพื้นที่ไหนบ้าง?', 'acceptedAnswer' => array('@type' => 'Answer', 'text' => 'เราให้บริการหลัก 3 ภูมิภาค: ขอนแก่นและจังหวัดใกล้เคียงในภาคอีสาน, เชียงใหม่และจังหวัดภาคเหนือ, ชลบุรีและจังหวัดภาคตะวันออก สำหรับพื้นที่อื่นๆ สามารถสอบถามได้')),
                array('@type' => 'Question', 'name' => 'ติดตั้งตาข่ายกันนกแล้วจะทำให้อาคารดูไม่สวยไหม?', 'acceptedAnswer' => array('@type' => 'Answer', 'text' => 'ตาข่าย HDPE ที่เราใช้เป็นสี Transparent Black (โปร่งแสง) เมื่อติดตั้งแล้วแทบมองไม่เห็นจากระยะไกล ไม่ทำให้อาคารดูเสียทัศนียภาพ เหมาะกับทุกรูปแบบอาคาร')),
                array('@type' => 'Question', 'name' => 'ตาข่ายทนต่อสภาพอากาศได้ดีแค่ไหน?', 'acceptedAnswer' => array('@type' => 'Answer', 'text' => 'ตาข่าย HDPE 2500D/1ply มีแรงดึงขาดจุดปม 13 kg แรงดึงขาดเส้นด้าย 7 kg ผ่าน UV Treatment ทนแดดจัด ไม่เปื่อยยุ่ยจากฝน เหมาะกับสภาพอากาศร้อนชื้นของไทย')),
                array('@type' => 'Question', 'name' => 'หนามกันนกสแตนเลสต่างจากหนามพลาสติกอย่างไร?', 'acceptedAnswer' => array('@type' => 'Answer', 'text' => 'หนามสแตนเลส SUS304 ของเรามี 90 ขาต่อ 1 เมตร ถี่กว่าหนามทั่วไป ไม่เป็นสนิม ทนทานตลอดอายุการใช้งาน ปรับองศาปลายหนามได้ตามพื้นที่ ทนแดด ทนฝน ไม่เสื่อมสภาพเร็วเหมือนพลาสติก')),
                array('@type' => 'Question', 'name' => 'มีใบรับรองหรือมาตรฐานอะไรบ้าง?', 'acceptedAnswer' => array('@type' => 'Answer', 'text' => 'จดทะเบียนนิติบุคคลถูกต้อง (เลขทะเบียน 0405567000088) ใบรับรองโรยตัว SAFESIRI ใบประกอบวิชาชีพวิศวกร (กว.) จากสภาวิศวกร ใบ จป. หัวหน้างาน วิศวกรคุมงานทุกไซต์')),
                array('@type' => 'Question', 'name' => 'ติดตั้งแล้วนกจะเจ็บไหม?', 'acceptedAnswer' => array('@type' => 'Answer', 'text' => 'ไม่เจ็บ ทุกวิธีเป็นสันติวิธี 100% ไม่ทำร้ายและไม่ฆ่านก ตาข่าย HDPE กันไม่ให้นกเข้าพื้นที่ หนามสแตนเลสปลายมนทำให้นกไม่สามารถเกาะได้ เจลไล่นกเป็นสารธรรมชาติ ปลอดภัยต่อนก คน และสัตว์เลี้ยง')),
                array('@type' => 'Question', 'name' => 'การรับประกันกี่ปี? ครอบคลุมอะไรบ้าง?', 'acceptedAnswer' => array('@type' => 'Answer', 'text' => 'รับประกัน 3 ปี ครอบคลุมตาข่ายขาด หลุด หรือเสื่อมสภาพจากการใช้งานปกติ หนามหลุดหรือคลายตัว งานติดตั้งที่มีข้อบกพร่อง ทีมงานเข้าแก้ไขฟรีตลอดระยะประกัน')),
                array('@type' => 'Question', 'name' => 'ติดตั้งในพื้นที่กรุงเทพฯ หรือต่างจังหวัดได้ไหม?', 'acceptedAnswer' => array('@type' => 'Answer', 'text' => 'บริการทั่วประเทศ มีสำนักงาน 3 แห่ง: ขอนแก่นครอบคลุมภาคอีสาน เชียงใหม่ครอบคลุมภาคเหนือ ชลบุรีครอบคลุมภาคตะวันออก ภาคกลาง กรุงเทพฯ และปริมณฑล สำหรับพื้นที่อื่นๆ ยินดีเดินทางไปทุกจังหวัด')),
            );
        }
        $faq_schema = json_encode(array(
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => $faq_items,
        ), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        echo '<script type="application/ld+json">' . $faq_schema . '</script>' . "\n";
    }
    // ImageObject schema on portfolio page
    if (is_page('portfolio')) {
        $site_url = get_site_url();
        $assets = $site_url . '/wp-content/uploads/birdnet-assets';
        $portfolio_images = array();
        if ($is_en) {
            $descriptions = array(
                '01' => 'HDPE bird net installation, condo balcony, Khon Kaen',
                '03' => 'Bird net installation, commercial building, Khon Kaen',
                '05' => 'Bird net installation, condominium, Chiang Mai',
                '07' => 'HDPE bird net, condo, Khon Kaen',
                '08' => 'Bird net installation, beachfront condo, Chonburi',
                '10' => 'SUS304 stainless steel bird spikes, balcony railing',
                '12' => 'Bird spikes installation, gas station, Chonburi',
                '15' => 'Bird net installation, commercial building, Chiang Mai',
                '18' => 'Bird net installation, factory, industrial estate',
                '20' => 'Bird net installation, factory, Khon Kaen',
                '22' => 'Bird net installation, warehouse, Chiang Mai',
            );
            $gallery_name = 'Bird Net Installation Portfolio — Birds Go Away';
            $gallery_desc = 'Completed bird control projects: condos, homes, commercial buildings, factories. 31+ projects across Thailand.';
        } else {
            $descriptions = array(
                '01' => 'ติดตั้งตาข่ายกันนก HDPE ระเบียงคอนโด ขอนแก่น',
                '03' => 'ติดตั้งตาข่ายกันนก อาคารพาณิชย์ ขอนแก่น',
                '05' => 'ติดตั้งตาข่ายกันนก คอนโดมิเนียม เชียงใหม่',
                '07' => 'ติดตั้งตาข่าย HDPE กันนก คอนโด ขอนแก่น',
                '08' => 'ติดตั้งตาข่ายกันนก คอนโดริมหาด ชลบุรี พัทยา',
                '10' => 'ติดตั้งหนามกันนกสแตนเลส SUS304 ราวระเบียง',
                '12' => 'ติดตั้งหนามกันนก ปั๊มน้ำมัน ชลบุรี',
                '15' => 'ติดตั้งตาข่ายกันนก อาคารพาณิชย์ เชียงใหม่',
                '18' => 'ติดตั้งตาข่ายกันนก โรงงาน นิคมอุตสาหกรรม',
                '20' => 'ติดตั้งตาข่ายกันนก โรงงาน ขอนแก่น',
                '22' => 'ติดตั้งตาข่ายกันนก โกดังสินค้า เชียงใหม่',
            );
            $gallery_name = 'ผลงานติดตั้งตาข่ายกันนก — Birds Go Away';
            $gallery_desc = 'รวมผลงานติดตั้งตาข่ายกันนก หนามกันนก เจลไล่นก 31+ โปรเจกต์ ทั้งคอนโด บ้าน อาคารพาณิชย์ โรงงาน';
        }
        foreach ($descriptions as $num => $desc) {
            $portfolio_images[] = array(
                '@type' => 'ImageObject',
                'contentUrl' => $assets . '/birdnet-' . $num . '.webp',
                'name' => $desc,
                'description' => $desc . ($is_en ? ' by Birds Go Away — Recheck Building Co., Ltd.' : ' โดย Birds Go Away บริษัท รีเช็ค บิ้วดิ้ง จำกัด'),
                'author' => array('@type' => 'Organization', 'name' => 'Birds Go Away'),
            );
        }
        $portfolio_schema = json_encode(array(
            '@context' => 'https://schema.org',
            '@type' => 'ImageGallery',
            'name' => $gallery_name,
            'description' => $gallery_desc,
            'url' => $site_url . ($is_en ? '/en/portfolio' : '/portfolio/'),
            'image' => $portfolio_images,
        ), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        echo '<script type="application/ld+json">' . $portfolio_schema . '</script>' . "\n";
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

// Flipbook initialization for portfolio page
function birdnet_flipbook_init() {
    if (!is_page('portfolio')) return;
    $is_en = birdnet_is_english();
    ?>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        var el = document.getElementById("flipbook-container");
        if (!el || typeof St === "undefined" || typeof St.PageFlip === "undefined") return;

        var vw = window.innerWidth;
        var isMobile = vw <= 768;
        var w, h;
        if (isMobile) {
            w = Math.min(vw - 24, 400);
            h = Math.round(w * 1.45);
        } else if (vw <= 1024) {
            w = 380;
            h = 540;
        } else {
            w = 460;
            h = 620;
        }

        var pageFlip = new St.PageFlip(el, {
            width: w,
            height: h,
            size: isMobile ? "fixed" : "stretch",
            minWidth: isMobile ? w : 300,
            maxWidth: isMobile ? w : 560,
            minHeight: isMobile ? h : 420,
            maxHeight: isMobile ? h : 780,
            showCover: true,
            mobileScrollSupport: true,
            maxShadowOpacity: isMobile ? 0.2 : 0.4,
            drawShadow: !isMobile,
            flippingTime: 700,
            usePortrait: isMobile,
            startZIndex: 0,
            autoSize: !isMobile,
            clickEventForward: true,
            swipeDistance: 20
        });

        var pages = el.querySelectorAll(".fb-page");
        if (pages.length === 0) return;
        pageFlip.loadFromHTML(pages);

        var pageNum = document.getElementById("fb-page-num");
        var pageTotal = document.getElementById("fb-page-total");
        if (pageTotal) pageTotal.textContent = pageFlip.getPageCount();
        pageFlip.on("flip", function(e) {
            if (pageNum) pageNum.textContent = e.data + 1;
        });

        var prevBtn = document.getElementById("fb-prev");
        var nextBtn = document.getElementById("fb-next");
        if (prevBtn) prevBtn.addEventListener("click", function() { pageFlip.flipPrev(); });
        if (nextBtn) nextBtn.addEventListener("click", function() { pageFlip.flipNext(); });

        // WP strips data-fb-page attrs, so auto-detect from category divider pages
        var catPages = {};
        pages.forEach(function(p, i) {
            if (p.classList.contains("fb-cat-divider")) {
                var h3 = p.querySelector("h3");
                if (h3) catPages[h3.textContent.trim()] = i;
            }
        });
        var tocBtns = document.querySelectorAll(".flipbook-toc button");
        tocBtns.forEach(function(btn) {
            btn.addEventListener("click", function() {
                var txt = this.textContent.trim();
                for (var cat in catPages) {
                    if (txt.indexOf(cat) !== -1 || cat.indexOf(txt.replace(/[^\u0E00-\u0E7Fa-zA-Z]/g, "")) !== -1) {
                        pageFlip.flip(catPages[cat]);
                        return;
                    }
                }
            });
        });
    });
    </script>
    <?php
}
add_action('wp_footer', 'birdnet_flipbook_init', 100);

// Custom Footer Content
function birdnet_custom_footer() {
    $is_en = birdnet_is_english();
    ?>
    <div class="birdnet-footer-content">
        <div class="footer-grid">
            <div class="footer-col">
                <h4>BIRDS GO AWAY</h4>
                <?php if ($is_en) : ?>
                <p>Recheck Building Co., Ltd.<br>
                Reg. 0405567000088<br>
                <strong>Head Office:</strong> 88/38 Klever Village Soi 5<br>
                Ban Pet, Mueang, Khon Kaen 40000</p>
                <p style="margin-top:8px;font-size:0.75rem;opacity:0.6;">Rope Access License / Engineering Council / Safety Officer</p>
                <?php else : ?>
                <p>บริษัท รีเช็ค บิ้วดิ้ง จำกัด<br>
                ทะเบียน 0405567000088<br>
                <strong>สำนักงานใหญ่:</strong> 88/38 หมู่บ้าน Klever ซอย5<br>
                ต.บ้านเป็ด อ.เมือง จ.ขอนแก่น 40000</p>
                <p style="margin-top:8px;font-size:0.75rem;opacity:0.6;">ใบอนุญาตโรยตัว / กว. สภาวิศวกร / จป.หัวหน้างาน</p>
                <?php endif; ?>
            </div>
            <div class="footer-col">
                <h4><?php echo $is_en ? 'Contact Us' : 'ติดต่อเรา'; ?></h4>
                <p><strong><?php echo $is_en ? 'Khon Kaen:' : 'ขอนแก่น:'; ?></strong> 062-996-4994<br>
                <strong><?php echo $is_en ? 'Wiwi (Quotation):' : 'วีวี่ (ฝ่ายประเมินราคา):'; ?></strong> 088-951-4924<br>
                <strong><?php echo $is_en ? 'Chiang Mai:' : 'เชียงใหม่:'; ?></strong> 093-641-5623<br>
                <strong><?php echo $is_en ? 'Chonburi:' : 'ชลบุรี:'; ?></strong> 095-629-2488</p>
                <p style="margin-top:8px;">LINE: <a href="https://line.me/ti/p/~oil_phanu">oil_phanu</a></p>
                <div style="margin-top:12px;display:flex;gap:10px;">
                    <a href="https://www.facebook.com/share/1ZAXHsxCft/?mibextid=wwXIfr" target="_blank" rel="noopener" style="color:#fff;text-decoration:none;font-size:1.2rem;" aria-label="Facebook" title="Facebook">📘</a>
                    <a href="https://line.me/ti/p/~oil_phanu" target="_blank" rel="noopener" style="color:#06C755;text-decoration:none;font-size:1.2rem;" aria-label="LINE" title="LINE">💬</a>
                    <a href="tel:062-996-4994" style="color:#E8792E;text-decoration:none;font-size:1.2rem;" aria-label="<?php echo $is_en ? 'Phone' : 'โทรศัพท์'; ?>" title="<?php echo $is_en ? 'Call Now' : 'โทรเลย'; ?>">📞</a>
                </div>
                <p><?php echo $is_en ? 'Office: Mon-Sat 08:00-17:00<br>Free Consult (LINE/Call): Daily 08:00-20:00' : 'สำนักงาน: จ-ส 08:00-17:00<br>ปรึกษาฟรี (LINE/โทร): ทุกวัน 08:00-20:00'; ?></p>
            </div>
            <div class="footer-col">
                <h4><?php echo $is_en ? 'Menu' : 'เมนู'; ?></h4>
                <ul>
                    <?php if ($is_en) : ?>
                    <li><a href="/en/home">Home</a></li>
                    <li><a href="/en/services">Services</a></li>
                    <li><a href="/en/portfolio">Portfolio</a></li>
                    <li><a href="/en/about">About</a></li>
                    <li><a href="/en/contact">Contact</a></li>
                    <li><a href="/en/faq">FAQ</a></li>
                    <?php else : ?>
                    <li><a href="/">หน้าแรก</a></li>
                    <li><a href="/services">บริการของเรา</a></li>
                    <li><a href="/portfolio">ผลงาน</a></li>
                    <li><a href="/about">เกี่ยวกับเรา</a></li>
                    <li><a href="/contact">ติดต่อเรา</a></li>
                    <li><a href="/faq">FAQ</a></li>
                    <li><a href="/blog">บทความ</a></li>
                    <?php endif; ?>
                </ul>
            </div>
            <div class="footer-col">
                <h4><?php echo $is_en ? 'Our Services' : 'บริการของเรา'; ?></h4>
                <ul>
                    <?php if ($is_en) : ?>
                    <li>HDPE Bird Netting</li>
                    <li>Stainless Steel Bird Spikes</li>
                    <li>Bird Repellent Gel</li>
                    <li>Solar Panel Bird Guards</li>
                    <?php else : ?>
                    <li>ตาข่ายกันนก HDPE</li>
                    <li>หนามกันนก สแตนเลส</li>
                    <li>เจลไล่นก</li>
                    <li>แผงกันนกโซลาร์เซลล์</li>
                    <?php endif; ?>
                </ul>
                <?php if ($is_en) : ?>
                <p style="margin-top:12px;"><strong>Service Areas:</strong><br>Nationwide coverage — Khon Kaen, Udon Thani, Nakhon Ratchasima, Maha Sarakham, Chiang Mai, Chonburi</p>
                <p style="margin-top:8px;color:#E8792E;font-weight:600;">Express service in Khon Kaen area — on-site assessment within 24 hrs.</p>
                <?php else : ?>
                <p style="margin-top:12px;"><strong>พื้นที่ให้บริการ:</strong><br>ยินดีให้บริการทั่วประเทศ โดยเฉพาะภาคอีสาน: ขอนแก่น, อุดรธานี, นครราชสีมา, มหาสารคาม, เชียงใหม่, ชลบุรี</p>
                <p style="margin-top:8px;color:#E8792E;font-weight:600;">🚀 บริการด่วนในพื้นที่ขอนแก่นและจังหวัดใกล้เคียง — นัดประเมินหน้างานได้ภายใน 24 ชม.</p>
                <?php endif; ?>
            </div>
        </div>
        <div style="background:linear-gradient(135deg,#E8792E,#d4631a);padding:1.2rem;border-radius:12px;text-align:center;margin:1.5rem 0;">
            <p style="margin:0 0 8px;font-size:1.2rem;font-weight:700;color:white;"><?php echo $is_en ? 'Free Assessment Within 24 Hrs — Contact Us Now' : '🚀 ประเมินฟรีภายใน 24 ชม. — กดเลย'; ?></p>
            <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap;">
                <a href="tel:0629964994" style="background:white;color:#E8792E;padding:10px 24px;border-radius:8px;text-decoration:none;font-weight:600;"><?php echo $is_en ? '📞 Call 062-996-4994' : '📞 โทรเลย 062-996-4994'; ?></a>
                <a href="https://line.me/ti/p/~oil_phanu" style="background:#06C755;color:white;padding:10px 24px;border-radius:8px;text-decoration:none;font-weight:600;"><?php echo $is_en ? '💬 LINE for Free Quote' : '💬 แอดไลน์ประเมินราคา'; ?></a>
            </div>
        </div>
        <div class="footer-bottom">
            <p><?php echo $is_en ? '&copy; 2026 BIRDS GO AWAY — Recheck Building Co., Ltd. | Professional bird net installation across Thailand' : '&copy; 2026 BIRDS GO AWAY — บริษัท รีเช็ค บิ้วดิ้ง จำกัด | บริการติดตั้งตาข่ายกันนก ขอนแก่น อุดรธานี นครราชสีมา เชียงใหม่ ชลบุรี ภาคอีสาน ทั่วประเทศ'; ?></p>
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
        $tags['form'] = array(
            'action' => true, 'method' => true, 'style' => true,
            'class' => true, 'id' => true, 'enctype' => true,
        );
        $tags['input'] = array(
            'type' => true, 'name' => true, 'value' => true,
            'placeholder' => true, 'required' => true, 'style' => true,
            'class' => true, 'id' => true,
        );
        $tags['select'] = array(
            'name' => true, 'style' => true, 'class' => true, 'id' => true,
        );
        $tags['option'] = array(
            'value' => true, 'style' => true, 'selected' => true,
        );
        $tags['textarea'] = array(
            'name' => true, 'rows' => true, 'cols' => true,
            'placeholder' => true, 'style' => true, 'class' => true,
        );
        $tags['button'] = array(
            'type' => true, 'style' => true, 'class' => true, 'id' => true,
        );
        $tags['label'] = array(
            'for' => true, 'style' => true, 'class' => true,
        );
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

// Remove WordPress default site icon (globe icon)
remove_action('wp_head', 'wp_site_icon', 99);

// Preload critical assets + custom favicon (from theme directory — always available)
function birdnet_preload_assets() {
    $favicon_url = get_stylesheet_directory_uri() . '/favicon.ico';
    echo '<link rel="preload" as="image" href="/wp-content/uploads/birdnet-assets/fb-cover.webp">' . "\n";
    echo '<link rel="icon" type="image/x-icon" href="' . esc_url($favicon_url) . '">' . "\n";
    echo '<link rel="shortcut icon" href="' . esc_url($favicon_url) . '">' . "\n";
    echo '<link rel="apple-touch-icon" href="' . esc_url(get_stylesheet_directory_uri() . '/apple-touch-icon.png') . '">' . "\n";
    echo '<link rel="dns-prefetch" href="//www.google.com">' . "\n";
    echo '<link rel="dns-prefetch" href="//www.googletagmanager.com">' . "\n";
}
add_action('wp_head', 'birdnet_preload_assets', 1);

// SEO: Override WordPress title tag when Yoast is not active
function birdnet_override_title($title_parts) {
    if (defined('WPSEO_VERSION')) return $title_parts;

    $custom_title = '';
    $is_en = birdnet_is_english();
    if (is_front_page()) {
        $custom_title = $is_en ? 'Bird Net Installation Thailand | Birds Go Away' : 'ตาข่ายกันนก ขอนแก่น เชียงใหม่ ชลบุรี | Birds Go Away';
    } elseif (is_page() || is_single()) {
        $custom_title = get_post_meta(get_the_ID(), '_yoast_wpseo_title', true);
    }

    if ($custom_title) {
        $title_parts['title'] = $custom_title;
        unset($title_parts['site']);
        unset($title_parts['tagline']);
    } else {
        $title_parts['site'] = 'Birds Go Away';
        unset($title_parts['tagline']);
    }

    return $title_parts;
}
add_filter('document_title_parts', 'birdnet_override_title', 99);

// SEO: Fallback meta description if Yoast is not active
function birdnet_fallback_meta_description() {
    if (defined('WPSEO_VERSION')) return;
    $desc = '';
    $is_en_meta = birdnet_is_english();
    if (is_front_page()) {
        $desc = $is_en_meta ? 'Professional bird net installation across Thailand. HDPE nets, stainless steel spikes, bird gel. Free on-site assessment. 3-year warranty. Call 062-996-4994' : 'บริการติดตั้งตาข่ายกันนก หนามกันนก มาตรฐานอุตสาหกรรม แก้ปัญหานกพิราบถาวร รับประกันงาน 3 ปี ประเมินหน้างานฟรีทั่วขอนแก่นและภาคอีสาน โทร 062-996-4994';
    } elseif (is_page() || is_single()) {
        $desc = get_post_meta(get_the_ID(), '_yoast_wpseo_metadesc', true);
        if (!$desc) $desc = wp_trim_words(get_the_excerpt(), 25, '...');
    }
    if ($desc) {
        echo '<meta name="description" content="' . esc_attr($desc) . '">' . "\n";
    }
}
add_action('wp_head', 'birdnet_fallback_meta_description', 2);

// Rewrite nav menu item URLs and labels when on EN pages
function birdnet_nav_menu_en_rewrite($items, $args) {
    $is_en = birdnet_is_english();
    if ($is_en) {
        $url_map = array(
            '/' => '/en/home',
            '/services/' => '/en/services',
            '/portfolio/' => '/en/portfolio',
            '/about/' => '/en/about',
            '/contact/' => '/en/contact',
            '/faq/' => '/en/faq',
        );
        $label_map = array(
            'หน้าแรก' => 'Home',
            'บริการของเรา' => 'Services',
            'ผลงานของเรา' => 'Portfolio',
            'ผลงาน' => 'Portfolio',
            'เกี่ยวกับเรา' => 'About',
            'ติดต่อเรา' => 'Contact',
            'FAQ' => 'FAQ',
        );
        foreach ($url_map as $th_url => $en_url) {
            $items = str_replace('href="' . home_url($th_url) . '"', 'href="' . $en_url . '"', $items);
            $items = str_replace('href="' . $th_url . '"', 'href="' . $en_url . '"', $items);
        }
        foreach ($label_map as $th => $en) {
            $items = str_replace('>' . $th . '</a>', '>' . $en . '</a>', $items);
        }
    }
    return $items;
}
add_filter('wp_nav_menu_items', 'birdnet_nav_menu_en_rewrite', 5, 2);

// Add CTA button to nav menu
function birdnet_nav_cta_button($items, $args) {
    if ($args->theme_location === 'primary' || $args->menu === 'Main Menu') {
        $is_en = birdnet_is_english();
        if ($is_en) {
            $items .= '<li class="menu-item birdnet-nav-cta"><a href="/en/contact" class="nav-cta-btn">Get Quote</a></li>';
        } else {
            $items .= '<li class="menu-item birdnet-nav-cta"><a href="/contact" class="nav-cta-btn">ขอใบเสนอราคา</a></li>';
        }
    }
    return $items;
}
add_filter('wp_nav_menu_items', 'birdnet_nav_cta_button', 10, 2);

// Force all nav menus (including mobile hamburger) to use "Main Menu"
function birdnet_fix_mobile_menu($args) {
    $args['menu'] = 'Main Menu';
    if (!empty($args['theme_location']) && $args['theme_location'] !== 'primary') {
        $args['theme_location'] = '';
    }
    $args['fallback_cb'] = false;
    return $args;
}
add_filter('wp_nav_menu_args', 'birdnet_fix_mobile_menu');

// JS: sync mobile menu with desktop primary nav (runs immediately in footer, DOM already loaded)
function birdnet_mobile_menu_sync_js() {
    echo '<script>
    (function() {
        var primary = document.querySelector("nav[aria-label=\'Primary Site Navigation\'] ul");
        var mobile = document.querySelector("nav[aria-label=\'Site Navigation\'] ul");
        if (primary && mobile) {
            mobile.innerHTML = primary.innerHTML;
        }
    })();
    </script>';
}
add_action('wp_footer', 'birdnet_mobile_menu_sync_js');

// Add body class for EN pages (used for CSS font-size adjustments)
function birdnet_en_body_class($classes) {
    if (birdnet_is_english()) {
        $classes[] = 'birdnet-en';
    }
    return $classes;
}
add_filter('body_class', 'birdnet_en_body_class');

// Ensure logo displays — JS fallback for broken image (ephemeral Docker uploads)
function birdnet_logo_fallback() {
    $fallback_url = home_url('/wp-content/uploads/birdnet-assets/logo-dark.jpg');
    echo '<script>
    document.addEventListener("DOMContentLoaded", function() {
        var logos = document.querySelectorAll(".custom-logo, .ast-site-identity img");
        logos.forEach(function(img) {
            // Remove srcset to prevent browser picking broken WP-generated URLs
            if (img.getAttribute("srcset")) img.removeAttribute("srcset");
            if (img.getAttribute("sizes")) img.removeAttribute("sizes");
            img.onerror = function() {
                this.onerror = null;
                this.src = "' . esc_url($fallback_url) . '";
            };
            if (img.complete && img.naturalWidth === 0) {
                img.src = "' . esc_url($fallback_url) . '";
            }
            // Force dark logo for visibility on white header
            if (img.src.indexOf("logo-white") !== -1) {
                img.src = img.src.replace("logo-white", "logo-dark");
            }
        });
    });
    </script>';
}
add_action('wp_head', 'birdnet_logo_fallback');

// Remove srcset from logo to prevent broken WP-generated URLs on ephemeral containers
function birdnet_remove_logo_srcset($sources, $size_array, $image_src) {
    if (strpos($image_src, 'logo') !== false) {
        return array();
    }
    return $sources;
}
add_filter('wp_calculate_image_srcset', 'birdnet_remove_logo_srcset', 10, 3);

// Set --topbar-height CSS variable for sticky header offset
function birdnet_topbar_height_js() {
    echo '<script>
    (function() {
        function setTopbarHeight() {
            var tb = document.querySelector(".birdnet-top-bar");
            if (tb) {
                document.documentElement.style.setProperty("--topbar-height", tb.offsetHeight + "px");
            }
        }
        document.addEventListener("DOMContentLoaded", setTopbarHeight);
        window.addEventListener("resize", setTopbarHeight);
        window.addEventListener("load", setTopbarHeight);
    })();
    </script>';
}
add_action('wp_head', 'birdnet_topbar_height_js');
