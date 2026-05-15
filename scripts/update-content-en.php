#!/usr/bin/env php
<?php
/**
 * English content for Birds Go Away website.
 * Creates /en/ parent page + English child pages.
 * Run via: php /tmp/update-content-en.php (after update-content.php)
 */

// Load WordPress
$wp_load = '/var/www/html/wp-load.php';
if (!file_exists($wp_load)) {
    echo "WordPress not found at $wp_load\n";
    exit(1);
}
require_once $wp_load;

$site_url = get_site_url();
$site_url = str_replace('http://', 'https://', $site_url);
$assets_base = $site_url . '/wp-content/uploads/birdnet-assets';

// Helper functions
function en_img($num) {
    global $assets_base;
    $padded = str_pad($num, 2, '0', STR_PAD_LEFT);
    return $assets_base . '/birdnet-' . $padded . '.webp';
}

function en_vid($num) {
    global $assets_base;
    $padded = str_pad($num, 2, '0', STR_PAD_LEFT);
    return $assets_base . '/reel-' . $padded . '.mp4?v=h264';
}

function en_project_img($page, $num = 1) {
    global $assets_base;
    return $assets_base . '/project-p' . str_pad($page, 2, '0', STR_PAD_LEFT) . '-' . $num . '.webp';
}

$unsplash = array(
    'hero'    => $assets_base . '/fb-cover.webp',
    'hdpe'    => $assets_base . '/new-netting-balcony.jpg',
    'solar'   => $assets_base . '/new-solar-netting-2.jpg?v=newE',
    'spikes'  => $assets_base . '/new-spikes-gable.jpg?v=newE',
    'gel'     => $assets_base . '/new-gel-pestman.jpg',
);

// ===== ENGLISH HOME PAGE =====
$en_home_content = '
<!-- wp:columns -->
<div class="wp-block-columns hero-section">
<!-- wp:column -->
<div class="wp-block-column" style="background:transparent !important;border:none !important;">

<!-- wp:heading {"level":1} -->
<h1 class="wp-block-heading">Permanent Pigeon Control<br>Professional Installation &amp; Guaranteed Results</h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"fontSize":"medium"} -->
<p class="has-medium-font-size">Bird net installation across Thailand — Khon Kaen, Chiang Mai, Chonburi. HDPE nets, stainless steel spikes, bird gel. Licensed engineers supervise every project. Safety-certified technicians.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"fontSize":"medium"} -->
<p class="has-medium-font-size"><strong>Free On-Site Survey | 3-Year Warranty | Nationwide Service</strong></p>
<!-- /wp:paragraph -->

<!-- wp:buttons -->
<div class="wp-block-buttons">
<!-- wp:button {"backgroundColor":"vivid-green-cyan","className":"hero-cta-primary"} -->
<div class="wp-block-button hero-cta-primary"><a class="wp-block-button__link has-vivid-green-cyan-background-color has-background" href="/en/contact">Get a Free Quote</a></div>
<!-- /wp:button -->
<!-- wp:button {"className":"hero-cta-phone"} -->
<div class="wp-block-button hero-cta-phone"><a class="wp-block-button__link" href="tel:0629964994">Call Now 062-996-4994</a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->

<!-- wp:html -->
<p style="font-size:0.8rem;color:#888;margin-top:8px;">Free on-site assessment — no charges, no obligations</p>
<!-- /wp:html -->

<!-- wp:html -->
<div style="display:flex;gap:16px;flex-wrap:wrap;margin-top:16px;">
<div style="display:flex;align-items:center;gap:6px;background:#f0f7fa;padding:6px 14px;border-radius:20px;">
<span style="font-size:1.2rem;">&#x1F3E0;</span><span style="font-size:0.8rem;color:#1B4D5C;font-weight:600;">Residential</span>
</div>
<div style="display:flex;align-items:center;gap:6px;background:#f0f7fa;padding:6px 14px;border-radius:20px;">
<span style="font-size:1.2rem;">&#x1F3E2;</span><span style="font-size:0.8rem;color:#1B4D5C;font-weight:600;">Condo / Building</span>
</div>
<div style="display:flex;align-items:center;gap:6px;background:#f0f7fa;padding:6px 14px;border-radius:20px;">
<span style="font-size:1.2rem;">&#x1F3ED;</span><span style="font-size:0.8rem;color:#1B4D5C;font-weight:600;">Factory / Warehouse</span>
</div>
<div style="display:flex;align-items:center;gap:6px;background:#f0f7fa;padding:6px 14px;border-radius:20px;">
<span style="font-size:1.2rem;">&#x2600;&#xFE0F;</span><span style="font-size:0.8rem;color:#1B4D5C;font-weight:600;">Solar Panels</span>
</div>
</div>
<!-- /wp:html -->

</div>
<!-- /wp:column -->
<!-- wp:column -->
<div class="wp-block-column" style="background:transparent !important;border:none !important;">
<!-- wp:image {"sizeSlug":"large"} -->
<figure class="wp-block-image size-large"><img src="' . $unsplash['hero'] . '" alt="Bird net installation by Birds Go Away" style="border-radius:16px;width:100%;max-width:100%;height:auto;object-fit:contain;"/></figure>
<!-- /wp:image -->
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->

<!-- wp:html -->
<div style="background:#f8f9fa;padding:1.5rem 1rem;text-align:center;">
<p style="font-size:0.8rem;color:#888;margin:0 0 12px;text-transform:uppercase;letter-spacing:2px;font-weight:600;">Trusted By</p>
<div style="display:flex;flex-wrap:wrap;justify-content:center;align-items:center;gap:20px 32px;max-width:900px;margin:0 auto;">
<span style="font-size:0.85rem;color:#555;font-weight:600;white-space:nowrap;">&#x1F3DB;&#xFE0F; NACC Region 4</span>
<span style="font-size:0.85rem;color:#555;font-weight:600;white-space:nowrap;">&#x1F393; Khon Kaen University</span>
<span style="font-size:0.85rem;color:#555;font-weight:600;white-space:nowrap;">&#x1F3E5; Sirindhorn College</span>
<span style="font-size:0.85rem;color:#555;font-weight:600;white-space:nowrap;">&#x1F3E5; Sirindhorn Hospital</span>
<span style="font-size:0.85rem;color:#555;font-weight:600;white-space:nowrap;">&#x2693; Royal Thai Navy Dockyard</span>
<span style="font-size:0.85rem;color:#555;font-weight:600;white-space:nowrap;">&#x1F3E2; Metro Condo Khon Kaen</span>
</div>
<p style="font-size:0.75rem;color:#aaa;margin:10px 0 0;">Over 31+ projects for both government and private sectors</p>
</div>
<!-- /wp:html -->

<!-- wp:html -->
<div style="background:#fff;padding:3rem 1rem;" data-aos="fade-up">
<div style="max-width:900px;margin:0 auto;text-align:center;">
<h2 style="color:#1B4D5C;font-size:1.8rem;margin:0 0 0.5rem;">Common Problems Our Clients Face</h2>
<p style="color:#888;font-size:0.9rem;margin:0 0 2rem;">Experiencing any of these issues? We can help.</p>
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:20px;text-align:left;">

<div style="background:#fef2f2;border-radius:12px;padding:1.5rem;border-left:5px solid #dc2626;">
<div style="font-size:2rem;margin-bottom:8px;">&#x1F4A9;</div>
<h3 style="color:#dc2626;margin:0 0 8px;font-size:1rem;">Bird Droppings Everywhere</h3>
<p style="margin:0;font-size:0.88rem;color:#555;line-height:1.6;">Balconies, ledges, and walkways covered in bird droppings. Unpleasant odor, health hazards, and impossible to keep clean no matter how often you try.</p>
</div>

