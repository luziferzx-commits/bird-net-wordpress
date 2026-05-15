#!/usr/bin/env php
<?php
/**
 * Update WordPress page content with images and videos.
 * Run via: php /tmp/update-content.php
 * This script uses WP-CLI functions loaded via wp-load.php
 */

// Load WordPress
$wp_load = '/var/www/html/wp-load.php';
if (!file_exists($wp_load)) {
    echo "WordPress not found at $wp_load\n";
    exit(1);
}
require_once $wp_load;

$site_url = get_site_url();
// Force HTTPS for asset URLs (Cloudflare handles SSL)
$site_url = str_replace('http://', 'https://', $site_url);
$assets_base = $site_url . '/wp-content/uploads/birdnet-assets';

// Helper functions
function img($num) {
    global $assets_base;
    $padded = str_pad($num, 2, '0', STR_PAD_LEFT);
    return $assets_base . '/birdnet-' . $padded . '.webp';
}

function vid($num) {
    global $assets_base;
    $padded = str_pad($num, 2, '0', STR_PAD_LEFT);
    return $assets_base . '/reel-' . $padded . '.mp4';
}

function project_img($page, $num = 1) {
    global $assets_base;
    return $assets_base . '/project-p' . str_pad($page, 2, '0', STR_PAD_LEFT) . '-' . $num . '.webp';
}

// Real project images from assets (replaces stock photos)
$unsplash = array(
    'hero'    => $assets_base . '/fb-cover.webp',
    'hdpe'    => project_img(10),
    'solar'   => project_img(14),
    'spikes'  => project_img(18),
    'gel'     => project_img(29),
    'team'    => img(6),
    'safety'  => img(7),
    'work1'   => img(8),
    'work2'   => img(9),
    'work3'   => img(10),
    'work4'   => img(11),
    'work5'   => img(12),
    'work6'   => img(13),
    'about'   => img(14),
);

// ===== HOME PAGE =====
$home_content = '
<!-- wp:columns -->
<div class="wp-block-columns">
<!-- wp:column -->
<div class="wp-block-column" style="background:transparent !important;border:none !important;">

<!-- wp:heading {"level":1} -->
<h1 class="wp-block-heading">แก้ปัญหานกพิราบถาวร<br>รับประกันงานติดตั้ง โดยผู้เชี่ยวชาญ</h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"fontSize":"medium"} -->
<p class="has-medium-font-size">รับติดตั้งตาข่ายกันนก ขอนแก่น เชียงใหม่ ชลบุรี ทั่วประเทศ — หนามสแตนเลส เจลไล่นก ครบวงจร วิศวกรคุมงานทุกไซต์ ทีมช่างผ่านอบรมโรยตัวมีใบ Certificate</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"fontSize":"medium"} -->
<p class="has-medium-font-size"><strong>ประเมินหน้างานฟรี | รับประกัน 3 ปี | บริการทั่วประเทศ</strong></p>
<!-- /wp:paragraph -->

<!-- wp:buttons -->
<div class="wp-block-buttons">
<!-- wp:button {"backgroundColor":"vivid-green-cyan","className":"hero-cta-primary"} -->
<div class="wp-block-button hero-cta-primary"><a class="wp-block-button__link has-vivid-green-cyan-background-color has-background" href="/contact">ประเมินราคาฟรี</a></div>
<!-- /wp:button -->
<!-- wp:button {"className":"is-style-outline hero-cta-secondary"} -->
<div class="wp-block-button is-style-outline hero-cta-secondary"><a class="wp-block-button__link" href="https://line.me/ti/p/~oil_phanu">ปรึกษาเราทาง Line</a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->

</div>
<!-- /wp:column -->
<!-- wp:column -->
<div class="wp-block-column" style="background:transparent !important;border:none !important;">
<!-- wp:image {"sizeSlug":"large"} -->
<figure class="wp-block-image size-large"><img src="' . $unsplash['hero'] . '" alt="ผลงานติดตั้งตาข่ายกันนก Birds Go Away" style="border-radius:16px;"/></figure>
<!-- /wp:image -->
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->

<!-- wp:html -->
<div style="background:#f8f9fa;padding:1.5rem 1rem;text-align:center;">
<p style="font-size:0.8rem;color:#888;margin:0 0 12px;text-transform:uppercase;letter-spacing:2px;font-weight:600;">องค์กรที่ไว้วางใจเรา</p>
<div style="display:flex;flex-wrap:wrap;justify-content:center;align-items:center;gap:20px 32px;max-width:900px;margin:0 auto;">
<span style="font-size:0.85rem;color:#555;font-weight:600;white-space:nowrap;">🏛️ สำนักงาน ป.ป.ช. ภาค 4</span>
<span style="font-size:0.85rem;color:#555;font-weight:600;white-space:nowrap;">🎓 มหาวิทยาลัยขอนแก่น</span>
<span style="font-size:0.85rem;color:#555;font-weight:600;white-space:nowrap;">🏥 วิทยาลัยสาธารณสุขสิรินธร</span>
<span style="font-size:0.85rem;color:#555;font-weight:600;white-space:nowrap;">🏥 โรงพยาบาลสิรินธร</span>
<span style="font-size:0.85rem;color:#555;font-weight:600;white-space:nowrap;">⚓ กรมอู่ทหารเรือ</span>
<span style="font-size:0.85rem;color:#555;font-weight:600;white-space:nowrap;">🏢 เมโทรคอนโด ขอนแก่น</span>
</div>
<p style="font-size:0.75rem;color:#aaa;margin:10px 0 0;">ผลงานกว่า 31+ โปรเจกต์ ทั้งภาครัฐและเอกชน</p>
</div>
<!-- /wp:html -->

<!-- wp:separator {"className":"is-style-wide"} -->
<hr class="wp-block-separator has-alpha-channel-opacity is-style-wide"/>
<!-- /wp:separator -->

<!-- wp:heading {"textAlign":"center","level":2} -->
<h2 class="has-text-align-center wp-block-heading">ทำไมต้องเลือกเรา?</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">บริษัท รีเช็ค บิ้วดิ้ง จำกัด — ผู้เชี่ยวชาญด้านการป้องกันนก ครบวงจร | วิธีไล่นกพิราบถาวร ภาคอีสาน ทั่วประเทศ</p>
<!-- /wp:paragraph -->

<!-- wp:html -->
<div style="max-width:800px;margin:0 auto;display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:16px;padding:0 1rem;">

<div style="background:#f8f9fa;border-radius:12px;padding:1.2rem;border-left:4px solid #E8792E;">
<h3 style="color:#1B4D5C;margin:0 0 8px;font-size:1rem;">🛡️ HDPE เกรดพรีเมียม</h3>
<p style="margin:0;font-size:0.88rem;color:#555;">วัสดุมาตรฐาน ทนทานทุกสภาพอากาศ ป้องกัน UV</p>
</div>

<div style="background:#f8f9fa;border-radius:12px;padding:1.2rem;border-left:4px solid #E8792E;">
<h3 style="color:#1B4D5C;margin:0 0 8px;font-size:1rem;">🦺 ทีมงานผ่านฝึกอบรมความปลอดภัย</h3>
<p style="margin:0;font-size:0.88rem;color:#555;">ช่างทุกคนผ่านอบรมการทำงานบนที่สูง (Safety) มีใบรับรองโรยตัว SAFESIRI + วิศวกร กว. คุมงานทุกไซต์</p>
</div>

<div style="background:#f8f9fa;border-radius:12px;padding:1.2rem;border-left:4px solid #E8792E;">
<h3 style="color:#1B4D5C;margin:0 0 8px;font-size:1rem;">📋 ประเมินหน้างานฟรี ไม่มีค่าใช้จ่าย</h3>
<p style="margin:0;font-size:0.88rem;color:#555;">วิศวกรเข้าสำรวจหน้างานฟรี วัดพื้นที่ วิเคราะห์ปัญหา เสนอราคาภายใน 1-2 วัน ไม่มีค่าใช้จ่ายแอบแฝง</p>
</div>

<div style="background:#f8f9fa;border-radius:12px;padding:1.2rem;border-left:4px solid #E8792E;">
<h3 style="color:#1B4D5C;margin:0 0 8px;font-size:1rem;">🕊️ ไม่ทำร้ายนก — สันติวิธี 100%</h3>
<p style="margin:0;font-size:0.88rem;color:#555;">วิธีป้องกันแบบสันติ ไม่ทำร้าย ไม่ฆ่านก เพียงกันไม่ให้นกเข้ามาในพื้นที่ ปลอดภัยต่อคนและสัตว์เลี้ยง</p>
</div>

<div style="background:#f8f9fa;border-radius:12px;padding:1.2rem;border-left:4px solid #E8792E;">
<h3 style="color:#1B4D5C;margin:0 0 8px;font-size:1rem;">🛡️ รับประกัน 3 ปี พร้อมซ่อมฟรี</h3>
<p style="margin:0;font-size:0.88rem;color:#555;">รับประกันคุณภาพงานติดตั้ง 3 ปี หากพบปัญหาทีมงานเข้าแก้ไขฟรี บริการหลังการขายตลอดอายุสัญญา</p>
</div>

<div style="background:#f8f9fa;border-radius:12px;padding:1.2rem;border-left:4px solid #E8792E;">
<h3 style="color:#1B4D5C;margin:0 0 8px;font-size:1rem;">⚡ ติดตั้งรวดเร็ว ไม่รบกวนการใช้ชีวิต</h3>
<p style="margin:0;font-size:0.88rem;color:#555;">ทีมช่างมืออาชีพ งานระเบียงคอนโดเสร็จใน 2-4 ชม. งานโรงงานใหญ่เสร็จใน 1-3 วัน พร้อมทำความสะอาดหลังงาน</p>
</div>

</div>
<!-- /wp:html -->

<!-- wp:html -->
<div style="max-width:700px;margin:2rem auto;display:flex;justify-content:center;gap:24px;flex-wrap:wrap;padding:0 1rem;">
<div style="text-align:center;flex:1;min-width:140px;">
<div style="font-size:2.5rem;margin-bottom:4px;">&#x1F6E1;&#xFE0F;</div>
<p style="font-weight:700;color:#1B4D5C;margin:0;font-size:0.95rem;">รับประกัน 3 ปี</p>
<p style="color:#666;font-size:0.78rem;margin:2px 0 0;">ซ่อมฟรีตลอดสัญญา</p>
</div>
<div style="text-align:center;flex:1;min-width:140px;">
<div style="font-size:2.5rem;margin-bottom:4px;">&#x1F9BA;</div>
<p style="font-weight:700;color:#1B4D5C;margin:0;font-size:0.95rem;">Safety Certified</p>
<p style="color:#666;font-size:0.78rem;margin:2px 0 0;">ใบรับรองโรยตัว SAFESIRI</p>
</div>
<div style="text-align:center;flex:1;min-width:140px;">
<div style="font-size:2.5rem;margin-bottom:4px;">&#x1F4CB;</div>
<p style="font-weight:700;color:#1B4D5C;margin:0;font-size:0.95rem;">ประเมินฟรี</p>
<p style="color:#666;font-size:0.78rem;margin:2px 0 0;">ไม่มีค่าใช้จ่ายแอบแฝง</p>
</div>
<div style="text-align:center;flex:1;min-width:140px;">
<div style="font-size:2.5rem;margin-bottom:4px;">&#x1F3D7;&#xFE0F;</div>
<p style="font-weight:700;color:#1B4D5C;margin:0;font-size:0.95rem;">วิศวกร กว.</p>
<p style="color:#666;font-size:0.78rem;margin:2px 0 0;">สภาวิศวกรคุมงานทุกไซต์</p>
</div>
</div>
<!-- /wp:html -->

<!-- wp:separator {"className":"is-style-wide"} -->
<hr class="wp-block-separator has-alpha-channel-opacity is-style-wide"/>
<!-- /wp:separator -->

<!-- wp:heading {"textAlign":"center","level":2} -->
<h2 class="has-text-align-center wp-block-heading">เลือกอุปกรณ์แบบไหนที่เหมาะกับคุณ?</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">ปัญหาแต่ละแบบใช้วิธีแก้ต่างกัน — ให้เราช่วยแนะนำ</p>
<!-- /wp:paragraph -->

<!-- wp:html -->
<div style="max-width:800px;margin:0 auto;display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:16px;padding:0 1rem;">

<div style="background:#fff8f3;border-radius:12px;padding:1.2rem;border:2px solid #E8792E;text-align:center;">
<div style="font-size:2rem;margin-bottom:8px;">&#x1F426;</div>
<h4 style="color:#E8792E;margin:0 0 6px;font-size:1rem;">นกเกาะราวกันตก / ขอบหน้าต่าง</h4>
<p style="margin:0 0 8px;font-size:0.85rem;color:#555;">แนะนำ: <strong>หนามกันนกสแตนเลส</strong></p>
<p style="margin:0;font-size:0.78rem;color:#888;">ติดตั้งง่าย ราคาประหยัด ทนทานตลอดอายุการใช้งาน</p>
</div>

<div style="background:#f3f9ff;border-radius:12px;padding:1.2rem;border:2px solid #1B4D5C;text-align:center;">
<div style="font-size:2rem;margin-bottom:8px;">&#x1F3E2;</div>
<h4 style="color:#1B4D5C;margin:0 0 6px;font-size:1rem;">นกทำรังใต้หลังคา / ระเบียง</h4>
<p style="margin:0 0 8px;font-size:0.85rem;color:#555;">แนะนำ: <strong>ตาข่าย HDPE</strong></p>
<p style="margin:0;font-size:0.78rem;color:#888;">ครอบคลุมพื้นที่กว้าง มองแทบไม่เห็น อายุ 5-7 ปี</p>
</div>

<div style="background:#f9fff3;border-radius:12px;padding:1.2rem;border:2px solid #06C755;text-align:center;">
<div style="font-size:2rem;margin-bottom:8px;">&#x2728;</div>
<h4 style="color:#06C755;margin:0 0 6px;font-size:1rem;">พื้นที่เน้นความสวยงาม</h4>
<p style="margin:0 0 8px;font-size:0.85rem;color:#555;">แนะนำ: <strong>เจลไล่นก</strong></p>
<p style="margin:0;font-size:0.78rem;color:#888;">ไม่เห็นจากภายนอก ไม่ทำลายทัศนียภาพ ปลอดภัย 100%</p>
</div>

</div>
<!-- /wp:html -->

<!-- wp:separator {"className":"is-style-wide"} -->
<hr class="wp-block-separator has-alpha-channel-opacity is-style-wide"/>
<!-- /wp:separator -->

<!-- wp:heading {"textAlign":"center","level":2} -->
<h2 class="has-text-align-center wp-block-heading">การรับประกันงานติดตั้ง</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">เรามั่นใจในคุณภาพงาน — รับประกันทุกโปรเจกต์ หากนกกลับมาในระยะรับประกัน เข้าไปดูแลฟรี</p>
<!-- /wp:paragraph -->

<!-- wp:html -->
<div style="max-width:800px;margin:0 auto;display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px;padding:0 1rem;">
<div style="background:#f0fdf4;border-radius:12px;padding:1.5rem;text-align:center;border:2px solid #22c55e;">
<div style="font-size:2.5rem;">&#x1F6E1;&#xFE0F;</div>
<h4 style="color:#166534;margin:8px 0 4px;font-size:1rem;">รับประกันงานติดตั้ง</h4>
<p style="font-size:2rem;font-weight:800;color:#22c55e;margin:0;">3 ปี</p>
<p style="font-size:0.8rem;color:#555;margin:4px 0 0;">หลุด ขาด เสียหาย ซ่อมฟรี<br>ตลอดระยะรับประกัน</p>
</div>
<div style="background:#eff6ff;border-radius:12px;padding:1.5rem;text-align:center;border:2px solid #3b82f6;">
<div style="font-size:2.5rem;">&#x1F527;</div>
<h4 style="color:#1e40af;margin:8px 0 4px;font-size:1rem;">วัสดุไม่เป็นสนิม</h4>
<p style="font-size:2rem;font-weight:800;color:#3b82f6;margin:0;">5+ ปี</p>
<p style="font-size:0.8rem;color:#555;margin:4px 0 0;">สแตนเลส SUS304 + HDPE<br>ทนทานทุกสภาพอากาศ</p>
</div>
<div style="background:#fef9f0;border-radius:12px;padding:1.5rem;text-align:center;border:2px solid #E8792E;">
<div style="font-size:2.5rem;">&#x1F426;</div>
<h4 style="color:#9a3412;margin:8px 0 4px;font-size:1rem;">นกกลับมา?</h4>
<p style="font-size:2rem;font-weight:800;color:#E8792E;margin:0;">ดูแลฟรี</p>
<p style="font-size:0.8rem;color:#555;margin:4px 0 0;">เข้าตรวจสอบและแก้ไข<br>โดยไม่มีค่าใช้จ่าย</p>
</div>
</div>
<!-- /wp:html -->

<!-- wp:separator {"className":"is-style-wide"} -->
<hr class="wp-block-separator has-alpha-channel-opacity is-style-wide"/>
<!-- /wp:separator -->

