#!/bin/bash
set -e

# Run the original WordPress entrypoint to set up wp-config.php
docker-entrypoint.sh apache2-foreground &
WP_PID=$!

echo "=== Waiting for WordPress files to be ready ==="
until [ -f /var/www/html/wp-includes/version.php ]; do
  sleep 2
done

echo "=== Waiting for database connection ==="
until wp db check --path=/var/www/html --allow-root 2>/dev/null; do
  echo "Waiting for database..."
  sleep 3
done

# Check if WordPress is already installed
if ! wp core is-installed --path=/var/www/html --allow-root 2>/dev/null; then
  echo "=== Installing WordPress Core ==="

  SITE_URL="${RAILWAY_PUBLIC_DOMAIN:-localhost:8080}"
  PROTOCOL="https"
  if [ "$SITE_URL" = "localhost:8080" ]; then
    PROTOCOL="http"
  fi

  wp core install \
    --url="${PROTOCOL}://${SITE_URL}" \
    --title="Bird Net Installation Services | บริการติดตั้งตาข่ายกันนก" \
    --admin_user="${WP_ADMIN_USER:-admin}" \
    --admin_password="${WP_ADMIN_PASSWORD:-admin123}" \
    --admin_email="${WP_ADMIN_EMAIL:-admin@birdnet.local}" \
    --skip-email \
    --path=/var/www/html \
    --allow-root

  echo "=== Setting Thai locale ==="
  wp language core install th --path=/var/www/html --allow-root || true
  wp site switch-language th --path=/var/www/html --allow-root || true

  echo "=== Setting timezone ==="
  wp option update timezone_string "Asia/Bangkok" --path=/var/www/html --allow-root
  wp option update date_format "j F Y" --path=/var/www/html --allow-root
  wp option update time_format "H:i" --path=/var/www/html --allow-root

  echo "=== Installing Astra Theme ==="
  wp theme install astra --activate --path=/var/www/html --allow-root

  # Install child theme
  if [ -d /tmp/birdnet-child ]; then
    cp -r /tmp/birdnet-child /var/www/html/wp-content/themes/birdnet-child
    chown -R www-data:www-data /var/www/html/wp-content/themes/birdnet-child
    wp theme activate birdnet-child --path=/var/www/html --allow-root
  fi

  echo "=== Installing Plugins ==="
  wp plugin install elementor --activate --path=/var/www/html --allow-root
  wp plugin install contact-form-7 --activate --path=/var/www/html --allow-root
  wp plugin install wordpress-seo --activate --path=/var/www/html --allow-root
  wp plugin install wordfence --activate --path=/var/www/html --allow-root
  wp plugin install wp-super-cache --activate --path=/var/www/html --allow-root
  wp plugin install addon-starter-templates --activate --path=/var/www/html --allow-root || true

  echo "=== Configuring Permalinks ==="
  wp rewrite structure '/%postname%/' --path=/var/www/html --allow-root
  wp rewrite flush --path=/var/www/html --allow-root

  echo "=== Setting Reading Options ==="
  wp option update show_on_front page --path=/var/www/html --allow-root

  echo "=== Removing default content ==="
  wp post delete 1 --force --path=/var/www/html --allow-root 2>/dev/null || true
  wp post delete 2 --force --path=/var/www/html --allow-root 2>/dev/null || true
  wp post delete 3 --force --path=/var/www/html --allow-root 2>/dev/null || true

  echo "=== Creating Contact Form ==="
  FORM_ID=$(wp cf7 create "แบบฟอร์มติดต่อ" --path=/var/www/html --allow-root --porcelain 2>/dev/null || echo "")

  if [ -n "$FORM_ID" ]; then
    wp cf7 update $FORM_ID --path=/var/www/html --allow-root \
      --form='<div class="cf7-form-grid">
