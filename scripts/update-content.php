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
    return $assets_base . '/birdnet-' . $padded . '.jpg';
}

function vid($num) {
    global $assets_base;
    $padded = str_pad($num, 2, '0', STR_PAD_LEFT);
    return $assets_base . '/reel-' . $padded . '.mp4';
}

// ===== HOME PAGE =====
$home_content = '
<!-- wp:columns -->
<div class="wp-block-columns">
<!-- wp:column -->
<div class="wp-block-column" style="background:transparent !important;border:none !important;">

<!-- wp:heading {"level":1} -->
<h1 class="wp-block-heading">BIRDS GO AWAY<br>บริการติดตั้งตาข่ายกันนก มืออาชีพ</h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"fontSize":"medium"} -->
<p class="has-medium-font-size">กำจัดปัญหานกพิราบรบกวนอย่างถาวร ด้วยทีมช่างมืออาชีพผ่านการอบรมโรยตัว มีใบ Certificate มีมาตรฐาน วิศวกรคุมงาน ภายใต้ บริษัท รีเช็ค บิ้วดิ้ง จำกัด</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"fontSize":"medium"} -->
<p class="has-medium-font-size"><strong>ตาข่าย HDPE คุณภาพสูง อายุการใช้งาน 5-7 ปี | รับประกันงานติดตั้ง 3 ปี | ปรึกษาฟรีโดยวิศวกรโยธา</strong></p>
<!-- /wp:paragraph -->