<!-- wp:heading {"textAlign":"center","level":2} -->
<h2 class="has-text-align-center wp-block-heading">ก่อน vs หลังติดตั้ง</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">ดูผลลัพธ์จริงจากหน้างาน — จากปัญหานกรบกวน สู่ความสะอาดเรียบร้อย</p>
<!-- /wp:paragraph -->

<!-- wp:html -->
<div style="max-width:800px;margin:0 auto;padding:0 1rem;">

<div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:1.5rem;">
<div style="text-align:center;">
<div style="background:#fef2f2;border-radius:12px;padding:0.8rem;border:2px solid #ef4444;">
<h4 style="color:#dc2626;margin:0 0 8px;font-size:0.95rem;">❌ ก่อนติดตั้ง</h4>
<p style="font-size:0.8rem;color:#555;margin:0;">ขี้นกเต็มระเบียง ราวกันตก ส่งกลิ่นเหม็น เชื้อโรคสะสม</p>
</div>
</div>
<div style="text-align:center;">
<div style="background:#f0fdf4;border-radius:12px;padding:0.8rem;border:2px solid #22c55e;">
<h4 style="color:#16a34a;margin:0 0 8px;font-size:0.95rem;">✅ หลังติดตั้ง</h4>
<p style="font-size:0.8rem;color:#555;margin:0;">สะอาดเรียบร้อย ไร้นก ปลอดเชื้อ ผ่าน 3 ปี นกไม่กลับมา</p>
</div>
</div>
</div>

<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:12px;">
<div style="border-radius:12px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,0.1);">
<img src="' . img(7) . '" alt="ผลงานติดตั้งตาข่ายกันนก HDPE ระเบียงคอนโด ขอนแก่น" style="width:100%;height:180px;object-fit:cover;" loading="lazy">
<div style="padding:8px;background:#f8f9fa;text-align:center;">
<p style="margin:0;font-size:0.8rem;color:#555;font-weight:600;">ติดตั้งตาข่าย HDPE — คอนโด</p>
</div>
</div>
<div style="border-radius:12px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,0.1);">
<img src="' . img(10) . '" alt="ผลงานติดตั้งหนามกันนกสแตนเลส ราวระเบียง" style="width:100%;height:180px;object-fit:cover;" loading="lazy">
<div style="padding:8px;background:#f8f9fa;text-align:center;">
<p style="margin:0;font-size:0.8rem;color:#555;font-weight:600;">หนามสแตนเลส — ราวระเบียง</p>
</div>
</div>
<div style="border-radius:12px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,0.1);">
<img src="' . img(15) . '" alt="ผลงานติดตั้งตาข่ายกันนก อาคารพาณิชย์ เชียงใหม่" style="width:100%;height:180px;object-fit:cover;" loading="lazy">
<div style="padding:8px;background:#f8f9fa;text-align:center;">
<p style="margin:0;font-size:0.8rem;color:#555;font-weight:600;">ตาข่ายกันนก — อาคารพาณิชย์</p>
</div>
</div>
</div>

<div style="text-align:center;margin-top:1rem;">
<a href="/portfolio" style="color:#E8792E;font-weight:600;text-decoration:none;font-size:0.95rem;">ดูผลงานทั้งหมด 31+ โปรเจกต์ →</a>
</div>
</div>
<!-- /wp:html -->

<!-- wp:separator {"className":"is-style-wide"} -->
<hr class="wp-block-separator has-alpha-channel-opacity is-style-wide"/>
<!-- /wp:separator -->

<!-- wp:heading {"textAlign":"center","level":2} -->
<h2 class="has-text-align-center wp-block-heading">&#x1F4F8; ส่งภาพหน้างาน ประเมินราคาฟรี!</h2>
<!-- /wp:heading -->

<!-- wp:html -->
<div style="max-width:600px;margin:0 auto;background:linear-gradient(135deg,#f0fdf4,#ecfdf5);border-radius:16px;padding:2rem;text-align:center;border:2px solid #06C755;">
<p style="font-size:1.1rem;color:#1B4D5C;margin:0 0 8px;font-weight:600;">แค่ถ่ายรูปส่งมา — รู้ราคาทันที!</p>
<p style="font-size:0.9rem;color:#555;margin:0 0 16px;">ถ่ายรูปจุดที่นกเกาะ/ทำรัง ส่งให้เราทาง LINE<br>ทีมงานประเมินราคาให้ฟรี ไม่มีค่าใช้จ่าย ไม่มีข้อผูกมัด</p>
<a href="https://line.me/ti/p/~oil_phanu" target="_blank" rel="noopener" style="display:inline-block;background:#06C755;color:#fff;padding:14px 32px;border-radius:8px;text-decoration:none;font-weight:700;font-size:1rem;box-shadow:0 4px 12px rgba(6,199,85,0.3);">💬 ส่งภาพหน้างานทาง LINE</a>
<p style="font-size:0.75rem;color:#888;margin:12px 0 0;">ตอบกลับภายใน 30 นาที ทุกวัน 08:00-20:00</p>
</div>
<!-- /wp:html -->

<!-- wp:separator {"className":"is-style-wide"} -->
<hr class="wp-block-separator has-alpha-channel-opacity is-style-wide"/>
<!-- /wp:separator -->

<!-- wp:heading {"textAlign":"center","level":2} -->
<h2 class="has-text-align-center wp-block-heading">บริการของเรา</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">เรามีบริการป้องกันนกครบวงจร 4 รูปแบบ ให้เลือกตามความเหมาะสม</p>
<!-- /wp:paragraph -->

<!-- wp:columns -->
<div class="wp-block-columns">
<!-- wp:column -->
<div class="wp-block-column" style="text-align:center;">
<!-- wp:image {"sizeSlug":"medium"} -->
<figure class="wp-block-image size-medium"><img src="' . $unsplash['hdpe'] . '" alt="ตาข่าย HDPE กันนก" style="border-radius:12px;object-fit:cover;aspect-ratio:4/3;width:100%;height:200px;" loading="lazy"/></figure>
<!-- /wp:image -->
<h3 class="wp-block-heading">ตาข่าย HDPE กันนก</h3>
<p>ตาข่าย HDPE คุณภาพสูง อายุการใช้งาน 5-7 ปี แข็งแรง ทนทานต่อแรงดึง แรงกระแทก และสารเคมี เหมาะสำหรับทุกพื้นที่</p>
</div>
<!-- /wp:column -->
<!-- wp:column -->
<div class="wp-block-column" style="text-align:center;">
<!-- wp:image {"sizeSlug":"medium"} -->
<figure class="wp-block-image size-medium"><img src="' . $unsplash['solar'] . '" alt="แผงกันนกโซลาร์เซลล์" style="border-radius:12px;object-fit:cover;aspect-ratio:4/3;width:100%;height:200px;" loading="lazy"/></figure>
<!-- /wp:image -->
<h3 class="wp-block-heading">แผงกันนกโซลาร์เซลล์</h3>
<p>ระบบคลิปไม่เจาะแผง ยืดอายุการใช้งานโซลาร์เซลล์ หมดปัญหานกทำรัง ใต้แผงสกปรก สายไฟเสียหาย</p>
</div>
<!-- /wp:column -->
<!-- wp:column -->
<div class="wp-block-column" style="text-align:center;">
<!-- wp:image {"sizeSlug":"medium"} -->
<figure class="wp-block-image size-medium"><img src="' . $unsplash['spikes'] . '" alt="หนามกันนก สแตนเลส" style="border-radius:12px;object-fit:cover;aspect-ratio:4/3;width:100%;height:200px;" loading="lazy"/></figure>
<!-- /wp:image -->
<h3 class="wp-block-heading">หนามกันนก</h3>
<p>หนามสแตนเลสกันนก ป้องกันนกเกาะ ทนทานต่อทุกสภาพอากาศ ติดตั้งง่าย ราคาประหยัด เหมาะกับขอบหน้าต่าง ราวกันตก</p>
</div>
<!-- /wp:column -->
<!-- wp:column -->
<div class="wp-block-column" style="text-align:center;">
<!-- wp:image {"sizeSlug":"medium"} -->
<figure class="wp-block-image size-medium"><img src="' . $unsplash['gel'] . '" alt="เจลไล่นก" style="border-radius:12px;object-fit:cover;aspect-ratio:4/3;width:100%;height:200px;" loading="lazy"/></figure>
<!-- /wp:image -->
<h3 class="wp-block-heading">เจลไล่นก</h3>
<p>เจลไล่นกสูตรพิเศษ ไม่มีสารพิษ ปลอดภัยต่อคนและสัตว์ ใช้ได้กับทุกพื้นผิว ไม่ทิ้งคราบ</p>
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons">
<!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link" href="/services">ดูรายละเอียดบริการทั้งหมด →</a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->

<!-- wp:separator {"className":"is-style-wide"} -->
<hr class="wp-block-separator has-alpha-channel-opacity is-style-wide"/>
<!-- /wp:separator -->

<!-- wp:heading {"textAlign":"center","level":2} -->
<h2 class="has-text-align-center wp-block-heading">ขั้นตอนการทำงาน</h2>
<!-- /wp:heading -->

<!-- wp:columns -->
<div class="wp-block-columns">
<!-- wp:column {"width":"25%"} -->
<div class="wp-block-column" style="flex-basis:25%;text-align:center;">
<h3 class="wp-block-heading">1. สำรวจหน้างาน</h3>
<p>วิศวกรเข้าสำรวจพื้นที่จริง วิเคราะห์ปัญหา วัดพื้นที่ เลือกวิธีที่เหมาะสมที่สุด</p>
</div>
<!-- /wp:column -->
<!-- wp:column {"width":"25%"} -->
<div class="wp-block-column" style="flex-basis:25%;text-align:center;">
<h3 class="wp-block-heading">2. เสนอราคา</h3>
<p>จัดทำใบเสนอราคาอย่างละเอียด ระบุวัสดุ ราคา ระยะเวลาชัดเจน ปรึกษาฟรี</p>
</div>
<!-- /wp:column -->
<!-- wp:column {"width":"25%"} -->
<div class="wp-block-column" style="flex-basis:25%;text-align:center;">
<h3 class="wp-block-heading">3. ดำเนินการติดตั้ง</h3>
<p>ทีมช่างมืออาชีพติดตั้งด้วยอุปกรณ์ครบครัน ทำงานรวดเร็ว สะอาด เรียบร้อย</p>
</div>
<!-- /wp:column -->
<!-- wp:column {"width":"25%"} -->
<div class="wp-block-column" style="flex-basis:25%;text-align:center;">
<h3 class="wp-block-heading">4. ส่งมอบงาน</h3>
<p>ตรวจสอบคุณภาพ ส่งมอบพร้อมใบรับประกัน 3 ปี มีบริการหลังการขาย</p>
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->

<!-- wp:separator {"className":"is-style-wide"} -->
<hr class="wp-block-separator has-alpha-channel-opacity is-style-wide"/>
<!-- /wp:separator -->

<!-- wp:heading {"textAlign":"center","level":2} -->
<h2 class="has-text-align-center wp-block-heading">ตัวอย่างผลงานล่าสุด</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">ขอบพระคุณลูกค้าทุกท่านที่ไว้วางใจ Birds Go Away</p>
<!-- /wp:paragraph -->