<div style="background:#fffbeb;border-radius:12px;padding:1.5rem;border-left:5px solid #d97706;">
<div style="font-size:2rem;margin-bottom:8px;">&#x1F3DA;&#xFE0F;</div>
<h3 style="color:#d97706;margin:0 0 8px;font-size:1rem;">Birds Nesting in Your Building</h3>
<p style="margin:0;font-size:0.88rem;color:#555;line-height:1.6;">Pigeons nesting under roofs, in AC units, and building gaps. Noise disturbance, feathers floating around, and clogged drainage.</p>
</div>

<div style="background:#f0fdf4;border-radius:12px;padding:1.5rem;border-left:5px solid #16a34a;">
<div style="font-size:2rem;margin-bottom:8px;">&#x1F3ED;</div>
<h3 style="color:#16a34a;margin:0 0 8px;font-size:1rem;">Contaminating Products &amp; Equipment</h3>
<p style="margin:0;font-size:0.88rem;color:#555;line-height:1.6;">Factories, warehouses — bird droppings contaminating products and machinery. Failing GMP/HACCP standards, customer complaints.</p>
</div>

</div>
<div style="margin-top:2rem;">
<a href="/en/contact" style="display:inline-block;background:#E8792E;color:#fff;padding:14px 36px;border-radius:8px;font-weight:600;text-decoration:none;font-size:0.95rem;box-shadow:0 4px 15px rgba(232,121,46,0.3);">&#x1F4F8; Send Photos for Free Assessment</a>
<p style="font-size:0.78rem;color:#999;margin-top:8px;">Send photos via LINE — get a preliminary quote instantly</p>
</div>
</div>
</div>
<!-- /wp:html -->

<!-- wp:separator {"className":"is-style-wide"} -->
<hr class="wp-block-separator has-alpha-channel-opacity is-style-wide"/>
<!-- /wp:separator -->

<!-- wp:heading {"textAlign":"center","level":2} -->
<h2 class="has-text-align-center wp-block-heading">Why Choose Us?</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">Recheck Building Co., Ltd. — Thailand\'s bird control specialists | Permanent pigeon solutions nationwide</p>
<!-- /wp:paragraph -->

<!-- wp:html -->
<div style="max-width:800px;margin:0 auto;display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:16px;padding:0 1rem;">

<div style="background:#f8f9fa;border-radius:12px;padding:1.2rem;border-left:4px solid #E8792E;">
<h3 style="color:#1B4D5C;margin:0 0 8px;font-size:1rem;">&#x1F6E1;&#xFE0F; Premium HDPE Material</h3>
<p style="margin:0;font-size:0.88rem;color:#555;">Industry-standard materials, weather-resistant, UV-protected. 5-7 year lifespan.</p>
</div>

<div style="background:#f8f9fa;border-radius:12px;padding:1.2rem;border-left:4px solid #E8792E;">
<h3 style="color:#1B4D5C;margin:0 0 8px;font-size:1rem;">&#x1F9BA; Safety-Certified Team</h3>
<p style="margin:0;font-size:0.88rem;color:#555;">All technicians are height-work certified (SAFESIRI). Licensed engineers supervise every site.</p>
</div>

<div style="background:#f8f9fa;border-radius:12px;padding:1.2rem;border-left:4px solid #E8792E;">
<h3 style="color:#1B4D5C;margin:0 0 8px;font-size:1rem;">&#x1F4CB; Free On-Site Assessment</h3>
<p style="margin:0;font-size:0.88rem;color:#555;">Our engineers visit your site for free. We measure, analyze, and provide a quote within 1-2 days. No hidden fees.</p>
</div>

<div style="background:#f8f9fa;border-radius:12px;padding:1.2rem;border-left:4px solid #E8792E;">
<h3 style="color:#1B4D5C;margin:0 0 8px;font-size:1rem;">&#x1F54A;&#xFE0F; 100% Humane — No Harm to Birds</h3>
<p style="margin:0;font-size:0.88rem;color:#555;">Peaceful deterrent methods only. We simply prevent birds from entering the area. Safe for humans, pets, and wildlife.</p>
</div>

<div style="background:#f8f9fa;border-radius:12px;padding:1.2rem;border-left:4px solid #E8792E;">
<h3 style="color:#1B4D5C;margin:0 0 8px;font-size:1rem;">&#x1F6E1;&#xFE0F; 3-Year Warranty with Free Repairs</h3>
<p style="margin:0;font-size:0.88rem;color:#555;">Quality guaranteed for 3 years. If any issues arise, our team fixes them for free throughout the warranty period.</p>
</div>

<div style="background:#f8f9fa;border-radius:12px;padding:1.2rem;border-left:4px solid #E8792E;">
<h3 style="color:#1B4D5C;margin:0 0 8px;font-size:1rem;">&#x26A1; Fast Installation, Minimal Disruption</h3>
<p style="margin:0;font-size:0.88rem;color:#555;">Condo balconies done in 2-4 hours. Large factories completed in 1-3 days. Clean-up included after every job.</p>
</div>

</div>
<!-- /wp:html -->

<!-- wp:html -->
<div style="max-width:700px;margin:2rem auto;display:flex;justify-content:center;gap:24px;flex-wrap:wrap;padding:0 1rem;">
<div style="text-align:center;flex:1;min-width:140px;">
<div style="font-size:2.5rem;margin-bottom:4px;">&#x1F6E1;&#xFE0F;</div>
<p style="font-weight:700;color:#1B4D5C;margin:0;font-size:0.95rem;">3-Year Warranty</p>
<p style="color:#666;font-size:0.78rem;margin:2px 0 0;">Free repairs throughout</p>
</div>
<div style="text-align:center;flex:1;min-width:140px;">
<div style="font-size:2.5rem;margin-bottom:4px;">&#x1F9BA;</div>
<p style="font-weight:700;color:#1B4D5C;margin:0;font-size:0.95rem;">Safety Certified</p>
<p style="color:#666;font-size:0.78rem;margin:2px 0 0;">SAFESIRI Height-Work License</p>
</div>
<div style="text-align:center;flex:1;min-width:140px;">
<div style="font-size:2.5rem;margin-bottom:4px;">&#x1F4CB;</div>
<p style="font-weight:700;color:#1B4D5C;margin:0;font-size:0.95rem;">Free Assessment</p>
<p style="color:#666;font-size:0.78rem;margin:2px 0 0;">No hidden charges</p>
</div>
<div style="text-align:center;flex:1;min-width:140px;">
<div style="font-size:2.5rem;margin-bottom:4px;">&#x1F3D7;&#xFE0F;</div>
<p style="font-weight:700;color:#1B4D5C;margin:0;font-size:0.95rem;">Licensed Engineers</p>
<p style="color:#666;font-size:0.78rem;margin:2px 0 0;">On-site supervision</p>
</div>
</div>
<!-- /wp:html -->

<!-- wp:separator {"className":"is-style-wide"} -->
<hr class="wp-block-separator has-alpha-channel-opacity is-style-wide"/>
<!-- /wp:separator -->

