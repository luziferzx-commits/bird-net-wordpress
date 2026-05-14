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
<h1 class="wp-block-heading">จบปัญหานกพิราบถาวร<br>โดยวิศวกรมืออาชีพ</h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"fontSize":"medium"} -->
<p class="has-medium-font-size">ทีมช่างผ่านการอบรมโรยตัว มีใบ Certificate วิศวกรคุมงานทุกไซต์ ภายใต้ บริษัท รีเช็ค บิ้วดิ้ง จำกัด</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"fontSize":"medium"} -->
<p class="has-medium-font-size"><strong>ตาข่าย HDPE คุณภาพสูง อายุการใช้งาน 5-7 ปี | รับประกัน 3 ปี | ปรึกษาฟรี</strong></p>
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

<!-- wp:separator {"className":"is-style-wide"} -->
<hr class="wp-block-separator has-alpha-channel-opacity is-style-wide"/>
<!-- /wp:separator -->

<!-- wp:heading {"textAlign":"center","level":2} -->
<h2 class="has-text-align-center wp-block-heading">ทำไมต้องเลือกเรา?</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">บริษัท รีเช็ค บิ้วดิ้ง จำกัด — ผู้เชี่ยวชาญด้านการป้องกันนก ครบวงจร</p>
<!-- /wp:paragraph -->

<!-- wp:columns -->
<div class="wp-block-columns">
<!-- wp:column {"width":"25%"} -->
<div class="wp-block-column" style="flex-basis:25%;text-align:center;">
<h3 class="wp-block-heading">ปลอดภัย 100%</h3>
<p>วัสดุคุณภาพสูง ไม่ทำร้ายนก ปลอดภัยต่อคนและสัตว์เลี้ยง ผ่านมาตรฐาน</p>
</div>
<!-- /wp:column -->
<!-- wp:column {"width":"25%"} -->
<div class="wp-block-column" style="flex-basis:25%;text-align:center;">
<h3 class="wp-block-heading">ติดตั้งรวดเร็ว</h3>
<p>ทีมช่างมืออาชีพ ดำเนินงานรวดเร็ว ไม่รบกวนการใช้ชีวิต มีวิศวกรคุมงาน</p>
</div>
<!-- /wp:column -->
<!-- wp:column {"width":"25%"} -->
<div class="wp-block-column" style="flex-basis:25%;text-align:center;">
<h3 class="wp-block-heading">รับประกัน 3 ปี</h3>
<p>รับประกันคุณภาพงานติดตั้ง 3 ปี พร้อมบริการหลังการขาย ซ่อมแซมฟรี</p>
</div>
<!-- /wp:column -->
<!-- wp:column {"width":"25%"} -->
<div class="wp-block-column" style="flex-basis:25%;text-align:center;">
<h3 class="wp-block-heading">ราคายุติธรรม</h3>
<p>เสนอราคาฟรี ไม่มีค่าใช้จ่ายแอบแฝง คุ้มค่าทุกบาท ปรึกษาฟรีไม่มีค่าใช้จ่าย</p>
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->

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
<figure class="wp-block-image"><img src="' . project_img(7) . '" alt="อาคารสำนักงาน ป.ป.ช. ภาค4"/></figure>
<!-- /wp:image -->
<!-- wp:image -->
<figure class="wp-block-image"><img src="' . project_img(10) . '" alt="หอพักชาย มหาวิทยาลัยขอนแก่น"/></figure>
<!-- /wp:image -->
<!-- wp:image -->
<figure class="wp-block-image"><img src="' . project_img(13) . '" alt="วิทยาลัยสาธารณสุขสิรินธร"/></figure>
<!-- /wp:image -->
<!-- wp:image -->
<figure class="wp-block-image"><img src="' . project_img(20) . '" alt="หอพักพยาบาล โรงพยาบาลสิรินธร"/></figure>
<!-- /wp:image -->
<!-- wp:image -->
<figure class="wp-block-image"><img src="' . project_img(35) . '" alt="เมโทรคอนโด (METRO CONDO)"/></figure>
<!-- /wp:image -->
<!-- wp:image -->
<figure class="wp-block-image"><img src="' . project_img(45) . '" alt="ESCENT CONDO"/></figure>
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
<figure class="wp-block-video"><video controls src="' . vid(1) . '"></video><figcaption>ตัวอย่างการติดตั้ง #1</figcaption></figure>
<!-- /wp:video -->
</div>
<!-- /wp:column -->
<!-- wp:column -->
<div class="wp-block-column">
<!-- wp:video -->
<figure class="wp-block-video"><video controls src="' . vid(2) . '"></video><figcaption>ตัวอย่างการติดตั้ง #2</figcaption></figure>
<!-- /wp:video -->
</div>
<!-- /wp:column -->
<!-- wp:column -->
<div class="wp-block-column">
<!-- wp:video -->
<figure class="wp-block-video"><video controls src="' . vid(3) . '"></video><figcaption>ตัวอย่างการติดตั้ง #3</figcaption></figure>
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
<figure class="wp-block-video"><video controls src="' . vid(4) . '"></video><figcaption>ตัวอย่างการติดตั้ง #4</figcaption></figure>
<!-- /wp:video -->
</div>
<!-- /wp:column -->
<!-- wp:column -->
<div class="wp-block-column">
<!-- wp:video -->
<figure class="wp-block-video"><video controls src="' . vid(5) . '"></video><figcaption>ตัวอย่างการติดตั้ง #5</figcaption></figure>
<!-- /wp:video -->
</div>
<!-- /wp:column -->
<!-- wp:column -->
<div class="wp-block-column">
<!-- wp:video -->
<figure class="wp-block-video"><video controls src="' . vid(6) . '"></video><figcaption>ตัวอย่างการติดตั้ง #6</figcaption></figure>
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