<div class="cf7-field">
<label>ชื่อ-นามสกุล (จำเป็น)</label>
[text* your-name autocomplete:name placeholder "กรุณากรอกชื่อ-นามสกุล"]
</div>
<div class="cf7-field">
<label>อีเมล (จำเป็น)</label>
[email* your-email autocomplete:email placeholder "example@email.com"]
</div>
<div class="cf7-field">
<label>เบอร์โทรศัพท์ (จำเป็น)</label>
[tel* your-phone placeholder "08X-XXX-XXXX"]
</div>
<div class="cf7-field">
<label>บริการที่สนใจ</label>
[select your-service "ตาข่าย HDPE กันนก" "แผงกันนกโซลาร์เซลล์" "หนามกันนก" "เจลไล่นก" "อื่นๆ"]
</div>
<div class="cf7-field cf7-full">
<label>ข้อความ</label>
[textarea your-message placeholder "รายละเอียดเพิ่มเติม เช่น สถานที่ ขนาดพื้นที่"]
</div>
<div class="cf7-field cf7-full">
[submit class:btn-submit "ส่งข้อความ"]
</div>
</div>' \
      --mail='{"subject":"[แบบฟอร์มติดต่อ] จาก [your-name]","sender":"[your-name] <[your-email]>","body":"ชื่อ: [your-name]\nอีเมล: [your-email]\nเบอร์โทร: [your-phone]\nบริการที่สนใจ: [your-service]\n\nข้อความ:\n[your-message]","recipient":"'"${WP_ADMIN_EMAIL:-admin@birdnet.local}"'","additional_headers":"Reply-To: [your-email]"}' 2>/dev/null || true
    FORM_SHORTCODE="[contact-form-7 id=\"${FORM_ID}\" title=\"แบบฟอร์มติดต่อ\"]"
  else
    FORM_SHORTCODE='[contact-form-7 title="แบบฟอร์มติดต่อ"]'
  fi

  echo "=== Creating Pages ==="

  # Home Page
  HOME_ID=$(wp post create --post_type=page --post_title='หน้าแรก' --post_status=publish --post_name='home' --path=/var/www/html --allow-root --porcelain --post_content='
<!-- wp:columns -->
<div class="wp-block-columns">

<!-- wp:column -->
<div class="wp-block-column">

<!-- wp:heading {"level":1} -->
<h1>บริการติดตั้งตาข่ายกันนก<br>มืออาชีพ ครบวงจร</h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"fontSize":"medium"} -->
<p class="has-medium-font-size">ปกป้องอาคาร บ้านเรือน และโซลาร์เซลล์จากปัญหานก ด้วยโซลูชันที่ปลอดภัย ทนทาน และเป็นมิตรกับสิ่งแวดล้อม — รับประกันผลงานทุกชิ้น</p>
<!-- /wp:paragraph -->