<!-- wp:html -->
<div style="background:linear-gradient(135deg,#1B4D5C,#2a6a7c);padding:3rem 1rem;text-align:center;" data-aos="fade-up">
<div style="max-width:900px;margin:0 auto;">
<h2 style="color:#fff;font-size:1.8rem;margin:0 0 0.5rem;">&#x26A0;&#xFE0F; High-Risk Work — Leave It to the Pros</h2>
<p style="color:#e0e0e0;font-size:0.95rem;margin:0 0 2rem;">Bird net installation requires expertise, safety equipment, and experience — don\'t risk doing it yourself.</p>
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:20px;text-align:left;">
<div style="background:rgba(255,255,255,0.1);border-radius:12px;padding:1.5rem;border:1px solid rgba(255,255,255,0.15);">
<div style="font-size:2rem;margin-bottom:8px;">&#x1F6A8;</div>
<h3 style="color:#fff;margin:0 0 8px;font-size:1rem;">Dangerous Heights — Life-Threatening</h3>
<p style="margin:0;font-size:0.85rem;color:#d0d0d0;line-height:1.6;">Installing nets on high-rise buildings requires full safety equipment — harnesses, scaffolding, boom lifts. Our team is fully trained with SAFESIRI rope access certification.</p>
</div>
<div style="background:rgba(255,255,255,0.1);border-radius:12px;padding:1.5rem;border:1px solid rgba(255,255,255,0.15);">
<div style="font-size:2rem;margin-bottom:8px;">&#x1F9F0;</div>
<h3 style="color:#fff;margin:0 0 8px;font-size:1rem;">DIY Installation Doesn\'t Work</h3>
<p style="margin:0;font-size:0.85rem;color:#d0d0d0;line-height:1.6;">Nets that aren\'t tight enough still let birds in. Spikes with wrong spacing don\'t deter perching. Professional techniques, engineer design, and precise measurement are needed for 100% effectiveness.</p>
</div>
<div style="background:rgba(255,255,255,0.1);border-radius:12px;padding:1.5rem;border:1px solid rgba(255,255,255,0.15);">
<div style="font-size:2rem;margin-bottom:8px;">&#x1F4B0;</div>
<h3 style="color:#fff;margin:0 0 8px;font-size:1rem;">Hiring Amateurs Costs More</h3>
<p style="margin:0;font-size:0.85rem;color:#d0d0d0;line-height:1.6;">Inexperienced contractors use cheap materials and wrong methods. Nets fall off, birds return, you pay again. Hire professionals once — save money with our 3-year warranty.</p>
</div>
</div>
<div style="margin-top:2rem;">
<a href="/en/contact" style="display:inline-block;background:#E8792E;color:#fff;padding:14px 36px;border-radius:8px;font-weight:700;text-decoration:none;font-size:1rem;box-shadow:0 4px 15px rgba(232,121,46,0.4);">&#x1F4DE; Free Consultation — Let the Experts Handle It</a>
</div>
</div>
</div>
<!-- /wp:html -->

<!-- wp:separator {"className":"is-style-wide"} -->
<hr class="wp-block-separator has-alpha-channel-opacity is-style-wide"/>
<!-- /wp:separator -->

<!-- wp:heading {"textAlign":"center","level":2} -->
<h2 class="has-text-align-center wp-block-heading">Which Solution Is Right for You?</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">Different problems require different solutions — let us help you choose.</p>
<!-- /wp:paragraph -->

<!-- wp:html -->
<div style="max-width:800px;margin:0 auto;display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:16px;padding:0 1rem;">

<div style="background:#fff8f3;border-radius:12px;padding:1.2rem;border:2px solid #E8792E;text-align:center;">
<div style="font-size:2rem;margin-bottom:8px;">&#x1F426;</div>
<h4 style="color:#E8792E;margin:0 0 6px;font-size:1rem;">Birds on Railings / Ledges</h4>
<p style="margin:0 0 8px;font-size:0.85rem;color:#555;">Recommended: <strong>Stainless Steel Bird Spikes</strong></p>
<p style="margin:0;font-size:0.78rem;color:#888;">Easy to install, affordable, lasts a lifetime</p>
</div>

<div style="background:#f3f9ff;border-radius:12px;padding:1.2rem;border:2px solid #1B4D5C;text-align:center;">
<div style="font-size:2rem;margin-bottom:8px;">&#x1F3E2;</div>
<h4 style="color:#1B4D5C;margin:0 0 6px;font-size:1rem;">Nesting Under Roofs / Balconies</h4>
<p style="margin:0 0 8px;font-size:0.85rem;color:#555;">Recommended: <strong>HDPE Bird Net</strong></p>
<p style="margin:0;font-size:0.78rem;color:#888;">Covers large areas, nearly invisible, 5-7 year lifespan</p>
</div>

<div style="background:#f9fff3;border-radius:12px;padding:1.2rem;border:2px solid #06C755;text-align:center;">
<div style="font-size:2rem;margin-bottom:8px;">&#x2728;</div>
<h4 style="color:#06C755;margin:0 0 6px;font-size:1rem;">Aesthetic-Sensitive Areas</h4>
<p style="margin:0 0 8px;font-size:0.85rem;color:#555;">Recommended: <strong>Bird Repellent Gel</strong></p>
<p style="margin:0;font-size:0.78rem;color:#888;">Invisible from outside, preserves aesthetics, 100% safe</p>
</div>

</div>
<!-- /wp:html -->

<!-- wp:separator {"className":"is-style-wide"} -->
<hr class="wp-block-separator has-alpha-channel-opacity is-style-wide"/>
<!-- /wp:separator -->

<!-- wp:heading {"textAlign":"center","level":2} -->
<h2 class="has-text-align-center wp-block-heading">Our Services</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">We offer 4 comprehensive bird control solutions to suit every situation.</p>
<!-- /wp:paragraph -->

<!-- wp:columns -->
<div class="wp-block-columns">
<!-- wp:column -->
<div class="wp-block-column" style="text-align:center;">
<!-- wp:image {"sizeSlug":"medium"} -->
<figure class="wp-block-image size-medium"><img src="' . $unsplash['hdpe'] . '" alt="HDPE Bird Net" style="border-radius:12px;object-fit:cover;aspect-ratio:4/3;width:100%;height:200px;" loading="lazy"/></figure>
<!-- /wp:image -->
<h3 class="wp-block-heading">HDPE Bird Net</h3>
<p>High-quality HDPE netting with 5-7 year lifespan. Strong, durable, resistant to pulling force, impact, and chemicals. Suitable for all areas.</p>
</div>
<!-- /wp:column -->
<!-- wp:column -->
<div class="wp-block-column" style="text-align:center;">
<h3 class="wp-block-heading">Solar Panel Bird Net</h3>
<p>No-drill clip system extends solar panel lifespan. Eliminates bird nesting, dirt buildup under panels, and wire damage.</p>
</div>
<!-- /wp:column -->
<!-- wp:column -->
<div class="wp-block-column" style="text-align:center;">
<!-- wp:image {"sizeSlug":"medium"} -->
<figure class="wp-block-image size-medium"><img src="' . $unsplash['spikes'] . '" alt="Stainless Steel Bird Spikes" style="border-radius:12px;object-fit:cover;aspect-ratio:4/3;width:100%;height:200px;" loading="lazy"/></figure>
<!-- /wp:image -->
<h3 class="wp-block-heading">Bird Spikes</h3>
<p>Stainless steel bird spikes prevent perching. Weather-resistant, easy to install, affordable. Ideal for window ledges and railings.</p>
</div>
<!-- /wp:column -->
<!-- wp:column -->
<div class="wp-block-column" style="text-align:center;">
<!-- wp:image {"sizeSlug":"medium"} -->
<figure class="wp-block-image size-medium"><img src="' . $unsplash['gel'] . '" alt="Bird Repellent Gel" style="border-radius:12px;object-fit:cover;aspect-ratio:4/3;width:100%;height:200px;" loading="lazy"/></figure>
<!-- /wp:image -->
<h3 class="wp-block-heading">Bird Repellent Gel</h3>
<p>Special formula gel, non-toxic, safe for humans and animals. Works on all surfaces, leaves no residue.</p>
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons">
<!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link" href="/en/services">View All Services &rarr;</a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->

<!-- wp:separator {"className":"is-style-wide"} -->
<hr class="wp-block-separator has-alpha-channel-opacity is-style-wide"/>
<!-- /wp:separator -->