<!-- wp:columns -->
<div class="wp-block-columns">
<!-- wp:column -->
<div class="wp-block-column testimonial-card" style="background:#f9f9f9;padding:1.5rem;border-radius:12px;border-left:4px solid var(--bn-orange,#E8792E);">
<p style="font-style:italic;">"ทีมงานมืออาชีพมาก ติดตั้งเรียบร้อย สะอาด ตาข่ายแทบมองไม่เห็น แต่นกไม่มาอีกเลย รับประกันงานด้วย ประทับใจมากครับ"</p>
<p style="color:#666;font-size:0.9em;margin-top:0.5rem;"><strong>— คุณสมชาย</strong> | คอนโด X10 ศรีนครินทร์, ขอนแก่น</p>
<p style="color:#E8792E;">⭐⭐⭐⭐⭐</p>
</div>
<!-- /wp:column -->
<!-- wp:column -->
<div class="wp-block-column testimonial-card" style="background:#f9f9f9;padding:1.5rem;border-radius:12px;border-left:4px solid var(--bn-orange,#E8792E);">
<p style="font-style:italic;">"ปัญหานกพิราบมานานหลายปี ลองหลายวิธีไม่ได้ผล พอติดตาข่ายกับ Birds Go Away จบเลย ราคาสมเหตุสมผล แนะนำเลยค่ะ"</p>
<p style="color:#666;font-size:0.9em;margin-top:0.5rem;"><strong>— คุณนิดา</strong> | หมู่บ้านสีวลี, ขอนแก่น</p>
<p style="color:#E8792E;">⭐⭐⭐⭐⭐</p>
</div>
<!-- /wp:column -->
<!-- wp:column -->
<div class="wp-block-column testimonial-card" style="background:#f9f9f9;padding:1.5rem;border-radius:12px;border-left:4px solid var(--bn-orange,#E8792E);">
<p style="font-style:italic;">"ใช้บริการติดตั้งที่โกดังสินค้า พื้นที่กว้างมาก แต่ทีมจัดการได้เรียบร้อยภายใน 2 วัน มีวิศวกรมาคุมงานด้วย วางใจได้"</p>
<p style="color:#666;font-size:0.9em;margin-top:0.5rem;"><strong>— คุณวิชัย</strong> | โกดังสินค้า, เชียงใหม่</p>
<p style="color:#E8792E;">⭐⭐⭐⭐⭐</p>
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
<div class="wp-block-button"><a class="wp-block-button__link has-vivid-green-cyan-background-color has-background" href="/contact">ติดต่อเราเลย</a></div>
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
<p class="has-text-align-center">เรามีบริการป้องกันนก 4 รูปแบบ ครบวงจร พร้อมรับประกัน</p>
<!-- /wp:paragraph -->

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
<p><strong>Square Mesh Durable Bird Control Netting</strong> — ตาข่าย HDPE คุณภาพสูง หนาแน่น แข็งแรง ทนต่อแรงกระแทก แรงดึง และสารเคมี</p>