<!-- wp:buttons -->
<div class="wp-block-buttons">
<!-- wp:button {"backgroundColor":"vivid-cyan-blue"} -->
<div class="wp-block-button"><a class="wp-block-button__link has-vivid-cyan-blue-background-color has-background" href="/contact">ขอใบเสนอราคาฟรี</a></div>
<!-- /wp:button -->
<!-- wp:button {"className":"is-style-outline"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link" href="/services">ดูบริการทั้งหมด</a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->

</div>
<!-- /wp:column -->

</div>
<!-- /wp:columns -->

<!-- wp:heading {"textAlign":"center","level":2} -->
<h2 class="has-text-align-center">ทำไมต้องเลือกเรา?</h2>
<!-- /wp:heading -->

<!-- wp:columns -->
<div class="wp-block-columns">

<!-- wp:column {"width":"25%"} -->
<div class="wp-block-column" style="flex-basis:25%">
<!-- wp:heading {"level":3,"textAlign":"center"} -->
<h3 class="has-text-align-center">🛡️ ปลอดภัย 100%</h3>
<!-- /wp:heading -->
<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">วัสดุคุณภาพสูง ไม่ทำร้ายนก ปลอดภัยต่อคนและสัตว์เลี้ยง</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:column -->

<!-- wp:column {"width":"25%"} -->
<div class="wp-block-column" style="flex-basis:25%">
<!-- wp:heading {"level":3,"textAlign":"center"} -->
<h3 class="has-text-align-center">⚡ ติดตั้งรวดเร็ว</h3>
<!-- /wp:heading -->
<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">ทีมช่างมืออาชีพ ดำเนินงานรวดเร็ว ไม่รบกวนการใช้ชีวิต</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:column -->

<!-- wp:column {"width":"25%"} -->
<div class="wp-block-column" style="flex-basis:25%">
<!-- wp:heading {"level":3,"textAlign":"center"} -->
<h3 class="has-text-align-center">✅ รับประกันผลงาน</h3>
<!-- /wp:heading -->
<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">รับประกันคุณภาพงานติดตั้ง พร้อมบริการหลังการขาย</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:column -->

<!-- wp:column {"width":"25%"} -->
<div class="wp-block-column" style="flex-basis:25%">
<!-- wp:heading {"level":3,"textAlign":"center"} -->
<h3 class="has-text-align-center">💰 ราคายุติธรรม</h3>
<!-- /wp:heading -->
<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">เสนอราคาฟรี ไม่มีค่าใช้จ่ายแอบแฝง คุ้มค่าทุกบาท</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:column -->

</div>
<!-- /wp:columns -->

<!-- wp:heading {"textAlign":"center","level":2} -->
<h2 class="has-text-align-center">บริการของเรา</h2>
<!-- /wp:heading -->

<!-- wp:columns -->
<div class="wp-block-columns">

<!-- wp:column -->
<div class="wp-block-column">
<!-- wp:heading {"level":3} -->
<h3>🔷 ตาข่าย HDPE กันนก</h3>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>ตาข่ายไนล่อน HDPE คุณภาพสูง ทนแดด ทนฝน อายุการใช้งานยาวนาน เหมาะสำหรับอาคาร คอนโด โรงงาน และบ้านพักอาศัย</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column">
<!-- wp:heading {"level":3} -->
<h3>☀️ แผงกันนกโซลาร์เซลล์</h3>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>ระบบคลิปยึดพิเศษ ไม่ต้องเจาะแผงโซลาร์ ป้องกันนกทำรังใต้แผง ไม่เป็นอันตรายต่อระบบไฟฟ้า</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:column -->

</div>
<!-- /wp:columns -->

<!-- wp:columns -->
<div class="wp-block-columns">

<!-- wp:column -->
<div class="wp-block-column">
<!-- wp:heading {"level":3} -->
<h3>🔺 หนามกันนก</h3>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>หนามสแตนเลสกันนก ป้องกันนกเกาะบนราวระเบียง ป้ายไฟ ชายคา ทนทานต่อทุกสภาพอากาศ ติดตั้งง่าย</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column">
<!-- wp:heading {"level":3} -->
<h3>💧 เจลไล่นก</h3>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>เจลไล่นกสูตรพิเศษ ไม่มีสารพิษ ปลอดภัยต่อคนและสัตว์ ใช้ได้กับทุกพื้นผิว ป้องกันนกเกาะได้อย่างมีประสิทธิภาพ</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:column -->

</div>
<!-- /wp:columns -->

<!-- wp:separator -->
<hr class="wp-block-separator has-alpha-channel-opacity"/>
<!-- /wp:separator -->

<!-- wp:heading {"textAlign":"center","level":2} -->
<h2 class="has-text-align-center">พร้อมแก้ปัญหานกให้คุณ</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center","fontSize":"medium"} -->
<p class="has-text-align-center has-medium-font-size">ติดต่อเราวันนี้เพื่อรับคำปรึกษาและใบเสนอราคาฟรี!</p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons">
<!-- wp:button {"backgroundColor":"vivid-cyan-blue","fontSize":"medium"} -->
<div class="wp-block-button has-custom-font-size has-medium-font-size"><a class="wp-block-button__link has-vivid-cyan-blue-background-color has-background" href="/contact">ติดต่อเราเลย</a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->
')

  # Services Page
  SERVICES_ID=$(wp post create --post_type=page --post_title='บริการของเรา' --post_status=publish --post_name='services' --path=/var/www/html --allow-root --porcelain --post_content='
<!-- wp:heading {"textAlign":"center","level":1} -->
<h1 class="has-text-align-center">บริการของเรา</h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center","fontSize":"medium"} -->
<p class="has-text-align-center has-medium-font-size">เราให้บริการป้องกันนกครบวงจร ด้วยทีมช่างมืออาชีพและวัสดุคุณภาพสูง</p>
<!-- /wp:paragraph -->

<!-- wp:separator -->
<hr class="wp-block-separator has-alpha-channel-opacity"/>
<!-- /wp:separator -->

<!-- wp:heading {"level":2} -->
<h2>🔷 ตาข่าย HDPE กันนก (HDPE Bird Netting)</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>ตาข่ายไนล่อน HDPE (High-Density Polyethylene) เป็นวิธีป้องกันนกที่มีประสิทธิภาพสูงสุด เหมาะสำหรับพื้นที่ขนาดใหญ่</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3>คุณสมบัติเด่น:</h3>
<!-- /wp:heading -->

<!-- wp:list -->
<ul>
<li><strong>ทนทานสูง</strong> — วัสดุ HDPE ทนแดด ทนฝน ไม่ผุกร่อน อายุการใช้งาน 5-10 ปี</li>
<li><strong>ไม่ทำร้ายนก</strong> — ออกแบบมาเพื่อป้องกัน ไม่ใช่ทำอันตราย</li>
<li><strong>มองไม่ชัดจากระยะไกล</strong> — ไม่ทำลายทัศนียภาพของอาคาร</li>
<li><strong>หลากหลายขนาดช่องตาข่าย</strong> — เลือกได้ตามชนิดของนก</li>
</ul>
<!-- /wp:list -->

<!-- wp:heading {"level":3} -->
<h3>เหมาะสำหรับ:</h3>
<!-- /wp:heading -->

<!-- wp:list -->
<ul>
<li>อาคารสำนักงาน / คอนโดมิเนียม</li>
<li>โรงงาน / โกดังสินค้า</li>
<li>บ้านพักอาศัย / ทาวน์เฮาส์</li>
<li>วัด / โบสถ์ / อาคารอนุรักษ์</li>
<li>ศูนย์การค้า / ร้านอาหาร</li>
</ul>
<!-- /wp:list -->

<!-- wp:separator -->
<hr class="wp-block-separator has-alpha-channel-opacity"/>
<!-- /wp:separator -->

<!-- wp:heading {"level":2} -->
<h2>☀️ แผงกันนกโซลาร์เซลล์ (Solar Panel Bird Guard)</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>ระบบป้องกันนกสำหรับแผงโซลาร์เซลล์โดยเฉพาะ ใช้ระบบคลิปยึดพิเศษ <strong>ไม่ต้องเจาะแผง ไม่เป็นอันตรายต่อระบบไฟฟ้า</strong></p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3>ทำไมต้องติดแผงกันนกโซลาร์?</h3>
<!-- /wp:heading -->

<!-- wp:list -->
<ul>
<li>นกทำรังใต้แผงโซลาร์ ทำให้เกิดความเสียหายต่อสายไฟ</li>
<li>มูลนกสะสมทำให้ประสิทธิภาพแผงลดลง 20-30%</li>
<li>เสียงนกรบกวน โดยเฉพาะช่วงเช้า</li>
<li>ป้องกันปัญหาไฟฟ้าลัดวงจรจากรังนก</li>
</ul>
<!-- /wp:list -->

<!-- wp:heading {"level":3} -->
<h3>จุดเด่นของระบบคลิป:</h3>
<!-- /wp:heading -->

<!-- wp:list -->
<ul>
<li><strong>ไม่เจาะแผงโซลาร์</strong> — ไม่ทำให้เสียการรับประกัน</li>
<li><strong>ติดตั้งง่าย รวดเร็ว</strong> — ใช้เวลาเพียง 1-2 วัน</li>
<li><strong>วัสดุสแตนเลส 304</strong> — ทนสนิม ทนทานทุกสภาพอากาศ</li>
<li><strong>ถอดล้างทำความสะอาดได้</strong> — สะดวกในการบำรุงรักษา</li>
</ul>
<!-- /wp:list -->

<!-- wp:separator -->
<hr class="wp-block-separator has-alpha-channel-opacity"/>
<!-- /wp:separator -->

<!-- wp:heading {"level":2} -->
<h2>🔺 หนามกันนก (Bird Spikes)</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>หนามกันนกสแตนเลสคุณภาพสูง เป็นวิธีป้องกันนกเกาะที่ได้ผลดีเยี่ยม เหมาะสำหรับราวระเบียง ชายคา ป้ายไฟ และขอบหน้าต่าง</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3>คุณสมบัติเด่น:</h3>
<!-- /wp:heading -->

<!-- wp:list -->
<ul>
<li><strong>วัสดุสแตนเลส 304</strong> — ทนสนิม ทนทานทุกสภาพอากาศ อายุการใช้งานยาวนาน</li>
<li><strong>ฐาน UV Polycarbonate</strong> — ไม่เปราะแตกจากแสงแดด</li>
<li><strong>ไม่ทำร้ายนก</strong> — ออกแบบให้นกไม่สามารถเกาะได้ แต่ไม่ทำอันตราย</li>
<li><strong>ติดตั้งง่าย</strong> — ใช้กาวซิลิโคนหรือสกรูยึด</li>
<li><strong>มองไม่ชัดจากระยะไกล</strong> — ไม่กระทบทัศนียภาพ</li>
</ul>
<!-- /wp:list -->

<!-- wp:heading {"level":3} -->
<h3>เหมาะสำหรับ:</h3>
<!-- /wp:heading -->

<!-- wp:list -->
<ul>
<li>ราวระเบียง / ราวกันตก</li>
<li>ชายคา / หลังคา</li>
<li>ป้ายไฟ / ป้ายโฆษณา</li>
<li>ขอบหน้าต่าง / กันสาด</li>
<li>ท่อแอร์ / รางน้ำ</li>
</ul>
<!-- /wp:list -->

<!-- wp:separator -->
<hr class="wp-block-separator has-alpha-channel-opacity"/>
<!-- /wp:separator -->

<!-- wp:heading {"level":2} -->
<h2>💧 เจลไล่นก (Bird Repellent Gel)</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>เจลไล่นกสูตรพิเศษ เป็นวิธีป้องกันนกแบบไม่ทำร้าย ใช้ได้กับทุกพื้นผิว เหมาะสำหรับพื้นที่ที่ไม่สามารถติดตั้งตาข่ายหรือหนามได้</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3>คุณสมบัติเด่น:</h3>
<!-- /wp:heading -->

<!-- wp:list -->
<ul>
<li><strong>ไม่มีสารพิษ</strong> — ปลอดภัยต่อคน สัตว์เลี้ยง และนก</li>
<li><strong>มองไม่เห็น</strong> — เจลใสไม่ทิ้งคราบ ไม่กระทบความสวยงาม</li>
<li><strong>ใช้ได้ทุกพื้นผิว</strong> — โลหะ คอนกรีต ไม้ พลาสติก กระเบื้อง</li>
<li><strong>ทนทานต่อสภาพอากาศ</strong> — ทนฝน ทนแดด ใช้ได้นาน 6-12 เดือน</li>
<li><strong>ทำงานด้วยประสาทสัมผัส</strong> — นกไม่ชอบความรู้สึกเหนียวที่เท้า</li>
</ul>
<!-- /wp:list -->

<!-- wp:heading {"level":3} -->
<h3>เหมาะสำหรับ:</h3>
<!-- /wp:heading -->

<!-- wp:list -->
<ul>
<li>ขอบหน้าต่าง / กรอบประตู</li>
<li>ราวระเบียงที่ไม่ต้องการติดหนาม</li>
<li>ป้ายไฟ / ตัวอักษรป้าย</li>
<li>อาคารอนุรักษ์ที่ห้ามเจาะหรือดัดแปลง</li>
<li>พื้นที่ที่ต้องการความสวยงาม</li>
</ul>
<!-- /wp:list -->

<!-- wp:separator -->
<hr class="wp-block-separator has-alpha-channel-opacity"/>
<!-- /wp:separator -->

<!-- wp:heading {"textAlign":"center","level":2} -->
<h2 class="has-text-align-center">สนใจบริการ? ติดต่อเราเลย!</h2>
<!-- /wp:heading -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons">
<!-- wp:button {"backgroundColor":"vivid-cyan-blue","fontSize":"medium"} -->
<div class="wp-block-button has-custom-font-size has-medium-font-size"><a class="wp-block-button__link has-vivid-cyan-blue-background-color has-background" href="/contact">ขอใบเสนอราคาฟรี</a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->
')

  # Portfolio Page
  PORTFOLIO_ID=$(wp post create --post_type=page --post_title='ผลงานของเรา' --post_status=publish --post_name='portfolio' --path=/var/www/html --allow-root --porcelain --post_content='
<!-- wp:heading {"textAlign":"center","level":1} -->
<h1 class="has-text-align-center">ผลงานของเรา</h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center","fontSize":"medium"} -->
<p class="has-text-align-center has-medium-font-size">ตัวอย่างผลงานการติดตั้งจากลูกค้าจริง — ก่อนและหลังการติดตั้ง</p>
<!-- /wp:paragraph -->

<!-- wp:separator -->
<hr class="wp-block-separator has-alpha-channel-opacity"/>
<!-- /wp:separator -->

<!-- wp:heading {"level":2} -->
<h2>ตาข่าย HDPE กันนก</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>ผลงานติดตั้งตาข่ายกันนก HDPE สำหรับอาคารพาณิชย์และบ้านพักอาศัย</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"backgroundColor":"light-gray","textColor":"dark-gray"} -->
<p class="has-dark-gray-color has-light-gray-background-color has-text-color has-background"><em>📷 เพิ่มรูปภาพผลงานได้ผ่าน WordPress Admin → Media → Add New จากนั้นแก้ไขหน้านี้เพื่อเพิ่มรูปภาพ</em></p>
<!-- /wp:paragraph -->

<!-- wp:separator -->
<hr class="wp-block-separator has-alpha-channel-opacity"/>
<!-- /wp:separator -->

<!-- wp:heading {"level":2} -->
<h2>แผงกันนกโซลาร์เซลล์</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>ผลงานติดตั้งแผงกันนกสำหรับระบบโซลาร์เซลล์ ด้วยระบบคลิปไม่เจาะแผง</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"backgroundColor":"light-gray","textColor":"dark-gray"} -->
<p class="has-dark-gray-color has-light-gray-background-color has-text-color has-background"><em>📷 เพิ่มรูปภาพผลงานได้ผ่าน WordPress Admin → Media → Add New จากนั้นแก้ไขหน้านี้เพื่อเพิ่มรูปภาพ</em></p>
<!-- /wp:paragraph -->

<!-- wp:separator -->
<hr class="wp-block-separator has-alpha-channel-opacity"/>
<!-- /wp:separator -->

<!-- wp:heading {"level":2} -->
<h2>หนามกันนก</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>ผลงานติดตั้งหนามกันนกสแตนเลสสำหรับอาคารและบ้านพักอาศัย</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"backgroundColor":"light-gray","textColor":"dark-gray"} -->
<p class="has-dark-gray-color has-light-gray-background-color has-text-color has-background"><em>📷 เพิ่มรูปภาพผลงานได้ผ่าน WordPress Admin → Media → Add New จากนั้นแก้ไขหน้านี้เพื่อเพิ่มรูปภาพ</em></p>
<!-- /wp:paragraph -->