<!-- wp:heading {"textAlign":"center","level":2} -->
<h2 class="has-text-align-center wp-block-heading">How We Work</h2>
<!-- /wp:heading -->

<!-- wp:columns -->
<div class="wp-block-columns">
<!-- wp:column {"width":"25%"} -->
<div class="wp-block-column" style="flex-basis:25%;text-align:center;">
<h3 class="wp-block-heading">1. Site Survey</h3>
<p>Our engineer visits your site, analyzes the problem, measures the area, and recommends the best solution.</p>
</div>
<!-- /wp:column -->
<!-- wp:column {"width":"25%"} -->
<div class="wp-block-column" style="flex-basis:25%;text-align:center;">
<h3 class="wp-block-heading">2. Quotation</h3>
<p>We provide a detailed quote specifying materials, pricing, and timeline. Free consultation included.</p>
</div>
<!-- /wp:column -->
<!-- wp:column {"width":"25%"} -->
<div class="wp-block-column" style="flex-basis:25%;text-align:center;">
<h3 class="wp-block-heading">3. Installation</h3>
<p>Our professional team installs with complete equipment. Fast, clean, and neat workmanship.</p>
</div>
<!-- /wp:column -->
<!-- wp:column {"width":"25%"} -->
<div class="wp-block-column" style="flex-basis:25%;text-align:center;">
<h3 class="wp-block-heading">4. Handover</h3>
<p>Quality inspection, handover with 3-year warranty certificate. After-sales service included.</p>
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->

<!-- wp:separator {"className":"is-style-wide"} -->
<hr class="wp-block-separator has-alpha-channel-opacity is-style-wide"/>
<!-- /wp:separator -->

<!-- wp:heading {"textAlign":"center","level":2} -->
<h2 class="has-text-align-center wp-block-heading">Before vs After Installation</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">Real results from our projects — from bird nuisance to clean and orderly spaces.</p>
<!-- /wp:paragraph -->

<!-- wp:html -->
<div style="max-width:800px;margin:0 auto;padding:0 1rem;">
<div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:1.5rem;">
<div style="text-align:center;">
<div style="background:#fef2f2;border-radius:12px;overflow:hidden;border:2px solid #ef4444;">
<h4 style="color:#dc2626;margin:0;padding:8px;font-size:0.95rem;background:#fef2f2;">&#x274C; Before Installation</h4>
<img src="' . $assets_base . '/before-install.webp" alt="Condo balcony before bird net installation - covered in bird droppings" style="width:100%;height:220px;object-fit:cover;" loading="lazy">
<p style="font-size:0.8rem;color:#555;margin:0;padding:8px;">Balcony covered in bird droppings, foul odor, health hazard</p>
</div>
</div>
<div style="text-align:center;">
<div style="background:#f0fdf4;border-radius:12px;overflow:hidden;border:2px solid #22c55e;">
<h4 style="color:#16a34a;margin:0;padding:8px;font-size:0.95rem;background:#f0fdf4;">&#x2705; After Installation</h4>
<img src="' . $assets_base . '/after-install.webp" alt="Condo balcony after bird net installation - clean and bird-free" style="width:100%;height:220px;object-fit:cover;" loading="lazy">
<p style="font-size:0.8rem;color:#555;margin:0;padding:8px;">Clean, bird-free, hygienic. 3 years later — still no birds.</p>
</div>
</div>
</div>
<div style="text-align:center;margin-top:1rem;">
<a href="/en/portfolio" style="color:#E8792E;font-weight:600;text-decoration:none;font-size:0.95rem;">View All 31+ Projects &rarr;</a>
</div>
</div>
<!-- /wp:html -->

<!-- wp:separator {"className":"is-style-wide"} -->
<hr class="wp-block-separator has-alpha-channel-opacity is-style-wide"/>
<!-- /wp:separator -->

<!-- wp:heading {"textAlign":"center","level":2} -->
<h2 class="has-text-align-center wp-block-heading">Installation Warranty</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">We stand behind our work — every project is warranted. If birds return during warranty, we fix it for free.</p>
<!-- /wp:paragraph -->

<!-- wp:html -->
<div style="max-width:800px;margin:0 auto;display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px;padding:0 1rem;">
<div style="background:#f0fdf4;border-radius:12px;padding:1.5rem;text-align:center;border:2px solid #22c55e;">
<div style="font-size:2.5rem;">&#x1F6E1;&#xFE0F;</div>
<h4 style="color:#166534;margin:8px 0 4px;font-size:1rem;">Installation Warranty</h4>
<p style="font-size:2rem;font-weight:800;color:#22c55e;margin:0;">3 Years</p>
<p style="font-size:0.8rem;color:#555;margin:4px 0 0;">Loose, torn, or damaged?<br>We fix it for free.</p>
</div>
<div style="background:#eff6ff;border-radius:12px;padding:1.5rem;text-align:center;border:2px solid #3b82f6;">
<div style="font-size:2.5rem;">&#x1F527;</div>
<h4 style="color:#1e40af;margin:8px 0 4px;font-size:1rem;">Rust-Free Materials</h4>
<p style="font-size:2rem;font-weight:800;color:#3b82f6;margin:0;">5+ Years</p>
<p style="font-size:0.8rem;color:#555;margin:4px 0 0;">SUS304 Stainless Steel + HDPE<br>All-weather resistant</p>
</div>
<div style="background:#fef9f0;border-radius:12px;padding:1.5rem;text-align:center;border:2px solid #E8792E;">
<div style="font-size:2.5rem;">&#x1F426;</div>
<h4 style="color:#9a3412;margin:8px 0 4px;font-size:1rem;">Birds Return?</h4>
<p style="font-size:2rem;font-weight:800;color:#E8792E;margin:0;">Free Fix</p>
<p style="font-size:0.8rem;color:#555;margin:4px 0 0;">We inspect and repair<br>at no additional cost</p>
</div>
</div>
<!-- /wp:html -->

<!-- wp:separator {"className":"is-style-wide"} -->
<hr class="wp-block-separator has-alpha-channel-opacity is-style-wide"/>
<!-- /wp:separator -->

<!-- wp:heading {"textAlign":"center","level":2} -->
<h2 class="has-text-align-center wp-block-heading">FAQ</h2>
<!-- /wp:heading -->

<!-- wp:html -->
<div class="faq-item">
<h4>How long does HDPE bird netting last?</h4>
<p>Our HDPE bird nets have a lifespan of approximately 5-7 years depending on environmental conditions. They are UV-resistant, strong, and durable.</p>
</div>
<div class="faq-item">
<h4>How long does installation take?</h4>
<p>Duration depends on the area size. Typically, a single condo balcony takes about 2-4 hours. Larger factory or building projects take 1-3 days.</p>
</div>
<div class="faq-item">
<h4>How much does bird net installation cost?</h4>
<p>Pricing depends on area size, height, and complexity. Starting from 350 THB/sq.m. You can request a free quote with no obligation.</p>
</div>
<div class="faq-item">
<h4>Is there a warranty after installation?</h4>
<p>Yes, we provide a 3-year installation warranty. If any issues arise, our team fixes them for free under warranty terms.</p>
</div>
<div class="faq-item">
<h4>Will birds be harmed?</h4>
<p>Absolutely not. Our methods are 100% humane — we simply prevent birds from entering the area. No killing, no harm. Safe for birds, humans, and pets.</p>
</div>
<div class="faq-item">
<h4>What areas do you serve?</h4>
<p>We serve all of Thailand, especially Isan region: Khon Kaen, Udon Thani, Nakhon Ratchasima, Maha Sarakham / Chiang Mai / Chonburi and Bangkok metropolitan area. Headquarters in Khon Kaen.</p>
</div>
<!-- /wp:html -->