<!-- wp:gallery {"columns":3,"linkTo":"none"} -->
<figure class="wp-block-gallery has-nested-images columns-3 is-cropped">
<!-- wp:image -->
<figure class="wp-block-image"><img src="' . project_img(7) . '" alt="ติดตั้งตาข่ายกันนก อาคารสำนักงาน ป.ป.ช. ภาค4 ขอนแก่น"/></figure>
<!-- /wp:image -->
<!-- wp:image -->
<figure class="wp-block-image"><img src="' . project_img(10) . '" alt="ติดตั้งตาข่ายกันนก หอพักชาย มหาวิทยาลัยขอนแก่น"/></figure>
<!-- /wp:image -->
<!-- wp:image -->
<figure class="wp-block-image"><img src="' . project_img(13) . '" alt="ติดตั้งตาข่ายกันนก วิทยาลัยสาธารณสุขสิรินธร ขอนแก่น"/></figure>
<!-- /wp:image -->
<!-- wp:image -->
<figure class="wp-block-image"><img src="' . project_img(20) . '" alt="ติดตั้งตาข่ายกันนก หอพักพยาบาล โรงพยาบาลสิรินธร ขอนแก่น"/></figure>
<!-- /wp:image -->
<!-- wp:image -->
<figure class="wp-block-image"><img src="' . project_img(35) . '" alt="ติดตั้งตาข่ายกันนก เมโทรคอนโด ขอนแก่น"/></figure>
<!-- /wp:image -->
<!-- wp:image -->
<figure class="wp-block-image"><img src="' . project_img(45) . '" alt="ติดตั้งตาข่ายกันนก ESCENT CONDO ขอนแก่น"/></figure>
<!-- /wp:image -->
</figure>
<!-- /wp:gallery -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons">
<!-- wp:button {"className":"is-style-outline"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link" href="/portfolio">ดูผลงานทั้งหมด →</a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->

<!-- wp:separator {"className":"is-style-wide"} -->
<hr class="wp-block-separator has-alpha-channel-opacity is-style-wide"/>
<!-- /wp:separator -->

<!-- wp:heading {"textAlign":"center","level":2} -->
<h2 class="has-text-align-center wp-block-heading">วิดีโอผลงานการติดตั้ง</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">ดูคลิปขั้นตอนการทำงานจริงจากหน้างาน</p>
<!-- /wp:paragraph -->

<!-- wp:columns -->
<div class="wp-block-columns">
<!-- wp:column -->
<div class="wp-block-column">
<!-- wp:video -->
<figure class="wp-block-video"><video controls src="' . vid(1) . '"></video><figcaption>เบื่อไหม? ขี้นกเต็มระเบียงคอนโด ล้างเท่าไหร่ก็ไม่หมด... เราช่วยได้! ติดตั้งตาข่าย HDPE เกรดส่งออก กลมกลืนกับตัวอาคาร นกหายขาด 100% 📞 ประเมินฟรี โทร 062-996-4994</figcaption></figure>
<!-- /wp:video -->
</div>
<!-- /wp:column -->
<!-- wp:column -->
<div class="wp-block-column">
<!-- wp:video -->
<figure class="wp-block-video"><video controls src="' . vid(2) . '"></video><figcaption>นกพิราบเกาะราวระเบียงทุกวัน? จบปัญหาด้วยหนามกันนกสแตนเลส SUS304 ไม่เป็นสนิม ทนแดดทนฝน 5+ ปี งานเนี๊ยบ กลมกลืนกับตัวบ้าน 💬 สนใจทัก LINE: oil_phanu</figcaption></figure>
<!-- /wp:video -->
</div>
<!-- /wp:column -->
<!-- wp:column -->
<div class="wp-block-column">
<!-- wp:video -->
<figure class="wp-block-video"><video controls src="' . vid(3) . '"></video><figcaption>นกทำรังใต้หลังคาร้าน ขี้นกหล่นใส่สินค้า? ดูคลิปนี้! ปิดช่องเปิดด้วยตาข่ายกันนก แข็งแรง ทนทาน กันนกเข้า 100% รับประกันงาน 3 ปี 📞 โทร 062-996-4994</figcaption></figure>
<!-- /wp:video -->
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->

<!-- wp:columns -->
<div class="wp-block-columns">
<!-- wp:column -->
<div class="wp-block-column">
<!-- wp:video -->
<figure class="wp-block-video"><video controls src="' . vid(4) . '"></video><figcaption>ยกระดับมาตรฐานความสะอาดให้โรงงานของคุณ ด้วยตาข่ายกันนกแบบมืออาชีพ ทีมงาน Safety ครบชุด งานไว ไม่กระทบการผลิต 💬 ติดต่อได้ทันที LINE: oil_phanu</figcaption></figure>
<!-- /wp:video -->
</div>
<!-- /wp:column -->
<!-- wp:column -->
<div class="wp-block-column">
<!-- wp:video -->
<figure class="wp-block-video"><video controls src="' . vid(5) . '"></video><figcaption>อาคารสวยแต่นกเกาะเต็ม? เจลไล่นกคือคำตอบ! วัสดุใสมองไม่เห็น ไม่ทำลายทัศนียภาพ นกไม่กล้าเกาะอีกเลย ผ่านมา 3 ปียังใช้ได้ดี 📞 ประเมินฟรี โทร 062-996-4994</figcaption></figure>
<!-- /wp:video -->
</div>
<!-- /wp:column -->
<!-- wp:column -->
<div class="wp-block-column">
<!-- wp:video -->
<figure class="wp-block-video"><video controls src="' . vid(6) . '"></video><figcaption>นกทำรังใต้แผงโซลาร์เซลล์? เสี่ยงไฟฟ้าลัดวงจร! ติดตั้งแผงกันนกโซลาร์ ปกป้องแผงโซลาร์ ไม่กระทบประสิทธิภาพ ปลอดภัย 100% 💬 ทัก LINE: oil_phanu</figcaption></figure>
<!-- /wp:video -->
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->

<!-- wp:separator {"className":"is-style-wide"} -->
<hr class="wp-block-separator has-alpha-channel-opacity is-style-wide"/>
<!-- /wp:separator -->

<!-- wp:heading {"textAlign":"center","level":2} -->
<h2 class="has-text-align-center wp-block-heading">คำถามที่พบบ่อย (FAQ)</h2>
<!-- /wp:heading -->

<!-- wp:html -->
<div class="faq-item">
<h4>ตาข่ายกันนก HDPE มีอายุการใช้งานกี่ปี?</h4>
<p>ตาข่าย HDPE ของเรามีอายุการใช้งานประมาณ 5-7 ปี ขึ้นอยู่กับสภาพแวดล้อม ทนต่อรังสี UV แข็งแรง ทนทาน</p>
</div>
<div class="faq-item">
<h4>ติดตั้งตาข่ายกันนก ใช้เวลาเท่าไหร่?</h4>
<p>ระยะเวลาขึ้นอยู่กับขนาดพื้นที่ โดยทั่วไป พื้นที่ระเบียงคอนโด 1 ห้อง ใช้เวลาประมาณ 2-4 ชั่วโมง พื้นที่โรงงานหรืออาคารใหญ่ 1-3 วัน</p>
</div>
<div class="faq-item">
<h4>ราคาติดตั้งตาข่ายกันนก เท่าไหร่?</h4>
<p>ราคาขึ้นอยู่กับขนาดพื้นที่ ความสูง และความยากง่ายของงาน สามารถขอใบเสนอราคาฟรีได้ โดยไม่มีค่าใช้จ่าย</p>
</div>
<div class="faq-item">
<h4>มีรับประกันหลังติดตั้งไหม?</h4>
<p>มีรับประกันงานติดตั้ง 3 ปี หากพบปัญหา ทีมงานเข้าแก้ไขฟรีตามเงื่อนไขการรับประกัน</p>
</div>
<div class="faq-item">
<h4>ติดตั้งแล้วนกจะเจ็บไหม?</h4>
<p>ไม่เจ็บครับ วิธีของเราเป็นแบบสันติวิธี 100% — เพียงกันไม่ให้นกเข้ามาในพื้นที่ ไม่ทำร้าย ไม่ฆ่า ปลอดภัยต่อนก คน และสัตว์เลี้ยง</p>
</div>
<div class="faq-item">
<h4>รับติดตั้งพื้นที่ไหนบ้าง?</h4>
<p>ยินดีให้บริการทั่วประเทศ โดยเฉพาะภาคอีสาน: ขอนแก่น อุดรธานี นครราชสีมา มหาสารคาม / เชียงใหม่ / ชลบุรี และปริมณฑล สำนักงานใหญ่อยู่ที่ขอนแก่น</p>
</div>
<!-- /wp:html -->

<!-- wp:separator {"className":"is-style-wide"} -->
<hr class="wp-block-separator has-alpha-channel-opacity is-style-wide"/>
<!-- /wp:separator -->

<!-- wp:heading {"textAlign":"center","level":2} -->
<h2 class="has-text-align-center wp-block-heading">รีวิวจากลูกค้า</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">ขอบพระคุณลูกค้าทุกท่านที่ไว้วางใจ Birds Go Away</p>
<!-- /wp:paragraph -->

<!-- wp:html -->
<div style="max-width:800px;margin:0 auto;display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:16px;padding:0 1rem;">

<div style="background:#f9f9f9;padding:1.5rem;border-radius:12px;border-left:4px solid #E8792E;">
<p style="font-size:0.8rem;color:#E8792E;margin:0 0 8px;font-weight:600;">🏢 คอนโดมิเนียม</p>
<p style="font-style:italic;margin:0 0 8px;font-size:0.9rem;">"ทีมงานมืออาชีพมาก ติดตั้งเรียบร้อย สะอาด ตาข่ายแทบมองไม่เห็น แต่นกไม่มาอีกเลย รับประกันงานด้วย ประทับใจมากครับ"</p>
<p style="color:#666;font-size:0.85em;margin:0;"><strong>— คุณสมชาย</strong> | คอนโด X10 ศรีนครินทร์, ขอนแก่น</p>
<p style="color:#E8792E;margin:4px 0 0;">⭐⭐⭐⭐⭐</p>
</div>

<div style="background:#f9f9f9;padding:1.5rem;border-radius:12px;border-left:4px solid #E8792E;">
<p style="font-size:0.8rem;color:#E8792E;margin:0 0 8px;font-weight:600;">🏠 บ้านพักอาศัย</p>
<p style="font-style:italic;margin:0 0 8px;font-size:0.9rem;">"ปัญหานกพิราบมานานหลายปี ลองหลายวิธีไม่ได้ผล พอติดตาข่ายกับ Birds Go Away จบเลย ราคาสมเหตุสมผล แนะนำเลยค่ะ"</p>
<p style="color:#666;font-size:0.85em;margin:0;"><strong>— คุณนิดา</strong> | หมู่บ้านสีวลี, ขอนแก่น</p>
<p style="color:#E8792E;margin:4px 0 0;">⭐⭐⭐⭐⭐</p>
</div>

<div style="background:#f9f9f9;padding:1.5rem;border-radius:12px;border-left:4px solid #E8792E;">
<p style="font-size:0.8rem;color:#E8792E;margin:0 0 8px;font-weight:600;">🏭 โรงงาน / โกดัง</p>
<p style="font-style:italic;margin:0 0 8px;font-size:0.9rem;">"ใช้บริการติดตั้งที่โกดังสินค้า พื้นที่กว้างมาก แต่ทีมจัดการได้เรียบร้อยภายใน 2 วัน มีวิศวกรมาคุมงานด้วย วางใจได้"</p>
<p style="color:#666;font-size:0.85em;margin:0;"><strong>— คุณวิชัย</strong> | โกดังสินค้า, เชียงใหม่</p>
<p style="color:#E8792E;margin:4px 0 0;">⭐⭐⭐⭐⭐</p>
</div>

<div style="background:#f9f9f9;padding:1.5rem;border-radius:12px;border-left:4px solid #E8792E;">
<p style="font-size:0.8rem;color:#E8792E;margin:0 0 8px;font-weight:600;">⛽ ปั๊มน้ำมัน / โชว์รูม</p>
<p style="font-style:italic;margin:0 0 8px;font-size:0.9rem;">"นกทำรังใต้หลังคาปั๊มจนสกปรก ลูกค้าร้องเรียนตลอด ติดตาข่ายแล้วสะอาดมาก ดูดีขึ้นเยอะ ขอบคุณทีมงานครับ"</p>
<p style="color:#666;font-size:0.85em;margin:0;"><strong>— ผู้จัดการสาขา</strong> | ปั๊มน้ำมัน, ชลบุรี</p>
<p style="color:#E8792E;margin:4px 0 0;">⭐⭐⭐⭐⭐</p>
</div>

</div>
<!-- /wp:html -->

<!-- wp:separator {"className":"is-style-wide"} -->
<hr class="wp-block-separator has-alpha-channel-opacity is-style-wide"/>
<!-- /wp:separator -->

<!-- wp:heading {"textAlign":"center","level":2} -->
<h2 class="has-text-align-center wp-block-heading">พื้นที่ให้บริการ</h2>
<!-- /wp:heading -->

<!-- wp:columns -->
<div class="wp-block-columns">
<!-- wp:column -->
<div class="wp-block-column" style="text-align:center;border:1px solid rgba(255,255,255,0.06);">
<h3 class="wp-block-heading">ขอนแก่น</h3>
<p><a href="tel:0629964994"><strong>062-996-4994</strong></a></p>
<p>LINE: <a href="https://line.me/ti/p/~oil_phanu">oil_phanu</a></p>
</div>
<!-- /wp:column -->
<!-- wp:column -->
<div class="wp-block-column" style="text-align:center;border:1px solid rgba(255,255,255,0.06);">
<h3 class="wp-block-heading">เชียงใหม่</h3>
<p><a href="tel:0936415623"><strong>093-641-5623</strong></a></p>
<p>LINE: <a href="https://line.me/ti/p/~th3-ta006-2">th3-ta006-2</a></p>
</div>
<!-- /wp:column -->
<!-- wp:column -->
<div class="wp-block-column" style="text-align:center;border:1px solid rgba(255,255,255,0.06);">
<h3 class="wp-block-heading">ชลบุรี</h3>
<p><a href="tel:0956292488"><strong>095-629-2488</strong></a></p>
<p>LINE: <a href="https://line.me/ti/p/~oil_phanu">oil_phanu</a></p>
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->

<!-- wp:paragraph {"align":"center","fontSize":"large"} -->
<p class="has-text-align-center has-large-font-size"><strong>ปรึกษาฟรี! ติดต่อเราวันนี้เพื่อรับใบเสนอราคา</strong></p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons">
<!-- wp:button {"backgroundColor":"vivid-green-cyan"} -->
<div class="wp-block-button"><a class="wp-block-button__link has-vivid-green-cyan-background-color has-background" href="/contact">🚀 ประเมินฟรีภายใน 24 ชม. — กดเลย</a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->
';

// ===== SERVICES PAGE =====
$services_content = '
<!-- wp:heading {"textAlign":"center","level":2} -->
<h2 class="has-text-align-center wp-block-heading">บริการของเรา</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">รับติดตั้งตาข่ายกันนก ขอนแก่น อุดรธานี นครราชสีมา เชียงใหม่ ชลบุรี — บริการป้องกันนก 4 รูปแบบ ครบวงจร พร้อมรับประกัน</p>
<!-- /wp:paragraph -->

<!-- wp:html -->
<div style="max-width:600px;margin:1.5rem auto;background:linear-gradient(135deg,#1B4D5C,#2a6a7c);border-radius:12px;padding:1.2rem 1.5rem;text-align:center;color:white;">
<p style="margin:0;font-size:1.3rem;font-weight:700;">💰 ราคาเริ่มต้นเพียง 350 บาท/ตร.ม.</p>
<p style="margin:6px 0 0;font-size:0.95rem;opacity:0.9;">ประเมินหน้างานและคำนวณราคาที่คุ้มค่าที่สุดให้ฟรี — ไม่มีค่าใช้จ่าย</p>
</div>
<!-- /wp:html -->

<!-- wp:separator {"className":"is-style-wide"} -->
<hr class="wp-block-separator has-alpha-channel-opacity is-style-wide"/>
<!-- /wp:separator -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">ตาข่าย HDPE กันนก (Bird Netting HDPE)</h2>
<!-- /wp:heading -->

<!-- wp:columns -->
<div class="wp-block-columns">
<!-- wp:column {"width":"50%"} -->
<div class="wp-block-column" style="flex-basis:50%;">
<p><strong>Square Mesh Durable Bird Control Netting</strong> — ตาข่าย HDPE คุณภาพสูง เกรดส่งออก หนาแน่น แข็งแรง ทนต่อแรงกระแทก แรงดึง และสารเคมี</p>

<h4 class="wp-block-heading">สเปคสินค้า</h4>
<ul>
<li><strong>วัสดุ:</strong> HDPE 2500D/1 ply (High-Density Polyethylene เกรดส่งออก)</li>
<li><strong>สี:</strong> Transparent Black (UV-Stabilized Treatment ทนรังสียูวี)</li>
<li><strong>ขนาดตา:</strong> 18 mm. x 18 mm. (Knitting net)</li>
<li><strong>แรงดึงขาดจุดปม:</strong> 13 kg.</li>
<li><strong>แรงดึงขาดเส้นด้าย:</strong> 7 kg.</li>
<li><strong>ขนาดม้วน:</strong> 6 m. (กว้าง) x 50 m. (ยาว)</li>
<li><strong>อายุการใช้งาน:</strong> 6-7 ปี</li>
<li><strong>การใช้งาน:</strong> อาคาร คอนโด โรงงาน โครงการต่างๆ</li>
</ul>
</div>
<!-- /wp:column -->
<!-- wp:column {"width":"50%"} -->
<div class="wp-block-column" style="flex-basis:50%;">
<!-- wp:image -->
<figure class="wp-block-image"><img src="' . $unsplash['hdpe'] . '" alt="ตาข่าย HDPE กันนก" style="border-radius:12px;object-fit:cover;aspect-ratio:4/3;width:100%;" loading="lazy"/></figure>
<!-- /wp:image -->
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->

<!-- wp:separator {"className":"is-style-wide"} -->
<hr class="wp-block-separator has-alpha-channel-opacity is-style-wide"/>
<!-- /wp:separator -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">แผงกันนกโซลาร์เซลล์</h2>
<!-- /wp:heading -->

<!-- wp:columns -->
<div class="wp-block-columns">
<!-- wp:column {"width":"50%"} -->
<div class="wp-block-column" style="flex-basis:50%;">
<p>ระบบป้องกันนกสำหรับแผงโซลาร์เซลล์ ใช้ระบบคลิปไม่เจาะแผง ป้องกันนกเข้าทำรังใต้แผง</p>
<ul>
<li>ระบบคลิปไม่ต้องเจาะแผง ไม่เสียประกัน</li>
<li>ยืดอายุการใช้งานแผงโซลาร์เซลล์</li>
<li>ป้องกันนกทำรัง ขับถ่ายมูลใต้แผง</li>
<li>ลดความเสี่ยงสายไฟเสียหายจากนกกัดแทะ</li>
<li>ไม่มีผลกระทบต่อประสิทธิภาพการผลิตไฟฟ้า</li>
</ul>
</div>
<!-- /wp:column -->
<!-- wp:column {"width":"50%"} -->
<div class="wp-block-column" style="flex-basis:50%;">
<!-- wp:image -->
<figure class="wp-block-image"><img src="' . $unsplash['solar'] . '" alt="แผงกันนกโซลาร์เซลล์" style="border-radius:12px;object-fit:cover;aspect-ratio:4/3;width:100%;" loading="lazy"/></figure>
<!-- /wp:image -->
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->

<!-- wp:separator {"className":"is-style-wide"} -->
<hr class="wp-block-separator has-alpha-channel-opacity is-style-wide"/>
<!-- /wp:separator -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">หนามกันนก</h2>
<!-- /wp:heading -->

<!-- wp:columns -->
<div class="wp-block-columns">
<!-- wp:column {"width":"50%"} -->
<div class="wp-block-column" style="flex-basis:50%;">
<p>หนามกันนกสแตนเลส <strong>เกรด SUS304 มาตรฐานส่งออก</strong> ปลายแหลม ความแหลมของหนามช่วยทำให้ป้องกันนกได้ดียิ่งขึ้น</p>

<h4 class="wp-block-heading">สเปคสินค้า</h4>
<ul>
<li><strong>วัสดุ:</strong> Stainless Steel Spring SUS304 (สแตนเลสเกรด 304 ไม่เป็นสนิมตลอดอายุการใช้งาน)</li>
<li><strong>จำนวนหนาม:</strong> 90 ขาต่อ 1 เมตร</li>
<li><strong>คุณสมบัติ:</strong> ปรับเปลี่ยนองศาปลายหนามตามพื้นที่ได้</li>
<li><strong>ทนทาน:</strong> ทนแดด ทนฝน ทนกรด-ด่าง เหมาะกับสภาพอากาศเมืองไทย</li>
<li><strong>อายุการใช้งาน:</strong> ยาวนาน ไม่เป็นสนิมตลอดอายุการใช้งาน (Corrosion Resistant)</li>
<li><strong>เหมาะกับ:</strong> ขอบหน้าต่าง ราวกันตก ป้ายอาคาร ชายคา งานภายนอกอาคาร</li>
</ul>
</div>
<!-- /wp:column -->
<!-- wp:column {"width":"50%"} -->
<div class="wp-block-column" style="flex-basis:50%;">
<!-- wp:image -->
<figure class="wp-block-image"><img src="' . $unsplash['spikes'] . '" alt="หนามกันนก สแตนเลส"/></figure>
<!-- /wp:image -->
<!-- wp:image -->
<figure class="wp-block-image"><img src="' . $unsplash['work3'] . '" alt="หนามกันนก ติดตั้ง"/></figure>
<!-- /wp:image -->
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->

<!-- wp:separator {"className":"is-style-wide"} -->
<hr class="wp-block-separator has-alpha-channel-opacity is-style-wide"/>
<!-- /wp:separator -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">เจลไล่นก</h2>
<!-- /wp:heading -->

<!-- wp:columns -->
<div class="wp-block-columns">
<!-- wp:column {"width":"50%"} -->
<div class="wp-block-column" style="flex-basis:50%;">
<p>เจลไล่นกสูตรพิเศษ <strong>Non-toxic & UV-resistant</strong> ใช้สำหรับพื้นที่ที่ไม่สามารถติดตั้งตาข่ายหรือหนามกันนกได้ เหมาะกับพื้นที่เน้นความสวยงาม</p>
<ul>
<li><strong>วัสดุ:</strong> Polycarbonate-based gel สูตรไม่มีสารพิษ (Non-toxic)</li>
<li>ทนรังสียูวี (UV-Resistant) ไม่ละลายในแสงแดด</li>
<li>ปลอดภัยต่อคนและสัตว์ ใช้ได้กับทุกพื้นผิว</li>
<li>ไม่ทิ้งคราบ ไม่เสียหาย มองไม่เห็นจากภายนอก</li>
<li>เหมาะกับ: ขอบระเบียง ราวกันตก ขอบหน้าต่าง พื้นที่แคบ</li>
</ul>
</div>
<!-- /wp:column -->
<!-- wp:column {"width":"50%"} -->
<div class="wp-block-column" style="flex-basis:50%;">
<!-- wp:image -->
<figure class="wp-block-image"><img src="' . $unsplash['gel'] . '" alt="เจลไล่นก"/></figure>
<!-- /wp:image -->
<!-- wp:image -->
<figure class="wp-block-image"><img src="' . $unsplash['work4'] . '" alt="เจลไล่นก ใช้งาน"/></figure>
<!-- /wp:image -->
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->

<!-- wp:separator {"className":"is-style-wide"} -->
<hr class="wp-block-separator has-alpha-channel-opacity is-style-wide"/>
<!-- /wp:separator -->

<!-- wp:heading {"textAlign":"center","level":2} -->
<h2 class="has-text-align-center wp-block-heading">เปรียบเทียบบริการ</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">เลือกวิธีป้องกันนกที่เหมาะสมกับพื้นที่ของคุณ</p>
<!-- /wp:paragraph -->

<!-- wp:html -->
<div class="comparison-table" style="overflow-x:auto;margin:1.5rem 0;">
<table style="width:100%;border-collapse:collapse;border-radius:12px;overflow:hidden;box-shadow:0 2px 12px rgba(0,0,0,0.06);">
<thead>
<tr style="background:#1B4D5C;color:#fff;">
<th style="padding:14px 16px;text-align:left;font-weight:600;">คุณสมบัติ</th>
<th style="padding:14px 16px;text-align:center;font-weight:600;">ตาข่าย HDPE</th>
<th style="padding:14px 16px;text-align:center;font-weight:600;">หนามสแตนเลส</th>
<th style="padding:14px 16px;text-align:center;font-weight:600;">เจลไล่นก</th>
</tr>
</thead>
<tbody>
<tr style="background:#fff;"><td style="padding:12px 16px;border-bottom:1px solid #eee;font-weight:600;">พื้นที่เหมาะสม</td><td style="padding:12px 16px;border-bottom:1px solid #eee;text-align:center;">ระเบียง, ช่องเปิดขนาดใหญ่, โรงงาน</td><td style="padding:12px 16px;border-bottom:1px solid #eee;text-align:center;">ขอบหน้าต่าง, ราวกันตก, ชายคา</td><td style="padding:12px 16px;border-bottom:1px solid #eee;text-align:center;">พื้นที่แคบ, ขอบระเบียง, ป้าย</td></tr>
<tr style="background:#f9f9f9;"><td style="padding:12px 16px;border-bottom:1px solid #eee;font-weight:600;">อายุการใช้งาน</td><td style="padding:12px 16px;border-bottom:1px solid #eee;text-align:center;">6-7 ปี</td><td style="padding:12px 16px;border-bottom:1px solid #eee;text-align:center;">10+ ปี (ไม่เป็นสนิม)</td><td style="padding:12px 16px;border-bottom:1px solid #eee;text-align:center;">1-2 ปี</td></tr>
<tr style="background:#fff;"><td style="padding:12px 16px;border-bottom:1px solid #eee;font-weight:600;">ประสิทธิภาพ</td><td style="padding:12px 16px;border-bottom:1px solid #eee;text-align:center;">★★★★★</td><td style="padding:12px 16px;border-bottom:1px solid #eee;text-align:center;">★★★★☆</td><td style="padding:12px 16px;border-bottom:1px solid #eee;text-align:center;">★★★☆☆</td></tr>
<tr style="background:#f9f9f9;"><td style="padding:12px 16px;border-bottom:1px solid #eee;font-weight:600;">ราคา</td><td style="padding:12px 16px;border-bottom:1px solid #eee;text-align:center;">ปานกลาง</td><td style="padding:12px 16px;border-bottom:1px solid #eee;text-align:center;">ประหยัด</td><td style="padding:12px 16px;border-bottom:1px solid #eee;text-align:center;">ประหยัดที่สุด</td></tr>
<tr style="background:#fff;"><td style="padding:12px 16px;border-bottom:1px solid #eee;font-weight:600;">ปลอดภัยต่อนก</td><td style="padding:12px 16px;border-bottom:1px solid #eee;text-align:center;">✓ ไม่ทำร้ายนก</td><td style="padding:12px 16px;border-bottom:1px solid #eee;text-align:center;">✓ ไม่ทำร้ายนก</td><td style="padding:12px 16px;border-bottom:1px solid #eee;text-align:center;">✓ ไม่มีสารพิษ</td></tr>
<tr style="background:#f9f9f9;"><td style="padding:12px 16px;font-weight:600;">รับประกัน</td><td style="padding:12px 16px;text-align:center;color:#E8792E;font-weight:700;">3 ปี</td><td style="padding:12px 16px;text-align:center;color:#E8792E;font-weight:700;">3 ปี</td><td style="padding:12px 16px;text-align:center;color:#E8792E;font-weight:700;">1 ปี</td></tr>
</tbody>
</table>
</div>
<!-- /wp:html -->

<!-- wp:separator {"className":"is-style-wide"} -->
<hr class="wp-block-separator has-alpha-channel-opacity is-style-wide"/>
<!-- /wp:separator -->

<!-- wp:paragraph {"align":"center","fontSize":"large"} -->
<p class="has-text-align-center has-large-font-size"><strong>สนใจบริการ? ปรึกษาฟรี!</strong></p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons">
<!-- wp:button {"backgroundColor":"vivid-green-cyan"} -->
<div class="wp-block-button"><a class="wp-block-button__link has-vivid-green-cyan-background-color has-background" href="/contact">ขอใบเสนอราคาฟรี</a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->
';

// ===== PORTFOLIO PAGE =====
// Project list from company profile PDF (40+ real projects) with categories
$projects = array(
    // อาคารราชการ & สถาบัน
    array('name' => 'อาคารสำนักงาน ป.ป.ช. ภาค4', 'location' => 'จังหวัดขอนแก่น', 'page' => 7, 'cat' => 'อาคารราชการ', 'desc' => 'ติดตั้งตาข่าย HDPE ป้องกันนกพิราบ อาคารราชการ 5 ชั้น แก้ปัญหาขี้นกตามระเบียง'),
    array('name' => 'อู่ราชนาวีมหิดลอดุลยเดช กรมอู่ทหารเรือ', 'location' => 'จังหวัดชลบุรี', 'page' => 9, 'cat' => 'อาคารราชการ', 'desc' => 'ติดตั้งตาข่ายกันนกอาคารทหาร พื้นที่กว้าง ทีมโรยตัวพร้อมอุปกรณ์ Safety ครบ'),
    array('name' => 'หอพักชาย 7 มหาวิทยาลัยขอนแก่น', 'location' => 'จังหวัดขอนแก่น', 'page' => 10, 'cat' => 'อาคารราชการ', 'desc' => 'ติดตั้งตาข่าย HDPE ระเบียงหอพัก 6 ชั้น จบปัญหานกทำรังถาวร'),
    array('name' => 'หอพักชาย 8 มหาวิทยาลัยขอนแก่น', 'location' => 'จังหวัดขอนแก่น', 'page' => 11, 'cat' => 'อาคารราชการ', 'desc' => 'ติดตั้งตาข่ายกันนก ระเบียง+ช่องแสง ป้องกันนกเข้าอาคาร'),
    array('name' => 'หอพัก 21-23 มหาวิทยาลัยขอนแก่น', 'location' => 'จังหวัดขอนแก่น', 'page' => 12, 'cat' => 'อาคารราชการ', 'desc' => 'ติดตั้งตาข่ายกันนก 3 อาคาร พื้นที่รวมกว่า 300 ตร.ม.'),
    array('name' => 'วิทยาลัยสาธารณสุขสิรินธร อาคาร 10 ชั้น', 'location' => 'จังหวัดขอนแก่น', 'page' => 13, 'cat' => 'อาคารราชการ', 'desc' => 'ติดตั้งตาข่ายกันนก อาคารสูง 10 ชั้น ทีมโรยตัวมืออาชีพ'),
    array('name' => 'หอพักแพทย์ โรงพยาบาลขอนแก่น', 'location' => 'จังหวัดขอนแก่น', 'page' => 16, 'cat' => 'อาคารราชการ', 'desc' => 'ติดตั้งตาข่าย HDPE ระเบียงหอพักแพทย์ แก้ปัญหาขี้นก+สุขอนามัย'),
    array('name' => 'อาคารสิริภักษ์ สำนักงานคลังจังหวัด', 'location' => 'จังหวัดขอนแก่น', 'page' => 18, 'cat' => 'อาคารราชการ', 'desc' => 'ติดตั้งหนามกันนกสแตนเลส 304 ขอบหน้าต่างอาคารราชการ'),
    array('name' => 'หอพักพยาบาล โรงพยาบาลขอนแก่น', 'location' => 'จังหวัดขอนแก่น', 'page' => 19, 'cat' => 'อาคารราชการ', 'desc' => 'ติดตั้งตาข่ายกันนกระเบียง ป้องกันนกทำรังในพื้นที่สุขอนามัย'),
    array('name' => 'หอพักพยาบาล โรงพยาบาลสิรินธร', 'location' => 'จังหวัดขอนแก่น', 'page' => 20, 'cat' => 'อาคารราชการ', 'desc' => 'ติดตั้งตาข่าย HDPE ครอบคลุมทั้งอาคาร จบปัญหา 100%'),
    array('name' => 'หอพักแพทย์ มหาวิทยาลัยขอนแก่น', 'location' => 'จังหวัดขอนแก่น', 'page' => 21, 'cat' => 'อาคารราชการ', 'desc' => 'ติดตั้งตาข่ายกันนกระเบียง+ช่องแสง อาคารสูง วิศวกรคุมงาน'),
    // โรงงาน & โกดัง
    array('name' => 'โรงงาน DOS', 'location' => 'สาขาขอนแก่น', 'page' => 15, 'cat' => 'โรงงาน', 'desc' => 'ติดตั้งตาข่ายกันนกโรงงานอุตสาหกรรม พื้นที่กว้าง หลังคาสูง'),
    array('name' => 'โกดัง บริษัทอินเวนทิโว คอสเมติก จำกัด', 'location' => 'จังหวัดมหาสารคาม', 'page' => 24, 'cat' => 'โรงงาน', 'desc' => 'ติดตั้งตาข่ายกันนกโกดังเก็บสินค้า ป้องกันมูลนกปนเปื้อนผลิตภัณฑ์'),
    array('name' => 'โกดังให้เช่า A6', 'location' => 'จังหวัดเชียงใหม่', 'page' => 25, 'cat' => 'โรงงาน', 'desc' => 'ติดตั้งตาข่ายกันนก โกดังให้เช่า ป้องกันนกทำรังใต้หลังคา'),
    array('name' => 'โกดังสินค้า อำเภอสารภี', 'location' => 'จังหวัดเชียงใหม่', 'page' => 28, 'cat' => 'โรงงาน', 'desc' => 'ติดตั้งตาข่ายกันนก โกดังสินค้า พื้นที่กว้าง เสร็จใน 2 วัน'),
    // คอนโด & หอพัก
    array('name' => 'หอพัก TRIPLE T RESIDENCE KKU', 'location' => 'จังหวัดขอนแก่น', 'page' => 30, 'cat' => 'คอนโด', 'desc' => 'ติดตั้งตาข่าย HDPE ระเบียงหอพักนักศึกษา หลายห้อง'),
    array('name' => 'คอนโดฉัตรเพชร โนนม่วง', 'location' => 'จังหวัดขอนแก่น', 'page' => 34, 'cat' => 'คอนโด', 'desc' => 'ติดตั้งตาข่ายกันนกระเบียงคอนโด แทบมองไม่เห็น สวยเรียบร้อย'),
    array('name' => 'เมโทรคอนโด (METRO CONDO)', 'location' => 'จังหวัดขอนแก่น', 'page' => 35, 'cat' => 'คอนโด', 'desc' => 'ติดตั้งตาข่ายกันนกคอนโด ป้องกันนกเข้าระเบียง+ห้องนอน'),
    array('name' => 'คอนโดมิเนียม X10 ศรีนครินทร์', 'location' => 'จังหวัดขอนแก่น', 'page' => 40, 'cat' => 'คอนโด', 'desc' => 'ติดตั้งตาข่าย HDPE ระเบียงคอนโดหรู เสร็จใน 3 ชม.'),
    array('name' => 'เดอะ เดสทินี เอ็กคลูซีพ คอนโดมิเนียม', 'location' => 'จังหวัดขอนแก่น', 'page' => 42, 'cat' => 'คอนโด', 'desc' => 'ติดตั้งตาข่ายกันนกคอนโดหรู งานละเอียดเรียบร้อย'),
    array('name' => 'ชาลิสา คอนโด', 'location' => 'จังหวัดขอนแก่น', 'page' => 43, 'cat' => 'คอนโด', 'desc' => 'ติดตั้งตาข่ายกันนก ระเบียงคอนโด มองจากภายนอกแทบไม่เห็น'),
    array('name' => 'ESCENT CONDO', 'location' => 'จังหวัดขอนแก่น', 'page' => 45, 'cat' => 'คอนโด', 'desc' => 'ติดตั้งตาข่าย HDPE คอนโดสูง ทีมโรยตัวมืออาชีพ'),
    array('name' => 'เอพี บูเลอวาร์ด คอนโด', 'location' => 'จังหวัดขอนแก่น', 'page' => 46, 'cat' => 'คอนโด', 'desc' => 'ติดตั้งตาข่ายกันนกระเบียงคอนโด จบปัญหานกพิราบ 100%'),
    array('name' => 'เดอะ เบส ไฮท์ มิตรภาพ', 'location' => 'จังหวัดขอนแก่น', 'page' => 49, 'cat' => 'คอนโด', 'desc' => 'ติดตั้งตาข่ายกันนก คอนโดสูง ถ.มิตรภาพ งานเสร็จเรียบร้อย'),
    array('name' => 'คอนโดกัลปพฤกษ์ เลควิว', 'location' => 'จังหวัดขอนแก่น', 'page' => 50, 'cat' => 'คอนโด', 'desc' => 'ติดตั้งตาข่ายกันนก ระเบียงวิวทะเลสาบ ไม่บังวิว'),
    // บ้านพักอาศัย
    array('name' => 'บ้านพักอธิการบดีอัยการภาค4', 'location' => 'จังหวัดขอนแก่น', 'page' => 22, 'cat' => 'บ้านพักอาศัย', 'desc' => 'ติดตั้งตาข่ายกันนก บ้านพักผู้บริหาร ใต้หลังคา+ชายคา'),
    array('name' => 'หมู่บ้านสีวลี', 'location' => 'อำเภอเมืองขอนแก่น', 'page' => 33, 'cat' => 'บ้านพักอาศัย', 'desc' => 'ติดตั้งตาข่ายกันนก บ้านจัดสรร แก้ปัญหานกทำรังใต้หลังคา'),
    array('name' => 'หมู่บ้านเออเบินนารา แอร์พอร์ต-บายพาส', 'location' => 'จังหวัดขอนแก่น', 'page' => 36, 'cat' => 'บ้านพักอาศัย', 'desc' => 'ติดตั้งตาข่ายกันนก บ้านพักอาศัย ป้องกันนกเข้าพื้นที่ซักล้าง'),
    array('name' => 'หมู่บ้าน KLEVER TYME', 'location' => 'จังหวัดขอนแก่น', 'page' => 39, 'cat' => 'บ้านพักอาศัย', 'desc' => 'ติดตั้งตาข่ายกันนก หมู่บ้านจัดสรร ป้องกันนกเข้าชายคา'),
    // อาคารพาณิชย์ & คลินิก
    array('name' => 'ตั้งฮ่งหลี อาคารพาณิชย์ 4 ชั้น', 'location' => 'จังหวัดขอนแก่น', 'page' => 27, 'cat' => 'อาคารพาณิชย์', 'desc' => 'ติดตั้งตาข่ายกันนก อาคารพาณิชย์ 4 ชั้น กันนกทำรังหน้าร้าน'),
    array('name' => 'คลินิกกายภาพ รีเฟรชชี่', 'location' => 'จังหวัดขอนแก่น', 'page' => 29, 'cat' => 'อาคารพาณิชย์', 'desc' => 'ติดตั้งตาข่ายกันนก คลินิก ป้องกันมูลนกในพื้นที่สุขอนามัย'),
);

$portfolio_content = '
<!-- wp:heading {"textAlign":"center","level":2} -->
<h2 class="has-text-align-center wp-block-heading">ผลงานของเรา</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center","fontSize":"medium"} -->
<p class="has-text-align-center has-medium-font-size">ผลงานติดตั้งตาข่ายกันนกจริงกว่า <strong>40+ โปรเจกต์</strong> ทั้งอาคารราชการ คอนโด โรงพยาบาล มหาวิทยาลัย โรงงาน และบ้านพักอาศัย<br>ให้บริการ<strong>ติดตั้งตาข่ายกันนก ขอนแก่น เชียงใหม่ ชลบุรี ภาคอีสาน</strong></p>
<!-- /wp:paragraph -->

<!-- wp:separator {"className":"is-style-wide"} -->
<hr class="wp-block-separator has-alpha-channel-opacity is-style-wide"/>
<!-- /wp:separator -->';

// Projects with multiple photos (top featured projects)
$multi_photo_pages = array(7, 9, 10, 13, 20, 22, 35, 45);

// Group projects by category
$categories = array();
foreach ($projects as $project) {
    $cat = isset($project['cat']) ? $project['cat'] : 'อื่นๆ';
    if (!isset($categories[$cat])) {
        $categories[$cat] = array();
    }
    $categories[$cat][] = $project;
}

// Generate portfolio grid grouped by category
foreach ($categories as $cat_name => $cat_projects) {
    $portfolio_content .= '
<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">' . $cat_name . ' (' . count($cat_projects) . ' โปรเจกต์)</h3>
<!-- /wp:heading -->';

    $count = 0;
    $total = count($cat_projects);
    foreach ($cat_projects as $project) {
        $count++;
        if ($count % 3 == 1) {
            $portfolio_content .= "\n<!-- wp:columns -->\n<div class=\"wp-block-columns\">";
        }
        
        $has_multi = in_array($project['page'], $multi_photo_pages);
        $gallery_html = '';
        if ($has_multi) {
            $gallery_html = '<div class="project-gallery" style="display:flex;gap:4px;margin-top:6px;">
<img src="' . project_img($project['page'], 2) . '" alt="' . $project['name'] . ' #2" style="width:48%;border-radius:8px;aspect-ratio:4/3;object-fit:cover;" loading="lazy"/>
<img src="' . project_img($project['page'], 3) . '" alt="' . $project['name'] . ' #3" style="width:48%;border-radius:8px;aspect-ratio:4/3;object-fit:cover;" loading="lazy"/>
</div>';
        }
        
        $alt_text = isset($project['desc']) ? 'ติดตั้งตาข่ายกันนก ' . $project['name'] . ' ' . $project['location'] : $project['name'];
        $desc_html = isset($project['desc']) ? '<p style="color:#555;font-size:0.82em;margin:4px 0 0;line-height:1.4;">' . $project['desc'] . '</p>' : '';
        
        $portfolio_content .= '
<!-- wp:column -->
<div class="wp-block-column" style="text-align:center;">
<!-- wp:image {"sizeSlug":"medium"} -->
<figure class="wp-block-image size-medium"><img src="' . project_img($project['page']) . '" alt="' . $alt_text . '" style="border-radius:12px;object-fit:cover;aspect-ratio:4/3;width:100%;" loading="lazy"/></figure>
<!-- /wp:image -->
' . $gallery_html . '
<h4 class="wp-block-heading">' . $project['name'] . '</h4>
<p style="color:#666;font-size:0.9em;margin:0;">' . $project['location'] . '</p>
' . $desc_html . '
</div>
<!-- /wp:column -->';
        if ($count % 3 == 0 || $count == $total) {
            if ($count == $total && $count % 3 != 0) {
                $remaining = 3 - ($count % 3);
                for ($r = 0; $r < $remaining; $r++) {
                    $portfolio_content .= "\n<!-- wp:column -->\n<div class=\"wp-block-column\"></div>\n<!-- /wp:column -->";
                }
            }
            $portfolio_content .= "\n</div>\n<!-- /wp:columns -->";
        }
    }
    
    $portfolio_content .= "\n<!-- wp:separator {\"className\":\"is-style-wide\"} -->\n<hr class=\"wp-block-separator has-alpha-channel-opacity is-style-wide\"/>\n<!-- /wp:separator -->";
}

$portfolio_content .= '

<!-- wp:separator {"className":"is-style-wide"} -->
<hr class="wp-block-separator has-alpha-channel-opacity is-style-wide"/>
<!-- /wp:separator -->

<!-- wp:heading {"textAlign":"center","level":2} -->
<h2 class="has-text-align-center wp-block-heading">วิดีโอผลงาน</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">ดูคลิปขั้นตอนการทำงานจริง จากหน้างาน</p>
<!-- /wp:paragraph -->';

$video_captions = array(
    1 => 'เบื่อไหม? ขี้นกเต็มระเบียงคอนโด ล้างเท่าไหร่ก็ไม่หมด... เราช่วยได้! ติดตั้งตาข่าย HDPE เกรดส่งออก กลมกลืนกับตัวอาคาร นกหายขาด 100% 📞 ประเมินฟรี โทร 062-996-4994',
    2 => 'นกพิราบเกาะราวระเบียงทุกวัน? จบปัญหาด้วยหนามกันนกสแตนเลส SUS304 ไม่เป็นสนิม ทนแดดทนฝน 5+ ปี งานเนี๊ยบ กลมกลืนกับตัวบ้าน 💬 สนใจทัก LINE: oil_phanu',
    3 => 'นกทำรังใต้หลังคาร้าน ขี้นกหล่นใส่สินค้า? ดูคลิปนี้! ปิดช่องเปิดด้วยตาข่ายกันนก แข็งแรง ทนทาน กันนกเข้า 100% รับประกันงาน 3 ปี 📞 โทร 062-996-4994',
    4 => 'ยกระดับมาตรฐานความสะอาดให้โรงงานของคุณ ด้วยตาข่ายกันนกแบบมืออาชีพ ทีมงาน Safety ครบชุด งานไว ไม่กระทบการผลิต 💬 ติดต่อได้ทันที LINE: oil_phanu',
    5 => 'อาคารสวยแต่นกเกาะเต็ม? เจลไล่นกคือคำตอบ! วัสดุใสมองไม่เห็น ไม่ทำลายทัศนียภาพ นกไม่กล้าเกาะอีกเลย ผ่านมา 3 ปียังใช้ได้ดี 📞 ประเมินฟรี โทร 062-996-4994',
    6 => 'นกทำรังใต้แผงโซลาร์เซลล์? เสี่ยงไฟฟ้าลัดวงจร! ติดตั้งแผงกันนกโซลาร์ ปกป้องแผงโซลาร์ ไม่กระทบประสิทธิภาพ ปลอดภัย 100% 💬 ทัก LINE: oil_phanu',
);
for ($i = 1; $i <= 6; $i++) {
    if ($i % 3 == 1) $portfolio_content .= "\n<!-- wp:columns -->\n<div class=\"wp-block-columns\">";
    $portfolio_content .= '
<!-- wp:column -->
<div class="wp-block-column">
<!-- wp:video -->
<figure class="wp-block-video"><video controls src="' . vid($i) . '"></video><figcaption>' . $video_captions[$i] . '</figcaption></figure>
<!-- /wp:video -->
</div>
<!-- /wp:column -->';
    if ($i % 3 == 0) $portfolio_content .= "\n</div>\n<!-- /wp:columns -->";
}

$portfolio_content .= '

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons">
<!-- wp:button {"backgroundColor":"vivid-green-cyan"} -->
<div class="wp-block-button"><a class="wp-block-button__link has-vivid-green-cyan-background-color has-background" href="/contact">ขอใบเสนอราคาฟรี</a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->
';

// ===== ABOUT PAGE =====
$about_content = '
<!-- wp:heading {"textAlign":"center","level":2} -->
<h2 class="has-text-align-center wp-block-heading">เกี่ยวกับ BIRDS GO AWAY</h2>
<!-- /wp:heading -->

<!-- wp:columns -->
<div class="wp-block-columns">
<!-- wp:column {"width":"60%"} -->
<div class="wp-block-column" style="flex-basis:60%;">
<p><strong>บริษัท รีเช็ค บิ้วดิ้ง จำกัด (Recheck Building Co.,Ltd.)</strong> ดำเนินธุรกิจภายใต้แบรนด์ <strong>BIRDS GO AWAY</strong> ให้บริการติดตั้งตาข่ายกันนก หนามกันนก เจลไล่นก และแผงกันนกโซลาร์เซลล์ ครบวงจร</p>

<h3 class="wp-block-heading">ข้อมูลบริษัท</h3>
<ul>
<li>จดทะเบียนเป็นนิติบุคคล ตามประมวลกฎหมายแพ่งและพาณิชย์ ถูกต้องตามกฎหมาย</li>
<li><strong>เลขทะเบียน:</strong> 0405567000088</li>
<li><strong>ที่อยู่:</strong> 88/38 หมู่บ้าน Klever ซอย5 ตำบลบ้านเป็ด อำเภอเมือง จังหวัดขอนแก่น 40000</li>
</ul>

<h3 class="wp-block-heading">ใบรับรองและมาตรฐาน</h3>
<ul>
<li>ผ่านการอบรมโรยตัวที่สูง ทั้งภาคทฤษฎี-ปฏิบัติ (Rappelling for Working Certificate)</li>
<li>มีใบประกอบวิชาชีพวิศวกรควบคุม (กว.) จากสภาวิศวกร</li>
<li>ผ่านการอบรมหลักสูตรเจ้าหน้าที่ความปลอดภัยในการทำงานระดับหัวหน้างาน</li>
<li>วิศวกรคุมงานทุกไซต์</li>
<li>ใช้อุปกรณ์ความปลอดภัยครบชุด</li>
</ul>
</div>
<!-- /wp:column -->
<!-- wp:column {"width":"40%"} -->
<div class="wp-block-column" style="flex-basis:40%;">
<!-- wp:image {"sizeSlug":"large"} -->
<figure class="wp-block-image size-large"><img src="' . $unsplash['about'] . '" alt="ทีมงาน Birds Go Away ติดตั้งตาข่ายกันนก พร้อมอุปกรณ์ Safety" style="border-radius:12px;width:100%;"/></figure>
<!-- /wp:image -->
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->

<!-- wp:separator {"className":"is-style-wide"} -->
<hr class="wp-block-separator has-alpha-channel-opacity is-style-wide"/>
<!-- /wp:separator -->

<!-- wp:heading {"textAlign":"center","level":2} -->
<h2 class="has-text-align-center wp-block-heading">ทีมงานมืออาชีพ พร้อมอุปกรณ์ Safety ครบชุด</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">ช่างทุกคนผ่านอบรมการทำงานบนที่สูง พร้อมอุปกรณ์ความปลอดภัยมาตรฐาน ลูกค้ากลุ่มโรงงานและอาคารสำนักงานวางใจได้</p>
<!-- /wp:paragraph -->

<!-- wp:columns -->
<div class="wp-block-columns">
<!-- wp:column -->
<div class="wp-block-column" style="text-align:center;">
<!-- wp:image {"sizeSlug":"medium"} -->
<figure class="wp-block-image size-medium"><img src="' . $unsplash['team'] . '" alt="ทีมช่างติดตั้งตาข่ายกันนก พร้อมชุดฟอร์มและอุปกรณ์ Safety" style="border-radius:12px;object-fit:cover;aspect-ratio:4/3;width:100%;" loading="lazy"/></figure>
<!-- /wp:image -->
<p style="font-size:0.85rem;color:#555;margin-top:8px;"><strong>ทีมช่างมืออาชีพ</strong><br>ชุดฟอร์มพร้อมอุปกรณ์ Safety ครบชุด</p>
</div>
<!-- /wp:column -->
<!-- wp:column -->
<div class="wp-block-column" style="text-align:center;">
<!-- wp:image {"sizeSlug":"medium"} -->
<figure class="wp-block-image size-medium"><img src="' . $unsplash['safety'] . '" alt="อุปกรณ์ Safety โรยตัว หมวกนิรภัย สายรัดนิรภัย ทีมงาน Birds Go Away" style="border-radius:12px;object-fit:cover;aspect-ratio:4/3;width:100%;" loading="lazy"/></figure>
<!-- /wp:image -->
<p style="font-size:0.85rem;color:#555;margin-top:8px;"><strong>อุปกรณ์ Safety มาตรฐาน</strong><br>หมวกนิรภัย สายรัด เชือกโรยตัว</p>
</div>
<!-- /wp:column -->
<!-- wp:column -->
<div class="wp-block-column" style="text-align:center;">
<!-- wp:image {"sizeSlug":"medium"} -->
<figure class="wp-block-image size-medium"><img src="' . $unsplash['work1'] . '" alt="ทีมงานกำลังติดตั้งตาข่ายกันนก บนอาคารสูง โรยตัว" style="border-radius:12px;object-fit:cover;aspect-ratio:4/3;width:100%;" loading="lazy"/></figure>
<!-- /wp:image -->
<p style="font-size:0.85rem;color:#555;margin-top:8px;"><strong>ปฏิบัติงานจริง</strong><br>โรยตัวติดตั้งตาข่ายอาคารสูง</p>
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->

<!-- wp:separator {"className":"is-style-wide"} -->
<hr class="wp-block-separator has-alpha-channel-opacity is-style-wide"/>
<!-- /wp:separator -->

<!-- wp:heading {"textAlign":"center","level":2} -->
<h2 class="has-text-align-center wp-block-heading">ใบรับรองบริษัท</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">บริษัท รีเช็ค บิ้วดิ้ง จำกัด จดทะเบียนถูกต้องตามกฎหมาย พร้อมใบรับรองมาตรฐานความปลอดภัย</p>
<!-- /wp:paragraph -->

<!-- wp:columns -->
<div class="wp-block-columns">
<!-- wp:column {"width":"50%"} -->
<div class="wp-block-column" style="flex-basis:50%;">
<!-- wp:image {"sizeSlug":"large"} -->
<figure class="wp-block-image size-large"><img src="' . $assets_base . '/cert-page-2.jpeg" alt="หนังสือรับรองบริษัท เลขทะเบียน 0405567000088" style="border-radius:12px;"/></figure>
<!-- /wp:image -->
<p style="text-align:center;font-size:0.9em;color:#666;"><strong>หนังสือรับรองจดทะเบียนบริษัท</strong><br>กรมพัฒนาธุรกิจการค้า</p>
</div>
<!-- /wp:column -->
<!-- wp:column {"width":"50%"} -->
<div class="wp-block-column" style="flex-basis:50%;">
<!-- wp:image {"sizeSlug":"large"} -->
<figure class="wp-block-image size-large"><img src="' . $assets_base . '/cert-page-3.jpeg" alt="ใบรับรอง SAFESIRI โรยตัว, ใบ กว. สภาวิศวกร, ใบ จป." style="border-radius:12px;"/></figure>
<!-- /wp:image -->
<p style="text-align:center;font-size:0.9em;color:#666;"><strong>ใบรับรองโรยตัว SAFESIRI / ใบ กว. / ใบ จป.</strong><br>มาตรฐานความปลอดภัยครบถ้วน</p>
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->

<!-- wp:separator {"className":"is-style-wide"} -->
<hr class="wp-block-separator has-alpha-channel-opacity is-style-wide"/>
<!-- /wp:separator -->

<!-- wp:heading {"textAlign":"center","level":2} -->
<h2 class="has-text-align-center wp-block-heading">พื้นที่ให้บริการ</h2>
<!-- /wp:heading -->

<!-- wp:columns -->
<div class="wp-block-columns">
<!-- wp:column -->
<div class="wp-block-column" style="text-align:center;border:1px solid rgba(255,255,255,0.06);">
<h3 class="wp-block-heading">ขอนแก่น</h3>
<p><a href="tel:0629964994"><strong>062-996-4994</strong></a><br>LINE: oil_phanu</p>
</div>
<!-- /wp:column -->
<!-- wp:column -->
<div class="wp-block-column" style="text-align:center;border:1px solid rgba(255,255,255,0.06);">
<h3 class="wp-block-heading">เชียงใหม่</h3>
<p><a href="tel:0936415623"><strong>093-641-5623</strong></a><br>LINE: th3-ta006-2</p>
</div>
<!-- /wp:column -->
<!-- wp:column -->
<div class="wp-block-column" style="text-align:center;border:1px solid rgba(255,255,255,0.06);">
<h3 class="wp-block-heading">ชลบุรี</h3>
<p><a href="tel:0956292488"><strong>095-629-2488</strong></a><br>LINE: oil_phanu</p>
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">อีเมล: <a href="mailto:admin@birdsgoaway.com">admin@birdsgoaway.com</a></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">Facebook: <a href="https://www.facebook.com/share/1ZAXHsxCft/?mibextid=wwXIfr" target="_blank" rel="noopener">ตาข่ายกันนก by Birds Go Away</a></p>
<!-- /wp:paragraph -->
';

// ===== CONTACT PAGE =====
$contact_content = '
<!-- wp:heading {"textAlign":"center","level":2} -->
<h2 class="has-text-align-center wp-block-heading">ติดต่อเรา</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">ปรึกษาฟรี! ติดต่อเราวันนี้เพื่อรับใบเสนอราคา</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"align":"center","fontSize":"small"} -->
<p class="has-text-align-center has-small-font-size" style="color:#E8792E;font-weight:600;">🚀 ยินดีให้บริการด่วนในพื้นที่ขอนแก่นและจังหวัดใกล้เคียง — นัดประเมินหน้างานได้ภายใน 24 ชม.</p>
<!-- /wp:paragraph -->

<!-- wp:html -->
<div style="max-width:600px;margin:0 auto;padding:0 1rem;">

<div style="background:#f8f9fa;border-radius:12px;padding:1.5rem;margin-bottom:1.5rem;">
<h3 style="color:#1B4D5C;margin:0 0 1rem;font-size:1.1rem;">📍 ที่อยู่</h3>
<p style="margin:0;color:#555;font-size:0.9rem;line-height:1.6;">88/38 หมู่บ้าน Klever ซอย5<br>ตำบลบ้านเป็ด อำเภอเมือง<br>จังหวัดขอนแก่น 40000</p>
</div>

<div style="background:#f8f9fa;border-radius:12px;padding:1.5rem;margin-bottom:1.5rem;">
<h3 style="color:#1B4D5C;margin:0 0 1rem;font-size:1.1rem;">📧 อีเมล</h3>
<p style="margin:0;"><a href="mailto:birdsgoaway.th@gmail.com" style="color:#E8792E;text-decoration:none;font-size:0.9rem;">birdsgoaway.th@gmail.com</a></p>
</div>

<div style="background:#f8f9fa;border-radius:12px;padding:1.5rem;margin-bottom:1.5rem;">
<h3 style="color:#1B4D5C;margin:0 0 1rem;font-size:1.1rem;">โทรศัพท์</h3>
<div style="display:flex;flex-direction:column;gap:8px;">
<a href="tel:0629964994" style="display:block;text-decoration:none;color:#1B4D5C;background:#fff;padding:12px 16px;border-radius:10px;border:1px solid #e2e8f0;">
<strong style="font-size:1rem;color:#E8792E;">062-996-4994</strong> <span style="color:#888;font-size:0.8rem;">— คุณออย (ขอนแก่น)</span></a>
<a href="tel:0889514924" style="display:block;text-decoration:none;color:#1B4D5C;background:#fff;padding:12px 16px;border-radius:10px;border:1px solid #e2e8f0;">
<strong style="font-size:1rem;color:#E8792E;">088-951-4924</strong> <span style="color:#888;font-size:0.8rem;">— คุณวีวี่ (ฝ่ายประเมินราคา)</span></a>
<a href="tel:0936415623" style="display:block;text-decoration:none;color:#1B4D5C;background:#fff;padding:12px 16px;border-radius:10px;border:1px solid #e2e8f0;">
<strong style="font-size:1rem;color:#E8792E;">093-641-5623</strong> <span style="color:#888;font-size:0.8rem;">— เชียงใหม่</span></a>
<a href="tel:0956292488" style="display:block;text-decoration:none;color:#1B4D5C;background:#fff;padding:12px 16px;border-radius:10px;border:1px solid #e2e8f0;">
<strong style="font-size:1rem;color:#E8792E;">095-629-2488</strong> <span style="color:#888;font-size:0.8rem;">— ชลบุรี</span></a>
</div>
</div>

<div style="background:#f8f9fa;border-radius:12px;padding:1.5rem;margin-bottom:1.5rem;">
<h3 style="color:#1B4D5C;margin:0 0 1rem;font-size:1.1rem;">LINE — ส่งรูปหน้างานประเมินราคาฟรี</h3>
<div style="display:flex;flex-direction:column;gap:8px;">
<a href="https://line.me/ti/p/~oil_phanu" target="_blank" style="display:block;background:#06C755;color:#fff;padding:12px 16px;border-radius:8px;font-weight:500;text-decoration:none;font-size:0.9rem;text-align:center;">
oil_phanu (ขอนแก่น)</a>
<a href="https://line.me/ti/p/~th3-ta006-2" target="_blank" style="display:block;background:#06C755;color:#fff;padding:12px 16px;border-radius:8px;font-weight:500;text-decoration:none;font-size:0.9rem;text-align:center;">
th3-ta006-2</a>
</div>
</div>

<div style="background:#f8f9fa;border-radius:12px;padding:1.5rem;margin-bottom:1.5rem;">
<h3 style="color:#1B4D5C;margin:0 0 1rem;font-size:1.1rem;">📘 Facebook</h3>
<p style="margin:0;"><a href="https://www.facebook.com/share/1ZAXHsxCft/?mibextid=wwXIfr" target="_blank" rel="noopener" style="color:#E8792E;text-decoration:none;font-size:0.9rem;">ตาข่ายกันนก by Birds Go Away</a></p>
</div>

</div>
<!-- /wp:html -->

<!-- wp:separator {"className":"is-style-wide"} -->
<hr class="wp-block-separator has-alpha-channel-opacity is-style-wide"/>
<!-- /wp:separator -->

<!-- wp:heading {"textAlign":"center","level":3} -->
<h3 class="has-text-align-center wp-block-heading">แผนที่สำนักงาน</h3>
<!-- /wp:heading -->

<!-- wp:html -->
<div style="width:100%;border-radius:12px;overflow:hidden;box-shadow:0 4px 12px rgba(0,0,0,0.08);">
<iframe src="https://maps.google.com/maps?q=88%2F38+%E0%B8%AB%E0%B8%A1%E0%B8%B9%E0%B9%88%E0%B8%9A%E0%B9%89%E0%B8%B2%E0%B8%99+Klever+%E0%B8%95%E0%B8%B3%E0%B8%9A%E0%B8%A5%E0%B8%9A%E0%B9%89%E0%B8%B2%E0%B8%99%E0%B9%80%E0%B8%9B%E0%B9%87%E0%B8%94+%E0%B8%82%E0%B8%AD%E0%B8%99%E0%B9%81%E0%B8%81%E0%B9%88%E0%B8%99+40000&t=&z=15&ie=UTF8&iwloc=&output=embed" width="100%" height="350" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
</div>
<!-- /wp:html -->

<!-- wp:separator {"className":"is-style-wide"} -->
<hr class="wp-block-separator has-alpha-channel-opacity is-style-wide"/>
<!-- /wp:separator -->

<!-- wp:heading {"textAlign":"center","level":3} -->
<h3 class="has-text-align-center wp-block-heading">เปิดให้บริการ</h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">สำนักงาน: จันทร์ - เสาร์ 08:00 - 17:00 น.<br>ปรึกษาฟรี (LINE/โทร): ทุกวัน 08:00 - 20:00 น.</p>
<!-- /wp:paragraph -->
';

// ===== FAQ PAGE =====
$faq_content = '
<!-- wp:heading {"textAlign":"center","level":2} -->
<h2 class="has-text-align-center wp-block-heading">คำถามที่พบบ่อย (FAQ)</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">รวมคำตอบสำหรับคำถามที่ลูกค้าสอบถามบ่อยที่สุด</p>
<!-- /wp:paragraph -->

<!-- wp:separator {"className":"is-style-wide"} -->
<hr class="wp-block-separator has-alpha-channel-opacity is-style-wide"/>
<!-- /wp:separator -->

<div class="faq-section">
<details class="faq-item" open>
<summary><strong>ตาข่ายกันนก HDPE มีอายุการใช้งานกี่ปี?</strong></summary>
<p>ตาข่าย HDPE ที่เราใช้มีอายุการใช้งาน <strong>6-7 ปี</strong> ผ่านการ UV Treatment ทนแดด ทนฝน เหมาะกับสภาพอากาศเมืองไทย พร้อมรับประกัน 3 ปี</p>
</details>

<details class="faq-item">
<summary><strong>ค่าบริการติดตั้งเริ่มต้นเท่าไหร่?</strong></summary>
<p>ค่าบริการขึ้นอยู่กับพื้นที่และความยากง่ายของงาน เราให้บริการ<strong>ประเมินราคาฟรี</strong> โดยวิศวกรจะไปสำรวจหน้างานและเสนอราคาให้ภายใน 1-2 วัน สามารถติดต่อสอบถามได้ที่ 062-996-4994</p>
</details>

<details class="faq-item">
<summary><strong>ขั้นตอนการทำงานเป็นอย่างไร?</strong></summary>
<div class="work-process">
<p><strong>ขั้นตอนที่ 1:</strong> สำรวจหน้างาน — วิศวกรไปดูพื้นที่จริง ประเมินปัญหาและวัดขนาด</p>
<p><strong>ขั้นตอนที่ 2:</strong> เสนอราคา — จัดทำใบเสนอราคาพร้อมแบบแปลน ภายใน 1-2 วัน</p>
<p><strong>ขั้นตอนที่ 3:</strong> นัดวันติดตั้ง — ตกลงราคาแล้วนัดวันทำงาน ทีมพร้อมอุปกรณ์ครบชุด</p>
<p><strong>ขั้นตอนที่ 4:</strong> ติดตั้ง — ทีมช่างพร้อมวิศวกรคุมงาน ใช้อุปกรณ์ความปลอดภัยครบ</p>
<p><strong>ขั้นตอนที่ 5:</strong> ตรวจรับงาน — ลูกค้าตรวจงานก่อนรับมอบ พร้อมรับประกันผลงาน</p>
</div>
</details>

<details class="faq-item">
<summary><strong>ให้บริการพื้นที่ไหนบ้าง?</strong></summary>
<p>เราให้บริการหลัก 3 ภูมิภาค:<br>
🟠 <strong>ขอนแก่น</strong> และจังหวัดใกล้เคียงในภาคอีสาน<br>
🟠 <strong>เชียงใหม่</strong> และจังหวัดภาคเหนือ<br>
🟠 <strong>ชลบุรี</strong> และจังหวัดภาคตะวันออก<br>
สำหรับพื้นที่อื่นๆ สามารถสอบถามได้เลยครับ</p>
</details>

<details class="faq-item">
<summary><strong>ติดตั้งตาข่ายกันนกแล้วจะทำให้อาคารดูไม่สวยไหม?</strong></summary>
<p>ตาข่าย HDPE ที่เราใช้เป็นสี <strong>Transparent Black (โปร่งแสง)</strong> เมื่อติดตั้งแล้วแทบมองไม่เห็นจากระยะไกล ไม่ทำให้อาคารดูเสียทัศนียภาพ เหมาะกับทุกรูปแบบอาคาร</p>
</details>

<details class="faq-item">
<summary><strong>ตาข่ายทนต่อสภาพอากาศได้ดีแค่ไหน?</strong></summary>
<p>ตาข่าย HDPE 2500D/1ply ของเรามี:<br>
• แรงดึงขาดจุดปม: <strong>13 kg</strong><br>
• แรงดึงขาดเส้นด้าย: <strong>7 kg</strong><br>
• ผ่าน UV Treatment ทนแดดจัด<br>
• ไม่เปื่อยยุ่ยจากฝน<br>
เหมาะกับสภาพอากาศร้อนชื้นของไทย</p>
</details>

<details class="faq-item">
<summary><strong>หนามกันนกสแตนเลสต่างจากหนามพลาสติกอย่างไร?</strong></summary>
<p>หนามสแตนเลส SUS304 ของเรา:<br>
• <strong>90 ขาต่อ 1 เมตร</strong> — ถี่กว่าหนามทั่วไป<br>
• ไม่เป็นสนิม ทนทานตลอดอายุการใช้งาน<br>
• ปรับองศาปลายหนามได้ตามพื้นที่<br>
• ทนแดด ทนฝน ไม่เสื่อมสภาพเร็วเหมือนพลาสติก</p>
</details>

<details class="faq-item">
<summary><strong>มีใบรับรองหรือมาตรฐานอะไรบ้าง?</strong></summary>
<p>บริษัทเรามี:<br>
✅ จดทะเบียนนิติบุคคลถูกต้อง (เลขทะเบียน 0405567000088)<br>
✅ ใบรับรองโรยตัว SAFESIRI (Rappelling for Working Certificate)<br>
✅ ใบประกอบวิชาชีพวิศวกร (กว.) จากสภาวิศวกร<br>
✅ ใบ จป. หัวหน้างาน (เจ้าหน้าที่ความปลอดภัย)<br>
✅ วิศวกรคุมงานทุกไซต์ + อุปกรณ์ความปลอดภัยครบชุด</p>
</details>

<details class="faq-item">
<summary><strong>ติดตั้งแล้วนกจะเจ็บไหม?</strong></summary>
<p>ไม่เจ็บครับ! ทุกวิธีที่เราใช้เป็น<strong>สันติวิธี 100%</strong> ไม่ทำร้ายและไม่ฆ่านก:<br>
🕊️ <strong>ตาข่าย HDPE</strong> — กันไม่ให้นกเข้าพื้นที่ ไม่มีส่วนแหลมคม<br>
🕊️ <strong>หนามสแตนเลส</strong> — ปลายมนทำให้นกไม่สามารถเกาะได้ แต่ไม่ทิ่มแทง<br>
🕊️ <strong>เจลไล่นก</strong> — สารธรรมชาติ นกไม่ชอบสัมผัส จะบินหนีเอง<br>
เราเน้นการป้องกัน ไม่ใช่การทำลาย ปลอดภัยทั้งต่อนก คน และสัตว์เลี้ยง</p>
</details>

<details class="faq-item">
<summary><strong>การรับประกันกี่ปี? ครอบคลุมอะไรบ้าง?</strong></summary>
<p><strong>รับประกัน 3 ปี</strong> ครอบคลุม:<br>
✅ ตาข่ายขาด หลุด หรือเสื่อมสภาพจากการใช้งานปกติ<br>
✅ หนามหลุดหรือคลายตัว<br>
✅ งานติดตั้งที่มีข้อบกพร่อง<br>
ทีมงานเข้าแก้ไข<strong>ฟรี</strong>ตลอดระยะประกัน ไม่มีค่าใช้จ่ายเพิ่มเติม</p>
</details>

<details class="faq-item">
<summary><strong>ติดตั้งในพื้นที่กรุงเทพฯ หรือต่างจังหวัดได้ไหม?</strong></summary>
<p>เราให้บริการ<strong>ทั่วประเทศ</strong> โดยมีสำนักงาน 3 แห่ง:<br>
📍 <strong>ขอนแก่น</strong> — ครอบคลุมภาคอีสาน (อุดรธานี มหาสารคาม นครราชสีมา สกลนคร ร้อยเอ็ด)<br>
📍 <strong>เชียงใหม่</strong> — ครอบคลุมภาคเหนือ (ลำพูน ลำปาง เชียงราย)<br>
📍 <strong>ชลบุรี</strong> — ครอบคลุมภาคตะวันออก ภาคกลาง กรุงเทพฯ และปริมณฑล<br>
สำหรับพื้นที่อื่นๆ สามารถสอบถามได้เลย เรายินดีเดินทางไปทุกจังหวัดครับ</p>
</details>
</div>

<!-- wp:separator {"className":"is-style-wide"} -->
<hr class="wp-block-separator has-alpha-channel-opacity is-style-wide"/>
<!-- /wp:separator -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons">
<!-- wp:button {"backgroundColor":"vivid-green-cyan"} -->
<div class="wp-block-button"><a class="wp-block-button__link has-vivid-green-cyan-background-color has-background" href="/contact">สอบถามเพิ่มเติม</a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->
';

// ===== BLOG PAGE =====
$blog_content = '
<!-- wp:heading {"textAlign":"center","level":2} -->
<h2 class="has-text-align-center wp-block-heading">บทความ & ความรู้</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">ความรู้เรื่องปัญหานกพิราบ วิธีป้องกัน และเคล็ดลับดูแลอาคาร</p>
<!-- /wp:paragraph -->

<!-- wp:separator {"className":"is-style-wide"} -->
<hr class="wp-block-separator has-alpha-channel-opacity is-style-wide"/>
<!-- /wp:separator -->

<!-- wp:html -->
<div style="max-width:800px;margin:0 auto;">

<div style="background:#f8f9fa;border-radius:12px;padding:1.5rem;margin-bottom:1.5rem;border-left:4px solid #E8792E;">
<h3 style="margin:0 0 8px;"><a href="/danger-of-pigeon-droppings/" style="color:#1B4D5C;text-decoration:none;">อันตรายจากขี้นกพิราบ — โรคที่คุณอาจไม่รู้</a></h3>
<p style="margin:0;color:#555;font-size:0.95rem;">มูลนกพิราบไม่ใช่แค่ปัญหาความสกปรก แต่ยังเป็นแหล่งสะสมเชื้อโรคอันตราย ปอดอักเสบ ซาลโมเนลลา เห็บไร...</p>
<a href="/danger-of-pigeon-droppings/" style="color:#E8792E;font-weight:600;font-size:0.9rem;">อ่านต่อ →</a>
</div>

<div style="background:#f8f9fa;border-radius:12px;padding:1.5rem;margin-bottom:1.5rem;border-left:4px solid #E8792E;">
<h3 style="margin:0 0 8px;"><a href="/how-to-get-rid-of-pigeons/" style="color:#1B4D5C;text-decoration:none;">วิธีไล่นกพิราบด้วยตัวเอง — ได้ผลจริงหรือ?</a></h3>
<p style="margin:0;color:#555;font-size:0.95rem;">เปรียบเทียบ 4 วิธีป้องกันนก ตาข่าย HDPE หนามสแตนเลส เจลไล่นก แผงกันนกโซลาร์ วิธีไหนได้ผลถาวร?</p>
<a href="/how-to-get-rid-of-pigeons/" style="color:#E8792E;font-weight:600;font-size:0.9rem;">อ่านต่อ →</a>
</div>

<div style="background:#f8f9fa;border-radius:12px;padding:1.5rem;margin-bottom:1.5rem;border-left:4px solid #E8792E;">
<h3 style="margin:0 0 8px;"><a href="/bird-net-pricing-guide/" style="color:#1B4D5C;text-decoration:none;">ตาข่ายกันนก ราคาเท่าไหร่? คำนวณอย่างไร?</a></h3>
<p style="margin:0;color:#555;font-size:0.95rem;">ราคาตาข่ายกันนก เริ่มต้น 350 บาท/ตร.ม. ขึ้นอยู่กับขนาดพื้นที่ ความสูง วัสดุ ส่งรูปประเมินฟรี</p>
<a href="/bird-net-pricing-guide/" style="color:#E8792E;font-weight:600;font-size:0.9rem;">อ่านต่อ →</a>
</div>

</div>
<!-- /wp:html -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons">
<!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link" href="/contact">ขอประเมินราคาฟรี</a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->
';

// ===== LANDING PAGES PER PROVINCE =====
$khonkaen_content = '
<!-- wp:heading {"textAlign":"center","level":1} -->
<h1 class="has-text-align-center wp-block-heading">ตาข่ายกันนก ขอนแก่น — บริการติดตั้งมืออาชีพ</h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">บริการรับติดตั้งตาข่ายกันนก ขอนแก่น โดยทีมช่างมืออาชีพ วิศวกร กว. คุมงานทุกไซต์ รับประกัน 3 ปี นัดสำรวจหน้างานได้ภายใน 24 ชม.</p>
<!-- /wp:paragraph -->

<!-- wp:html -->
<div style="max-width:700px;margin:2rem auto;text-align:center;">
<h2 style="color:#1B4D5C;">ทำไมลูกค้าขอนแก่นเลือกเรา?</h2>
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px;margin:1.5rem 0;">
<div style="background:#f8f9fa;padding:1.2rem;border-radius:12px;border-left:4px solid #E8792E;">
<h4 style="margin:0 0 8px;color:#1B4D5C;">🚀 เข้าถึงเร็ว</h4>
<p style="margin:0;font-size:0.9rem;color:#555;">สำนักงานใหญ่อยู่ขอนแก่น นัดสำรวจได้ภายใน 24 ชม.</p>
</div>
<div style="background:#f8f9fa;padding:1.2rem;border-radius:12px;border-left:4px solid #E8792E;">
<h4 style="margin:0 0 8px;color:#1B4D5C;">🛡️ รับประกัน 3 ปี</h4>
<p style="margin:0;font-size:0.9rem;color:#555;">นกกลับมาในระยะรับประกัน เข้าดูแลฟรี</p>
</div>
<div style="background:#f8f9fa;padding:1.2rem;border-radius:12px;border-left:4px solid #E8792E;">
<h4 style="margin:0 0 8px;color:#1B4D5C;">💰 ราคาเริ่มต้น 350 บาท/ตร.ม.</h4>
<p style="margin:0;font-size:0.9rem;color:#555;">ประเมินหน้างานและคำนวณราคาที่คุ้มค่าที่สุดให้ฟรี</p>
</div>
</div>

<h3 style="color:#1B4D5C;">พื้นที่ให้บริการในขอนแก่น</h3>
<p style="color:#555;">เมืองขอนแก่น, บ้านเป็ด, ศิลา, สำราญ, บ้านค้อ, เมืองเก่า, พระลับ, ท่าพระ และจังหวัดใกล้เคียง: อุดรธานี, มหาสารคาม, นครราชสีมา, กาฬสินธุ์, ร้อยเอ็ด</p>

<h3 style="color:#1B4D5C;">ผลงานในขอนแก่น</h3>
<p style="color:#555;">ติดตั้งตาข่ายกันนกให้คอนโด X10 ศรีนครินทร์, หมู่บ้านสีวลี, โรงงานในนิคมอุตสาหกรรม, ปั๊มน้ำมัน และอาคารพาณิชย์ทั่วขอนแก่น รวม 32+ โปรเจกต์</p>

<div style="margin:2rem 0;">
<a href="tel:0629964994" style="display:inline-block;background:#E8792E;color:white;padding:14px 28px;border-radius:8px;text-decoration:none;font-weight:600;font-size:1.1rem;">📞 โทรเลย 062-996-4994</a>
<p style="margin-top:8px;"><a href="https://line.me/ti/p/~oil_phanu" style="color:#06C755;font-weight:600;">💬 แอดไลน์ oil_phanu</a></p>
</div>
</div>
<!-- /wp:html -->
';

$chiangmai_content = '
<!-- wp:heading {"textAlign":"center","level":1} -->
<h1 class="has-text-align-center wp-block-heading">ตาข่ายกันนก เชียงใหม่ — บริการติดตั้งมืออาชีพ</h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">บริการรับติดตั้งตาข่ายกันนก เชียงใหม่ โดยทีมช่างมืออาชีพ ผ่านอบรม Safety ครบ รับประกัน 3 ปี</p>
<!-- /wp:paragraph -->

<!-- wp:html -->
<div style="max-width:700px;margin:2rem auto;text-align:center;">
<h2 style="color:#1B4D5C;">บริการตาข่ายกันนก เชียงใหม่</h2>
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px;margin:1.5rem 0;">
<div style="background:#f8f9fa;padding:1.2rem;border-radius:12px;border-left:4px solid #E8792E;">
<h4 style="margin:0 0 8px;color:#1B4D5C;">🏗️ ทีมช่างประจำเชียงใหม่</h4>
<p style="margin:0;font-size:0.9rem;color:#555;">ทีมงานประจำพื้นที่เชียงใหม่ พร้อมเข้างานทันที</p>
</div>
<div style="background:#f8f9fa;padding:1.2rem;border-radius:12px;border-left:4px solid #E8792E;">
<h4 style="margin:0 0 8px;color:#1B4D5C;">🛡️ รับประกัน 3 ปี</h4>
<p style="margin:0;font-size:0.9rem;color:#555;">นกกลับมาในระยะรับประกัน เข้าดูแลฟรี</p>
</div>
<div style="background:#f8f9fa;padding:1.2rem;border-radius:12px;border-left:4px solid #E8792E;">
<h4 style="margin:0 0 8px;color:#1B4D5C;">💰 ราคาเริ่มต้น 350 บาท/ตร.ม.</h4>
<p style="margin:0;font-size:0.9rem;color:#555;">ประเมินหน้างานและคำนวณราคาที่คุ้มค่าที่สุดให้ฟรี</p>
</div>
</div>

<h3 style="color:#1B4D5C;">พื้นที่ให้บริการในเชียงใหม่</h3>
<p style="color:#555;">เมืองเชียงใหม่, หางดง, สันทราย, สันกำแพง, แม่ริม, ดอยสะเก็ด, สารภี และจังหวัดใกล้เคียง: ลำพูน, ลำปาง, เชียงราย</p>

<div style="margin:2rem 0;">
<a href="tel:0936415623" style="display:inline-block;background:#E8792E;color:white;padding:14px 28px;border-radius:8px;text-decoration:none;font-weight:600;font-size:1.1rem;">📞 โทรเลย 093-641-5623</a>
<p style="margin-top:8px;"><a href="https://line.me/ti/p/~th3-ta006-2" style="color:#06C755;font-weight:600;">💬 แอดไลน์ th3-ta006-2</a></p>
</div>
</div>
<!-- /wp:html -->
';

$chonburi_content = '
<!-- wp:heading {"textAlign":"center","level":1} -->
<h1 class="has-text-align-center wp-block-heading">ตาข่ายกันนก ชลบุรี — บริการติดตั้งมืออาชีพ</h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">บริการรับติดตั้งตาข่ายกันนก ชลบุรี พัทยา ศรีราชา โดยทีมช่างมืออาชีพ รับประกัน 3 ปี</p>
<!-- /wp:paragraph -->

<!-- wp:html -->
<div style="max-width:700px;margin:2rem auto;text-align:center;">
<h2 style="color:#1B4D5C;">บริการตาข่ายกันนก ชลบุรี</h2>
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px;margin:1.5rem 0;">
<div style="background:#f8f9fa;padding:1.2rem;border-radius:12px;border-left:4px solid #E8792E;">
<h4 style="margin:0 0 8px;color:#1B4D5C;">🏗️ ครอบคลุม EEC</h4>
<p style="margin:0;font-size:0.9rem;color:#555;">ชลบุรี พัทยา ศรีราชา บางแสน แหลมฉบัง ระยอง</p>
</div>
<div style="background:#f8f9fa;padding:1.2rem;border-radius:12px;border-left:4px solid #E8792E;">
<h4 style="margin:0 0 8px;color:#1B4D5C;">🛡️ รับประกัน 3 ปี</h4>
<p style="margin:0;font-size:0.9rem;color:#555;">นกกลับมาในระยะรับประกัน เข้าดูแลฟรี</p>
</div>
<div style="background:#f8f9fa;padding:1.2rem;border-radius:12px;border-left:4px solid #E8792E;">
<h4 style="margin:0 0 8px;color:#1B4D5C;">💰 ราคาเริ่มต้น 350 บาท/ตร.ม.</h4>
<p style="margin:0;font-size:0.9rem;color:#555;">ประเมินหน้างานและคำนวณราคาที่คุ้มค่าที่สุดให้ฟรี</p>
</div>
</div>

<h3 style="color:#1B4D5C;">พื้นที่ให้บริการในชลบุรี</h3>
<p style="color:#555;">เมืองชลบุรี, พัทยา, ศรีราชา, บางแสน, แหลมฉบัง, บ่อวิน, สัตหีบ และจังหวัดใกล้เคียง: ระยอง, ฉะเชิงเทรา, ปราจีนบุรี</p>

<div style="margin:2rem 0;">
<a href="tel:0956292488" style="display:inline-block;background:#E8792E;color:white;padding:14px 28px;border-radius:8px;text-decoration:none;font-weight:600;font-size:1.1rem;">📞 โทรเลย 095-629-2488</a>
<p style="margin-top:8px;"><a href="https://line.me/ti/p/~oil_phanu" style="color:#06C755;font-weight:600;">💬 แอดไลน์ oil_phanu</a></p>
</div>
</div>
<!-- /wp:html -->
';

// ===== SEPARATE BLOG POSTS =====
$blog_posts = array(
    'danger-of-pigeon-droppings' => array(
        'title'   => 'อันตรายจากขี้นกพิราบ — โรคที่คุณอาจไม่รู้',
        'content' => '<!-- wp:paragraph -->
<p>มูลนกพิราบไม่ใช่แค่ปัญหาความสกปรก แต่ยังเป็นแหล่งสะสมเชื้อโรคอันตรายหลายชนิด ได้แก่:</p>
<!-- /wp:paragraph -->
<!-- wp:list -->
<ul>
<li><strong>โรคปอดอักเสบ (Cryptococcosis)</strong> — เชื้อราในมูลนกแห้ง เมื่อสูดดมเข้าปอดอาจทำให้ปอดอักเสบรุนแรง</li>
<li><strong>โรคฮิสโตพลาสโมซิส (Histoplasmosis)</strong> — เชื้อราที่เจริญเติบโตในมูลนก สามารถแพร่กระจายในอากาศ</li>
<li><strong>โรคซาลโมเนลลา (Salmonellosis)</strong> — เชื้อแบคทีเรียจากมูลนกปนเปื้อนอาหารและน้ำ</li>
<li><strong>เห็บ ไร หมัด</strong> — ปรสิตที่อาศัยอยู่ในรังนกและมูลนก สามารถเข้าสู่ที่พักอาศัยได้</li>
</ul>
<!-- /wp:list -->
<!-- wp:paragraph -->
<p><strong>วิธีป้องกัน:</strong> ติดตั้งตาข่ายกันนก HDPE หรือหนามกันนกสแตนเลส เป็นวิธีที่ปลอดภัยและได้ผลถาวร ไม่ทำร้ายนก แต่ป้องกันไม่ให้นกเข้ามาทำรังในพื้นที่ หากคุณอยู่ในพื้นที่<strong>ขอนแก่น เชียงใหม่ ชลบุรี หรือภาคอีสาน</strong> สามารถติดต่อ BIRDS GO AWAY เพื่อขอคำปรึกษาฟรีได้เลย โทร 062-996-4994</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph -->
<p>👉 <a href="/services/"><strong>ดูบริการทั้งหมดของเรา</strong></a> | <a href="/contact/"><strong>ติดต่อประเมินราคาฟรี</strong></a></p>
<!-- /wp:paragraph -->',
    ),
    'how-to-get-rid-of-pigeons' => array(
        'title'   => 'วิธีไล่นกพิราบด้วยตัวเอง — ได้ผลจริงหรือ?',
        'content' => '<!-- wp:paragraph -->
<p>หลายคนเคยลองวิธีไล่นกพิราบด้วยตัวเอง เช่น แขวนซีดี ใช้เสียงไล่ ติดสติกเกอร์ตานก แต่วิธีเหล่านี้ได้ผลแค่ชั่วคราว เพราะนกพิราบเป็นสัตว์ที่ปรับตัวได้เร็วมาก</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph -->
<p><strong>วิธีที่ได้ผลถาวร:</strong></p>
<!-- /wp:paragraph -->
<!-- wp:list {"ordered":true} -->
<ol>
<li><strong>ตาข่ายกันนก HDPE</strong> — เหมาะกับระเบียง ช่องเปิด พื้นที่กว้าง อายุการใช้งาน 6-7 ปี เริ่มต้น 350 บาท/ตร.ม.</li>
<li><strong>หนามกันนก สแตนเลส SUS304</strong> — เหมาะกับขอบหน้าต่าง ราวกันตก ชายคา อายุ 10+ ปี</li>
<li><strong>เจลไล่นก</strong> — เหมาะกับพื้นที่แคบ ติดตั้งง่าย แต่ต้องเปลี่ยนทุก 1-2 ปี</li>
<li><strong>แผงกันนกโซลาร์เซลล์</strong> — ระบบคลิปไม่เจาะแผง ป้องกันนกทำรังใต้แผง</li>
</ol>
<!-- /wp:list -->
<!-- wp:paragraph -->
<p>หากต้องการคำปรึกษาจากผู้เชี่ยวชาญ บริการ<strong>ติดตั้งตาข่ายกันนก ขอนแก่น</strong> <strong>เชียงใหม่</strong> <strong>ชลบุรี</strong> และ<strong>ทั่วภาคอีสาน</strong> โดย BIRDS GO AWAY ปรึกษาฟรี โทร 062-996-4994</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph -->
<p>👉 <a href="/services/"><strong>ดูบริการป้องกันนกทั้งหมด</strong></a> | <a href="/contact/"><strong>ติดต่อประเมินราคาฟรี</strong></a></p>
<!-- /wp:paragraph -->',
    ),
    'bird-net-pricing-guide' => array(
        'title'   => 'ตาข่ายกันนก ราคาเท่าไหร่? คำนวณอย่างไร?',
        'content' => '<!-- wp:paragraph -->
<p>ราคาติดตั้งตาข่ายกันนกขึ้นอยู่กับหลายปัจจัย:</p>
<!-- /wp:paragraph -->
<!-- wp:list -->
<ul>
<li><strong>ขนาดพื้นที่</strong> — วัดเป็นตารางเมตร ยิ่งพื้นที่ใหญ่ ราคาต่อ ตร.ม. จะถูกลง</li>
<li><strong>ความสูง</strong> — งานที่ต้องใช้รถกระเช้าหรือโรยตัว จะมีค่าใช้จ่ายเพิ่ม</li>
<li><strong>ความซับซ้อน</strong> — พื้นที่โล่งจะง่ายกว่าพื้นที่มีสิ่งกีดขวาง ท่อ ราวตากผ้า</li>
<li><strong>ประเภทวัสดุ</strong> — ตาข่าย HDPE, หนามสแตนเลส, เจล มีราคาต่างกัน</li>
</ul>
<!-- /wp:list -->
<!-- wp:paragraph -->
<p><strong>ราคาเริ่มต้นเพียง 350 บาท/ตร.ม.</strong> (ประเมินหน้างานและคำนวณราคาที่คุ้มค่าที่สุดให้ฟรี) ส่งรูปหน้างานทาง LINE: oil_phanu หรือโทร 062-996-4994 ทีมวิศวกรจะเข้าสำรวจพื้นที่ วัดขนาด และเสนอราคาให้ฟรี ไม่มีค่าใช้จ่าย</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph -->
<p>👉 <a href="/services/"><strong>ดูบริการและวัสดุทั้งหมด</strong></a> | <a href="/contact/"><strong>ติดต่อขอใบเสนอราคาฟรี</strong></a></p>
<!-- /wp:paragraph -->',
    ),
);

// Create blog posts as separate WordPress posts
echo "=== Creating Blog Posts ===\n";
foreach ($blog_posts as $slug => $post_data) {
    $existing = get_page_by_path($slug, OBJECT, 'post');
    if ($existing) {
        wp_update_post(array(
            'ID' => $existing->ID,
            'post_content' => $post_data['content'],
        ));
        echo "Updated post: {$post_data['title']} (ID: {$existing->ID})\n";
    } else {
        $id = wp_insert_post(array(
            'post_title'   => $post_data['title'],
            'post_name'    => $slug,
            'post_content' => $post_data['content'],
            'post_status'  => 'publish',
            'post_type'    => 'post',
        ));
        echo "Created post: {$post_data['title']} (ID: {$id})\n";
    }
}

// ===== UPDATE PAGES =====
$pages = array(
    'home'       => array('title' => 'หน้าแรก',      'content' => $home_content),
    'services'   => array('title' => 'บริการของเรา',  'content' => $services_content),
    'portfolio'  => array('title' => 'ผลงานของเรา',  'content' => $portfolio_content),
    'about'      => array('title' => 'เกี่ยวกับเรา',  'content' => $about_content),
    'contact'    => array('title' => 'ติดต่อเรา',     'content' => $contact_content),
    'faq'        => array('title' => 'คำถามที่พบบ่อย', 'content' => $faq_content),
    'blog'       => array('title' => 'บทความ',        'content' => $blog_content),
    'khon-kaen'  => array('title' => 'ตาข่ายกันนก ขอนแก่น', 'content' => $khonkaen_content),
    'chiang-mai' => array('title' => 'ตาข่ายกันนก เชียงใหม่', 'content' => $chiangmai_content),
    'chonburi'   => array('title' => 'ตาข่ายกันนก ชลบุรี', 'content' => $chonburi_content),
);

foreach ($pages as $slug => $page_data) {
    $existing = get_page_by_path($slug);
    if ($existing) {
        wp_update_post(array(
            'ID' => $existing->ID,
            'post_content' => $page_data['content'],
        ));
        echo "Updated page: {$page_data['title']} (ID: {$existing->ID})\n";
    } else {
        $id = wp_insert_post(array(
            'post_title'   => $page_data['title'],
            'post_name'    => $slug,
            'post_content' => $page_data['content'],
            'post_status'  => 'publish',
            'post_type'    => 'page',
        ));
        echo "Created page: {$page_data['title']} (ID: {$id})\n";
    }
}

// Set home page as front page
$home_page = get_page_by_path('home');
if ($home_page) {
    update_option('show_on_front', 'page');
    update_option('page_on_front', $home_page->ID);
    echo "Set home page as front page\n";
}

// ===== SEO CONFIGURATION (Yoast SEO) =====
echo "=== Configuring SEO ===\n";

// SEO meta data for each page
$seo_data = array(
    'home' => array(
        'title' => 'ตาข่ายกันนก ขอนแก่น เชียงใหม่ ชลบุรี | Birds Go Away',
        'desc'  => 'บริการติดตั้งตาข่ายกันนก หนามกันนก มาตรฐานอุตสาหกรรม แก้ปัญหานกพิราบถาวร รับประกันงาน 3 ปี ประเมินหน้างานฟรีทั่วขอนแก่นและภาคอีสาน โทร 062-996-4994',
        'focus' => 'ตาข่ายกันนก ขอนแก่น',
    ),
    'services' => array(
        'title' => 'บริการติดตั้งตาข่ายกันนก ครบวงจร | Birds Go Away',
        'desc'  => 'ตาข่าย HDPE หนามสแตนเลส เจลไล่นก แผงกันนกโซลาร์ ติดตั้งโดยวิศวกร รับประกัน 3 ปี ราคาเริ่มต้น 350 บาท/ตร.ม. ประเมินฟรี โทร 062-996-4994',
        'focus' => 'บริการติดตั้งตาข่ายกันนก',
    ),
    'portfolio' => array(
        'title' => 'ผลงานติดตั้งตาข่ายกันนก 32+ โปรเจกต์ | Birds Go Away',
        'desc'  => 'ดูผลงานจริงการติดตั้งตาข่ายกันนก หนามกันนก เจลไล่นก พร้อมวิดีโอจากหน้างาน คอนโด โรงงาน ปั๊มน้ำมัน วัด ขอนแก่น เชียงใหม่ ชลบุรี',
        'focus' => 'ผลงานติดตั้งตาข่ายกันนก',
    ),
    'about' => array(
        'title' => 'เกี่ยวกับเรา บริษัท รีเช็ค บิ้วดิ้ง | Birds Go Away',
        'desc'  => 'บริษัท รีเช็ค บิ้วดิ้ง จำกัด ผู้เชี่ยวชาญติดตั้งตาข่ายกันนก ทีมช่างผ่านอบรมโรยตัว มี Certificate วิศวกร กว. คุมงาน ให้บริการขอนแก่น เชียงใหม่ ชลบุรี',
        'focus' => 'บริษัทติดตั้งตาข่ายกันนก',
    ),
    'contact' => array(
        'title' => 'ติดต่อเรา ปรึกษาฟรี ประเมินราคา | Birds Go Away',
        'desc'  => 'ติดต่อ Birds Go Away ปรึกษาฟรี ประเมินหน้างานภายใน 24 ชม. โทร 062-996-4994 (ขอนแก่น) 093-641-5623 (เชียงใหม่) LINE: oil_phanu',
        'focus' => 'ติดต่อตาข่ายกันนก',
    ),
    'faq' => array(
        'title' => 'คำถามที่พบบ่อย ตาข่ายกันนก ราคา | Birds Go Away',
        'desc'  => 'รวมคำถามที่พบบ่อยเกี่ยวกับตาข่ายกันนก ราคาเริ่มต้น ขั้นตอนติดตั้ง อายุการใช้งาน การรับประกัน พื้นที่ให้บริการทั่วประเทศ',
        'focus' => 'ตาข่ายกันนก คำถามที่พบบ่อย',
    ),
    'blog' => array(
        'title' => 'บทความ วิธีไล่นกพิราบถาวร | Birds Go Away',
        'desc'  => 'ความรู้เรื่องปัญหานกพิราบ อันตรายจากขี้นก วิธีไล่นกที่ได้ผลจริง ราคาตาข่ายกันนก เปรียบเทียบวิธีป้องกันนกแต่ละแบบ โดยผู้เชี่ยวชาญ',
        'focus' => 'วิธีไล่นกพิราบ ตาข่ายกันนก ขอนแก่น',
    ),
    'khon-kaen' => array(
        'title' => 'ตาข่ายกันนก ขอนแก่น รับประกัน 3 ปี | Birds Go Away',
        'desc'  => 'บริการติดตั้งตาข่ายกันนก ขอนแก่น โดยทีมช่างมืออาชีพ วิศวกรคุมงาน รับประกัน 3 ปี ประเมินหน้างานฟรี นัดสำรวจภายใน 24 ชม. โทร 062-996-4994',
        'focus' => 'ตาข่ายกันนก ขอนแก่น',
    ),
    'chiang-mai' => array(
        'title' => 'ตาข่ายกันนก เชียงใหม่ รับประกัน 3 ปี | Birds Go Away',
        'desc'  => 'บริการติดตั้งตาข่ายกันนก เชียงใหม่ หนามกันนก เจลไล่นก โดยทีมช่างมืออาชีพ รับประกัน 3 ปี ประเมินหน้างานฟรี โทร 093-641-5623',
        'focus' => 'ตาข่ายกันนก เชียงใหม่',
    ),
    'chonburi' => array(
        'title' => 'ตาข่ายกันนก ชลบุรี รับประกัน 3 ปี | Birds Go Away',
        'desc'  => 'บริการติดตั้งตาข่ายกันนก ชลบุรี พัทยา ศรีราชา โดยทีมช่างมืออาชีพ รับประกัน 3 ปี ประเมินหน้างานฟรี โทร 095-629-2488',
        'focus' => 'ตาข่ายกันนก ชลบุรี',
    ),
);

foreach ($seo_data as $slug => $seo) {
    $page = get_page_by_path($slug);
    if (!$page) continue;
    $pid = $page->ID;

    // Yoast SEO meta
    update_post_meta($pid, '_yoast_wpseo_title', $seo['title']);
    update_post_meta($pid, '_yoast_wpseo_metadesc', $seo['desc']);
    update_post_meta($pid, '_yoast_wpseo_focuskw', $seo['focus']);

    // Open Graph
    update_post_meta($pid, '_yoast_wpseo_opengraph-title', $seo['title']);
    update_post_meta($pid, '_yoast_wpseo_opengraph-description', $seo['desc']);
    update_post_meta($pid, '_yoast_wpseo_opengraph-image', img(12));

    // Twitter Card
    update_post_meta($pid, '_yoast_wpseo_twitter-title', $seo['title']);
    update_post_meta($pid, '_yoast_wpseo_twitter-description', $seo['desc']);

    echo "SEO configured for: {$seo['title']}\n";
}

// SEO for blog posts
$blog_seo = array(
    'danger-of-pigeon-droppings' => array(
        'title' => 'อันตรายจากขี้นกพิราบ โรคที่ไม่รู้ | Birds Go Away',
        'desc'  => 'มูลนกพิราบมีเชื้อโรคอันตราย ปอดอักเสบ ซาลโมเนลลา เห็บไร วิธีป้องกันถาวรด้วยตาข่ายกันนก HDPE หนามสแตนเลส รับประกัน 3 ปี',
        'focus' => 'อันตรายจากขี้นกพิราบ',
    ),
    'how-to-get-rid-of-pigeons' => array(
        'title' => 'วิธีไล่นกพิราบ ได้ผลจริงถาวร | Birds Go Away',
        'desc'  => 'เปรียบเทียบวิธีไล่นกพิราบ ตาข่าย HDPE หนามสแตนเลส เจลไล่นก แผงกันนกโซลาร์ วิธีไหนได้ผลถาวร ราคาเริ่มต้น 350 บาท/ตร.ม.',
        'focus' => 'วิธีไล่นกพิราบ',
    ),
    'bird-net-pricing-guide' => array(
        'title' => 'ตาข่ายกันนก ราคาเท่าไหร่ คำนวณอย่างไร | Birds Go Away',
        'desc'  => 'ราคาตาข่ายกันนก เริ่มต้น 350 บาท/ตร.ม. ขึ้นอยู่กับขนาดพื้นที่ ความสูง วัสดุ ส่งรูปประเมินฟรี โทร 062-996-4994',
        'focus' => 'ตาข่ายกันนก ราคา',
    ),
);

foreach ($blog_seo as $slug => $seo) {
    $post = get_page_by_path($slug, OBJECT, 'post');
    if (!$post) continue;
    $pid = $post->ID;
    update_post_meta($pid, '_yoast_wpseo_title', $seo['title']);
    update_post_meta($pid, '_yoast_wpseo_metadesc', $seo['desc']);
    update_post_meta($pid, '_yoast_wpseo_focuskw', $seo['focus']);
    update_post_meta($pid, '_yoast_wpseo_opengraph-title', $seo['title']);
    update_post_meta($pid, '_yoast_wpseo_opengraph-description', $seo['desc']);
    echo "SEO configured for post: {$seo['title']}\n";
}

// Yoast global settings
update_option('wpseo_titles', array_merge(
    (array) get_option('wpseo_titles', array()),
    array(
        'company_name' => 'BIRDS GO AWAY — ตาข่ายกันนก',
        'company_or_person' => 'company',
        'company_logo' => '',
        'website_name' => 'BIRDS GO AWAY — ตาข่ายกันนก',
        'separator' => 'sc-pipe',
        'title-home-wpseo' => 'ตาข่ายกันนก ขอนแก่น เชียงใหม่ ชลบุรี | Birds Go Away',
        'metadesc-home-wpseo' => 'บริการติดตั้งตาข่ายกันนก หนามกันนก มาตรฐานอุตสาหกรรม แก้ปัญหานกพิราบถาวร รับประกันงาน 3 ปี ประเมินหน้างานฟรี โทร 062-996-4994',
        'open_graph_frontpage_title' => 'BIRDS GO AWAY — บริการติดตั้งตาข่ายกันนก มืออาชีพ',
        'open_graph_frontpage_desc' => 'บริการติดตั้งตาข่ายกันนก HDPE หนามกันนก เจลไล่นก แผงกันนกโซลาร์เซลล์ รับประกัน 3 ปี ปรึกษาฟรี',
        'open_graph_frontpage_image' => img(12),
    )
));

// Yoast social settings
update_option('wpseo_social', array_merge(
    (array) get_option('wpseo_social', array()),
    array(
        'og_default_image' => img(12),
        'og_frontpage_title' => 'BIRDS GO AWAY — ตาข่ายกันนก',
        'og_frontpage_desc' => 'บริการติดตั้งตาข่ายกันนก มืออาชีพ ครบวงจร',
        'facebook_site' => 'https://www.facebook.com/people/%E0%B8%95%E0%B8%B2%E0%B8%82%E0%B9%88%E0%B8%B2%E0%B8%A2%E0%B8%81%E0%B8%B1%E0%B8%99%E0%B8%99%E0%B8%81-%E0%B8%82%E0%B8%AD%E0%B8%99%E0%B9%81%E0%B8%81%E0%B9%88%E0%B8%99-%E0%B9%80%E0%B8%8A%E0%B8%B5%E0%B8%A2%E0%B8%87%E0%B9%83%E0%B8%AB%E0%B8%A1%E0%B9%88-%E0%B8%8A%E0%B8%A5%E0%B8%9A%E0%B8%B8%E0%B8%A3%E0%B8%B5-by-Birds-Go-Away/61554258339668/',
        'opengraph' => true,
        'twitter' => true,
        'twitter_card_type' => 'summary_large_image',
    )
));

// Yoast XML Sitemap settings
update_option('wpseo', array_merge(
    (array) get_option('wpseo', array()),
    array(
        'enable_xml_sitemap' => true,
        'enable_text_link_counter' => true,
    )
));

// Flush rewrite rules for sitemap
flush_rewrite_rules();
echo "Sitemap enabled at: {$site_url}/sitemap_index.xml\n";

// Add schema.org LocalBusiness structured data via custom meta
$home_page = get_page_by_path('home');
if ($home_page) {
    $schema = json_encode(array(
        '@context' => 'https://schema.org',
        '@type' => 'LocalBusiness',
        'name' => 'BIRDS GO AWAY — ตาข่ายกันนก',
        'description' => 'บริการติดตั้งตาข่ายกันนก HDPE หนามกันนก เจลไล่นก แผงกันนกโซลาร์เซลล์',
        'url' => $site_url,
        'telephone' => ['+66629964994', '+66936415623', '+66956292488'],
        'address' => array(
            '@type' => 'PostalAddress',
            'streetAddress' => '88/38',
            'addressLocality' => 'Khon Kaen',
            'addressCountry' => 'TH',
        ),
        'areaServed' => ['ขอนแก่น', 'เชียงใหม่', 'ชลบุรี'],
        'priceRange' => '$$',
        'openingHours' => ['Mo-Sa 08:00-17:00'],
    ), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    update_post_meta($home_page->ID, '_schema_json_ld', $schema);
}

echo "SEO configuration complete!\n";

// ===== UPLOAD LOGO AND SET AS CUSTOM LOGO =====
echo "\n=== Setting up logo ===\n";

$logo_file = '/var/www/html/wp-content/uploads/birdnet-assets/logo-white.jpg';
if (file_exists($logo_file)) {
    // Check if logo already uploaded
    $existing_logo = get_posts(array(
        'post_type' => 'attachment',
        'meta_key' => '_birdnet_logo_type',
        'meta_value' => 'main',
        'posts_per_page' => 1,
    ));

    if (empty($existing_logo)) {
        // Upload logo to media library
        require_once ABSPATH . 'wp-admin/includes/image.php';
        require_once ABSPATH . 'wp-admin/includes/file.php';
        require_once ABSPATH . 'wp-admin/includes/media.php';

        $upload_dir = wp_upload_dir();
        $logo_dest = $upload_dir['path'] . '/birds-go-away-logo.jpg';
        copy($logo_file, $logo_dest);

        $attachment = array(
            'post_mime_type' => 'image/jpeg',
            'post_title'     => 'Birds Go Away Logo',
            'post_content'   => '',
            'post_status'    => 'inherit',
            'guid'           => $upload_dir['url'] . '/birds-go-away-logo.jpg',
        );

        $attach_id = wp_insert_attachment($attachment, $logo_dest);
        $attach_data = wp_generate_attachment_metadata($attach_id, $logo_dest);
        wp_update_attachment_metadata($attach_id, $attach_data);
        update_post_meta($attach_id, '_birdnet_logo_type', 'main');

        // Set as custom logo
        set_theme_mod('custom_logo', $attach_id);
        echo "Logo uploaded and set as custom logo (ID: $attach_id)\n";
    } else {
        $attach_id = $existing_logo[0]->ID;
        set_theme_mod('custom_logo', $attach_id);
        echo "Logo already uploaded (ID: $attach_id), custom logo updated\n";
    }

    // Astra-specific: set logo width
    $astra_settings = get_option('astra-settings', array());
    $astra_settings['ast-header-responsive-logo-width'] = array(
        'desktop' => 180,
        'tablet'  => 150,
        'mobile'  => 130,
    );
    update_option('astra-settings', $astra_settings);
    echo "Astra logo width configured\n";
} else {
    echo "Logo file not found at: $logo_file\n";
}

echo "Content update complete!\n";