<h4 class="wp-block-heading">สเปคสินค้า</h4>
<ul>
<li><strong>วัสดุ:</strong> HDPE 2500D/1 ply</li>
<li><strong>สี:</strong> Transparent Black (UV Treatment)</li>
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
<p>หนามกันนกสแตนเลส ปลายแหลม ความแหลมของหนามช่วยทำให้ป้องกันนกได้ดียิ่งขึ้น</p>

<h4 class="wp-block-heading">สเปคสินค้า</h4>
<ul>
<li><strong>วัสดุ:</strong> Stainless Spring SUS304</li>
<li><strong>จำนวนหนาม:</strong> 90 ขาต่อ 1 เมตร</li>
<li><strong>คุณสมบัติ:</strong> ปรับเปลี่ยนองศาปลายหนามตามพื้นที่ได้</li>
<li><strong>ทนทาน:</strong> ทนแดด ทนฝน เหมาะกับสภาพอากาศเมืองไทย</li>
<li><strong>อายุการใช้งาน:</strong> ยาวนาน ไม่เป็นสนิม</li>
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
<p>เจลไล่นกสูตรพิเศษ ใช้สำหรับพื้นที่ที่ไม่สามารถติดตั้งตาข่ายหรือหนามกันนกได้</p>
<ul>
<li>ไม่มีสารพิษ ปลอดภัยต่อคนและสัตว์</li>
<li>ใช้ได้กับทุกพื้นผิว</li>
<li>ไม่ทิ้งคราบ ไม่เสียหาย</li>
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
    array('name' => 'อาคารสำนักงาน ป.ป.ช. ภาค4', 'location' => 'จังหวัดขอนแก่น', 'page' => 7, 'cat' => 'อาคารราชการ'),
    array('name' => 'อู่ราชนาวีมหิดลอดุลยเดช กรมอู่ทหารเรือ', 'location' => 'จังหวัดชลบุรี', 'page' => 9, 'cat' => 'อาคารราชการ'),
    array('name' => 'หอพักชาย 7 มหาวิทยาลัยขอนแก่น', 'location' => 'จังหวัดขอนแก่น', 'page' => 10, 'cat' => 'อาคารราชการ'),
    array('name' => 'หอพักชาย 8 มหาวิทยาลัยขอนแก่น', 'location' => 'จังหวัดขอนแก่น', 'page' => 11, 'cat' => 'อาคารราชการ'),
    array('name' => 'หอพัก 21-23 มหาวิทยาลัยขอนแก่น', 'location' => 'จังหวัดขอนแก่น', 'page' => 12, 'cat' => 'อาคารราชการ'),
    array('name' => 'วิทยาลัยสาธารณสุขสิรินธร อาคาร 10 ชั้น', 'location' => 'จังหวัดขอนแก่น', 'page' => 13, 'cat' => 'อาคารราชการ'),
    array('name' => 'หอพักแพทย์ โรงพยาบาลขอนแก่น', 'location' => 'จังหวัดขอนแก่น', 'page' => 16, 'cat' => 'อาคารราชการ'),
    array('name' => 'อาคารสิริภักษ์ สำนักงานคลังจังหวัด', 'location' => 'จังหวัดขอนแก่น', 'page' => 18, 'cat' => 'อาคารราชการ'),
    array('name' => 'หอพักพยาบาล โรงพยาบาลขอนแก่น', 'location' => 'จังหวัดขอนแก่น', 'page' => 19, 'cat' => 'อาคารราชการ'),
    array('name' => 'หอพักพยาบาล โรงพยาบาลสิรินธร', 'location' => 'จังหวัดขอนแก่น', 'page' => 20, 'cat' => 'อาคารราชการ'),
    array('name' => 'หอพักแพทย์ มหาวิทยาลัยขอนแก่น', 'location' => 'จังหวัดขอนแก่น', 'page' => 21, 'cat' => 'อาคารราชการ'),
    // โรงงาน & โกดัง
    array('name' => 'โรงงาน DOS', 'location' => 'สาขาขอนแก่น', 'page' => 15, 'cat' => 'โรงงาน'),
    array('name' => 'โกดัง บริษัทอินเวนทิโว คอสเมติก จำกัด', 'location' => 'จังหวัดมหาสารคาม', 'page' => 24, 'cat' => 'โรงงาน'),
    array('name' => 'โกดังให้เช่า A6', 'location' => 'จังหวัดเชียงใหม่', 'page' => 25, 'cat' => 'โรงงาน'),
    array('name' => 'โกดังสินค้า อำเภอสารภี', 'location' => 'จังหวัดเชียงใหม่', 'page' => 28, 'cat' => 'โรงงาน'),
    // คอนโด & หอพัก
    array('name' => 'หอพัก TRIPLE T RESIDENCE KKU', 'location' => 'จังหวัดขอนแก่น', 'page' => 30, 'cat' => 'คอนโด'),
    array('name' => 'คอนโดฉัตรเพชร โนนม่วง', 'location' => 'จังหวัดขอนแก่น', 'page' => 34, 'cat' => 'คอนโด'),
    array('name' => 'เมโทรคอนโด (METRO CONDO)', 'location' => 'จังหวัดขอนแก่น', 'page' => 35, 'cat' => 'คอนโด'),
    array('name' => 'คอนโดมิเนียม X10 ศรีนครินทร์', 'location' => 'จังหวัดขอนแก่น', 'page' => 40, 'cat' => 'คอนโด'),
    array('name' => 'เดอะ เดสทินี เอ็กคลูซีพ คอนโดมิเนียม', 'location' => 'จังหวัดขอนแก่น', 'page' => 42, 'cat' => 'คอนโด'),
    array('name' => 'ชาลิสา คอนโด', 'location' => 'จังหวัดขอนแก่น', 'page' => 43, 'cat' => 'คอนโด'),
    array('name' => 'ESCENT CONDO', 'location' => 'จังหวัดขอนแก่น', 'page' => 45, 'cat' => 'คอนโด'),
    array('name' => 'เอพี บูเลอวาร์ด คอนโด', 'location' => 'จังหวัดขอนแก่น', 'page' => 46, 'cat' => 'คอนโด'),
    array('name' => 'เดอะ เบส ไฮท์ มิตรภาพ', 'location' => 'จังหวัดขอนแก่น', 'page' => 49, 'cat' => 'คอนโด'),
    array('name' => 'คอนโดกัลปพฤกษ์ เลควิว', 'location' => 'จังหวัดขอนแก่น', 'page' => 50, 'cat' => 'คอนโด'),
    // บ้านพักอาศัย
    array('name' => 'บ้านพักอธิการบดีอัยการภาค4', 'location' => 'จังหวัดขอนแก่น', 'page' => 22, 'cat' => 'บ้านพักอาศัย'),
    array('name' => 'หมู่บ้านสีวลี', 'location' => 'อำเภอเมืองขอนแก่น', 'page' => 33, 'cat' => 'บ้านพักอาศัย'),
    array('name' => 'หมู่บ้านเออเบินนารา แอร์พอร์ต-บายพาส', 'location' => 'จังหวัดขอนแก่น', 'page' => 36, 'cat' => 'บ้านพักอาศัย'),
    array('name' => 'หมู่บ้าน KLEVER TYME', 'location' => 'จังหวัดขอนแก่น', 'page' => 39, 'cat' => 'บ้านพักอาศัย'),
    // อาคารพาณิชย์ & คลินิก
    array('name' => 'ตั้งฮ่งหลี อาคารพาณิชย์ 4 ชั้น', 'location' => 'จังหวัดขอนแก่น', 'page' => 27, 'cat' => 'อาคารพาณิชย์'),
    array('name' => 'คลินิกกายภาพ รีเฟรชชี่', 'location' => 'จังหวัดขอนแก่น', 'page' => 29, 'cat' => 'อาคารพาณิชย์'),
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
        
        $portfolio_content .= '