<!-- wp:html -->
<div style="max-width:600px;margin:1.5rem auto;background:linear-gradient(135deg,#1B4D5C,#2a6a7c);border-radius:12px;padding:1.2rem 1.5rem;text-align:center;color:white;">
<p style="margin:0;font-size:1.3rem;font-weight:700;">&#x1F4B0; Starting from just 350 THB/sq.m.</p>
<p style="margin:6px 0 0;font-size:0.95rem;color:#e0e0e0;">Free on-site assessment and cost estimation — no charges</p>
</div>
<!-- /wp:html -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons">
<!-- wp:button {"backgroundColor":"vivid-green-cyan","className":"hero-cta-primary"} -->
<div class="wp-block-button hero-cta-primary"><a class="wp-block-button__link has-vivid-green-cyan-background-color has-background" href="/en/contact">Get a Free Quote Today</a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->
';

// ===== ENGLISH SERVICES PAGE =====
$en_services_content = '
<!-- wp:heading {"level":1} -->
<h1 class="wp-block-heading">Our Bird Control Services</h1>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Birds Go Away offers comprehensive bird control solutions for residential, commercial, and industrial properties across Thailand. Our solutions are humane, effective, and backed by a 3-year warranty.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">1. HDPE Bird Net</h2>
<!-- /wp:heading -->
<!-- wp:image {"sizeSlug":"large"} -->
<figure class="wp-block-image size-large"><img src="' . $unsplash['hdpe'] . '" alt="HDPE Bird Net Installation" style="border-radius:12px;max-height:400px;object-fit:cover;width:100%;" loading="lazy"/></figure>
<!-- /wp:image -->
<!-- wp:paragraph -->
<p>Our premium HDPE (High-Density Polyethylene) netting is the most popular solution for large areas. Key features:</p>
<!-- /wp:paragraph -->
<!-- wp:list -->
<ul><li><strong>Material:</strong> UV-stabilized HDPE, export-grade quality</li><li><strong>Lifespan:</strong> 5-7 years depending on conditions</li><li><strong>Visibility:</strong> Nearly invisible from a distance — blends with the building</li><li><strong>Applications:</strong> Balconies, parking structures, warehouses, factories, building facades</li><li><strong>Strength:</strong> Resistant to pulling force, impact, and chemicals</li></ul>
<!-- /wp:list -->
<!-- wp:paragraph -->
<p><strong>Starting from 350 THB/sq.m.</strong> — includes materials, installation, and 3-year warranty.</p>
<!-- /wp:paragraph -->

<!-- wp:separator {"className":"is-style-wide"} -->
<hr class="wp-block-separator has-alpha-channel-opacity is-style-wide"/>
<!-- /wp:separator -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">2. Stainless Steel Bird Spikes</h2>
<!-- /wp:heading -->
<!-- wp:image {"sizeSlug":"large"} -->
<figure class="wp-block-image size-large"><img src="' . $unsplash['spikes'] . '" alt="Stainless Steel Bird Spikes Installation" style="border-radius:12px;max-height:400px;object-fit:cover;width:100%;" loading="lazy"/></figure>
<!-- /wp:image -->
<!-- wp:paragraph -->
<p>Stainless steel bird spikes are ideal for ledges, railings, and narrow surfaces where birds perch. Key features:</p>
<!-- /wp:paragraph -->
<!-- wp:list -->
<ul><li><strong>Material:</strong> SUS304 Stainless Steel — rust-proof</li><li><strong>Lifespan:</strong> 5+ years, weather-resistant</li><li><strong>Installation:</strong> Quick and easy, minimal disruption</li><li><strong>Applications:</strong> Window ledges, railings, signage, AC units, building edges</li><li><strong>Humane:</strong> Birds simply avoid the area — no harm caused</li></ul>
<!-- /wp:list -->

<!-- wp:separator {"className":"is-style-wide"} -->
<hr class="wp-block-separator has-alpha-channel-opacity is-style-wide"/>
<!-- /wp:separator -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">3. Bird Repellent Gel</h2>
<!-- /wp:heading -->
<!-- wp:image {"sizeSlug":"large"} -->
<figure class="wp-block-image size-large"><img src="' . $unsplash['gel'] . '" alt="Bird Repellent Gel Application" style="border-radius:12px;max-height:400px;object-fit:cover;width:100%;" loading="lazy"/></figure>
<!-- /wp:image -->
<!-- wp:paragraph -->
<p>Invisible bird repellent gel is perfect for areas where aesthetics matter most. Key features:</p>
<!-- /wp:paragraph -->
<!-- wp:list -->
<ul><li><strong>Visibility:</strong> Completely invisible — preserves building aesthetics</li><li><strong>Safety:</strong> Non-toxic formula, safe for humans and pets</li><li><strong>Applications:</strong> Heritage buildings, luxury condos, shopfronts, signage</li><li><strong>Effectiveness:</strong> Creates an uncomfortable surface for birds without harm</li></ul>
<!-- /wp:list -->

<!-- wp:separator {"className":"is-style-wide"} -->
<hr class="wp-block-separator has-alpha-channel-opacity is-style-wide"/>
<!-- /wp:separator -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">4. Solar Panel Bird Net</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Protect your solar investment from bird damage. Our clip-on guard system requires no drilling into panels. Key features:</p>
<!-- /wp:paragraph -->
<!-- wp:list -->
<ul><li><strong>No-Drill System:</strong> Clip-on design — no panel warranty voiding</li><li><strong>Protection:</strong> Prevents nesting, droppings, and wire chewing under panels</li><li><strong>Performance:</strong> Does not affect solar panel efficiency</li><li><strong>Safety:</strong> Prevents fire hazards from nesting materials near electrical components</li></ul>
<!-- /wp:list -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons">
<!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link" href="/en/contact">Get a Free Quote &rarr;</a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->
';

// ===== ENGLISH ABOUT PAGE =====
$en_about_content = '
<!-- wp:heading {"level":1} -->
<h1 class="wp-block-heading">About Birds Go Away</h1>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><strong>Recheck Building Co., Ltd.</strong> (operating as <strong>Birds Go Away</strong>) is Thailand\'s professional bird control company specializing in humane bird deterrent solutions for residential, commercial, and industrial properties.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>With over 31 completed projects across Thailand, we serve both government agencies and private sector clients. Our headquarters is in Khon Kaen, with operations covering Chiang Mai, Chonburi, and nationwide.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Our Expertise</h2>
<!-- /wp:heading -->

<!-- wp:list -->
<ul>
<li><strong>Licensed Engineers:</strong> Every project supervised by a registered engineer</li>
<li><strong>Safety Certified:</strong> All technicians hold SAFESIRI height-work certification</li>
<li><strong>Complete Equipment:</strong> Scaffolding, boom lifts, safety harnesses, and professional tools</li>
<li><strong>3-Year Warranty:</strong> Quality guaranteed on every installation</li>
<li><strong>100% Humane:</strong> We never harm birds — peaceful deterrent methods only</li>
</ul>
<!-- /wp:list -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Trusted Clients</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>We are proud to serve leading organizations including:</p>
<!-- /wp:paragraph -->

<!-- wp:list -->
<ul>
<li>National Anti-Corruption Commission (NACC) Region 4</li>
<li>Khon Kaen University</li>
<li>Sirindhorn College of Public Health</li>
<li>Sirindhorn Hospital</li>
<li>Royal Thai Navy Dockyard Department</li>
<li>Metro Condo Khon Kaen</li>
<li>ESCENT Condo Khon Kaen</li>
</ul>
<!-- /wp:list -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Company Information</h2>
<!-- /wp:heading -->

