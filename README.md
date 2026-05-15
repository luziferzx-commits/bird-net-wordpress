# Bird Net Installation Services - WordPress Website

เว็บไซต์ WordPress สำหรับธุรกิจบริการติดตั้งตาข่ายกันนก

## บริการ

- ตาข่าย HDPE กันนก (HDPE Bird Netting)
- ตาข่ายกันนกใต้แผงโซล่าร์เซลล์ (Solar Panel Bird Net)
- หนามกันนก (Bird Spikes)
- เจลไล่นก (Bird Repellent Gel)

## Tech Stack

- **CMS**: WordPress 6.7 + PHP 8.2
- **Theme**: Astra + Custom Child Theme (Bird Net)
- **Page Builder**: Elementor
- **Plugins**: Contact Form 7, Yoast SEO, Wordfence, WP Super Cache
- **Database**: MySQL 8.0
- **Hosting**: Railway

## Pages

1. **หน้าแรก (Home)** - Hero section, บริการ, trust signals, CTA
2. **บริการของเรา (Services)** - รายละเอียดบริการทั้ง 4 ประเภท
3. **ผลงานของเรา (Portfolio)** - แกลเลอรีรูปภาพ Before & After
4. **เกี่ยวกับเรา (About Us)** - ข้อมูลบริษัท ค่านิยม มาตรฐานความปลอดภัย
5. **ติดต่อเรา (Contact Us)** - แบบฟอร์มติดต่อ, Google Maps, Line Chat

## Local Development

```bash
# Start the local environment
docker compose up -d --build

# Wait ~30 seconds for WordPress to install
# Then visit: http://localhost:8080

# Admin panel: http://localhost:8080/wp-admin
# Username: admin
# Password: admin123
```

## Railway Deployment

### Environment Variables (Required)

| Variable | Description |
|----------|-------------|
| `WORDPRESS_DB_HOST` | MySQL host (from Railway MySQL addon) |
| `WORDPRESS_DB_USER` | MySQL username |
| `WORDPRESS_DB_PASSWORD` | MySQL password |
| `WORDPRESS_DB_NAME` | Database name |
| `WP_ADMIN_USER` | WordPress admin username |
| `WP_ADMIN_PASSWORD` | WordPress admin password |
| `WP_ADMIN_EMAIL` | Admin email for notifications |

### Deploy Steps

1. Create a new project on Railway
2. Add a MySQL database service
3. Deploy this repo from GitHub
4. Set environment variables (connect to MySQL service)
5. Generate a public domain

## Features

- **Mobile Responsive**: ปรับแต่งสำหรับมือถือ
- **SEO Optimized**: Yoast SEO + meta tags + clean URLs
- **Security**: Wordfence + security headers
- **Performance**: WP Super Cache + OPcache + image optimization
- **Contact**: Form → Email + Click-to-Call + Line Chat
- **Thai Language**: เนื้อหาภาษาไทยทั้งหมด

## Admin Guide

### อัพโหลดรูปภาพผลงาน
1. ไปที่ WordPress Admin → Media → Add New
2. อัพโหลดรูปภาพ
3. ไปที่ Pages → ผลงานของเรา → Edit
4. เพิ่มรูปภาพในแต่ละหมวดหมู่

### แก้ไขข้อมูลติดต่อ
1. ไปที่ Settings → Bird Net Settings
2. แก้ไขเบอร์โทร, Line ID, อีเมล
3. กด Save

### เขียนบล็อก
1. ไปที่ Posts → Add New
2. เขียนเนื้อหาและเพิ่มรูปภาพ
3. กด Publish