<!-- wp:column -->
<div class="wp-block-column" style="text-align:center;">
<!-- wp:image {"sizeSlug":"medium"} -->
<figure class="wp-block-image size-medium"><img src="' . project_img($project['page']) . '" alt="' . $project['name'] . '" style="border-radius:12px;object-fit:cover;aspect-ratio:4/3;width:100%;" loading="lazy"/></figure>
<!-- /wp:image -->
' . $gallery_html . '
<h4 class="wp-block-heading">' . $project['name'] . '</h4>
<p style="color:#666;font-size:0.9em;">' . $project['location'] . '</p>
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

for ($i = 1; $i <= 6; $i++) {
    if ($i % 3 == 1) $portfolio_content .= "\n<!-- wp:columns -->\n<div class=\"wp-block-columns\">";
    $portfolio_content .= '
<!-- wp:column -->
<div class="wp-block-column">
<!-- wp:video -->
<figure class="wp-block-video"><video controls src="' . vid($i) . '"></video><figcaption>ผลงาน #' . $i . '</figcaption></figure>
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
<!-- wp:image -->
<figure class="wp-block-image"><img src="' . $unsplash['about'] . '" alt="ทีมงาน Birds Go Away"/></figure>
<!-- /wp:image -->
<!-- wp:image -->
<figure class="wp-block-image"><img src="' . $unsplash['safety'] . '" alt="มาตรฐานความปลอดภัย Birds Go Away"/></figure>
<!-- /wp:image -->
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

<!-- wp:columns -->
<div class="wp-block-columns">
<!-- wp:column {"width":"50%"} -->
<div class="wp-block-column" style="flex-basis:50%;">

<h3 class="wp-block-heading">ช่องทางติดต่อ</h3>

<p><strong>ที่อยู่:</strong> 88/38 หมู่บ้าน Klever ซอย5 ตำบลบ้านเป็ด อำเภอเมือง จังหวัดขอนแก่น 40000</p>
<p><strong>อีเมล:</strong> <a href="mailto:admin@birdsgoaway.com">admin@birdsgoaway.com</a></p>

<h4 class="wp-block-heading">โทรศัพท์</h4>
<!-- wp:html -->
<div style="display:flex;flex-direction:column;gap:6px;margin:0.5rem 0 1rem;">
<a href="tel:0629964994" style="display:inline-flex;align-items:center;gap:6px;text-decoration:none;color:#1B4D5C;font-size:0.9rem;">
<svg width="16" height="16" viewBox="0 0 24 24" fill="#E8792E"><path d="M6.62 10.79a15.053 15.053 0 006.59 6.59l2.2-2.2a1.003 1.003 0 011.01-.24c1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/></svg>
<strong>062-996-4994</strong> <span style="color:#666;font-size:0.8rem;">(คุณออย — ขอนแก่น)</span></a>
<a href="tel:0889514924" style="display:inline-flex;align-items:center;gap:6px;text-decoration:none;color:#1B4D5C;font-size:0.9rem;">
<svg width="16" height="16" viewBox="0 0 24 24" fill="#E8792E"><path d="M6.62 10.79a15.053 15.053 0 006.59 6.59l2.2-2.2a1.003 1.003 0 011.01-.24c1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/></svg>
<strong>088-951-4924</strong> <span style="color:#666;font-size:0.8rem;">(คุณวีวี่)</span></a>
<a href="tel:0936415623" style="display:inline-flex;align-items:center;gap:6px;text-decoration:none;color:#1B4D5C;font-size:0.9rem;">
<svg width="16" height="16" viewBox="0 0 24 24" fill="#E8792E"><path d="M6.62 10.79a15.053 15.053 0 006.59 6.59l2.2-2.2a1.003 1.003 0 011.01-.24c1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/></svg>
<strong>093-641-5623</strong> <span style="color:#666;font-size:0.8rem;">(เชียงใหม่)</span></a>
<a href="tel:0956292488" style="display:inline-flex;align-items:center;gap:6px;text-decoration:none;color:#1B4D5C;font-size:0.9rem;">
<svg width="16" height="16" viewBox="0 0 24 24" fill="#E8792E"><path d="M6.62 10.79a15.053 15.053 0 006.59 6.59l2.2-2.2a1.003 1.003 0 011.01-.24c1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/></svg>
<strong>095-629-2488</strong> <span style="color:#666;font-size:0.8rem;">(ชลบุรี)</span></a>
</div>
<!-- /wp:html -->

<h4 class="wp-block-heading">💬 LINE — แอดไลน์ส่งรูปหน้างานประเมินราคาฟรี</h4>
<!-- wp:html -->
<div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;margin:0.5rem 0 1rem;">
<a href="https://line.me/ti/p/~oil_phanu" target="_blank" style="display:inline-flex;align-items:center;gap:6px;background:#06C755;color:#fff;padding:8px 14px;border-radius:6px;font-weight:500;text-decoration:none;font-size:0.8rem;">
<svg width="16" height="16" viewBox="0 0 24 24" fill="white"><path d="M12 2C6.48 2 2 5.82 2 10.5c0 2.95 1.95 5.55 4.87 7.13-.19.66-.68 2.37-.78 2.73-.13.47.17.46.36.34.15-.1 2.37-1.61 3.33-2.26.73.1 1.47.16 2.22.16 5.52 0 10-3.82 10-8.5S17.52 2 12 2z"/></svg>
oil_phanu (ขอนแก่น)</a>
<a href="https://line.me/ti/p/~th3-ta006-2" target="_blank" style="display:inline-flex;align-items:center;gap:6px;background:#06C755;color:#fff;padding:8px 14px;border-radius:6px;font-weight:500;text-decoration:none;font-size:0.8rem;">
<svg width="16" height="16" viewBox="0 0 24 24" fill="white"><path d="M12 2C6.48 2 2 5.82 2 10.5c0 2.95 1.95 5.55 4.87 7.13-.19.66-.68 2.37-.78 2.73-.13.47.17.46.36.34.15-.1 2.37-1.61 3.33-2.26.73.1 1.47.16 2.22.16 5.52 0 10-3.82 10-8.5S17.52 2 12 2z"/></svg>
th3-ta006-2</a>
</div>
<p style="font-size:0.8rem;color:#888;margin-top:4px;">กดปุ่มด้านบนเพื่อแอดไลน์ ส่งรูปหน้างานประเมินราคาได้เลย</p>
<!-- /wp:html -->

<h4 class="wp-block-heading">Facebook</h4>
<p><a href="https://www.facebook.com/share/1ZAXHsxCft/?mibextid=wwXIfr" target="_blank" rel="noopener">ตาข่ายกันนก by Birds Go Away</a></p>

</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->

<!-- wp:separator {"className":"is-style-wide"} -->
<hr class="wp-block-separator has-alpha-channel-opacity is-style-wide"/>
<!-- /wp:separator -->

<!-- wp:heading {"textAlign":"center","level":3} -->
<h3 class="has-text-align-center wp-block-heading">แผนที่สำนักงาน</h3>
<!-- /wp:heading -->

<!-- wp:html -->
<div style="width:100%;border-radius:12px;overflow:hidden;box-shadow:0 4px 12px rgba(0,0,0,0.08);">
<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3826.8!2d102.8195!3d16.4457!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31228a9c6bffffff%3A0x0!2sKlever+Tyme+Srichan!5e0!3m2!1sth!2sth!4v1700000000000!5m2!1sth!2sth" width="100%" height="350" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
</div>
<!-- /wp:html -->

<!-- wp:separator {"className":"is-style-wide"} -->
<hr class="wp-block-separator has-alpha-channel-opacity is-style-wide"/>
<!-- /wp:separator -->

<!-- wp:heading {"textAlign":"center","level":3} -->
<h3 class="has-text-align-center wp-block-heading">เปิดให้บริการ</h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">จันทร์ - เสาร์: 08:00 - 18:00 น.<br>อาทิตย์: นัดหมายล่วงหน้า</p>
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

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">อันตรายจากขี้นกพิราบ — โรคที่คุณอาจไม่รู้</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
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
<p><strong>วิธีป้องกัน:</strong> ติดตั้งตาข่ายกันนก HDPE หรือหนามกันนกสแตนเลส เป็นวิธีที่ปลอดภัยและได้ผลถาวร ไม่ทำร้ายนก แต่ป้องกันไม่ให้นกเข้ามาทำรังในพื้นที่ หากคุณอยู่ในพื้นที่<strong>ขอนแก่น เชียงใหม่ ชลบุรี หรือภาคอีสาน</strong> สามารถติดต่อ BIRDS GO AWAY เพื่อขอคำปรึกษาฟรีได้เลย</p>
<!-- /wp:paragraph -->

<!-- wp:separator {"className":"is-style-wide"} -->
<hr class="wp-block-separator has-alpha-channel-opacity is-style-wide"/>
<!-- /wp:separator -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">วิธีไล่นกพิราบด้วยตัวเอง — ได้ผลจริงหรือ?</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>หลายคนเคยลองวิธีไล่นกพิราบด้วยตัวเอง เช่น แขวนซีดี ใช้เสียงไล่ ติดสติกเกอร์ตานก แต่วิธีเหล่านี้ได้ผลแค่ชั่วคราว เพราะนกพิราบเป็นสัตว์ที่ปรับตัวได้เร็วมาก</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p><strong>วิธีที่ได้ผลถาวร:</strong></p>
<!-- /wp:paragraph -->

<!-- wp:list {"ordered":true} -->
<ol>
<li><strong>ตาข่ายกันนก HDPE</strong> — เหมาะกับระเบียง ช่องเปิด พื้นที่กว้าง อายุการใช้งาน 6-7 ปี</li>
<li><strong>หนามกันนก สแตนเลส SUS304</strong> — เหมาะกับขอบหน้าต่าง ราวกันตก ชายคา อายุ 10+ ปี</li>
<li><strong>เจลไล่นก</strong> — เหมาะกับพื้นที่แคบ ติดตั้งง่าย แต่ต้องเปลี่ยนทุก 1-2 ปี</li>
<li><strong>แผงกันนกโซลาร์เซลล์</strong> — ระบบคลิปไม่เจาะแผง ป้องกันนกทำรังใต้แผง</li>
</ol>
<!-- /wp:list -->

<!-- wp:paragraph -->
<p>หากต้องการคำปรึกษาจากผู้เชี่ยวชาญ บริการ<strong>ติดตั้งตาข่ายกันนก ขอนแก่น</strong> <strong>เชียงใหม่</strong> <strong>ชลบุรี</strong> และ<strong>ทั่วภาคอีสาน</strong> โดย BIRDS GO AWAY ปรึกษาฟรี โทร 062-996-4994</p>
<!-- /wp:paragraph -->

<!-- wp:separator {"className":"is-style-wide"} -->
<hr class="wp-block-separator has-alpha-channel-opacity is-style-wide"/>
<!-- /wp:separator -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">ตาข่ายกันนก ราคาเท่าไหร่? คำนวณอย่างไร?</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
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
<p><strong>ประเมินราคาฟรี!</strong> ส่งรูปหน้างานทาง LINE: oil_phanu หรือโทร 062-996-4994 ทีมวิศวกรจะเข้าสำรวจพื้นที่ วัดขนาด และเสนอราคาให้ฟรี ไม่มีค่าใช้จ่าย ให้บริการ<strong>ติดตั้งตาข่ายกันนก ขอนแก่น เชียงใหม่ ชลบุรี ภาคอีสาน</strong>ทั่วประเทศ</p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons">
<!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link" href="/contact">ขอประเมินราคาฟรี</a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->
';

// ===== UPDATE PAGES =====
$pages = array(
    'home'      => array('title' => 'หน้าแรก',      'content' => $home_content),
    'services'  => array('title' => 'บริการของเรา',  'content' => $services_content),
    'portfolio' => array('title' => 'ผลงานของเรา',  'content' => $portfolio_content),
    'about'     => array('title' => 'เกี่ยวกับเรา',  'content' => $about_content),
    'contact'   => array('title' => 'ติดต่อเรา',     'content' => $contact_content),
    'faq'       => array('title' => 'คำถามที่พบบ่อย', 'content' => $faq_content),
    'blog'      => array('title' => 'บทความ',        'content' => $blog_content),
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
        'title' => 'BIRDS GO AWAY — บริการติดตั้งตาข่ายกันนก ขอนแก่น เชียงใหม่ ชลบุรี ภาคอีสาน',
        'desc'  => 'บริการติดตั้งตาข่ายกันนก HDPE หนามกันนก เจลไล่นก แผงกันนกโซลาร์เซลล์ ขอนแก่น เชียงใหม่ ชลบุรี ภาคอีสาน โดยวิศวกรมืออาชีพ รับประกัน 3 ปี ปรึกษาฟรี โทร 062-996-4994',
        'focus' => 'ตาข่ายกันนก ขอนแก่น',
    ),
    'services' => array(
        'title' => 'บริการของเรา — ตาข่ายกันนก หนามกันนก เจลไล่นก แผงกันนกโซลาร์ | BIRDS GO AWAY',
        'desc'  => 'บริการติดตั้งตาข่าย HDPE กันนก แผงกันนกโซลาร์เซลล์ หนามกันนกสแตนเลส เจลไล่นก ครบวงจร ราคายุติธรรม มีวิศวกรคุมงาน รับประกัน 3 ปี',
        'focus' => 'บริการติดตั้งตาข่ายกันนก',
    ),
    'portfolio' => array(
        'title' => 'ผลงานของเรา — ตัวอย่างงานติดตั้งตาข่ายกันนก | BIRDS GO AWAY',
        'desc'  => 'ดูตัวอย่างผลงานการติดตั้งตาข่ายกันนก หนามกันนก เจลไล่นก พร้อมวิดีโอจากหน้างานจริง โดย BIRDS GO AWAY ขอนแก่น เชียงใหม่ ชลบุรี',
        'focus' => 'ผลงานติดตั้งตาข่ายกันนก',
    ),
    'about' => array(
        'title' => 'เกี่ยวกับเรา — บริษัท รีเช็ค บิ้วดิ้ง จำกัด | BIRDS GO AWAY',
        'desc'  => 'บริษัท รีเช็ค บิ้วดิ้ง จำกัด ผู้ให้บริการติดตั้งตาข่ายกันนก ทีมช่างผ่านอบรมโรยตัว มี Certificate วิศวกรคุมงาน ให้บริการ ขอนแก่น เชียงใหม่ ชลบุรี',
        'focus' => 'บริษัทติดตั้งตาข่ายกันนก',
    ),
    'contact' => array(
        'title' => 'ติดต่อเรา — ปรึกษาฟรี ขอใบเสนอราคาตาข่ายกันนก | BIRDS GO AWAY',
        'desc'  => 'ติดต่อ BIRDS GO AWAY ปรึกษาฟรีเรื่องตาข่ายกันนก โทร 062-996-4994 (ขอนแก่น) 093-641-5623 (เชียงใหม่) 095-629-2488 (ชลบุรี) LINE: oil_phanu',
        'focus' => 'ติดต่อตาข่ายกันนก',
    ),
    'faq' => array(
        'title' => 'คำถามที่พบบ่อย FAQ — ตาข่ายกันนก ราคา วิธีติดตั้ง | BIRDS GO AWAY',
        'desc'  => 'รวมคำถามที่พบบ่อยเกี่ยวกับตาข่ายกันนก ราคาติดตั้ง ขั้นตอนการทำงาน อายุการใช้งาน รับประกัน พื้นที่ให้บริการ โดย BIRDS GO AWAY',
        'focus' => 'ตาข่ายกันนก คำถามที่พบบ่อย',
    ),
    'blog' => array(
        'title' => 'บทความ — วิธีไล่นกพิราบ อันตรายจากขี้นก ตาข่ายกันนก ราคา | BIRDS GO AWAY',
        'desc'  => 'บทความความรู้เรื่องปัญหานกพิราบ อันตรายจากขี้นก วิธีไล่นกด้วยตัวเอง ราคาตาข่ายกันนก ขอนแก่น เชียงใหม่ ชลบุรี ภาคอีสาน โดยผู้เชี่ยวชาญ BIRDS GO AWAY',
        'focus' => 'วิธีไล่นกพิราบ ตาข่ายกันนก ขอนแก่น',
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

// Yoast global settings
update_option('wpseo_titles', array_merge(
    (array) get_option('wpseo_titles', array()),
    array(
        'company_name' => 'BIRDS GO AWAY — ตาข่ายกันนก',
        'company_or_person' => 'company',
        'company_logo' => '',
        'website_name' => 'BIRDS GO AWAY — ตาข่ายกันนก',
        'separator' => 'sc-pipe',
        'title-home-wpseo' => 'BIRDS GO AWAY — บริการติดตั้งตาข่ายกันนก มืออาชีพ',
        'metadesc-home-wpseo' => 'บริการติดตั้งตาข่ายกันนก HDPE หนามกันนก เจลไล่นก แผงกันนกโซลาร์เซลล์ รับประกัน 3 ปี ปรึกษาฟรี โทร 062-996-4994',
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
        'openingHours' => 'Mo-Sa 08:00-18:00',
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