<!-- wp:html -->
<div style="max-width:600px;background:#f8f9fa;border-radius:12px;padding:1.5rem;margin:1rem 0;">
<p style="margin:0 0 8px;"><strong>Company:</strong> Recheck Building Co., Ltd.</p>
<p style="margin:0 0 8px;"><strong>Brand:</strong> Birds Go Away</p>
<p style="margin:0 0 8px;"><strong>Service:</strong> Bird control solutions — nets, spikes, gel, solar guards</p>
<p style="margin:0 0 8px;"><strong>Headquarters:</strong> Khon Kaen, Thailand</p>
<p style="margin:0 0 8px;"><strong>Email:</strong> <a href="mailto:birdsgoaway.th@gmail.com">birdsgoaway.th@gmail.com</a></p>
<p style="margin:0;"><strong>Phone:</strong> <a href="tel:0629964994">062-996-4994</a> (Khon Kaen) | <a href="tel:0936415623">093-641-5623</a> (Chiang Mai) | <a href="tel:0956292488">095-629-2488</a> (Chonburi)</p>
</div>
<!-- /wp:html -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons">
<!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link" href="/en/contact">Contact Us &rarr;</a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->
';

// ===== ENGLISH CONTACT PAGE =====
$en_contact_content = '
<!-- wp:heading {"level":1} -->
<h1 class="wp-block-heading">Contact Us</h1>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Ready to solve your bird problem? Contact us for a free on-site assessment and quotation.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Our Offices</h2>
<!-- /wp:heading -->

<!-- wp:columns -->
<div class="wp-block-columns">
<!-- wp:column -->
<div class="wp-block-column" style="text-align:center;">
<h3 class="wp-block-heading">Khon Kaen (HQ)</h3>
<p><a href="tel:0629964994"><strong>062-996-4994</strong></a></p>
<p style="font-size:0.85rem;color:#666;">Mon-Sat 08:00-18:00</p>
</div>
<!-- /wp:column -->
<!-- wp:column -->
<div class="wp-block-column" style="text-align:center;">
<h3 class="wp-block-heading">Chiang Mai</h3>
<p><a href="tel:0936415623"><strong>093-641-5623</strong></a></p>
<p style="font-size:0.85rem;color:#666;">Mon-Sat 08:00-18:00</p>
</div>
<!-- /wp:column -->
<!-- wp:column -->
<div class="wp-block-column" style="text-align:center;">
<h3 class="wp-block-heading">Chonburi</h3>
<p><a href="tel:0956292488"><strong>095-629-2488</strong></a></p>
<p style="font-size:0.85rem;color:#666;">Mon-Sat 08:00-18:00</p>
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Quick Contact</h2>
<!-- /wp:heading -->

<!-- wp:html -->
<div style="max-width:400px;margin:1rem auto;display:flex;flex-direction:column;gap:12px;text-align:center;">
<a href="https://line.me/ti/p/~phanupong_oil" target="_blank" rel="noopener" style="display:block;background:#06C755;color:#fff;padding:14px 20px;border-radius:8px;text-decoration:none;font-weight:700;font-size:1rem;">LINE: @phanupong_oil</a>
<a href="mailto:birdsgoaway.th@gmail.com" style="display:block;background:#E8792E;color:#fff;padding:14px 20px;border-radius:8px;text-decoration:none;font-weight:700;font-size:1rem;">Email: birdsgoaway.th@gmail.com</a>
<a href="https://www.facebook.com/birdsgoaway" target="_blank" rel="noopener" style="display:block;background:#1877f2;color:#fff;padding:14px 20px;border-radius:8px;text-decoration:none;font-weight:700;font-size:1rem;">Facebook: Birds Go Away</a>
</div>
<!-- /wp:html -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Request a Quote</h2>
<!-- /wp:heading -->

<!-- wp:html -->
<div style="max-width:500px;margin:0 auto;background:#f8f9fa;border-radius:12px;padding:2rem;text-align:center;">
<p style="font-size:1.1rem;color:#1B4D5C;font-weight:600;margin:0 0 8px;">Send us photos of your site</p>
<p style="font-size:0.9rem;color:#555;margin:0 0 16px;">Take a photo of the bird problem area and send it via LINE. We will provide a preliminary quote within 30 minutes.</p>
<a href="https://line.me/ti/p/~phanupong_oil" target="_blank" rel="noopener" style="display:inline-block;background:#06C755;color:#fff;padding:14px 32px;border-radius:8px;text-decoration:none;font-weight:700;font-size:1rem;">Send Photos via LINE</a>
<p style="font-size:0.75rem;color:#888;margin:12px 0 0;">Response within 30 minutes, daily 08:00-20:00</p>
</div>
<!-- /wp:html -->

<!-- wp:html -->
<p style="font-size:0.78rem;color:#aaa;margin-top:1.5rem;text-align:center;">&#x1F512; Your personal information is protected under Thailand\'s Personal Data Protection Act (PDPA) B.E. 2562. We do not share or sell your data to third parties.</p>
<!-- /wp:html -->
';

// ===== ENGLISH FAQ PAGE =====
$en_faq_content = '
<!-- wp:heading {"level":1} -->
<h1 class="wp-block-heading">Frequently Asked Questions</h1>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Find answers to common questions about our bird control services.</p>
<!-- /wp:paragraph -->

<!-- wp:html -->
<div class="faq-item">
<h4>How long does HDPE bird netting last?</h4>
<p>Our HDPE bird nets have a lifespan of approximately 5-7 years depending on environmental conditions. The material is UV-resistant, strong, and withstands all weather conditions.</p>
</div>

<div class="faq-item">
<h4>How long does installation take?</h4>
<p>Duration depends on the area size. A single condo balcony typically takes 2-4 hours. Larger factory or building projects take 1-3 days. We clean up after every job.</p>
</div>

<div class="faq-item">
<h4>How much does bird net installation cost?</h4>
<p>Pricing starts from 350 THB/sq.m. and depends on area size, height, and complexity. You can request a free on-site assessment and quotation with no obligation.</p>
</div>

<div class="faq-item">
<h4>Is there a warranty?</h4>
<p>Yes, we provide a 3-year installation warranty. If any issues arise (loose, torn, or damaged nets), our team fixes them for free under warranty terms.</p>
</div>

<div class="faq-item">
<h4>Will birds be harmed?</h4>
<p>Absolutely not. All our methods are 100% humane. We simply prevent birds from entering the area. No killing, no harm. Safe for birds, humans, and pets.</p>
</div>

<div class="faq-item">
<h4>What areas do you serve?</h4>
<p>We serve all of Thailand, especially the Isan region (Khon Kaen, Udon Thani, Nakhon Ratchasima, Maha Sarakham), Chiang Mai, Chonburi, and the Bangkok metropolitan area. Our headquarters is in Khon Kaen.</p>
</div>

<div class="faq-item">
<h4>What types of birds do you handle?</h4>
<p>We primarily deal with pigeons, mynas, and sparrows — the most common pest birds in Thailand. Our solutions are effective against all species without causing harm.</p>
</div>

<div class="faq-item">
<h4>Can bird nets be installed on high-rise buildings?</h4>
<p>Yes! Our team is fully certified for height work (SAFESIRI rope access certification). We have scaffolding, boom lifts, and all required safety equipment for high-rise installations.</p>
</div>

<div class="faq-item">
<h4>Do the nets block sunlight or airflow?</h4>
<p>No. Our HDPE nets are nearly invisible and do not significantly block sunlight or airflow. They are designed to blend with the building aesthetics.</p>
</div>

<div class="faq-item">
<h4>What payment methods do you accept?</h4>
<p>We accept bank transfer and cash. A 50% deposit is required before installation, with the remainder due upon completion and inspection.</p>
</div>

<div class="faq-item">
<h4>Can I see your previous work?</h4>
<p>Yes! Visit our <a href="/en/portfolio">Portfolio page</a> to see 31+ completed projects. We also share project updates on our <a href="https://www.facebook.com/birdsgoaway" target="_blank" rel="noopener">Facebook page</a>.</p>
</div>
<!-- /wp:html -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons">
<!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link" href="/en/contact">Still Have Questions? Contact Us &rarr;</a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->
';