<!-- wp:buttons -->
<div class="wp-block-buttons">
<!-- wp:button {"backgroundColor":"vivid-green-cyan"} -->
<div class="wp-block-button"><a class="wp-block-button__link has-vivid-green-cyan-background-color has-background" href="tel:0629964994">โทรปรึกษาฟรี 062-996-4994</a></div>
<!-- /wp:button -->
<!-- wp:button {"className":"is-style-outline"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link" href="/services">ดูบริการทั้งหมด</a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->

</div>
<!-- /wp:column -->
<!-- wp:column -->
<div class="wp-block-column" style="background:transparent !important;border:none !important;">
<!-- wp:image {"sizeSlug":"large"} -->
<figure class="wp-block-image size-large"><img src="' . img(12) . '" alt="ติดตั้งตาข่ายกันนก Birds Go Away" style="border-radius:16px;"/></figure>
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
<h3 class="wp-block-heading">🛡️ ปลอดภัย 100%</h3>
<p>วัสดุคุณภาพสูง ไม่ทำร้ายนก ปลอดภัยต่อคนและสัตว์เลี้ยง ผ่านมาตรฐาน</p>
</div>
<!-- /wp:column -->
<!-- wp:column {"width":"25%"} -->
<div class="wp-block-column" style="flex-basis:25%;text-align:center;">
<h3 class="wp-block-heading">⚡ ติดตั้งรวดเร็ว</h3>
<p>ทีมช่างมืออาชีพ ดำเนินงานรวดเร็ว ไม่รบกวนการใช้ชีวิต มีวิศวกรคุมงาน</p>
</div>
<!-- /wp:column -->
<!-- wp:column {"width":"25%"} -->
<div class="wp-block-column" style="flex-basis:25%;text-align:center;">
<h3 class="wp-block-heading">✅ รับประกัน 3 ปี</h3>
<p>รับประกันคุณภาพงานติดตั้ง 3 ปี พร้อมบริการหลังการขาย ซ่อมแซมฟรี</p>
</div>
<!-- /wp:column -->
<!-- wp:column {"width":"25%"} -->
<div class="wp-block-column" style="flex-basis:25%;text-align:center;">
<h3 class="wp-block-heading">💰 ราคายุติธรรม</h3>
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
<figure class="wp-block-image size-medium"><img src="' . img(14) . '" alt="ตาข่าย HDPE กันนก" style="border-radius:12px;height:200px;object-fit:cover;"/></figure>
<!-- /wp:image -->
<h3 class="wp-block-heading">🔷 ตาข่าย HDPE กันนก</h3>
<p>ตาข่าย HDPE คุณภาพสูง อายุการใช้งาน 5-7 ปี แข็งแรง ทนทานต่อแรงดึง แรงกระแทก และสารเคมี เหมาะสำหรับทุกพื้นที่</p>
</div>
<!-- /wp:column -->
<!-- wp:column -->
<div class="wp-block-column" style="text-align:center;">
<!-- wp:image {"sizeSlug":"medium"} -->
<figure class="wp-block-image size-medium"><img src="' . img(24) . '" alt="แผงกันนกโซลาร์เซลล์" style="border-radius:12px;height:200px;object-fit:cover;"/></figure>
<!-- /wp:image -->
<h3 class="wp-block-heading">☀️ แผงกันนกโซลาร์เซลล์</h3>
<p>ระบบคลิปไม่เจาะแผง ยืดอายุการใช้งานโซลาร์เซลล์ หมดปัญหานกทำรัง ใต้แผงสกปรก สายไฟเสียหาย</p>
</div>
<!-- /wp:column -->
<!-- wp:column -->
<div class="wp-block-column" style="text-align:center;">
<!-- wp:image {"sizeSlug":"medium"} -->
<figure class="wp-block-image size-medium"><img src="' . img(26) . '" alt="หนามกันนก สแตนเลส" style="border-radius:12px;height:200px;object-fit:cover;"/></figure>
<!-- /wp:image -->
<h3 class="wp-block-heading">🔺 หนามกันนก</h3>
<p>หนามสแตนเลสกันนก ป้องกันนกเกาะ ทนทานต่อทุกสภาพอากาศ ติดตั้งง่าย ราคาประหยัด เหมาะกับขอบหน้าต่าง ราวกันตก</p>
</div>
<!-- /wp:column -->
<!-- wp:column -->
<div class="wp-block-column" style="text-align:center;">
<!-- wp:image {"sizeSlug":"medium"} -->
<figure class="wp-block-image size-medium"><img src="' . img(27) . '" alt="เจลไล่นก" style="border-radius:12px;height:200px;object-fit:cover;"/></figure>
<!-- /wp:image -->
<h3 class="wp-block-heading">💧 เจลไล่นก</h3>
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
<figure class="wp-block-image"><img src="' . img(12) . '" alt="ผลงานติดตั้งตาข่ายกันนก 01"/></figure>
<!-- /wp:image -->
<!-- wp:image -->
<figure class="wp-block-image"><img src="' . img(13) . '" alt="ผลงานติดตั้งตาข่ายกันนก 02"/></figure>
<!-- /wp:image -->
<!-- wp:image -->
<figure class="wp-block-image"><img src="' . img(14) . '" alt="ผลงานติดตั้งตาข่ายกันนก 03"/></figure>
<!-- /wp:image -->
<!-- wp:image -->
<figure class="wp-block-image"><img src="' . img(15) . '" alt="ผลงานติดตั้งตาข่ายกันนก 04"/></figure>
<!-- /wp:image -->
<!-- wp:image -->
<figure class="wp-block-image"><img src="' . img(16) . '" alt="ผลงานติดตั้งตาข่ายกันนก 05"/></figure>
<!-- /wp:image -->
<!-- wp:image -->
<figure class="wp-block-image"><img src="' . img(17) . '" alt="ผลงานติดตั้งตาข่ายกันนก 06"/></figure>
<!-- /wp:image -->
<!-- wp:image -->
<figure class="wp-block-image"><img src="' . img(18) . '" alt="ผลงานติดตั้งตาข่ายกันนก 07"/></figure>
<!-- /wp:image -->
<!-- wp:image -->
<figure class="wp-block-image"><img src="' . img(19) . '" alt="ผลงานติดตั้งตาข่ายกันนก 08"/></figure>
<!-- /wp:image -->
<!-- wp:image -->
<figure class="wp-block-image"><img src="' . img(20) . '" alt="ผลงานติดตั้งตาข่ายกันนก 09"/></figure>
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
<h2 class="has-text-align-center wp-block-heading">พื้นที่ให้บริการ</h2>
<!-- /wp:heading -->

<!-- wp:columns -->
<div class="wp-block-columns">
<!-- wp:column -->
<div class="wp-block-column" style="text-align:center;border:2px solid #e2e8f0;">
<h3 class="wp-block-heading">📍 ขอนแก่น</h3>
<p><a href="tel:0629964994"><strong>062-996-4994</strong></a></p>
<p>LINE: <a href="https://line.me/ti/p/~oil_phanu">oil_phanu</a></p>
</div>
<!-- /wp:column -->
<!-- wp:column -->
<div class="wp-block-column" style="text-align:center;border:2px solid #e2e8f0;">
<h3 class="wp-block-heading">📍 เชียงใหม่</h3>
<p><a href="tel:0936415623"><strong>093-641-5623</strong></a></p>
<p>LINE: <a href="https://line.me/ti/p/~th3-ta006-2">th3-ta006-2</a></p>
</div>
<!-- /wp:column -->
<!-- wp:column -->
<div class="wp-block-column" style="text-align:center;border:2px solid #e2e8f0;">
<h3 class="wp-block-heading">📍 ชลบุรี</h3>
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
<h2 class="wp-block-heading">🔷 ตาข่าย HDPE กันนก</h2>
<!-- /wp:heading -->

<!-- wp:columns -->
<div class="wp-block-columns">
<!-- wp:column {"width":"50%"} -->
<div class="wp-block-column" style="flex-basis:50%;">
<p>ตาข่าย HDPE (High Density Polyethylene) คุณภาพสูง เป็นวิธีที่มีประสิทธิภาพสูงสุดในการป้องกันนกเข้าพื้นที่</p>
<ul>
<li>อายุการใช้งานประมาณ 5-7 ปี</li>
<li>แข็งแรง ทนทานต่อแรงดึง แรงกระแทก</li>
<li>ทนต่อรังสี UV และสารเคมี</li>
<li>มองไม่เห็นจากภายนอก สบายตา</li>
<li>เหมาะกับ: ระเบียงคอนโด, โรงงาน, อาคารสำนักงาน, บ้านพักอาศัย</li>
</ul>
</div>
<!-- /wp:column -->
<!-- wp:column {"width":"50%"} -->
<div class="wp-block-column" style="flex-basis:50%;">
<!-- wp:image -->
<figure class="wp-block-image"><img src="' . img(13) . '" alt="ตาข่าย HDPE กันนก"/></figure>
<!-- /wp:image -->
<!-- wp:image -->
<figure class="wp-block-image"><img src="' . img(15) . '" alt="ตาข่ายกันนก ติดตั้งระเบียง"/></figure>
<!-- /wp:image -->
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->

<!-- wp:separator {"className":"is-style-wide"} -->
<hr class="wp-block-separator has-alpha-channel-opacity is-style-wide"/>
<!-- /wp:separator -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">☀️ แผงกันนกโซลาร์เซลล์</h2>
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
<figure class="wp-block-image"><img src="' . img(25) . '" alt="แผงกันนกโซลาร์เซลล์"/></figure>
<!-- /wp:image -->
<!-- wp:image -->
<figure class="wp-block-image"><img src="' . img(28) . '" alt="ระบบคลิปกันนกโซลาร์"/></figure>
<!-- /wp:image -->
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->

<!-- wp:separator {"className":"is-style-wide"} -->
<hr class="wp-block-separator has-alpha-channel-opacity is-style-wide"/>
<!-- /wp:separator -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">🔺 หนามกันนก</h2>
<!-- /wp:heading -->

<!-- wp:columns -->
<div class="wp-block-columns">
<!-- wp:column {"width":"50%"} -->
<div class="wp-block-column" style="flex-basis:50%;">
<p>หนามสแตนเลสกันนก เป็นอุปกรณ์ป้องกันนกเกาะที่นิยมใช้มากที่สุด ติดตั้งง่าย ราคาประหยัด</p>
<ul>
<li>สแตนเลสเกรดพรีเมียม ทนทานสูง</li>
<li>ทนต่อทุกสภาพอากาศ ไม่เป็นสนิม</li>
<li>ติดตั้งง่าย รวดเร็ว</li>
<li>เหมาะกับ: ขอบหน้าต่าง ราวกันตก ป้ายอาคาร ชายคา</li>
</ul>
</div>
<!-- /wp:column -->
<!-- wp:column {"width":"50%"} -->
<div class="wp-block-column" style="flex-basis:50%;">
<!-- wp:image -->
<figure class="wp-block-image"><img src="' . img(30) . '" alt="หนามกันนก สแตนเลส"/></figure>
<!-- /wp:image -->
<!-- wp:image -->
<figure class="wp-block-image"><img src="' . img(31) . '" alt="หนามกันนก ติดตั้ง"/></figure>
<!-- /wp:image -->
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->

<!-- wp:separator {"className":"is-style-wide"} -->
<hr class="wp-block-separator has-alpha-channel-opacity is-style-wide"/>
<!-- /wp:separator -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">💧 เจลไล่นก</h2>
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
<figure class="wp-block-image"><img src="' . img(32) . '" alt="เจลไล่นก"/></figure>
<!-- /wp:image -->
<!-- wp:image -->
<figure class="wp-block-image"><img src="' . img(33) . '" alt="เจลไล่นก ใช้งาน"/></figure>
<!-- /wp:image -->
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->

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
$portfolio_content = '
<!-- wp:heading {"textAlign":"center","level":2} -->
<h2 class="has-text-align-center wp-block-heading">ผลงานของเรา</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">ตัวอย่างผลงานการติดตั้งตาข่ายกันนก หนามกันนก เจลไล่นก และแผงกันนกโซลาร์เซลล์ จากทีม Birds Go Away</p>
<!-- /wp:paragraph -->

<!-- wp:gallery {"columns":3,"linkTo":"none"} -->
<figure class="wp-block-gallery has-nested-images columns-3 is-cropped">';

// Only use clear images (skip blurry thumbnails 1, 3-11)
$clear_images = array(2, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33);
$count = 0;
foreach ($clear_images as $i) {
    $count++;
    $portfolio_content .= '
<!-- wp:image -->
<figure class="wp-block-image"><img src="' . img($i) . '" alt="ผลงาน Birds Go Away #' . $count . '"/></figure>
<!-- /wp:image -->';
}

$portfolio_content .= '
</figure>
<!-- /wp:gallery -->

<!-- wp:separator {"className":"is-style-wide"} -->
<hr class="wp-block-separator has-alpha-channel-opacity is-style-wide"/>
<!-- /wp:separator -->

<!-- wp:heading {"textAlign":"center","level":2} -->
<h2 class="has-text-align-center wp-block-heading">วิดีโอผลงาน</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">ดูคลิปขั้นตอนการทำงานจริง จากหน้างาน</p>
<!-- /wp:paragraph -->';

for ($i = 1; $i <= 10; $i++) {
    if ($i % 3 == 1) $portfolio_content .= "\n<!-- wp:columns -->\n<div class=\"wp-block-columns\">";
    $portfolio_content .= '
<!-- wp:column -->
<div class="wp-block-column">
<!-- wp:video -->
<figure class="wp-block-video"><video controls src="' . vid($i) . '"></video><figcaption>ผลงาน #' . $i . '</figcaption></figure>
<!-- /wp:video -->
</div>
<!-- /wp:column -->';
    if ($i % 3 == 0 || $i == 10) $portfolio_content .= "\n</div>\n<!-- /wp:columns -->";
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
<p><strong>บริษัท รีเช็ค บิ้วดิ้ง จำกัด</strong> ดำเนินธุรกิจภายใต้แบรนด์ <strong>BIRDS GO AWAY</strong> ให้บริการติดตั้งตาข่ายกันนก หนามกันนก เจลไล่นก และแผงกันนกโซลาร์เซลล์ ครบวงจร</p>
<p>ทีมช่างของเราผ่านการอบรมโรยตัวภาคทฤษฎีและปฏิบัติ มีใบ Certificate มีมาตรฐาน มีวิศวกรคุมงานทุกไซต์งาน</p>

<h3 class="wp-block-heading">มาตรฐานความปลอดภัย</h3>
<ul>
<li>ผ่านการอบรมโรยตัว ภาคทฤษฎี-ปฏิบัติ</li>
<li>มีใบ Certificate รับรอง</li>
<li>วิศวกรโยธาคุมงานทุกไซต์</li>
<li>ใช้อุปกรณ์ความปลอดภัยครบชุด</li>
<li>ประกันอุบัติเหตุสำหรับทีมงาน</li>
</ul>
</div>
<!-- /wp:column -->
<!-- wp:column {"width":"40%"} -->
<div class="wp-block-column" style="flex-basis:40%;">
<!-- wp:image -->
<figure class="wp-block-image"><img src="' . img(2) . '" alt="ทีมงาน Birds Go Away"/></figure>
<!-- /wp:image -->
<!-- wp:image -->
<figure class="wp-block-image"><img src="' . img(21) . '" alt="มาตรฐานความปลอดภัย Birds Go Away"/></figure>
<!-- /wp:image -->
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
<div class="wp-block-column" style="text-align:center;border:2px solid #e2e8f0;">
<h3 class="wp-block-heading">📍 ขอนแก่น</h3>
<p><a href="tel:0629964994"><strong>062-996-4994</strong></a><br>LINE: oil_phanu</p>
</div>
<!-- /wp:column -->
<!-- wp:column -->
<div class="wp-block-column" style="text-align:center;border:2px solid #e2e8f0;">
<h3 class="wp-block-heading">📍 เชียงใหม่</h3>
<p><a href="tel:0936415623"><strong>093-641-5623</strong></a><br>LINE: th3-ta006-2</p>
</div>
<!-- /wp:column -->
<!-- wp:column -->
<div class="wp-block-column" style="text-align:center;border:2px solid #e2e8f0;">
<h3 class="wp-block-heading">📍 ชลบุรี</h3>
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

<p>📍 <strong>ที่อยู่:</strong> 88/38 ขอนแก่น</p>
<p>📧 <strong>อีเมล:</strong> <a href="mailto:admin@birdsgoaway.com">admin@birdsgoaway.com</a></p>

<h4 class="wp-block-heading">📞 โทรศัพท์</h4>
<p><a href="tel:0629964994"><strong>062-996-4994</strong></a> (ขอนแก่น)<br>
<a href="tel:0936415623"><strong>093-641-5623</strong></a> (เชียงใหม่)<br>
<a href="tel:0956292488"><strong>095-629-2488</strong></a> (ชลบุรี)</p>

<h4 class="wp-block-heading">💬 LINE</h4>
<p><a href="https://line.me/ti/p/~oil_phanu">oil_phanu</a><br>
<a href="https://line.me/ti/p/~th3-ta006-2">th3-ta006-2</a></p>

<h4 class="wp-block-heading">🌐 Facebook</h4>
<p><a href="https://www.facebook.com/share/1ZAXHsxCft/?mibextid=wwXIfr" target="_blank" rel="noopener">ตาข่ายกันนก by Birds Go Away</a></p>

</div>
<!-- /wp:column -->
<!-- wp:column {"width":"50%"} -->
<div class="wp-block-column" style="flex-basis:50%;">

<h3 class="wp-block-heading">ส่งข้อความถึงเรา</h3>

<!-- wp:shortcode -->
[contact-form-7 title="แบบฟอร์มติดต่อ"]
<!-- /wp:shortcode -->

</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->

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

// ===== UPDATE PAGES =====
$pages = array(
    'home'      => array('title' => 'หน้าแรก',      'content' => $home_content),
    'services'  => array('title' => 'บริการของเรา',  'content' => $services_content),
    'portfolio' => array('title' => 'ผลงานของเรา',  'content' => $portfolio_content),
    'about'     => array('title' => 'เกี่ยวกับเรา',  'content' => $about_content),
    'contact'   => array('title' => 'ติดต่อเรา',     'content' => $contact_content),
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
        'title' => 'BIRDS GO AWAY — บริการติดตั้งตาข่ายกันนก มืออาชีพ | ขอนแก่น เชียงใหม่ ชลบุรี',
        'desc'  => 'บริการติดตั้งตาข่ายกันนก HDPE หนามกันนก เจลไล่นก แผงกันนกโซลาร์เซลล์ โดยทีมช่างมืออาชีพ รับประกัน 3 ปี ปรึกษาฟรี โทร 062-996-4994',
        'focus' => 'ตาข่ายกันนก',
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
echo "Content update complete!\n";