<!-- wp:separator -->
<hr class="wp-block-separator has-alpha-channel-opacity"/>
<!-- /wp:separator -->

<!-- wp:heading {"level":2} -->
<h2>เจลไล่นก</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>ผลงานการใช้เจลไล่นกสำหรับพื้นที่ที่ต้องการความสวยงาม</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"backgroundColor":"light-gray","textColor":"dark-gray"} -->
<p class="has-dark-gray-color has-light-gray-background-color has-text-color has-background"><em>📷 เพิ่มรูปภาพผลงานได้ผ่าน WordPress Admin → Media → Add New จากนั้นแก้ไขหน้านี้เพื่อเพิ่มรูปภาพ</em></p>
<!-- /wp:paragraph -->

<!-- wp:separator -->
<hr class="wp-block-separator has-alpha-channel-opacity"/>
<!-- /wp:separator -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons">
<!-- wp:button {"backgroundColor":"vivid-cyan-blue"} -->
<div class="wp-block-button"><a class="wp-block-button__link has-vivid-cyan-blue-background-color has-background" href="/contact">สนใจบริการ? ติดต่อเราเลย</a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->
')

  # About Us Page
  ABOUT_ID=$(wp post create --post_type=page --post_title='เกี่ยวกับเรา' --post_status=publish --post_name='about' --path=/var/www/html --allow-root --porcelain --post_content='
<!-- wp:heading {"textAlign":"center","level":1} -->
<h1 class="has-text-align-center">เกี่ยวกับเรา</h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center","fontSize":"medium"} -->
<p class="has-text-align-center has-medium-font-size">Bird Net Installation Services — ผู้เชี่ยวชาญด้านการป้องกันนกแบบครบวงจร</p>
<!-- /wp:paragraph -->

<!-- wp:separator -->
<hr class="wp-block-separator has-alpha-channel-opacity"/>
<!-- /wp:separator -->

<!-- wp:heading {"level":2} -->
<h2>เรื่องราวของเรา</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Bird Net Installation Services ก่อตั้งขึ้นจากความมุ่งมั่นในการแก้ปัญหานกรบกวนอย่างมืออาชีพและเป็นมิตรกับสิ่งแวดล้อม เราเชื่อว่าการป้องกันนกไม่จำเป็นต้องทำร้ายสัตว์ เราจึงเลือกใช้วิธีการที่ปลอดภัยและมีประสิทธิภาพ</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>ด้วยประสบการณ์หลายปีในวงการ เราได้ให้บริการลูกค้ามากมาย ตั้งแต่บ้านพักอาศัย คอนโดมิเนียม อาคารสำนักงาน โรงงาน ไปจนถึงวัดและอาคารอนุรักษ์ ทุกงานของเราผ่านการวางแผนอย่างรอบคอบและดำเนินการโดยทีมช่างมืออาชีพ</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2>วิสัยทัศน์</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>เป็นผู้นำด้านบริการป้องกันนกที่ปลอดภัย มีประสิทธิภาพ และเป็นมิตรกับสิ่งแวดล้อม ด้วยมาตรฐานสากล</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2>ค่านิยมหลัก</h2>
<!-- /wp:heading -->

<!-- wp:columns -->
<div class="wp-block-columns">

<!-- wp:column -->
<div class="wp-block-column">
<!-- wp:heading {"level":3} -->
<h3>🌿 เป็นมิตรกับสิ่งแวดล้อม</h3>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>ใช้วิธีการที่ไม่ทำร้ายนกและสัตว์ เลือกวัสดุที่ปลอดภัยและยั่งยืน</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column">
<!-- wp:heading {"level":3} -->
<h3>⭐ คุณภาพเป็นเลิศ</h3>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>ใช้วัสดุคุณภาพสูง ติดตั้งโดยทีมช่างที่ผ่านการอบรม รับประกันทุกงาน</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column">
<!-- wp:heading {"level":3} -->
<h3>🤝 ซื่อสัตย์โปร่งใส</h3>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>เสนอราคาตรงไปตรงมา ไม่มีค่าใช้จ่ายแอบแฝง ให้คำปรึกษาอย่างจริงใจ</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:column -->

</div>
<!-- /wp:columns -->

<!-- wp:heading {"level":2} -->
<h2>มาตรฐานความปลอดภัย</h2>
<!-- /wp:heading -->

<!-- wp:list -->
<ul>
<li>ทีมช่างผ่านการอบรมด้านความปลอดภัยในการทำงานบนที่สูง</li>
<li>ใช้อุปกรณ์ PPE (Personal Protective Equipment) ครบชุด</li>
<li>มีประกันอุบัติเหตุสำหรับพนักงานทุกคน</li>
<li>ปฏิบัติตามมาตรฐาน OSHA (Occupational Safety and Health)</li>
<li>ตรวจสอบพื้นที่ก่อนเริ่มงานทุกครั้ง</li>
</ul>
<!-- /wp:list -->

<!-- wp:heading {"level":2} -->
<h2>ทีมงานของเรา</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>ทีมงานของเราประกอบด้วยผู้เชี่ยวชาญที่มีประสบการณ์ ผ่านการอบรมทั้งด้านเทคนิคและความปลอดภัย พร้อมให้บริการด้วยความเป็นมืออาชีพและใส่ใจในทุกรายละเอียด</p>
<!-- /wp:paragraph -->

<!-- wp:separator -->
<hr class="wp-block-separator has-alpha-channel-opacity"/>
<!-- /wp:separator -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons">
<!-- wp:button {"backgroundColor":"vivid-cyan-blue"} -->
<div class="wp-block-button"><a class="wp-block-button__link has-vivid-cyan-blue-background-color has-background" href="/contact">ติดต่อเรา</a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->
')

  # Contact Us Page
  CONTACT_ID=$(wp post create --post_type=page --post_title='ติดต่อเรา' --post_status=publish --post_name='contact' --path=/var/www/html --allow-root --porcelain --post_content="
<!-- wp:heading {\"textAlign\":\"center\",\"level\":1} -->
<h1 class=\"has-text-align-center\">ติดต่อเรา</h1>
<!-- /wp:heading -->

<!-- wp:paragraph {\"align\":\"center\",\"fontSize\":\"medium\"} -->
<p class=\"has-text-align-center has-medium-font-size\">พร้อมให้คำปรึกษาและเสนอราคาฟรี ติดต่อเราได้ทุกช่องทาง</p>
<!-- /wp:paragraph -->

<!-- wp:separator -->
<hr class=\"wp-block-separator has-alpha-channel-opacity\"/>
<!-- /wp:separator -->

<!-- wp:columns -->
<div class=\"wp-block-columns\">

<!-- wp:column {\"width\":\"50%\"} -->
<div class=\"wp-block-column\" style=\"flex-basis:50%\">

<!-- wp:heading {\"level\":2} -->
<h2>ส่งข้อความถึงเรา</h2>
<!-- /wp:heading -->

${FORM_SHORTCODE}

</div>
<!-- /wp:column -->

<!-- wp:column {\"width\":\"50%\"} -->
<div class=\"wp-block-column\" style=\"flex-basis:50%\">

<!-- wp:heading {\"level\":2} -->
<h2>ข้อมูลติดต่อ</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>📞 <strong>โทรศัพท์:</strong> <a href=\"tel:0800000000\">080-000-0000</a></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>📱 <strong>Line:</strong> <a href=\"https://line.me/ti/p/~birdnet\" target=\"_blank\" rel=\"noreferrer noopener\">@birdnet</a></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>📧 <strong>อีเมล:</strong> <a href=\"mailto:info@birdnet.co.th\">info@birdnet.co.th</a></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>🕐 <strong>เวลาทำการ:</strong> จันทร์ - เสาร์ 08:00 - 18:00 น.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {\"level\":3} -->
<h3>ติดต่อด่วน</h3>
<!-- /wp:heading -->

<!-- wp:buttons -->
<div class=\"wp-block-buttons\">
<!-- wp:button {\"backgroundColor\":\"vivid-green-cyan\"} -->
<div class=\"wp-block-button\"><a class=\"wp-block-button__link has-vivid-green-cyan-background-color has-background\" href=\"tel:0800000000\">📞 โทรเลย</a></div>
<!-- /wp:button -->
<!-- wp:button {\"backgroundColor\":\"vivid-green-cyan\"} -->
<div class=\"wp-block-button\"><a class=\"wp-block-button__link has-vivid-green-cyan-background-color has-background\" href=\"https://line.me/ti/p/~birdnet\" target=\"_blank\" rel=\"noreferrer noopener\">💬 แชท Line</a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->

<!-- wp:heading {\"level\":3} -->
<h3>แผนที่</h3>
<!-- /wp:heading -->

<!-- wp:html -->
<iframe src=\"https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d496115.33099588484!2d100.35202039453124!3d13.724674500000001!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x311d6032280d61f3%3A0x10100b25de24820!2sBangkok%2C%20Thailand!5e0!3m2!1sen!2sus!4v1234567890\" width=\"100%\" height=\"300\" style=\"border:0;border-radius:8px;\" allowfullscreen=\"\" loading=\"lazy\" referrerpolicy=\"no-referrer-when-downgrade\"></iframe>
<!-- /wp:html -->

</div>
<!-- /wp:column -->

</div>
<!-- /wp:columns -->
")

  echo "=== Setting Home Page ==="
  wp option update page_on_front $HOME_ID --path=/var/www/html --allow-root

  echo "=== Creating Navigation Menu ==="
  wp menu create "Main Menu" --path=/var/www/html --allow-root

  wp menu item add-post "Main Menu" $HOME_ID --title="หน้าแรก" --path=/var/www/html --allow-root
  wp menu item add-post "Main Menu" $SERVICES_ID --title="บริการของเรา" --path=/var/www/html --allow-root
  wp menu item add-post "Main Menu" $PORTFOLIO_ID --title="ผลงานของเรา" --path=/var/www/html --allow-root
  wp menu item add-post "Main Menu" $ABOUT_ID --title="เกี่ยวกับเรา" --path=/var/www/html --allow-root
  wp menu item add-post "Main Menu" $CONTACT_ID --title="ติดต่อเรา" --path=/var/www/html --allow-root

  wp menu location assign "Main Menu" primary --path=/var/www/html --allow-root
  wp menu location assign "Main Menu" main --path=/var/www/html --allow-root 2>/dev/null || true

  echo "=== Configuring Astra Theme ==="
  wp option update blogdescription "บริการติดตั้งตาข่ายกันนก หนามกันนก เจลไล่นก แผงกันนกโซลาร์เซลล์ มืออาชีพ ครบวงจร" --path=/var/www/html --allow-root

  echo "=== Setup Complete ==="
  echo "Site URL: ${PROTOCOL}://${SITE_URL}"
  echo "Admin URL: ${PROTOCOL}://${SITE_URL}/wp-admin"
  echo "Username: ${WP_ADMIN_USER:-admin}"
else
  echo "=== WordPress already installed, checking URL ==="
  SITE_URL="${RAILWAY_PUBLIC_DOMAIN:-localhost:8080}"
  if [ "$SITE_URL" != "localhost:8080" ]; then
    wp option update siteurl "https://${SITE_URL}" --path=/var/www/html --allow-root 2>/dev/null || true
    wp option update home "https://${SITE_URL}" --path=/var/www/html --allow-root 2>/dev/null || true
  fi
fi

# Ensure proper permissions
chown -R www-data:www-data /var/www/html/wp-content 2>/dev/null || true

echo "=== WordPress is ready ==="
wait $WP_PID