// ===== ENGLISH PORTFOLIO PAGE (Flipbook) =====
$en_projects = array(
    // Government Buildings
    array('name' => 'NACC Region 4 Office', 'location' => 'Khon Kaen', 'page' => 7, 'cat' => 'Government', 'desc' => 'HDPE bird net installation on 5-story government building — solved pigeon droppings on balconies'),
    array('name' => 'Royal Thai Navy Dockyard', 'location' => 'Chonburi', 'page' => 9, 'cat' => 'Government', 'desc' => 'Bird net for military building — rope access team with full safety equipment'),
    array('name' => 'KKU Male Dormitory 7', 'location' => 'Khon Kaen', 'page' => 10, 'cat' => 'Government', 'desc' => 'HDPE bird net on 6-story dormitory balconies — permanent nesting prevention'),
    array('name' => 'KKU Male Dormitory 8', 'location' => 'Khon Kaen', 'page' => 11, 'cat' => 'Government', 'desc' => 'Bird net on balconies and light wells — blocked bird entry points'),
    array('name' => 'KKU Dormitory 21-23', 'location' => 'Khon Kaen', 'page' => 12, 'cat' => 'Government', 'desc' => 'Bird net across 3 buildings — over 300 sqm total coverage'),
    array('name' => 'Sirindhorn College (10 floors)', 'location' => 'Khon Kaen', 'page' => 13, 'cat' => 'Government', 'desc' => 'Bird net on 10-story building — professional rope access team'),
    array('name' => 'Khon Kaen Hospital Staff Housing', 'location' => 'Khon Kaen', 'page' => 16, 'cat' => 'Government', 'desc' => 'HDPE bird net on medical staff dormitory — hygiene & sanitation priority'),
    array('name' => 'Siripak Building, Treasury Office', 'location' => 'Khon Kaen', 'page' => 18, 'cat' => 'Government', 'desc' => 'Stainless steel 304 bird spikes on window ledges'),
    array('name' => 'Khon Kaen Hospital Nurse Dorm', 'location' => 'Khon Kaen', 'page' => 19, 'cat' => 'Government', 'desc' => 'Bird net on balconies — protecting healthcare facility hygiene'),
    array('name' => 'Sirindhorn Hospital Nurse Dorm', 'location' => 'Khon Kaen', 'page' => 20, 'cat' => 'Government', 'desc' => 'Full-coverage HDPE bird net — 100% bird prevention'),
    array('name' => 'KKU Medical Staff Housing', 'location' => 'Khon Kaen', 'page' => 21, 'cat' => 'Government', 'desc' => 'Bird net on high-rise balconies — engineer-supervised installation'),
    // Factories & Warehouses
    array('name' => 'DOS Factory', 'location' => 'Khon Kaen', 'page' => 15, 'cat' => 'Factory', 'desc' => 'Industrial bird net for large factory — high ceiling, wide coverage area'),
    array('name' => 'Inventivo Cosmetic Warehouse', 'location' => 'Maha Sarakham', 'page' => 24, 'cat' => 'Factory', 'desc' => 'Warehouse bird net — preventing droppings on stored products'),
    array('name' => 'Warehouse A6', 'location' => 'Chiang Mai', 'page' => 25, 'cat' => 'Factory', 'desc' => 'Rental warehouse bird net — stopped nesting under roof'),
    array('name' => 'Saraphi Warehouse', 'location' => 'Chiang Mai', 'page' => 28, 'cat' => 'Factory', 'desc' => 'Large warehouse bird net — completed in 2 days'),
    // Condominiums
    array('name' => 'Triple T Residence KKU', 'location' => 'Khon Kaen', 'page' => 30, 'cat' => 'Condo', 'desc' => 'HDPE bird net on student housing balconies — multiple rooms'),
    array('name' => 'Chatpetch Condo, Non Muang', 'location' => 'Khon Kaen', 'page' => 34, 'cat' => 'Condo', 'desc' => 'Nearly invisible condo balcony bird net — clean finish'),
    array('name' => 'Metro Condo', 'location' => 'Khon Kaen', 'page' => 35, 'cat' => 'Condo', 'desc' => 'Condo bird net — blocks balcony & bedroom entry'),
    array('name' => 'X10 Condo Srinakarin', 'location' => 'Khon Kaen', 'page' => 40, 'cat' => 'Condo', 'desc' => 'Premium HDPE bird net — completed in 3 hours'),
    array('name' => 'The Destiny Exclusive Condo', 'location' => 'Khon Kaen', 'page' => 42, 'cat' => 'Condo', 'desc' => 'Luxury condo bird net — precision detail work'),
    array('name' => 'Chalisa Condo', 'location' => 'Khon Kaen', 'page' => 43, 'cat' => 'Condo', 'desc' => 'Virtually invisible condo balcony bird net'),
    array('name' => 'ESCENT Condo', 'location' => 'Khon Kaen', 'page' => 45, 'cat' => 'Condo', 'desc' => 'High-rise HDPE bird net — professional rope access'),
    array('name' => 'AP Boulevard Condo', 'location' => 'Khon Kaen', 'page' => 46, 'cat' => 'Condo', 'desc' => 'Condo balcony bird net — 100% pigeon solution'),
    array('name' => 'The Base Heights Mittraphap', 'location' => 'Khon Kaen', 'page' => 49, 'cat' => 'Condo', 'desc' => 'High-rise condo bird net on Mittraphap Road'),
    array('name' => 'Kanlapaphruek Lakeview Condo', 'location' => 'Khon Kaen', 'page' => 50, 'cat' => 'Condo', 'desc' => 'Lake-view balcony bird net — unobstructed view'),
    // Residential
    array('name' => 'Chief Prosecutor Residence', 'location' => 'Khon Kaen', 'page' => 22, 'cat' => 'Residential', 'desc' => 'Executive home bird net — under roof & eaves'),
    array('name' => 'Sivalee Village', 'location' => 'Khon Kaen', 'page' => 33, 'cat' => 'Residential', 'desc' => 'Housing estate bird net — stopped nesting under roof'),
    array('name' => 'Urban Nara Airport Bypass', 'location' => 'Khon Kaen', 'page' => 36, 'cat' => 'Residential', 'desc' => 'Residential bird net — protected laundry area'),
    array('name' => 'Klever Tyme Village', 'location' => 'Khon Kaen', 'page' => 39, 'cat' => 'Residential', 'desc' => 'Housing estate bird net — eaves protection'),
    // Commercial
    array('name' => 'Tang Hong Lee Commercial Bldg', 'location' => 'Khon Kaen', 'page' => 27, 'cat' => 'Commercial', 'desc' => '4-story commercial building bird net — storefront protection'),
    array('name' => 'Refreshy Physiotherapy Clinic', 'location' => 'Khon Kaen', 'page' => 29, 'cat' => 'Commercial', 'desc' => 'Clinic bird net — healthcare sanitation area'),
);

$en_multi_photo_pages = array(7, 9, 10, 13, 20, 22, 35, 45);

$en_categories = array();
foreach ($en_projects as $p) {
    $c = $p['cat'];
    if (!isset($en_categories[$c])) $en_categories[$c] = array();
    $en_categories[$c][] = $p;
}

$en_cat_icons = array(
    'Government' => '🏛️', 'Factory' => '🏭', 'Condo' => '🏢',
    'Residential' => '🏠', 'Commercial' => '🏪',
);

// Build EN flipbook
$en_portfolio_content = '
<!-- wp:html -->
<div class="flipbook-wrapper">

<div class="flipbook-toc">';

