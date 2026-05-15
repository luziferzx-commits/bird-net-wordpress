#!/bin/bash

# Create healthcheck endpoint immediately (before anything else)
mkdir -p /var/www/html
cat > /var/www/html/healthcheck.php << 'HEALTHCHECK'
<?php
http_response_code(200);
echo "OK";
HEALTHCHECK

# Fix MPM conflict - disable mpm_event if mpm_prefork is loaded (WordPress default)
if [ -f /etc/apache2/mods-enabled/mpm_event.load ]; then
  a2dismod mpm_event 2>/dev/null || true
  a2enmod mpm_prefork 2>/dev/null || true
fi

# Configure Apache to ALSO listen on $PORT (Railway healthcheck uses $PORT,
# but edge proxy routes to port 80 based on Dockerfile EXPOSE)
if [ -n "$PORT" ] && [ "$PORT" != "80" ]; then
  echo "Configuring Apache to also listen on port $PORT (healthcheck port)"
  echo "Listen $PORT" >> /etc/apache2/ports.conf
fi

# Run WordPress setup in background (after Apache starts)
(
sleep 3
echo "=== Waiting for WordPress files to be ready ==="
until [ -f /var/www/html/wp-includes/version.php ]; do
  sleep 2
done

echo "=== Waiting for database connection ==="
DB_HOST="${WORDPRESS_DB_HOST:-db}"
DB_USER="${WORDPRESS_DB_USER:-wordpress}"
DB_PASS="${WORDPRESS_DB_PASSWORD:-wordpress}"
DB_NAME="${WORDPRESS_DB_NAME:-wordpress}"
until php -r "new mysqli('$DB_HOST', '$DB_USER', '$DB_PASS', '$DB_NAME');" 2>/dev/null; do
  echo "Waiting for database..."
  sleep 3
done
echo "Database connected!"

# Enable WP_DEBUG for troubleshooting (display errors on blank pages)
echo "=== Enabling WP_DEBUG ==="
if [ -f /var/www/html/wp-config.php ]; then
  sed -i "s/define( *'WP_DEBUG'.*/define('WP_DEBUG', true);/" /var/www/html/wp-config.php 2>/dev/null || true
  # Add WP_DEBUG_DISPLAY if not present
  if ! grep -q 'WP_DEBUG_DISPLAY' /var/www/html/wp-config.php; then
    sed -i "/define('WP_DEBUG'/a define('WP_DEBUG_DISPLAY', true);" /var/www/html/wp-config.php 2>/dev/null || true
    sed -i "/define('WP_DEBUG'/a define('WP_DEBUG_LOG', true);" /var/www/html/wp-config.php 2>/dev/null || true
  fi
fi

# Always copy child theme (container is ephemeral, but DB remembers theme choice)
if [ -d /tmp/birdnet-child ]; then
  echo "=== Copying child theme ==="
  cp -r /tmp/birdnet-child /var/www/html/wp-content/themes/birdnet-child
  chown -R www-data:www-data /var/www/html/wp-content/themes/birdnet-child
fi

# Clear WP Super Cache on every deploy to ensure fresh CSS/content
echo "=== Clearing WP Super Cache ==="
rm -rf /var/www/html/wp-content/cache/supercache/* 2>/dev/null || true
rm -rf /var/www/html/wp-content/cache/wp-cache-* 2>/dev/null || true
wp cache flush --path=/var/www/html --allow-root 2>/dev/null || true

# Always install Astra parent theme if missing
if [ ! -d /var/www/html/wp-content/themes/astra ]; then
  echo "=== Installing Astra parent theme ==="
  wp theme install astra --path=/var/www/html --allow-root 2>/dev/null || true
fi

# Always copy media assets (container is ephemeral, uploads dir is lost on redeploy)
ASSETS_DIR="/var/www/html/wp-content/uploads/birdnet-assets"
mkdir -p "$ASSETS_DIR"
if [ -d /tmp/birdnet-assets/images ]; then
  echo "=== Copying image assets ==="
  cp -f /tmp/birdnet-assets/images/*.jpg "$ASSETS_DIR/" 2>/dev/null || true
  cp -f /tmp/birdnet-assets/images/*.jpeg "$ASSETS_DIR/" 2>/dev/null || true
  cp -f /tmp/birdnet-assets/images/*.webp "$ASSETS_DIR/" 2>/dev/null || true
  cp -f /tmp/birdnet-assets/images/*.ico "$ASSETS_DIR/" 2>/dev/null || true
  cp -f /tmp/birdnet-assets/images/*.png "$ASSETS_DIR/" 2>/dev/null || true
fi
if [ -d /tmp/birdnet-assets/videos ]; then
  echo "=== Copying video assets ==="
  cp -f /tmp/birdnet-assets/videos/*.mp4 "$ASSETS_DIR/" 2>/dev/null || true
fi
chown -R www-data:www-data "$ASSETS_DIR"
echo "Assets copied to $ASSETS_DIR"

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
    --title="Birds Go Away — ตาข่ายกันนก" \
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
  wp plugin install elementor --version=3.25.10 --activate --path=/var/www/html --allow-root 2>/dev/null || \
    wp plugin install elementor --activate --path=/var/www/html --allow-root 2>/dev/null || \
    echo "Warning: Elementor installation skipped (compatibility issue)"
  wp plugin install contact-form-7 --activate --path=/var/www/html --allow-root || true
  wp plugin install wordpress-seo --version=24.9 --activate --path=/var/www/html --allow-root 2>/dev/null || \
    echo "Warning: Yoast SEO installation skipped (compatibility issue)"
  wp plugin install wordfence --activate --path=/var/www/html --allow-root || true
  wp plugin install wp-super-cache --activate --path=/var/www/html --allow-root || true
  wp plugin install addon-starter-templates --activate --path=/var/www/html --allow-root 2>/dev/null || true

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

  echo "=== Importing Media Files ==="
  SITE_URL_FOR_MEDIA="${PROTOCOL}://${SITE_URL}"

  # Import all images and store their URLs
  declare -A IMG_URLS
  if [ -d /tmp/birdnet-assets/images ]; then
    for img in /tmp/birdnet-assets/images/birdnet-*.jpg; do
      if [ -f "$img" ] && [ $(wc -c < "$img") -gt 1000 ]; then
        BASENAME=$(basename "$img" .jpg)
        ATTACHMENT_ID=$(wp media import "$img" --title="Birds Go Away - ${BASENAME}" --path=/var/www/html --allow-root --porcelain 2>/dev/null || echo "")
        if [ -n "$ATTACHMENT_ID" ]; then
          IMG_URL=$(wp post get "$ATTACHMENT_ID" --field=guid --path=/var/www/html --allow-root 2>/dev/null || echo "")
          if [ -n "$IMG_URL" ]; then
            IMG_URLS["$BASENAME"]="$IMG_URL"
          fi
        fi
      fi
    done
    echo "Imported ${#IMG_URLS[@]} images"
  fi

  # Import videos
  declare -A VID_URLS
  if [ -d /tmp/birdnet-assets/videos ]; then
    for vid in /tmp/birdnet-assets/videos/reel-*.mp4; do
      if [ -f "$vid" ]; then
        BASENAME=$(basename "$vid" .mp4)
        ATTACHMENT_ID=$(wp media import "$vid" --title="Birds Go Away - ${BASENAME}" --path=/var/www/html --allow-root --porcelain 2>/dev/null || echo "")
        if [ -n "$ATTACHMENT_ID" ]; then
          VID_URL=$(wp post get "$ATTACHMENT_ID" --field=guid --path=/var/www/html --allow-root 2>/dev/null || echo "")
          if [ -n "$VID_URL" ]; then
            VID_URLS["$BASENAME"]="$VID_URL"
          fi
        fi
      fi
    done
    echo "Imported ${#VID_URLS[@]} videos"
  fi

  # Helper: get image URL with fallback
  get_img() {
    local key="$1"
    echo "${IMG_URLS[$key]:-${SITE_URL_FOR_MEDIA}/wp-content/uploads/birdnet-assets/${key}.jpg}"
  }
  get_vid() {
    local key="$1"
    echo "${VID_URLS[$key]:-${SITE_URL_FOR_MEDIA}/wp-content/uploads/birdnet-assets/${key}.mp4}"
  }

  echo "=== Creating Pages ==="

  # Home Page
  HOME_ID=$(wp post create --post_type=page --post_title='หน้าแรก' --post_status=publish --post_name='home' --path=/var/www/html --allow-root --porcelain --post_content='
<!-- wp:columns -->
<div class="wp-block-columns hero-section">

<!-- wp:column -->
<div class="wp-block-column" style="background:transparent !important;border:none !important;">

<!-- wp:heading {"level":1} -->
<h1>BIRDS GO AWAY<br>บริการติดตั้งตาข่ายกันนก มืออาชีพ</h1>
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

</div>
<!-- /wp:columns -->

<!-- wp:separator {"className":"is-style-wide"} -->
<hr class="wp-block-separator has-alpha-channel-opacity is-style-wide"/>
<!-- /wp:separator -->

<!-- wp:heading {"textAlign":"center","level":2} -->
<h2 class="has-text-align-center">ปัญหานกพิราบ ที่คุณกำลังเผชิญ</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">นกพิราบสร้างปัญหามากมายให้กับบ้าน คอนโด และอาคารของคุณ ไม่ว่าจะเป็น...</p>
<!-- /wp:paragraph -->

<!-- wp:columns -->
<div class="wp-block-columns">
<!-- wp:column -->
<div class="wp-block-column" style="text-align:center;">
<h3>มูลนกสกปรก</h3>
<p>มูลนกพิราบสร้างความสกปรกบนพื้นระเบียง ราวตากผ้า เครื่องปรับอากาศ ทำให้เกิดคราบสกปรกและกลิ่นเหม็น</p>
</div>
<!-- /wp:column -->
<!-- wp:column -->
<div class="wp-block-column" style="text-align:center;">
<h3>เชื้อโรคและไรนก</h3>
<p>มูลนกเป็นแหล่งสะสมเชื้อโรค แบคทีเรีย เชื้อรา และไรนก ซึ่งเป็นอันตรายต่อสุขภาพของคนในครอบครัว</p>
</div>
<!-- /wp:column -->
<!-- wp:column -->
<div class="wp-block-column" style="text-align:center;">
<h3>เสียงรบกวน</h3>
<p>นกพิราบส่งเสียงร้องรบกวนตั้งแต่เช้ามืด ทำให้นอนไม่หลับ สร้างความรำคาญต่อผู้อยู่อาศัย</p>
</div>
<!-- /wp:column -->
<!-- wp:column -->
<div class="wp-block-column" style="text-align:center;">
<h3>ความเสียหายต่ออาคาร</h3>
<p>กรดในมูลนกกัดกร่อนสี ปูน โลหะ ทำให้อาคารเสื่อมสภาพเร็ว เสียค่าซ่อมแซมสูง</p>
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->

<!-- wp:separator {"className":"is-style-wide"} -->
<hr class="wp-block-separator has-alpha-channel-opacity is-style-wide"/>
<!-- /wp:separator -->

<!-- wp:heading {"textAlign":"center","level":2} -->
<h2 class="has-text-align-center">ทำไมต้องเลือก BIRDS GO AWAY?</h2>
<!-- /wp:heading -->

<!-- wp:columns -->
<div class="wp-block-columns">
<!-- wp:column {"width":"25%"} -->
<div class="wp-block-column" style="flex-basis:25%;text-align:center;">
<h3>ผ่านการอบรมโรยตัว</h3>
<p>ทีมช่างผ่านการอบรมโรยตัวภาคทฤษฎี-ปฏิบัติ มีใบ Certificate รับรองมาตรฐาน ทำงานบนที่สูงอย่างปลอดภัย</p>
</div>
<!-- /wp:column -->
<!-- wp:column {"width":"25%"} -->
<div class="wp-block-column" style="flex-basis:25%;text-align:center;">
<h3>วิศวกรคุมงาน</h3>
<p>ทุกโปรเจคมีวิศวกรโยธาคุมงาน วางแผนอย่างรอบคอบ ปรึกษาฟรีโดยวิศวกรในพื้นที่</p>
</div>
<!-- /wp:column -->
<!-- wp:column {"width":"25%"} -->
<div class="wp-block-column" style="flex-basis:25%;text-align:center;">
<h3>รับประกัน 3 ปี</h3>
<p>รับประกันงานติดตั้งนานถึง 3 ปี ตาข่าย HDPE คุณภาพสูง อายุการใช้งาน 5-7 ปี แข็งแรงทนทาน</p>
</div>
<!-- /wp:column -->
<!-- wp:column {"width":"25%"} -->
<div class="wp-block-column" style="flex-basis:25%;text-align:center;">
<h3>เสนอราคาฟรี</h3>
<p>เสนอราคาฟรี ไม่มีค่าใช้จ่ายแอบแฝง ราคายุติธรรม คุ้มค่าทุกบาท พร้อมบริการหลังการขาย</p>
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->

<!-- wp:separator {"className":"is-style-wide"} -->
<hr class="wp-block-separator has-alpha-channel-opacity is-style-wide"/>
<!-- /wp:separator -->

<!-- wp:heading {"textAlign":"center","level":2} -->
<h2 class="has-text-align-center">บริการของเรา</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">เราให้บริการป้องกันนกครบวงจร ด้วยทีมช่างมืออาชีพและวัสดุคุณภาพสูง</p>
<!-- /wp:paragraph -->

<!-- wp:columns -->
<div class="wp-block-columns">
<!-- wp:column -->
<div class="wp-block-column" style="text-align:center;">
<h3>ตาข่าย HDPE กันนก</h3>
<p>ตาข่ายไนล่อน HDPE คุณภาพสูง แข็งแรง ทนทานต่อแรงดึง แรงกระแทก และสารเคมี อายุการใช้งานประมาณ 5-7 ปี ติดตั้งแบบไร้รอยต่อ มองแล้วสบายตา กลมกลืนกับอาคาร</p>
<p><a href="/services">รายละเอียดเพิ่มเติม →</a></p>
</div>
<!-- /wp:column -->
<!-- wp:column -->
<div class="wp-block-column" style="text-align:center;">
<h3>หนามกันนก</h3>
<p>เหล็กแหลมกันนก Bird Spikes ฐานสแตนเลส รุ่นพิเศษ 3 แถวแบบหนามไขว้ ป้องกันนกพิราบเกาะ ติดตั้งขอบบัว หน้าต่าง ดาดฟ้า ป้องกันขับถ่ายมูลลงพื้นด้านล่าง</p>
<p><a href="/services">รายละเอียดเพิ่มเติม →</a></p>
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->

<!-- wp:columns -->
<div class="wp-block-columns">
<!-- wp:column -->
<div class="wp-block-column" style="text-align:center;">
<h3>แผงกันนกโซลาร์เซลล์</h3>
<p>ระบบป้องกันนกสำหรับแผงโซลาร์เซลล์โดยเฉพาะ ใช้ระบบคลิปยึดพิเศษ ไม่ต้องเจาะแผง ไม่เสียการรับประกัน ป้องกันนกทำรังใต้แผง ยืดอายุการใช้งาน</p>
<p><a href="/services">รายละเอียดเพิ่มเติม →</a></p>
</div>
<!-- /wp:column -->
<!-- wp:column -->
<div class="wp-block-column" style="text-align:center;">
<h3>เจลไล่นก</h3>
<p>เจลไล่นกสูตรพิเศษ ไม่มีสารพิษ ปลอดภัยต่อคนและสัตว์ ใช้ได้กับทุกพื้นผิว เหมาะสำหรับพื้นที่ที่ต้องการความสวยงาม ไม่ทิ้งรอย มองไม่เห็น</p>
<p><a href="/services">รายละเอียดเพิ่มเติม →</a></p>
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->

<!-- wp:separator {"className":"is-style-wide"} -->
<hr class="wp-block-separator has-alpha-channel-opacity is-style-wide"/>
<!-- /wp:separator -->

<!-- wp:heading {"textAlign":"center","level":2} -->
<h2 class="has-text-align-center">ขั้นตอนการทำงาน</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">เราดำเนินงานอย่างเป็นระบบ ตั้งแต่ต้นจนจบ</p>
<!-- /wp:paragraph -->

<!-- wp:columns -->
<div class="wp-block-columns">
<!-- wp:column {"width":"25%"} -->
<div class="wp-block-column" style="flex-basis:25%;text-align:center;">
<h3>1. สำรวจหน้างาน</h3>
<p>วิศวกรโยธาเข้าสำรวจพื้นที่จริง ประเมินสภาพปัญหา วัดพื้นที่ วิเคราะห์ลักษณะนิสัยของนก เพื่อเลือกวิธีที่เหมาะสมที่สุด</p>
</div>
<!-- /wp:column -->
<!-- wp:column {"width":"25%"} -->
<div class="wp-block-column" style="flex-basis:25%;text-align:center;">
<h3>2. เสนอราคา</h3>
<p>จัดทำใบเสนอราคาอย่างละเอียด ระบุวัสดุ ราคา ระยะเวลาชัดเจน ไม่มีค่าใช้จ่ายแอบแฝง ปรึกษาฟรีไม่มีค่าใช้จ่าย</p>
</div>
<!-- /wp:column -->
<!-- wp:column {"width":"25%"} -->
<div class="wp-block-column" style="flex-basis:25%;text-align:center;">
<h3>3. ดำเนินการติดตั้ง</h3>
<p>ทีมช่างมืออาชีพติดตั้งด้วยอุปกรณ์ครบครัน ทำงานรวดเร็ว เรียบร้อย สะอาด พร้อมล้างทำความสะอาดพื้นที่ให้ฟรี</p>
</div>
<!-- /wp:column -->
<!-- wp:column {"width":"25%"} -->
<div class="wp-block-column" style="flex-basis:25%;text-align:center;">
<h3>4. ส่งมอบงาน</h3>
<p>ตรวจสอบคุณภาพงาน ส่งมอบพร้อมใบรับประกัน 3 ปี มีบริการหลังการขาย ซ่อมแซมฟรีตามเงื่อนไข</p>
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->

<!-- wp:separator {"className":"is-style-wide"} -->
<hr class="wp-block-separator has-alpha-channel-opacity is-style-wide"/>
<!-- /wp:separator -->

<!-- wp:heading {"textAlign":"center","level":2} -->
<h2 class="has-text-align-center">ตัวอย่างผลงานล่าสุด</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">ขอบพระคุณลูกค้าทุกท่านที่ไว้ใจ Birds Go Away ใช้บริการ</p>
<!-- /wp:paragraph -->

<!-- wp:gallery {"columns":3,"linkTo":"none"} -->
<figure class="wp-block-gallery has-nested-images columns-3 is-cropped">
<!-- wp:image -->
<figure class="wp-block-image"><img src="PLACEHOLDER_IMG_12" alt="ผลงานติดตั้งตาข่ายกันนก Birds Go Away"/></figure>
<!-- /wp:image -->
<!-- wp:image -->
<figure class="wp-block-image"><img src="PLACEHOLDER_IMG_13" alt="ผลงานติดตั้งตาข่ายกันนก คอนโด"/></figure>
<!-- /wp:image -->
<!-- wp:image -->
<figure class="wp-block-image"><img src="PLACEHOLDER_IMG_14" alt="ผลงานติดตั้งตาข่ายกันนก ระเบียง"/></figure>
<!-- /wp:image -->
<!-- wp:image -->
<figure class="wp-block-image"><img src="PLACEHOLDER_IMG_15" alt="ผลงานติดตั้งตาข่ายกันนก อาคาร"/></figure>
<!-- /wp:image -->
<!-- wp:image -->
<figure class="wp-block-image"><img src="PLACEHOLDER_IMG_16" alt="ผลงานติดตั้งตาข่ายกันนก บ้าน"/></figure>
<!-- /wp:image -->
<!-- wp:image -->
<figure class="wp-block-image"><img src="PLACEHOLDER_IMG_17" alt="ผลงานติดตั้งตาข่ายกันนก สำนักงาน"/></figure>
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
<h2 class="has-text-align-center">วิดีโอผลงานการติดตั้ง</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">ดูคลิปขั้นตอนการทำงานจริงจากหน้างาน</p>
<!-- /wp:paragraph -->

<!-- wp:columns -->
<div class="wp-block-columns">
<!-- wp:column -->
<div class="wp-block-column">
<!-- wp:video -->
<figure class="wp-block-video"><video controls src="PLACEHOLDER_VID_01"></video><figcaption>ตัวอย่างการติดตั้งตาข่ายกันนก #1</figcaption></figure>
<!-- /wp:video -->
</div>
<!-- /wp:column -->
<!-- wp:column -->
<div class="wp-block-column">
<!-- wp:video -->
<figure class="wp-block-video"><video controls src="PLACEHOLDER_VID_02"></video><figcaption>ตัวอย่างการติดตั้งตาข่ายกันนก #2</figcaption></figure>
<!-- /wp:video -->
</div>
<!-- /wp:column -->
<!-- wp:column -->
<div class="wp-block-column">
<!-- wp:video -->
<figure class="wp-block-video"><video controls src="PLACEHOLDER_VID_03"></video><figcaption>ตัวอย่างการติดตั้งตาข่ายกันนก #3</figcaption></figure>
<!-- /wp:video -->
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->

<!-- wp:separator {"className":"is-style-wide"} -->
<hr class="wp-block-separator has-alpha-channel-opacity is-style-wide"/>
<!-- /wp:separator -->

<!-- wp:heading {"textAlign":"center","level":2} -->
<h2 class="has-text-align-center">คำถามที่พบบ่อย (FAQ)</h2>
<!-- /wp:heading -->

<!-- wp:html -->
<div class="faq-item">
<h4>ตาข่ายกันนก HDPE มีอายุการใช้งานกี่ปี?</h4>
<p>ตาข่าย HDPE ของเรามีอายุการใช้งานประมาณ 5-7 ปี ขึ้นอยู่กับสภาพแวดล้อม เช่น แสงแดด ลม ฝน ตาข่ายทนต่อรังสี UV แข็งแรง ทนทานต่อแรงดึง แรงกระแทก และสารเคมี</p>
</div>
<div class="faq-item">
<h4>ตาข่ายกันนก ติดตั้งแล้วมองเห็นจากภายนอกไหม?</h4>
<p>ตาข่ายกันนกที่เราใช้เป็นสีดำ เส้นเล็ก ทึบแสง ไม่สะท้อนแสง เมื่อติดตั้งเสร็จแล้วจะกลมกลืนกับอาคาร มองจากภายนอกแทบมองไม่เห็นตาข่าย ให้ความรู้สึกสบายตา</p>
</div>
<div class="faq-item">
<h4>ติดตั้งตาข่ายกันนกใช้เวลานานแค่ไหน?</h4>
<p>ขึ้นอยู่กับขนาดพื้นที่ โดยทั่วไปสำหรับระเบียงคอนโดใช้เวลาประมาณ 1-2 ชั่วโมง สำหรับพื้นที่ขนาดใหญ่ เช่น โรงงาน หรือตึกสูง อาจใช้เวลา 1-3 วัน</p>
</div>
<div class="faq-item">
<h4>มีบริการล้างทำความสะอาดหลังติดตั้งไหม?</h4>
<p>มีครับ! หลังติดตั้งเสร็จ ทีมงาน Birds Go Away จะเก็บรังนก มูลนก ล้างทำความสะอาดให้ลูกค้าฟรี ช่วยประหยัดค่าใช้จ่ายค่าทำความสะอาด</p>
</div>
<div class="faq-item">
<h4>รับประกันงานติดตั้งกี่ปี?</h4>
<p>เรารับประกันงานติดตั้งนานถึง 3 ปี หากตาข่ายหลุด หลวม หรือเสียหายจากการติดตั้ง เราจะเข้าซ่อมแซมให้ฟรีตามเงื่อนไข</p>
</div>
<div class="faq-item">
<h4>ติดตั้งในคอนโดสูงได้ไหม?</h4>
<p>ได้ครับ! ทีมช่างของเราผ่านการอบรมโรยตัวภาคทฤษฎี-ปฏิบัติ มีใบ Certificate รับรองมาตรฐาน พร้อมอุปกรณ์ PPE ครบครัน ติดตั้งได้ทุกชั้น ทุกความสูง</p>
</div>
<!-- /wp:html -->

<!-- wp:separator {"className":"is-style-wide"} -->
<hr class="wp-block-separator has-alpha-channel-opacity is-style-wide"/>
<!-- /wp:separator -->

<!-- wp:heading {"textAlign":"center","level":2} -->
<h2 class="has-text-align-center">พื้นที่ให้บริการ</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">พร้อมให้บริการใน 3 จังหวัดหลัก และพื้นที่ใกล้เคียง</p>
<!-- /wp:paragraph -->

<!-- wp:columns -->
<div class="wp-block-columns">
<!-- wp:column {"width":"33.33%"} -->
<div class="wp-block-column" style="flex-basis:33.33%;text-align:center;">
<h3>ขอนแก่น</h3>
<p style="font-size:1.3rem;font-weight:700;color:#f97316;"><a href="tel:0629964994" style="color:#f97316;">062-996-4994</a></p>
<p>สำนักงานใหญ่<br>88/38 ขอนแก่น</p>
</div>
<!-- /wp:column -->
<!-- wp:column {"width":"33.33%"} -->
<div class="wp-block-column" style="flex-basis:33.33%;text-align:center;">
<h3>เชียงใหม่</h3>
<p style="font-size:1.3rem;font-weight:700;color:#f97316;"><a href="tel:0936415623" style="color:#f97316;">093-641-5623</a></p>
<p>สาขาเชียงใหม่<br>พร้อมให้บริการทั่วภาคเหนือ</p>
</div>
<!-- /wp:column -->
<!-- wp:column {"width":"33.33%"} -->
<div class="wp-block-column" style="flex-basis:33.33%;text-align:center;">
<h3>ชลบุรี</h3>
<p style="font-size:1.3rem;font-weight:700;color:#f97316;"><a href="tel:0956292488" style="color:#f97316;">095-629-2488</a></p>
<p>สาขาชลบุรี<br>พร้อมให้บริการภาคตะวันออก</p>
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->

<!-- wp:html -->
<div class="guarantee-badge">รับประกันงานติดตั้งนานถึง 3 ปี | ปรึกษาฟรี ไม่มีค่าใช้จ่าย</div>
<!-- /wp:html -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons">
<!-- wp:button {"backgroundColor":"vivid-green-cyan","fontSize":"medium"} -->
<div class="wp-block-button has-custom-font-size has-medium-font-size"><a class="wp-block-button__link has-vivid-green-cyan-background-color has-background" href="/contact">ติดต่อเราเลย — ขอใบเสนอราคาฟรี</a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->
')

  # Services Page
  SERVICES_ID=$(wp post create --post_type=page --post_title='บริการของเรา' --post_status=publish --post_name='services' --path=/var/www/html --allow-root --porcelain --post_content='
<!-- wp:columns -->
<div class="wp-block-columns hero-section">
<!-- wp:column -->
<div class="wp-block-column" style="background:transparent !important;border:none !important;">
<!-- wp:heading {"level":1} -->
<h1>บริการป้องกันนกครบวงจร</h1>
<!-- /wp:heading -->
<!-- wp:paragraph {"fontSize":"medium"} -->
<p class="has-medium-font-size">เราให้บริการป้องกันนก 4 รูปแบบ เลือกได้ตามสภาพพื้นที่ ด้วยทีมช่างมืออาชีพผ่านการอบรมโรยตัว มีใบ Certificate วิศวกรคุมงานทุกโปรเจค</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->

<!-- wp:separator {"className":"is-style-wide"} -->
<hr class="wp-block-separator has-alpha-channel-opacity is-style-wide"/>
<!-- /wp:separator -->

<!-- wp:heading {"level":2} -->
<h2>1. ตาข่าย HDPE กันนก (HDPE Bird Netting)</h2>
<!-- /wp:heading -->

<!-- wp:columns -->
<div class="wp-block-columns">
<!-- wp:column {"width":"60%"} -->
<div class="wp-block-column" style="flex-basis:60%">
<!-- wp:paragraph -->
<p>ตาข่ายไนล่อน HDPE (High-Density Polyethylene) เป็นวิธีป้องกันนกที่มีประสิทธิภาพสูงสุดและเป็นที่นิยมมากที่สุด เหมาะสำหรับพื้นที่ขนาดใหญ่ ทั้งระเบียงคอนโด บ้านพัก อาคารพาณิชย์ โรงงาน และโกดัง</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3>คุณสมบัติเด่น:</h3>
<!-- /wp:heading -->

<!-- wp:list -->
<ul>
<li><strong>ทนทานสูง</strong> — วัสดุ HDPE ทนแดด ทนฝน ไม่ผุกร่อน อายุการใช้งาน 5-7 ปี (ตาข่ายคุณภาพสูง ใช้ได้ถึง 10 ปี)</li>
<li><strong>ไม่ทำร้ายนก</strong> — ออกแบบมาเพื่อป้องกัน ไม่ใช่ทำอันตราย ไม่มีเงี่ยงหรือส่วนแหลมคม</li>
<li><strong>มองไม่ชัดจากระยะไกล</strong> — ตาข่ายสีดำ เส้นเล็ก ทึบแสง ไม่สะท้อนแสง กลมกลืนกับอาคาร</li>
<li><strong>หลากหลายขนาดช่องตาข่าย</strong> — เลือกได้ตามชนิดของนก ช่องเล็ก 2 ซม. สำหรับนกกระจอก ช่องใหญ่ 5 ซม. สำหรับนกพิราบ</li>
<li><strong>กันแมลง กันใบไม้</strong> — ป้องกันแมลงวัน แมลงสาบ รวมถึงใบไม้ร่วง</li>
<li><strong>ทนแรงลม</strong> — ตาข่ายมีความยืดหยุ่น ไม่ขาดง่ายจากแรงลม</li>
</ul>
<!-- /wp:list -->

<!-- wp:heading {"level":3} -->
<h3>เหมาะสำหรับ:</h3>
<!-- /wp:heading -->

<!-- wp:list -->
<ul>
<li>ระเบียงคอนโดมิเนียม / อพาร์ทเมนต์</li>
<li>อาคารสำนักงาน / อาคารพาณิชย์</li>
<li>โรงงาน / โกดังสินค้า / คลังสินค้า</li>
<li>บ้านพักอาศัย / ทาวน์เฮาส์ / บ้านเดี่ยว</li>
<li>วัด / โบสถ์ / อาคารอนุรักษ์</li>
<li>ศูนย์การค้า / ร้านอาหาร / โรงแรม</li>
<li>โรงพยาบาล / คลินิก</li>
<li>สนามบิน / สถานีรถไฟ</li>
</ul>
<!-- /wp:list -->
</div>
<!-- /wp:column -->
<!-- wp:column {"width":"40%"} -->
<div class="wp-block-column" style="flex-basis:40%">
<!-- wp:image -->
<figure class="wp-block-image"><img src="PLACEHOLDER_IMG_1" alt="ตาข่าย HDPE กันนก - ผลงาน Birds Go Away"/></figure>
<!-- /wp:image -->
<!-- wp:image -->
<figure class="wp-block-image"><img src="PLACEHOLDER_IMG_3" alt="ตาข่ายกันนก ติดตั้งระเบียงคอนโด"/></figure>
<!-- /wp:image -->
<!-- wp:image -->
<figure class="wp-block-image"><img src="PLACEHOLDER_IMG_5" alt="ตาข่ายกันนก HDPE คุณภาพสูง"/></figure>
<!-- /wp:image -->
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->

<!-- wp:separator {"className":"is-style-wide"} -->
<hr class="wp-block-separator has-alpha-channel-opacity is-style-wide"/>
<!-- /wp:separator -->

<!-- wp:heading {"level":2} -->
<h2>2. หนามกันนก (Bird Spikes)</h2>
<!-- /wp:heading -->

<!-- wp:columns -->
<div class="wp-block-columns">
<!-- wp:column {"width":"60%"} -->
<div class="wp-block-column" style="flex-basis:60%">
<!-- wp:paragraph -->
<p>เหล็กแหลมกันนก Bird Spikes ฐานสแตนเลส รุ่นพิเศษ 3 แถวแบบหนามไขว้ ป้องกันนกพิราบเกาะ ไม่ว่าจะบริเวณขอบบัว หน้าต่าง ดาดฟ้า ท่อแอร์ ราวระเบียง ป้องกันการขับถ่ายมูลลงพื้นด้านล่าง ติดตั้งง่าย ราคาประหยัด</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3>คุณสมบัติเด่น:</h3>
<!-- /wp:heading -->

<!-- wp:list -->
<ul>
<li><strong>วัสดุสแตนเลส 304</strong> — ทนสนิม ทนทานทุกสภาพอากาศ อายุการใช้งาน 10+ ปี</li>
<li><strong>ฐาน UV Polycarbonate</strong> — ไม่เปราะแตกจากแสงแดด</li>
<li><strong>รุ่น 3 แถว หนามไขว้</strong> — ป้องกันนกได้ทุกทิศทาง ไม่ว่านกจะลงจอดจากมุมไหน</li>
<li><strong>ไม่ทำร้ายนก</strong> — ออกแบบให้นกไม่สามารถเกาะได้ แต่ไม่ทำอันตราย</li>
<li><strong>ติดตั้งง่าย</strong> — ใช้กาวซิลิโคนหรือสกรูยึด ติดแน่น ทนทาน</li>
<li><strong>มองไม่ชัดจากระยะไกล</strong> — ไม่กระทบทัศนียภาพของอาคาร</li>
</ul>
<!-- /wp:list -->

<!-- wp:heading {"level":3} -->
<h3>เหมาะสำหรับ:</h3>
<!-- /wp:heading -->

<!-- wp:list -->
<ul>
<li>ราวระเบียง / ราวกันตก / ราวบันได</li>
<li>ชายคา / หลังคา / สันหลังคา</li>
<li>ป้ายไฟ / ป้ายโฆษณา / ตัวอักษร</li>
<li>ขอบหน้าต่าง / กันสาด / ขอบบัว</li>
<li>ท่อแอร์ / รางน้ำ / ราวตากผ้า</li>
<li>กล้องวงจรปิด / ไฟส่องสว่าง</li>
</ul>
<!-- /wp:list -->
</div>
<!-- /wp:column -->
<!-- wp:column {"width":"40%"} -->
<div class="wp-block-column" style="flex-basis:40%">
<!-- wp:image -->
<figure class="wp-block-image"><img src="PLACEHOLDER_IMG_7" alt="หนามกันนก สแตนเลส Birds Go Away"/></figure>
<!-- /wp:image -->
<!-- wp:image -->
<figure class="wp-block-image"><img src="PLACEHOLDER_IMG_9" alt="หนามกันนก ติดตั้งขอบหน้าต่าง"/></figure>
<!-- /wp:image -->
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->

<!-- wp:separator {"className":"is-style-wide"} -->
<hr class="wp-block-separator has-alpha-channel-opacity is-style-wide"/>
<!-- /wp:separator -->

<!-- wp:heading {"level":2} -->
<h2>3. แผงกันนกโซลาร์เซลล์ (Solar Panel Bird Guard)</h2>
<!-- /wp:heading -->

<!-- wp:columns -->
<div class="wp-block-columns">
<!-- wp:column {"width":"60%"} -->
<div class="wp-block-column" style="flex-basis:60%">
<!-- wp:paragraph -->
<p>ระบบป้องกันนกสำหรับแผงโซลาร์เซลล์โดยเฉพาะ ใช้ระบบคลิปยึดพิเศษ <strong>ไม่ต้องเจาะแผง ไม่เสียการรับประกัน ไม่เป็นอันตรายต่อระบบไฟฟ้า</strong> ป้องกันนกทำรังใต้แผงโซลาร์ ยืดอายุการใช้งาน</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3>ปัญหาที่พบ:</h3>
<!-- /wp:heading -->

<!-- wp:list -->
<ul>
<li>นกทำรังใต้แผงโซลาร์ ทำให้เกิดความเสียหายต่อสายไฟ เสี่ยงไฟฟ้าลัดวงจร</li>
<li>มูลนกสะสมบนแผง ทำให้ประสิทธิภาพการผลิตไฟฟ้าลดลง 20-30%</li>
<li>รังนกอุดตันรางน้ำ ทำให้น้ำรั่วซึมเข้าบ้าน</li>
<li>เสียงนกรบกวน โดยเฉพาะช่วงเช้ามืด</li>
<li>ค่าซ่อมแซมระบบไฟฟ้าสูง หากปล่อยทิ้งไว้</li>
</ul>
<!-- /wp:list -->

<!-- wp:heading {"level":3} -->
<h3>จุดเด่นของระบบคลิป:</h3>
<!-- /wp:heading -->

<!-- wp:list -->
<ul>
<li><strong>ไม่เจาะแผงโซลาร์</strong> — ไม่ทำให้เสียการรับประกันแผงโซลาร์</li>
<li><strong>ติดตั้งง่าย รวดเร็ว</strong> — ใช้เวลาเพียง 1-2 วัน</li>
<li><strong>วัสดุสแตนเลส 304 + ตะแกรงสแตนเลส</strong> — ทนสนิม ทนทานทุกสภาพอากาศ</li>
<li><strong>ถอดล้างทำความสะอาดได้</strong> — สะดวกในการบำรุงรักษาแผงโซลาร์</li>
<li><strong>ไม่บังแสง</strong> — ไม่ลดประสิทธิภาพการผลิตไฟฟ้า</li>
</ul>
<!-- /wp:list -->
</div>
<!-- /wp:column -->
<!-- wp:column {"width":"40%"} -->
<div class="wp-block-column" style="flex-basis:40%">
<!-- wp:image -->
<figure class="wp-block-image"><img src="PLACEHOLDER_IMG_4" alt="แผงกันนกโซลาร์เซลล์ Birds Go Away"/></figure>
<!-- /wp:image -->
<!-- wp:image -->
<figure class="wp-block-image"><img src="PLACEHOLDER_IMG_6" alt="ระบบคลิปกันนกโซลาร์เซลล์"/></figure>
<!-- /wp:image -->
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->

<!-- wp:separator {"className":"is-style-wide"} -->
<hr class="wp-block-separator has-alpha-channel-opacity is-style-wide"/>
<!-- /wp:separator -->

<!-- wp:heading {"level":2} -->
<h2>4. เจลไล่นก (Bird Repellent Gel)</h2>
<!-- /wp:heading -->

<!-- wp:columns -->
<div class="wp-block-columns">
<!-- wp:column {"width":"60%"} -->
<div class="wp-block-column" style="flex-basis:60%">
<!-- wp:paragraph -->
<p>เจลไล่นกสูตรพิเศษ เป็นวิธีป้องกันนกแบบไม่ทำร้าย ใช้ได้กับทุกพื้นผิว เหมาะสำหรับพื้นที่ที่ไม่สามารถติดตั้งตาข่ายหรือหนามได้ หรือพื้นที่ที่ต้องการความสวยงามสูง</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3>คุณสมบัติเด่น:</h3>
<!-- /wp:heading -->

<!-- wp:list -->
<ul>
<li><strong>ไม่มีสารพิษ</strong> — ปลอดภัยต่อคน สัตว์เลี้ยง และนก 100%</li>
<li><strong>มองไม่เห็น</strong> — เจลใสไม่ทิ้งคราบ ไม่กระทบความสวยงามของอาคาร</li>
<li><strong>ใช้ได้ทุกพื้นผิว</strong> — โลหะ คอนกรีต ไม้ พลาสติก กระเบื้อง อิฐ</li>
<li><strong>ทนทานต่อสภาพอากาศ</strong> — ทนฝน ทนแดด ใช้ได้นาน 6-12 เดือน</li>
<li><strong>ทำงานด้วยประสาทสัมผัส</strong> — นกไม่ชอบความรู้สึกเหนียวที่เท้า จึงบินหนี</li>
<li><strong>ไม่ต้องเจาะ ไม่ต้องยึด</strong> — ทาบนพื้นผิวได้เลย ติดตั้งง่ายที่สุด</li>
</ul>
<!-- /wp:list -->

<!-- wp:heading {"level":3} -->
<h3>เหมาะสำหรับ:</h3>
<!-- /wp:heading -->

<!-- wp:list -->
<ul>
<li>ขอบหน้าต่าง / กรอบประตู / ขอบบัว</li>
<li>ราวระเบียงที่ไม่ต้องการติดหนาม</li>
<li>ป้ายไฟ / ตัวอักษรป้าย / ป้ายโฆษณา</li>
<li>อาคารอนุรักษ์ที่ห้ามเจาะหรือดัดแปลง</li>
<li>โบสถ์ / วัด / อาคารประวัติศาสตร์</li>
<li>พื้นที่ที่ต้องการความสวยงามสูง</li>
</ul>
<!-- /wp:list -->
</div>
<!-- /wp:column -->
<!-- wp:column {"width":"40%"} -->
<div class="wp-block-column" style="flex-basis:40%">
<!-- wp:image -->
<figure class="wp-block-image"><img src="PLACEHOLDER_IMG_8" alt="เจลไล่นก Birds Go Away"/></figure>
<!-- /wp:image -->
<!-- wp:image -->
<figure class="wp-block-image"><img src="PLACEHOLDER_IMG_10" alt="เจลไล่นก ใช้ได้ทุกพื้นผิว"/></figure>
<!-- /wp:image -->
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->

<!-- wp:separator {"className":"is-style-wide"} -->
<hr class="wp-block-separator has-alpha-channel-opacity is-style-wide"/>
<!-- /wp:separator -->

<!-- wp:heading {"textAlign":"center","level":2} -->
<h2 class="has-text-align-center">เปรียบเทียบบริการ</h2>
<!-- /wp:heading -->

<!-- wp:html -->
<table class="service-comparison" style="width:100%;border-collapse:collapse;text-align:center;">
<thead>
<tr style="background:#1a56db;color:white;">
<th style="padding:12px;border:1px solid #e2e8f0;">คุณสมบัติ</th>
<th style="padding:12px;border:1px solid #e2e8f0;">ตาข่าย HDPE</th>
<th style="padding:12px;border:1px solid #e2e8f0;">หนามกันนก</th>
<th style="padding:12px;border:1px solid #e2e8f0;">แผงกันนกโซลาร์</th>
<th style="padding:12px;border:1px solid #e2e8f0;">เจลไล่นก</th>
</tr>
</thead>
<tbody>
<tr><td style="padding:10px;border:1px solid #e2e8f0;font-weight:600;">อายุการใช้งาน</td><td style="padding:10px;border:1px solid #e2e8f0;">5-7 ปี</td><td style="padding:10px;border:1px solid #e2e8f0;">10+ ปี</td><td style="padding:10px;border:1px solid #e2e8f0;">10+ ปี</td><td style="padding:10px;border:1px solid #e2e8f0;">6-12 เดือน</td></tr>
<tr><td style="padding:10px;border:1px solid #e2e8f0;font-weight:600;">ประสิทธิภาพ</td><td style="padding:10px;border:1px solid #e2e8f0;">สูงมาก</td><td style="padding:10px;border:1px solid #e2e8f0;">สูง</td><td style="padding:10px;border:1px solid #e2e8f0;">สูงมาก</td><td style="padding:10px;border:1px solid #e2e8f0;">ปานกลาง</td></tr>
<tr><td style="padding:10px;border:1px solid #e2e8f0;font-weight:600;">ความสวยงาม</td><td style="padding:10px;border:1px solid #e2e8f0;">ดี</td><td style="padding:10px;border:1px solid #e2e8f0;">ดี</td><td style="padding:10px;border:1px solid #e2e8f0;">ดี</td><td style="padding:10px;border:1px solid #e2e8f0;">ดีมาก</td></tr>
<tr><td style="padding:10px;border:1px solid #e2e8f0;font-weight:600;">เหมาะกับพื้นที่</td><td style="padding:10px;border:1px solid #e2e8f0;">พื้นที่กว้าง</td><td style="padding:10px;border:1px solid #e2e8f0;">ขอบ/ราว</td><td style="padding:10px;border:1px solid #e2e8f0;">โซลาร์เซลล์</td><td style="padding:10px;border:1px solid #e2e8f0;">ทุกพื้นผิว</td></tr>
<tr><td style="padding:10px;border:1px solid #e2e8f0;font-weight:600;">ปลอดภัยต่อนก</td><td style="padding:10px;border:1px solid #e2e8f0;">ปลอดภัย</td><td style="padding:10px;border:1px solid #e2e8f0;">ปลอดภัย</td><td style="padding:10px;border:1px solid #e2e8f0;">ปลอดภัย</td><td style="padding:10px;border:1px solid #e2e8f0;">ปลอดภัย</td></tr>
</tbody>
</table>
<!-- /wp:html -->

<!-- wp:separator {"className":"is-style-wide"} -->
<hr class="wp-block-separator has-alpha-channel-opacity is-style-wide"/>
<!-- /wp:separator -->

<!-- wp:heading {"textAlign":"center","level":2} -->
<h2 class="has-text-align-center">ตัวอย่างผลงานจากลูกค้าจริง</h2>
<!-- /wp:heading -->

<!-- wp:gallery {"columns":4,"linkTo":"none"} -->
<figure class="wp-block-gallery has-nested-images columns-4 is-cropped">
<!-- wp:image -->
<figure class="wp-block-image"><img src="PLACEHOLDER_IMG_11" alt="ผลงาน Birds Go Away 01"/></figure>
<!-- /wp:image -->
<!-- wp:image -->
<figure class="wp-block-image"><img src="PLACEHOLDER_IMG_12" alt="ผลงาน Birds Go Away 02"/></figure>
<!-- /wp:image -->
<!-- wp:image -->
<figure class="wp-block-image"><img src="PLACEHOLDER_IMG_13" alt="ผลงาน Birds Go Away 03"/></figure>
<!-- /wp:image -->
<!-- wp:image -->
<figure class="wp-block-image"><img src="PLACEHOLDER_IMG_14" alt="ผลงาน Birds Go Away 04"/></figure>
<!-- /wp:image -->
</figure>
<!-- /wp:gallery -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons">
<!-- wp:button {"backgroundColor":"vivid-green-cyan","fontSize":"medium"} -->
<div class="wp-block-button has-custom-font-size has-medium-font-size"><a class="wp-block-button__link has-vivid-green-cyan-background-color has-background" href="/contact">ขอใบเสนอราคาฟรี — โทร 062-996-4994</a></div>
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
<p class="has-text-align-center has-medium-font-size">ขอบพระคุณลูกค้าทุกท่านที่ไว้ใจ Birds Go Away — ตัวอย่างผลงานจากลูกค้าจริง</p>
<!-- /wp:paragraph -->

<!-- wp:html -->
<div class="guarantee-badge">ผลงานติดตั้งจากลูกค้าจริง ทั่วประเทศ | D Condo ขอนแก่น | บ้านพัก | คอนโด | โรงงาน</div>
<!-- /wp:html -->

<!-- wp:separator {"className":"is-style-wide"} -->
<hr class="wp-block-separator has-alpha-channel-opacity is-style-wide"/>
<!-- /wp:separator -->

<!-- wp:heading {"level":2} -->
<h2>รูปภาพผลงาน — ตาข่ายกันนก HDPE</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>ผลงานติดตั้งตาข่ายกันนก HDPE สำหรับระเบียงคอนโด บ้านพักอาศัย อาคารพาณิชย์ และโรงงาน ติดตั้งแบบไร้รอยต่อ มองแล้วสบายตา กลมกลืนกับอาคาร</p>
<!-- /wp:paragraph -->

<!-- wp:gallery {"columns":3,"linkTo":"none"} -->
<figure class="wp-block-gallery has-nested-images columns-3 is-cropped">
<!-- wp:image -->
<figure class="wp-block-image"><img src="PLACEHOLDER_IMG_12" alt="ผลงานติดตั้งตาข่ายกันนก 01"/></figure>
<!-- /wp:image -->
<!-- wp:image -->
<figure class="wp-block-image"><img src="PLACEHOLDER_IMG_13" alt="ผลงานติดตั้งตาข่ายกันนก 02"/></figure>
<!-- /wp:image -->
<!-- wp:image -->
<figure class="wp-block-image"><img src="PLACEHOLDER_IMG_14" alt="ผลงานติดตั้งตาข่ายกันนก 03"/></figure>
<!-- /wp:image -->
<!-- wp:image -->
<figure class="wp-block-image"><img src="PLACEHOLDER_IMG_15" alt="ผลงานติดตั้งตาข่ายกันนก 04"/></figure>
<!-- /wp:image -->
<!-- wp:image -->
<figure class="wp-block-image"><img src="PLACEHOLDER_IMG_16" alt="ผลงานติดตั้งตาข่ายกันนก 05"/></figure>
<!-- /wp:image -->
<!-- wp:image -->
<figure class="wp-block-image"><img src="PLACEHOLDER_IMG_17" alt="ผลงานติดตั้งตาข่ายกันนก 06"/></figure>
<!-- /wp:image -->
<!-- wp:image -->
<figure class="wp-block-image"><img src="PLACEHOLDER_IMG_18" alt="ผลงานติดตั้งตาข่ายกันนก 07"/></figure>
<!-- /wp:image -->
<!-- wp:image -->
<figure class="wp-block-image"><img src="PLACEHOLDER_IMG_19" alt="ผลงานติดตั้งตาข่ายกันนก 08"/></figure>
<!-- /wp:image -->
<!-- wp:image -->
<figure class="wp-block-image"><img src="PLACEHOLDER_IMG_20" alt="ผลงานติดตั้งตาข่ายกันนก 09"/></figure>
<!-- /wp:image -->
</figure>
<!-- /wp:gallery -->

<!-- wp:separator -->
<hr class="wp-block-separator has-alpha-channel-opacity"/>
<!-- /wp:separator -->

<!-- wp:heading {"level":2} -->
<h2>รูปภาพผลงาน — ก่อนและหลัง</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>เปรียบเทียบก่อนและหลังการติดตั้ง — หมดปัญหานกพิราบรบกวน พื้นที่สะอาด ปลอดภัย</p>
<!-- /wp:paragraph -->

<!-- wp:gallery {"columns":3,"linkTo":"none"} -->
<figure class="wp-block-gallery has-nested-images columns-3 is-cropped">
<!-- wp:image -->
<figure class="wp-block-image"><img src="PLACEHOLDER_IMG_21" alt="ผลงานก่อนหลังติดตั้ง 01"/></figure>
<!-- /wp:image -->
<!-- wp:image -->
<figure class="wp-block-image"><img src="PLACEHOLDER_IMG_22" alt="ผลงานก่อนหลังติดตั้ง 02"/></figure>
<!-- /wp:image -->
<!-- wp:image -->
<figure class="wp-block-image"><img src="PLACEHOLDER_IMG_23" alt="ผลงานก่อนหลังติดตั้ง 03"/></figure>
<!-- /wp:image -->
<!-- wp:image -->
<figure class="wp-block-image"><img src="PLACEHOLDER_IMG_24" alt="ผลงานก่อนหลังติดตั้ง 04"/></figure>
<!-- /wp:image -->
<!-- wp:image -->
<figure class="wp-block-image"><img src="PLACEHOLDER_IMG_25" alt="ผลงานก่อนหลังติดตั้ง 05"/></figure>
<!-- /wp:image -->
<!-- wp:image -->
<figure class="wp-block-image"><img src="PLACEHOLDER_IMG_26" alt="ผลงานก่อนหลังติดตั้ง 06"/></figure>
<!-- /wp:image -->
</figure>
<!-- /wp:gallery -->

<!-- wp:separator -->
<hr class="wp-block-separator has-alpha-channel-opacity"/>
<!-- /wp:separator -->

<!-- wp:heading {"level":2} -->
<h2>รูปภาพเพิ่มเติม</h2>
<!-- /wp:heading -->

<!-- wp:gallery {"columns":4,"linkTo":"none"} -->
<figure class="wp-block-gallery has-nested-images columns-4 is-cropped">
<!-- wp:image -->
<figure class="wp-block-image"><img src="PLACEHOLDER_IMG_27" alt="ผลงาน Birds Go Away 11"/></figure>
<!-- /wp:image -->
<!-- wp:image -->
<figure class="wp-block-image"><img src="PLACEHOLDER_IMG_28" alt="ผลงาน Birds Go Away 12"/></figure>
<!-- /wp:image -->
<!-- wp:image -->
<figure class="wp-block-image"><img src="PLACEHOLDER_IMG_29" alt="ผลงาน Birds Go Away 13"/></figure>
<!-- /wp:image -->
<!-- wp:image -->
<figure class="wp-block-image"><img src="PLACEHOLDER_IMG_30" alt="ผลงาน Birds Go Away 14"/></figure>
<!-- /wp:image -->
<!-- wp:image -->
<figure class="wp-block-image"><img src="PLACEHOLDER_IMG_31" alt="ผลงาน Birds Go Away 15"/></figure>
<!-- /wp:image -->
<!-- wp:image -->
<figure class="wp-block-image"><img src="PLACEHOLDER_IMG_32" alt="ผลงาน Birds Go Away 16"/></figure>
<!-- /wp:image -->
<!-- wp:image -->
<figure class="wp-block-image"><img src="PLACEHOLDER_IMG_33" alt="ผลงาน Birds Go Away 17"/></figure>
<!-- /wp:image -->
<!-- wp:image -->
<figure class="wp-block-image"><img src="PLACEHOLDER_IMG_02" alt="ผลงาน Birds Go Away 18"/></figure>
<!-- /wp:image -->
</figure>
<!-- /wp:gallery -->

<!-- wp:separator {"className":"is-style-wide"} -->
<hr class="wp-block-separator has-alpha-channel-opacity is-style-wide"/>
<!-- /wp:separator -->

<!-- wp:heading {"level":2} -->
<h2>วิดีโอผลงานการติดตั้ง</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>ดูคลิปขั้นตอนการทำงานจริงจากหน้างาน — ทีมช่างมืออาชีพ อุปกรณ์ครบครัน</p>
<!-- /wp:paragraph -->

<!-- wp:columns -->
<div class="wp-block-columns">
<!-- wp:column -->
<div class="wp-block-column">
<!-- wp:video -->
<figure class="wp-block-video"><video controls src="PLACEHOLDER_VID_01"></video><figcaption>คลิปติดตั้ง #1</figcaption></figure>
<!-- /wp:video -->
</div>
<!-- /wp:column -->
<!-- wp:column -->
<div class="wp-block-column">
<!-- wp:video -->
<figure class="wp-block-video"><video controls src="PLACEHOLDER_VID_02"></video><figcaption>คลิปติดตั้ง #2</figcaption></figure>
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
<figure class="wp-block-video"><video controls src="PLACEHOLDER_VID_03"></video><figcaption>คลิปติดตั้ง #3</figcaption></figure>
<!-- /wp:video -->
</div>
<!-- /wp:column -->
<!-- wp:column -->
<div class="wp-block-column">
<!-- wp:video -->
<figure class="wp-block-video"><video controls src="PLACEHOLDER_VID_04"></video><figcaption>คลิปติดตั้ง #4</figcaption></figure>
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
<figure class="wp-block-video"><video controls src="PLACEHOLDER_VID_05"></video><figcaption>คลิปติดตั้ง #5</figcaption></figure>
<!-- /wp:video -->
</div>
<!-- /wp:column -->
<!-- wp:column -->
<div class="wp-block-column">
<!-- wp:video -->
<figure class="wp-block-video"><video controls src="PLACEHOLDER_VID_06"></video><figcaption>คลิปติดตั้ง #6</figcaption></figure>
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
<figure class="wp-block-video"><video controls src="PLACEHOLDER_VID_07"></video><figcaption>คลิปติดตั้ง #7</figcaption></figure>
<!-- /wp:video -->
</div>
<!-- /wp:column -->
<!-- wp:column -->
<div class="wp-block-column">
<!-- wp:video -->
<figure class="wp-block-video"><video controls src="PLACEHOLDER_VID_08"></video><figcaption>คลิปติดตั้ง #8</figcaption></figure>
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
<figure class="wp-block-video"><video controls src="PLACEHOLDER_VID_09"></video><figcaption>คลิปติดตั้ง #9</figcaption></figure>
<!-- /wp:video -->
</div>
<!-- /wp:column -->
<!-- wp:column -->
<div class="wp-block-column">
<!-- wp:video -->
<figure class="wp-block-video"><video controls src="PLACEHOLDER_VID_10"></video><figcaption>คลิปติดตั้ง #10</figcaption></figure>
<!-- /wp:video -->
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->

<!-- wp:separator {"className":"is-style-wide"} -->
<hr class="wp-block-separator has-alpha-channel-opacity is-style-wide"/>
<!-- /wp:separator -->

<!-- wp:heading {"textAlign":"center","level":2} -->
<h2 class="has-text-align-center">ติดตามเราบน Facebook</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">ติดตามผลงานล่าสุด เคล็ดลับ และข่าวสารจาก Birds Go Away<br><a href="https://www.facebook.com/people/%E0%B8%95%E0%B8%B2%E0%B8%82%E0%B9%88%E0%B8%B2%E0%B8%A2%E0%B8%81%E0%B8%B1%E0%B8%99%E0%B8%99%E0%B8%81-%E0%B8%82%E0%B8%AD%E0%B8%99%E0%B9%81%E0%B8%81%E0%B9%88%E0%B8%99-%E0%B9%80%E0%B8%8A%E0%B8%B5%E0%B8%A2%E0%B8%87%E0%B9%83%E0%B8%AB%E0%B8%A1%E0%B9%88-%E0%B8%8A%E0%B8%A5%E0%B8%9A%E0%B8%B8%E0%B8%A3%E0%B8%B5-by-Birds-Go-Away/61554258339668/" target="_blank">ตาข่ายกันนก by Birds Go Away — 3,000+ Followers</a></p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons">
<!-- wp:button {"backgroundColor":"vivid-green-cyan"} -->
<div class="wp-block-button"><a class="wp-block-button__link has-vivid-green-cyan-background-color has-background" href="/contact">สนใจบริการ? ติดต่อเราเลย — ขอใบเสนอราคาฟรี</a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->
')

  # About Us Page
  ABOUT_ID=$(wp post create --post_type=page --post_title='เกี่ยวกับเรา' --post_status=publish --post_name='about' --path=/var/www/html --allow-root --porcelain --post_content='
<!-- wp:columns -->
<div class="wp-block-columns hero-section">
<!-- wp:column -->
<div class="wp-block-column" style="background:transparent !important;border:none !important;">
<!-- wp:heading {"level":1} -->
<h1>เกี่ยวกับ BIRDS GO AWAY</h1>
<!-- /wp:heading -->
<!-- wp:paragraph {"fontSize":"medium"} -->
<p class="has-medium-font-size">บริษัท รีเช็ค บิ้วดิ้ง จำกัด (Recheck Building Co., Ltd.) — ผู้เชี่ยวชาญด้านการป้องกันนกแบบครบวงจร</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->

<!-- wp:separator {"className":"is-style-wide"} -->
<hr class="wp-block-separator has-alpha-channel-opacity is-style-wide"/>
<!-- /wp:separator -->

<!-- wp:columns -->
<div class="wp-block-columns">
<!-- wp:column {"width":"60%"} -->
<div class="wp-block-column" style="flex-basis:60%">
<!-- wp:heading {"level":2} -->
<h2>เรื่องราวของเรา</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>BIRDS GO AWAY ภายใต้ <strong>บริษัท รีเช็ค บิ้วดิ้ง จำกัด</strong> (Recheck Building Co., Ltd.) ก่อตั้งขึ้นจากความมุ่งมั่นในการแก้ปัญหานกรบกวนอย่างมืออาชีพและเป็นมิตรกับสิ่งแวดล้อม</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>เราเข้าใจปัญหาที่เจ้าของบ้านและเจ้าของอาคารต้องเผชิญ — มูลนกสกปรก เสียงรบกวน ไรนก เชื้อโรค ความเสียหายต่ออาคาร ทุกวันนับพันครอบครัวต้องทนทุกข์กับปัญหาเหล่านี้ เราจึงรวมทีมวิศวกรและช่างมืออาชีพที่มีประสบการณ์จริง สร้าง BIRDS GO AWAY ขึ้นมาเพื่อแก้ปัญหานี้อย่างถาวร</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>ทีมช่างของเราผ่านการอบรมโรยตัวภาคทฤษฎี-ปฏิบัติ มีใบ Certificate รับรองมาตรฐาน ทุกโปรเจคมีวิศวกรคุมงาน วางแผนอย่างรอบคอบ ปรึกษาฟรีโดยวิศวกรโยธาในพื้นที่</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>เราให้บริการติดตั้งตาข่ายกันนก หนามกันนก เจลไล่นก และแผงกันนกโซลาร์เซลล์ ครอบคลุมพื้นที่ <strong>ขอนแก่น เชียงใหม่ และชลบุรี</strong> พร้อมขยายบริการทั่วประเทศ</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:column -->
<!-- wp:column {"width":"40%"} -->
<div class="wp-block-column" style="flex-basis:40%">
<!-- wp:image -->
<figure class="wp-block-image"><img src="PLACEHOLDER_IMG_1" alt="ทีมงาน Birds Go Away"/></figure>
<!-- /wp:image -->
<!-- wp:image -->
<figure class="wp-block-image"><img src="PLACEHOLDER_IMG_2" alt="การทำงาน Birds Go Away"/></figure>
<!-- /wp:image -->
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->

<!-- wp:separator {"className":"is-style-wide"} -->
<hr class="wp-block-separator has-alpha-channel-opacity is-style-wide"/>
<!-- /wp:separator -->

<!-- wp:heading {"textAlign":"center","level":2} -->
<h2 class="has-text-align-center">ทำไมต้องเลือก BIRDS GO AWAY?</h2>
<!-- /wp:heading -->

<!-- wp:columns -->
<div class="wp-block-columns">
<!-- wp:column -->
<div class="wp-block-column" style="text-align:center;">
<h3>ผ่านการอบรมโรยตัว</h3>
<p>ทีมช่างทุกคนผ่านการอบรมโรยตัวภาคทฤษฎี-ปฏิบัติ มีใบ Certificate รับรองมาตรฐาน สามารถทำงานบนที่สูงอย่างปลอดภัย</p>
</div>
<!-- /wp:column -->
<!-- wp:column -->
<div class="wp-block-column" style="text-align:center;">
<h3>วิศวกรคุมงาน</h3>
<p>ทุกโปรเจคมีวิศวกรโยธาคุมงาน วางแผนอย่างรอบคอบ ปรึกษาฟรีโดยวิศวกรในพื้นที่ ไม่มีค่าใช้จ่าย</p>
</div>
<!-- /wp:column -->
<!-- wp:column -->
<div class="wp-block-column" style="text-align:center;">
<h3>รับประกัน 3 ปี</h3>
<p>รับประกันงานติดตั้งนานถึง 3 ปี ตาข่าย HDPE คุณภาพสูง อายุการใช้งาน 5-7 ปี แข็งแรงทนทาน</p>
</div>
<!-- /wp:column -->
<!-- wp:column -->
<div class="wp-block-column" style="text-align:center;">
<h3>ราคาตรงไปตรงมา</h3>
<p>เสนอราคาฟรี ไม่มีค่าใช้จ่ายแอบแฝง ราคายุติธรรม คุ้มค่าทุกบาท ไม่เก็บเพิ่มทีหลัง</p>
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->

<!-- wp:separator {"className":"is-style-wide"} -->
<hr class="wp-block-separator has-alpha-channel-opacity is-style-wide"/>
<!-- /wp:separator -->

<!-- wp:heading {"textAlign":"center","level":2} -->
<h2 class="has-text-align-center">มาตรฐานความปลอดภัย</h2>
<!-- /wp:heading -->

<!-- wp:columns -->
<div class="wp-block-columns">
<!-- wp:column {"width":"50%"} -->
<div class="wp-block-column" style="flex-basis:50%">
<!-- wp:list -->
<ul>
<li>ทีมช่างผ่านการอบรมโรยตัวภาคทฤษฎี-ปฏิบัติ มีใบ Certificate</li>
<li>ใช้อุปกรณ์ PPE (Personal Protective Equipment) ครบชุด</li>
<li>มีประกันอุบัติเหตุสำหรับพนักงานทุกคน</li>
<li>วิศวกรโยธาคุมงานทุกโปรเจค</li>
<li>ตรวจสอบและสำรวจพื้นที่ก่อนเริ่มงานทุกครั้ง</li>
<li>ใช้อุปกรณ์ป้องกันการตกจากที่สูงตามมาตรฐาน</li>
<li>มีแผนปฏิบัติการฉุกเฉินสำหรับทุกโปรเจค</li>
</ul>
<!-- /wp:list -->
</div>
<!-- /wp:column -->
<!-- wp:column {"width":"50%"} -->
<div class="wp-block-column" style="flex-basis:50%">
<!-- wp:image -->
<figure class="wp-block-image"><img src="PLACEHOLDER_IMG_3" alt="มาตรฐานความปลอดภัย Birds Go Away"/></figure>
<!-- /wp:image -->
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->

<!-- wp:separator {"className":"is-style-wide"} -->
<hr class="wp-block-separator has-alpha-channel-opacity is-style-wide"/>
<!-- /wp:separator -->

<!-- wp:heading {"textAlign":"center","level":2} -->
<h2 class="has-text-align-center">บริการครบวงจร 4 รูปแบบ</h2>
<!-- /wp:heading -->

<!-- wp:columns -->
<div class="wp-block-columns">
<!-- wp:column -->
<div class="wp-block-column" style="text-align:center;">
<h3>ตาข่าย HDPE กันนก</h3>
<p>ตาข่ายกันนกคุณภาพสูง ทนทาน 5-7 ปี ติดตั้งได้ทุกพื้นที่</p>
</div>
<!-- /wp:column -->
<!-- wp:column -->
<div class="wp-block-column" style="text-align:center;">
<h3>หนามกันนก</h3>
<p>หนามสแตนเลส 304 รุ่น 3 แถวไขว้ ป้องกันนกเกาะทุกทิศทาง</p>
</div>
<!-- /wp:column -->
<!-- wp:column -->
<div class="wp-block-column" style="text-align:center;">
<h3>แผงกันนกโซลาร์</h3>
<p>ระบบคลิปไม่เจาะแผง ไม่เสียการรับประกัน ป้องกันนกทำรัง</p>
</div>
<!-- /wp:column -->
<!-- wp:column -->
<div class="wp-block-column" style="text-align:center;">
<h3>เจลไล่นก</h3>
<p>เจลใสไร้สารพิษ ปลอดภัย ใช้ได้ทุกพื้นผิว มองไม่เห็น</p>
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->

<!-- wp:separator {"className":"is-style-wide"} -->
<hr class="wp-block-separator has-alpha-channel-opacity is-style-wide"/>
<!-- /wp:separator -->

<!-- wp:heading {"textAlign":"center","level":2} -->
<h2 class="has-text-align-center">ตัวอย่างผลงานของเรา</h2>
<!-- /wp:heading -->

<!-- wp:gallery {"columns":4,"linkTo":"none"} -->
<figure class="wp-block-gallery has-nested-images columns-4 is-cropped">
<!-- wp:image -->
<figure class="wp-block-image"><img src="PLACEHOLDER_IMG_15" alt="ผลงาน Birds Go Away"/></figure>
<!-- /wp:image -->
<!-- wp:image -->
<figure class="wp-block-image"><img src="PLACEHOLDER_IMG_16" alt="ผลงาน Birds Go Away"/></figure>
<!-- /wp:image -->
<!-- wp:image -->
<figure class="wp-block-image"><img src="PLACEHOLDER_IMG_17" alt="ผลงาน Birds Go Away"/></figure>
<!-- /wp:image -->
<!-- wp:image -->
<figure class="wp-block-image"><img src="PLACEHOLDER_IMG_18" alt="ผลงาน Birds Go Away"/></figure>
<!-- /wp:image -->
</figure>
<!-- /wp:gallery -->

<!-- wp:heading {"textAlign":"center","level":2} -->
<h2 class="has-text-align-center">ข้อมูลบริษัท</h2>
<!-- /wp:heading -->

<!-- wp:html -->
<table style="width:100%;border-collapse:collapse;margin:0 auto;max-width:700px;">
<tr><td style="padding:12px;border-bottom:1px solid #e2e8f0;font-weight:600;width:40%;">ชื่อบริษัท</td><td style="padding:12px;border-bottom:1px solid #e2e8f0;">บริษัท รีเช็ค บิ้วดิ้ง จำกัด (Recheck Building Co., Ltd.)</td></tr>
<tr><td style="padding:12px;border-bottom:1px solid #e2e8f0;font-weight:600;">แบรนด์</td><td style="padding:12px;border-bottom:1px solid #e2e8f0;">BIRDS GO AWAY</td></tr>
<tr><td style="padding:12px;border-bottom:1px solid #e2e8f0;font-weight:600;">บริการ</td><td style="padding:12px;border-bottom:1px solid #e2e8f0;">ตาข่ายกันนก, หนามกันนก, เจลไล่นก, แผงกันนกโซลาร์เซลล์</td></tr>
<tr><td style="padding:12px;border-bottom:1px solid #e2e8f0;font-weight:600;">พื้นที่ให้บริการ</td><td style="padding:12px;border-bottom:1px solid #e2e8f0;">ขอนแก่น, เชียงใหม่, ชลบุรี (และพื้นที่ใกล้เคียง)</td></tr>
<tr><td style="padding:12px;border-bottom:1px solid #e2e8f0;font-weight:600;">ที่อยู่</td><td style="padding:12px;border-bottom:1px solid #e2e8f0;">88/38, ขอนแก่น, ประเทศไทย</td></tr>
<tr><td style="padding:12px;border-bottom:1px solid #e2e8f0;font-weight:600;">โทรศัพท์</td><td style="padding:12px;border-bottom:1px solid #e2e8f0;">062-996-4994 / 093-641-5623 / 095-629-2488</td></tr>
<tr><td style="padding:12px;border-bottom:1px solid #e2e8f0;font-weight:600;">LINE</td><td style="padding:12px;border-bottom:1px solid #e2e8f0;">oil_phanu / th3-ta006-2</td></tr>
<tr><td style="padding:12px;border-bottom:1px solid #e2e8f0;font-weight:600;">อีเมล</td><td style="padding:12px;border-bottom:1px solid #e2e8f0;">admin@birdsgoaway.com</td></tr>
<tr><td style="padding:12px;border-bottom:1px solid #e2e8f0;font-weight:600;">Facebook</td><td style="padding:12px;border-bottom:1px solid #e2e8f0;">ตาข่ายกันนก by Birds Go Away (3,000+ Followers)</td></tr>
</table>
<!-- /wp:html -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons">
<!-- wp:button {"backgroundColor":"vivid-green-cyan","fontSize":"medium"} -->
<div class="wp-block-button has-custom-font-size has-medium-font-size"><a class="wp-block-button__link has-vivid-green-cyan-background-color has-background" href="/contact">ติดต่อเรา — ขอใบเสนอราคาฟรี</a></div>
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
<p>📞 <strong>ขอนแก่น:</strong> <a href=\"tel:0629964994\">062-996-4994</a></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>📞 <strong>เชียงใหม่:</strong> <a href=\"tel:0936415623\">093-641-5623</a></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>📞 <strong>ชลบุรี:</strong> <a href=\"tel:0956292488\">095-629-2488</a></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>📱 <strong>Line:</strong> <a href=\"https://line.me/ti/p/~oil_phanu\" target=\"_blank\" rel=\"noreferrer noopener\">oil_phanu</a> / <a href=\"https://line.me/ti/p/~th3-ta006-2\" target=\"_blank\" rel=\"noreferrer noopener\">th3-ta006-2</a></p>
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
<div class=\"wp-block-button\"><a class=\"wp-block-button__link has-vivid-green-cyan-background-color has-background\" href=\"tel:0629964994\">📞 โทรเลย (ขอนแก่น)</a></div>
<!-- /wp:button -->
<!-- wp:button {\"backgroundColor\":\"vivid-green-cyan\"} -->
<div class=\"wp-block-button\"><a class=\"wp-block-button__link has-vivid-green-cyan-background-color has-background\" href=\"https://line.me/ti/p/~oil_phanu\" target=\"_blank\" rel=\"noreferrer noopener\">💬 แชท Line</a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->

<!-- wp:heading {\"level\":3} -->
<h3>แผนที่</h3>
<!-- /wp:heading -->

<!-- wp:html -->
<iframe src=\"https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3831.4!2d102.8!3d16.4!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3122789de0000001%3A0x1234567890abcdef!2sKhon%20Kaen%2C%20Thailand!5e0!3m2!1sen!2sth!4v1234567890\" width=\"100%\" height=\"300\" style=\"border:0;border-radius:8px;\" allowfullscreen=\"\" loading=\"lazy\" referrerpolicy=\"no-referrer-when-downgrade\"></iframe>
<!-- /wp:html -->

</div>
<!-- /wp:column -->

</div>
<!-- /wp:columns -->
")

  echo "=== Replacing Placeholder Media URLs ==="
  # Replace placeholder image URLs in page content
  for PAGE_ID in $HOME_ID $SERVICES_ID $PORTFOLIO_ID $ABOUT_ID; do
    if [ -n "$PAGE_ID" ]; then
      CONTENT=$(wp post get "$PAGE_ID" --field=post_content --path=/var/www/html --allow-root 2>/dev/null || echo "")
      if [ -n "$CONTENT" ]; then
        MODIFIED=false
        for NUM in $(seq -w 1 33); do
          KEY="birdnet-${NUM}"
          URL="${IMG_URLS[$KEY]:-}"
          if [ -n "$URL" ]; then
            NEW_CONTENT=$(echo "$CONTENT" | sed "s|PLACEHOLDER_IMG_${NUM#0}|${URL}|g")
            if [ "$NEW_CONTENT" != "$CONTENT" ]; then
              CONTENT="$NEW_CONTENT"
              MODIFIED=true
            fi
          fi
        done
        for NUM in $(seq -w 1 10); do
          KEY="reel-${NUM}"
          URL="${VID_URLS[$KEY]:-}"
          if [ -n "$URL" ]; then
            NEW_CONTENT=$(echo "$CONTENT" | sed "s|PLACEHOLDER_VID_${NUM#0}|${URL}|g")
            if [ "$NEW_CONTENT" != "$CONTENT" ]; then
              CONTENT="$NEW_CONTENT"
              MODIFIED=true
            fi
          fi
        done
        if [ "$MODIFIED" = true ]; then
          wp post update "$PAGE_ID" --post_content="$CONTENT" --path=/var/www/html --allow-root 2>/dev/null || true
          echo "Updated media URLs for page $PAGE_ID"
        fi
      fi
    fi
  done

  echo "=== Setting Home Page ==="
  wp option update page_on_front $HOME_ID --path=/var/www/html --allow-root

  echo "=== Creating Navigation Menu ==="
  wp menu delete "Main Menu" --path=/var/www/html --allow-root 2>/dev/null || true
  wp menu create "Main Menu" --path=/var/www/html --allow-root

  wp menu item add-post "Main Menu" $HOME_ID --title="หน้าแรก" --path=/var/www/html --allow-root
  wp menu item add-post "Main Menu" $SERVICES_ID --title="บริการของเรา" --path=/var/www/html --allow-root
  wp menu item add-post "Main Menu" $PORTFOLIO_ID --title="ผลงานของเรา" --path=/var/www/html --allow-root
  wp menu item add-post "Main Menu" $ABOUT_ID --title="เกี่ยวกับเรา" --path=/var/www/html --allow-root
  wp menu item add-post "Main Menu" $CONTACT_ID --title="ติดต่อเรา" --path=/var/www/html --allow-root

  wp menu location assign "Main Menu" primary --path=/var/www/html --allow-root
  wp menu location assign "Main Menu" main --path=/var/www/html --allow-root 2>/dev/null || true
  wp menu location assign "Main Menu" mobile_menu --path=/var/www/html --allow-root 2>/dev/null || true

  echo "=== Configuring Astra Theme ==="
  wp option update blogname "Birds Go Away — ตาข่ายกันนก" --path=/var/www/html --allow-root
  wp option update blogdescription "บริการติดตั้งตาข่ายกันนก ครบวงจร รับประกัน 3 ปี" --path=/var/www/html --allow-root

  # Fix navigation: assign Main Menu to ALL registered menu locations
  echo "=== Fixing Navigation ==="
  MENU_LOCATIONS=$(wp menu location list --format=csv --path=/var/www/html --allow-root 2>/dev/null | tail -n +2 | cut -d',' -f1)
  for LOC in $MENU_LOCATIONS; do
    wp menu location assign "Main Menu" "$LOC" --path=/var/www/html --allow-root 2>/dev/null || true
    echo "Assigned Main Menu to location: $LOC"
  done

  # Astra Header Builder: set mobile menu to use Menu 1 (custom menu, not fallback page list)
  wp theme mod set header-mobile-menu-source menu-1 --path=/var/www/html --allow-root 2>/dev/null || true

  # Disable Astra above/below header sections
  wp theme mod set above-header-layout disabled --path=/var/www/html --allow-root 2>/dev/null || true
  wp theme mod set below-header-layout disabled --path=/var/www/html --allow-root 2>/dev/null || true
  wp theme mod set header-above-header-display 0 --path=/var/www/html --allow-root 2>/dev/null || true
  wp theme mod set header-below-header-display 0 --path=/var/www/html --allow-root 2>/dev/null || true

  echo "=== Setting Default Contact Options ==="
  wp option update birdnet_phone "0629964994" --path=/var/www/html --allow-root
  wp option update birdnet_line_id "oil_phanu" --path=/var/www/html --allow-root
  wp option update birdnet_email "admin@birdsgoaway.com" --path=/var/www/html --allow-root

  echo "=== Setting Favicon ==="
  if [ -f "$ASSETS_DIR/favicon.ico" ]; then
    FAVICON_ID=$(wp media import "$ASSETS_DIR/favicon.ico" --title="Birds Go Away Favicon" --porcelain --path=/var/www/html --allow-root 2>/dev/null || true)
    if [ -n "$FAVICON_ID" ] && [ "$FAVICON_ID" -gt 0 ] 2>/dev/null; then
      wp option update site_icon "$FAVICON_ID" --path=/var/www/html --allow-root 2>/dev/null || true
      echo "Favicon set (attachment ID: $FAVICON_ID)"
    fi
  fi

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

  # Set favicon if not already set
  CURRENT_ICON=$(wp option get site_icon --path=/var/www/html --allow-root 2>/dev/null || echo "0")
  if [ "$CURRENT_ICON" = "0" ] || [ -z "$CURRENT_ICON" ]; then
    if [ -f "$ASSETS_DIR/favicon.ico" ]; then
      echo "=== Setting Favicon ==="
      FAVICON_ID=$(wp media import "$ASSETS_DIR/favicon.ico" --title="Birds Go Away Favicon" --porcelain --path=/var/www/html --allow-root 2>/dev/null || true)
      if [ -n "$FAVICON_ID" ] && [ "$FAVICON_ID" -gt 0 ] 2>/dev/null; then
        wp option update site_icon "$FAVICON_ID" --path=/var/www/html --allow-root 2>/dev/null || true
        echo "Favicon set (attachment ID: $FAVICON_ID)"
      fi
    fi
  fi

  # Update page content with proper images/videos via PHP
  echo "=== Updating page content ==="
  php /tmp/update-content.php 2>/dev/null || echo "Content update had errors, continuing..."
fi

# Ensure proper permissions
chown -R www-data:www-data /var/www/html/wp-content 2>/dev/null || true

echo "=== WordPress is ready ==="
) &

# Hand off to WordPress entrypoint (becomes PID 1 via exec)
exec docker-entrypoint.sh "$@"