$en_page_num = 1;
$en_cat_page_map = array();
foreach ($en_categories as $cn => $cp) {
    $en_cat_page_map[$cn] = $en_page_num;
    $en_page_num++;
    $en_page_num += count($cp);
}
foreach ($en_cat_page_map as $cn => $pg) {
    $ic = isset($en_cat_icons[$cn]) ? $en_cat_icons[$cn] . ' ' : '';
    $en_portfolio_content .= '<button data-fb-page="' . $pg . '">' . $ic . $cn . '</button>';
}

$en_portfolio_content .= '
</div>

<div id="flipbook-container">';

// Cover
$en_portfolio_content .= '
<div class="fb-page fb-cover" data-density="hard">
  <div class="fb-cover-logo">BIRDS GO AWAY</div>
  <div class="fb-cover-divider"></div>
  <h2>E-Brochure Portfolio</h2>
  <p>Over <strong>40+ completed projects</strong> across Thailand</p>
  <p>Government · Condos · Factories · Residences</p>
  <p style="margin-top:12px;font-size:0.75rem;opacity:0.6;">Swipe or click to flip pages →</p>
</div>';

foreach ($en_categories as $cn => $cp) {
    $ic = isset($en_cat_icons[$cn]) ? $en_cat_icons[$cn] : '📋';
    $cnt = count($cp);
    $en_portfolio_content .= '
<div class="fb-page fb-cat-divider">
  <div class="fb-cat-icon">' . $ic . '</div>
  <h3>' . $cn . '</h3>
  <div class="fb-cat-count">' . $cnt . ' projects</div>
</div>';

    foreach ($cp as $p) {
        $hm = in_array($p['page'], $en_multi_photo_pages);
        $alt = 'Bird net installation at ' . $p['name'] . ', ' . $p['location'];
        $gal = '';
        if ($hm) {
            $gal = '<div class="fb-proj-gallery">
<img src="' . en_project_img($p['page'], 2) . '" alt="' . $p['name'] . ' #2" loading="lazy"/>
<img src="' . en_project_img($p['page'], 3) . '" alt="' . $p['name'] . ' #3" loading="lazy"/>
</div>';
        }
        $en_portfolio_content .= '
<div class="fb-page fb-project">
  <img src="' . en_project_img($p['page']) . '" alt="' . $alt . '" loading="lazy"/>
  <div class="fb-proj-name">' . $p['name'] . '</div>
  <div class="fb-proj-loc">' . $p['location'] . '</div>
  <div class="fb-proj-desc">' . $p['desc'] . '</div>
  ' . $gal . '
</div>';
    }
}

// Back cover
$en_portfolio_content .= '
<div class="fb-page fb-back" data-density="hard">
  <h3>Interested in Our Service?</h3>
  <p>Free on-site assessment — no obligation</p>
  <p>📞 062-996-4994</p>
  <p>💬 LINE: phanupong_oil</p>
  <a href="/en/contact" class="fb-back-cta">Get a Free Quote</a>
</div>';

$en_portfolio_content .= '
</div>

<div class="fb-nav">
  <button id="fb-prev" aria-label="Previous page">&#9664;</button>
  <span class="fb-page-info"><span id="fb-page-num">1</span> / <span id="fb-page-total"></span></span>
  <button id="fb-next" aria-label="Next page">&#9654;</button>
</div>
<p class="fb-hint">Swipe or click the page edge to flip</p>

</div>
<!-- /wp:html -->
';


// ===== CREATE/UPDATE ENGLISH PAGES =====
echo "=== Creating English Pages ===\n";

// First, create the parent "en" page
$en_parent = get_page_by_path('en');
$en_parent_id = 0;
if ($en_parent) {
    $en_parent_id = $en_parent->ID;
    echo "Found existing EN parent page (ID: {$en_parent_id})\n";
} else {
    $en_parent_id = wp_insert_post(array(
        'post_title'   => 'English',
        'post_name'    => 'en',
        'post_content' => '<!-- wp:paragraph --><p>Welcome to Birds Go Away — Thailand\'s professional bird control service.</p><!-- /wp:paragraph -->',
        'post_status'  => 'publish',
        'post_type'    => 'page',
    ));
    echo "Created EN parent page (ID: {$en_parent_id})\n";
}

// English pages with parent
$en_pages = array(
    'home'       => array('title' => 'Home',             'content' => $en_home_content),
    'services'   => array('title' => 'Our Services',     'content' => $en_services_content),
    'portfolio'  => array('title' => 'Portfolio',         'content' => $en_portfolio_content),
    'about'      => array('title' => 'About Us',         'content' => $en_about_content),
    'contact'    => array('title' => 'Contact Us',        'content' => $en_contact_content),
    'faq'        => array('title' => 'FAQ',               'content' => $en_faq_content),
);

foreach ($en_pages as $slug => $page_data) {
    $full_path = 'en/' . $slug;
    $existing = get_page_by_path($full_path);
    if ($existing) {
        wp_update_post(array(
            'ID' => $existing->ID,
            'post_content' => $page_data['content'],
        ));
        echo "Updated EN page: {$page_data['title']} (ID: {$existing->ID})\n";
    } else {
        $id = wp_insert_post(array(
            'post_title'   => $page_data['title'],
            'post_name'    => $slug,
            'post_content' => $page_data['content'],
            'post_status'  => 'publish',
            'post_type'    => 'page',
            'post_parent'  => $en_parent_id,
        ));
        echo "Created EN page: {$page_data['title']} (ID: {$id})\n";
    }
}

// Set EN home page SEO
$en_home = get_page_by_path('en/home');
if ($en_home) {
    update_post_meta($en_home->ID, '_yoast_wpseo_title', 'Bird Net Installation Thailand | Birds Go Away');
    update_post_meta($en_home->ID, '_yoast_wpseo_metadesc', 'Professional bird net installation across Thailand. HDPE nets, stainless steel spikes, bird gel. Free on-site assessment. 3-year warranty. Call 062-996-4994');
    echo "Set EN home SEO meta\n";
}

// SEO for EN pages
$en_seo = array(
    'en/services' => array(
        'title' => 'Bird Control Services | Birds Go Away Thailand',
        'desc'  => 'HDPE bird nets, stainless steel spikes, bird repellent gel, solar panel guards. Professional installation with 3-year warranty. Free assessment.',
    ),
    'en/portfolio' => array(
        'title' => 'Our Portfolio — 31+ Bird Net Projects | Birds Go Away',
        'desc'  => 'View our completed bird control projects across Thailand. Government buildings, universities, hospitals, condos, and factories.',
    ),
    'en/about' => array(
        'title' => 'About Birds Go Away | Professional Bird Control Thailand',
        'desc'  => 'Recheck Building Co., Ltd. — Thailand\'s bird control specialists. Licensed engineers, safety-certified team, 31+ projects, 3-year warranty.',
    ),
    'en/contact' => array(
        'title' => 'Contact Us | Birds Go Away Thailand',
        'desc'  => 'Get a free on-site assessment and quote. Call 062-996-4994 or send photos via LINE for instant pricing. Serving Khon Kaen, Chiang Mai, Chonburi, nationwide.',
    ),
    'en/faq' => array(
        'title' => 'FAQ — Bird Net Installation | Birds Go Away',
        'desc'  => 'Frequently asked questions about bird net installation, pricing, warranty, and service areas. Starting from 350 THB/sq.m.',
    ),
);

foreach ($en_seo as $path => $seo) {
    $page = get_page_by_path($path);
    if ($page) {
        update_post_meta($page->ID, '_yoast_wpseo_title', $seo['title']);
        update_post_meta($page->ID, '_yoast_wpseo_metadesc', $seo['desc']);
        echo "Set SEO for: {$path}\n";
    }
}

echo "=== English pages completed ===\n";
