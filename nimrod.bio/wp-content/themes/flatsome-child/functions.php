<?php
// SmallFarmsAgent market report shortcode (M7 Go-Live)
function sfagent_market_report_shortcode($atts) {
    $upload_dir = wp_upload_dir();
    $file = $upload_dir['basedir'] . '/market/public_report_body.html';
    if (!file_exists($file)) {
        return '<p style="color:red;">Market report not available.</p>';
    }
    return file_get_contents($file);
}
add_shortcode('sfagent_market_report', 'sfagent_market_report_shortcode');

// SmallFarmsAgent shared CSS — loaded in <head> on pages with SFA shortcode
function sfagent_enqueue_styles() {
    global $post;
    if (is_a($post, 'WP_Post') && has_shortcode($post->post_content, 'sfagent_market_report')) {
        $css_file = get_stylesheet_directory() . '/sfagent-base.css';
        if (file_exists($css_file)) {
            wp_enqueue_style(
                'sfagent-base',
                get_stylesheet_directory_uri() . '/sfagent-base.css',
                array(),
                filemtime($css_file)
            );
        }
    }
}
add_action('wp_enqueue_scripts', 'sfagent_enqueue_styles');

// Security headers (M9)
function sfagent_security_headers() {
    if (!is_admin()) {
        header('X-Content-Type-Options: nosniff');
        header('X-Frame-Options: SAMEORIGIN');
        header('Referrer-Policy: strict-origin-when-cross-origin');
        header('Permissions-Policy: camera=(), microphone=(), geolocation=()');
    }
}
add_action('send_headers', 'sfagent_security_headers');

// Lightweight contact form shortcode (replaces WPForms)
function sfagent_contact_form_shortcode($atts) {
    $atts = shortcode_atts(array(
        'to'      => 'nimrod@mezoo.co',
        'subject' => 'הודעה חדשה מהאתר nimrod.bio',
    ), $atts);

    $msg = '';
    if (isset($_GET['sfagent_form'])) {
        if ($_GET['sfagent_form'] === 'ok') {
            $msg = '<div class="sfa-form-msg sfa-form-ok">ההודעה נשלחה בהצלחה! נחזור אליך בהקדם.</div>';
        } elseif ($_GET['sfagent_form'] === 'err') {
            $msg = '<div class="sfa-form-msg sfa-form-err">שגיאה בשליחה. נסה שוב או פנה ישירות במייל.</div>';
        } elseif ($_GET['sfagent_form'] === 'spam') {
            $msg = '<div class="sfa-form-msg sfa-form-err">שגיאה בשליחה.</div>';
        }
    }

    $nonce = wp_nonce_field('sfagent_contact', '_sfnonce', true, false);
    $action_url = esc_url(admin_url('admin-post.php'));

    $html = $msg . '
    <form method="post" action="' . $action_url . '" class="sfa-contact-form" dir="rtl">
        <input type="hidden" name="action" value="sfagent_contact">
        <input type="hidden" name="sfagent_to" value="' . esc_attr($atts['to']) . '">
        <input type="hidden" name="sfagent_subj" value="' . esc_attr($atts['subject']) . '">
        ' . $nonce . '
        <div class="sfa-form-row">
            <label for="sfa-name">שם *</label>
            <input type="text" id="sfa-name" name="sfagent_name" required maxlength="100" autocomplete="name">
        </div>
        <div class="sfa-form-row sfa-form-grid">
            <div class="sfa-form-col">
                <label for="sfa-email">אימייל *</label>
                <input type="email" id="sfa-email" name="sfagent_email" required maxlength="150" autocomplete="email">
            </div>
            <div class="sfa-form-col">
                <label for="sfa-phone">טלפון</label>
                <input type="tel" id="sfa-phone" name="sfagent_phone" maxlength="20" autocomplete="tel">
            </div>
        </div>
        <div class="sfa-form-row">
            <label for="sfa-message">הודעה *</label>
            <textarea id="sfa-message" name="sfagent_message" required rows="5" maxlength="2000"></textarea>
        </div>
        <div style="position:absolute;left:-9999px" aria-hidden="true">
            <input type="text" name="sfagent_hp" tabindex="-1" autocomplete="off">
        </div>
        <div class="sfa-form-row">
            <button type="submit" class="button primary">שלח הודעה</button>
        </div>
    </form>';

    return $html;
}
add_shortcode('sfagent_contact_form', 'sfagent_contact_form_shortcode');

// Contact form submission handler
function sfagent_handle_contact_form() {
    if (!isset($_POST['_sfnonce']) || !wp_verify_nonce($_POST['_sfnonce'], 'sfagent_contact')) {
        wp_die('Unauthorized', 403);
    }

    // Honeypot check
    if (!empty($_POST['sfagent_hp'])) {
        $referer = wp_get_referer();
        wp_safe_redirect($referer ? add_query_arg('sfagent_form', 'spam', $referer) : home_url());
        exit;
    }

    $name    = sanitize_text_field($_POST['sfagent_name'] ?? '');
    $email   = sanitize_email($_POST['sfagent_email'] ?? '');
    $phone   = sanitize_text_field($_POST['sfagent_phone'] ?? '');
    $message = sanitize_textarea_field($_POST['sfagent_message'] ?? '');
    $to      = sanitize_email($_POST['sfagent_to'] ?? 'nimrod@mezoo.co');
    $subject = sanitize_text_field($_POST['sfagent_subj'] ?? 'הודעה חדשה מהאתר');

    if (empty($name) || empty($email) || empty($message)) {
        $referer = wp_get_referer();
        wp_safe_redirect($referer ? add_query_arg('sfagent_form', 'err', $referer) : home_url());
        exit;
    }

    $body  = "שם: {$name}
";
    $body .= "אימייל: {$email}
";
    if (!empty($phone)) {
        $body .= "טלפון: {$phone}
";
    }
    $body .= "
--- הודעה ---
{$message}
";

    $headers = array(
        'Content-Type: text/plain; charset=UTF-8',
        'Reply-To: ' . $name . ' <' . $email . '>',
    );

    $sent = wp_mail($to, $subject, $body, $headers);

    $referer = wp_get_referer();
    $status = $sent ? 'ok' : 'err';
    wp_safe_redirect($referer ? add_query_arg('sfagent_form', $status, $referer) : home_url());
    exit;
}
add_action('admin_post_sfagent_contact', 'sfagent_handle_contact_form');
add_action('admin_post_nopriv_sfagent_contact', 'sfagent_handle_contact_form');

// Inline CSS for contact form (loaded only when form is present)
function sfagent_contact_form_styles() {
    global $post;
    if (!is_a($post, 'WP_Post') || !has_shortcode($post->post_content, 'sfagent_contact_form')) return;
    echo '<style>
.sfa-contact-form{max-width:600px;margin:0 auto}
.sfa-form-row{margin-bottom:16px}
.sfa-form-row label{display:block;margin-bottom:4px;font-weight:600;font-size:14px}
.sfa-form-row input,.sfa-form-row textarea{width:100%;padding:10px 12px;border:1px solid #ccc;border-radius:4px;font-size:15px;font-family:inherit;color:#333!important}
.sfa-form-row textarea{resize:vertical}
.sfa-form-row input:focus,.sfa-form-row textarea:focus{border-color:#4c3113;outline:none;box-shadow:0 0 0 2px rgba(76,49,19,.15)}
.sfa-form-grid{display:flex;gap:12px}
.sfa-form-col{flex:1}
.sfa-form-row button{cursor:pointer;font-size:16px;padding:12px 32px}
.sfa-form-msg{padding:14px 18px;border-radius:6px;margin-bottom:18px;font-size:15px;text-align:center}
.sfa-form-ok{background:#e8f5e9;color:#2e7d32;border:1px solid #a5d6a7}
.sfa-form-err{background:#fce4ec;color:#c62828;border:1px solid #ef9a9a}
@media(max-width:480px){.sfa-form-grid{flex-direction:column;gap:0}}
</style>';
}
add_action('wp_head', 'sfagent_contact_form_styles');

// M9: Dequeue heavy assets not needed on frontend
function sfagent_optimize_frontend_assets() {
    if (is_admin()) return;
    global $post;

    // Dequeue Toolset Views frontend assets when not needed
    if (is_a($post, 'WP_Post') && !has_shortcode($post->post_content, 'wpv-view')) {
        wp_dequeue_style('view_editor_gutenberg_frontend_assets');
        wp_dequeue_script('view_editor_gutenberg_frontend_assets');
    }

    // Dequeue admin-only assets that leak to frontend
    wp_dequeue_script('thickbox');
    wp_dequeue_style('thickbox');
    wp_dequeue_style('dashicons');
    wp_dequeue_script('suggest');
    wp_dequeue_script('wp-mediaelement');
    wp_dequeue_style('wp-mediaelement');
    wp_dequeue_style('mediaelement');
}
add_action('wp_enqueue_scripts', 'sfagent_optimize_frontend_assets', 999);

// WP Accessibility: One-time optimal configuration
function sfagent_configure_wpa() {
    if (get_option('sfagent_wpa_configured') === 'v1') return;

    update_option('wpa_focus', 'on');
    update_option('wpa_focus_color', '4c3113');

    update_option('sfagent_wpa_configured', 'v1');
}
add_action('init', 'sfagent_configure_wpa');

// WP Accessibility: Hebrew labels for auto-labeled form fields
function sfagent_wpa_hebrew_labels($labels) {
    return array(
        's'       => 'חיפוש',
        'author'  => 'שם',
        'email'   => 'אימייל',
        'url'     => 'אתר',
        'comment' => 'תגובה',
    );
}
add_filter('wpa_labels', 'sfagent_wpa_hebrew_labels');

// Accessibility statement shortcode (Israeli law - IS 5568)
function sfagent_accessibility_statement_shortcode($atts) {
    $atts = shortcode_atts(array(
        'site_name'    => 'nimrod.bio',
        'contact_email' => 'nimrod@mezoo.co',
        'contact_phone' => '054-7776770',
        'last_updated'  => '2026-04-02',
    ), $atts);

    $html = '<div class="sfagent-a11y-statement" dir="rtl" style="max-width:800px;margin:0 auto;line-height:1.8">
    <h2>הצהרת נגישות</h2>
    <p>אתר <strong>' . esc_html($atts['site_name']) . '</strong> שם דגש רב על מתן חוויית גלישה נגישה ונוחה לכלל המשתמשים, לרבות אנשים עם מוגבלויות, בהתאם לתקן הישראלי SI 5568 המבוסס על הנחיות WCAG 2.0 ברמת AA.</p>

    <h3>מה עשינו?</h3>
    <ul>
        <li>התאמת האתר לתקן הנגישות הישראלי SI 5568 (WCAG 2.0 AA)</li>
        <li>תמיכה בניווט מלא באמצעות מקלדת</li>
        <li>מבנה כותרות היררכי ותקין</li>
        <li>תמיכה בקוראי מסך ובטכנולוגיות מסייעות</li>
        <li>ניגודיות צבעים מספקת בין טקסט לרקע</li>
        <li>תמיכה בהגדלת טקסט ושינוי גודל תצוגה</li>
        <li>תיאורי תמונות (alt text) לתמונות משמעותיות</li>
        <li>תוויות לשדות טפסים</li>
        <li>קישורי דילוג לתוכן העיקרי</li>
    </ul>

    <h3>יצירת קשר בנושא נגישות</h3>
    <p>אנו ממשיכים לעבוד על שיפור הנגישות באתר. אם נתקלתם בבעיית נגישות או שיש לכם הצעות לשיפור, נשמח לשמוע:</p>
    <ul>
        <li>אימייל: <a href="mailto:' . esc_attr($atts['contact_email']) . '">' . esc_html($atts['contact_email']) . '</a></li>
        <li>טלפון: <a href="tel:' . esc_attr(str_replace('-', '', $atts['contact_phone'])) . '">' . esc_html($atts['contact_phone']) . '</a></li>
        <li>WhatsApp: <a href="https://wa.me/972' . esc_attr(ltrim(str_replace('-', '', $atts['contact_phone']), '0')) . '" target="_blank" rel="noopener">שלחו הודעה</a></li>
    </ul>

    <h3>מידע טכני</h3>
    <p>הנגשת האתר בוצעה באמצעות תוסף WP Accessibility ותיקוני קוד ידניים נוספים. האתר נבדק ומותאם לדפדפנים המובילים (Chrome, Firefox, Safari, Edge) ולמכשירים ניידים.</p>

    <p><em>הצהרה זו עודכנה לאחרונה: ' . esc_html($atts['last_updated']) . '</em></p>
</div>';

    return $html;
}
add_shortcode('sfagent_accessibility_statement', 'sfagent_accessibility_statement_shortcode');

// G9: One-time site content updates (phone, email, menu, widgets, pages, SEO)
function sfagent_g9_site_updates() {
    if (get_option('sfagent_g9_updates') === 'v1') return;

    global $wpdb;

    // 1. Replace [wpforms id="90050"] with [sfagent_contact_form] in all post content
    $wpdb->query(
        "UPDATE {$wpdb->posts} SET post_content = REPLACE(post_content, '[wpforms id=\"90050\"]', '[sfagent_contact_form]') WHERE post_content LIKE '%[wpforms id=\"90050\"]%'"
    );

    // 2. Update footer widget "צרו קשר" (text-4): phone + email + remove /shop link
    $text_widgets = get_option('widget_text');
    if (is_array($text_widgets)) {
        foreach ($text_widgets as $key => &$widget) {
            if (!is_array($widget) || empty($widget['text'])) continue;
            $t = $widget['text'];
            if (strpos($t, '052-42-42-342') !== false || strpos($t, '0524242342') !== false || strpos($t, 'office@nimrod.bio') !== false) {
                $t = str_replace('052-42-42-342', '054-7776770', $t);
                $t = str_replace('0524242342', '0547776770', $t);
                $t = str_replace('office@nimrod.bio', 'nimrod@mezoo.co', $t);
                $t = preg_replace('/<p><a[^>]*href=["\'][^"\']*\/shop["\'][^>]*>[^<]*<\/a><\/p>/i', '', $t);
                $t = preg_replace('/<a[^>]*href=["\'][^"\']*\/shop["\'][^>]*>[^<]*<\/a>/i', '', $t);
                $widget['text'] = $t;
            }
            // Update "איפה ומתי?" widget (text-3): replace old hours
            if (strpos($t, '10:00-19:00') !== false || strpos($t, '8:30-13:30') !== false) {
                $widget['text'] = '<p style="direction: rtl;">הגינה פועלת כיום מול לקוחות מסחריים בלבד ובהזמנה מראש.<br />' .
                    'לפרטים צרו קשר — <a href="https://wa.me/972547776770" target="_blank" rel="noopener">WhatsApp</a></p>';
                $widget['title'] = $widget['title'];
            }
        }
        unset($widget);
        update_option('widget_text', $text_widgets);
    }

    // 3. Remove "הזמנות" menu item (ID 90280)
    wp_delete_post(90280, true);

    // 4. Delete WooCommerce orphan pages by slug
    $orphan_slugs = array('shop', 'cart', 'checkout', 'my-account');
    foreach ($orphan_slugs as $slug) {
        $page = get_page_by_path($slug);
        if ($page) {
            wp_delete_post($page->ID, true);
        }
    }

    // 5. Update Yoast SEO site description
    $wpseo_titles = get_option('wpseo_titles', array());
    if (is_array($wpseo_titles)) {
        $wpseo_titles['metadesc-home-wpseo'] = 'nimrod.bio — מערכת סוכנים חכמים לחקלאות אורגנית קהילתית. מדד מחירים שקוף, כלים לניהול חוות קטנות, ושירותי פיתוח לחקלאים.';
        update_option('wpseo_titles', $wpseo_titles);
    }

    // 6. Update Yoast OG/Social settings
    $wpseo_social = get_option('wpseo_social', array());
    if (is_array($wpseo_social)) {
        $wpseo_social['og_default_image'] = '';
        $wpseo_social['og_frontpage_title'] = 'nimrod.bio — חקלאות אורגנית קהילתית | MyFarmAgents';
        $wpseo_social['og_frontpage_desc'] = 'מערכת סוכנים חכמים לחקלאות אורגנית. מדד מחירים שקוף מבוסס נתוני קהילה, כלים לניהול חוות קטנות, ושירותי פיתוח.';
        update_option('wpseo_social', $wpseo_social);
    }

    // 7. Also fix phone/email in any post_content globally
    $wpdb->query(
        "UPDATE {$wpdb->posts} SET post_content = REPLACE(post_content, '052-42-42-342', '054-7776770') WHERE post_content LIKE '%052-42-42-342%'"
    );
    $wpdb->query(
        "UPDATE {$wpdb->posts} SET post_content = REPLACE(post_content, '0524242342', '0547776770') WHERE post_content LIKE '%0524242342%'"
    );
    $wpdb->query(
        "UPDATE {$wpdb->posts} SET post_content = REPLACE(post_content, 'office@nimrod.bio', 'nimrod@mezoo.co') WHERE post_content LIKE '%office@nimrod.bio%'"
    );

    update_option('sfagent_g9_updates', 'v1');
}
add_action('init', 'sfagent_g9_site_updates');

// G9: Fix header social icons (stored in Flatsome theme mods)
function sfagent_g9_fix_theme_mods() {
    if (get_option('sfagent_g9_theme_fix') === 'v1') return;

    $theme_slug = get_option('stylesheet');
    $mods_key = "theme_mods_{$theme_slug}";
    $mods = get_option($mods_key);

    if (is_array($mods)) {
        $json = json_encode($mods);
        $json = str_replace('office@nimrod.bio', 'nimrod@mezoo.co', $json);
        $json = str_replace('052-4242-342', '054-7776770', $json);
        $json = str_replace('052-42-42-342', '054-7776770', $json);
        $json = str_replace('0524242342', '0547776770', $json);
        $updated = json_decode($json, true);
        if ($updated) {
            update_option($mods_key, $updated);
        }
    }

    // Also check flatsome_options if Flatsome stores settings there
    $flatsome_opts = get_option('flatsome_options');
    if (is_array($flatsome_opts)) {
        $json = json_encode($flatsome_opts);
        $json = str_replace('office@nimrod.bio', 'nimrod@mezoo.co', $json);
        $json = str_replace('052-4242-342', '054-7776770', $json);
        $json = str_replace('052-42-42-342', '054-7776770', $json);
        $updated = json_decode($json, true);
        if ($updated) {
            update_option('flatsome_options', $updated);
        }
    }

    update_option('sfagent_g9_theme_fix', 'v1');
}
add_action('init', 'sfagent_g9_fix_theme_mods');

// G9 v2: Clean remaining WPForms CSS, phone variants, menu items
function sfagent_g9_fixes_v2() {
    if (get_option('sfagent_g9_fixes_v2') === 'v1') return;

    global $wpdb;

    // 1. Clean WPForms CSS from Additional CSS (custom_css post type)
    $custom_css_post = wp_get_custom_css_post();
    if ($custom_css_post) {
        $css = $custom_css_post->post_content;
        $css = preg_replace('/\.wpforms-submit[^}]*\}/', '', $css);
        $css = preg_replace('/div\.wpforms-container-full[^}]*\}/', '', $css);
        $css = preg_replace('/\.wpforms[^}]*\}/', '', $css);
        wp_update_custom_css_post($css);
    }

    // 2. Fix remaining phone variant 052-4242-342 (different dash pattern)
    $wpdb->query(
        "UPDATE {$wpdb->posts} SET post_content = REPLACE(post_content, '052-4242-342', '054-7776770') WHERE post_content LIKE '%052-4242-342%'"
    );

    // 3. Remove "הזמנות" from nav menus (find by title, not by fixed ID)
    $menu_items = $wpdb->get_results(
        "SELECT ID FROM {$wpdb->posts} WHERE post_type = 'nav_menu_item' AND ID IN (
            SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = '_menu_item_url' AND meta_value LIKE '%mypips%'
        )"
    );
    foreach ($menu_items as $item) {
        wp_delete_post($item->ID, true);
    }

    // 4. Fix page content referencing old shop/orders links
    $wpdb->query(
        "UPDATE {$wpdb->posts} SET post_content = REPLACE(post_content, 'http://www.nimrod.bio/shop', 'https://wa.me/972547776770') WHERE post_content LIKE '%nimrod.bio/shop%'"
    );

    update_option('sfagent_g9_fixes_v2', 'v1');
}
add_action('init', 'sfagent_g9_fixes_v2');

// ── SFAgent: Application Password for REST API (created v2, do not re-run) ──
// App password 'sfagent-automation' was created for user NimrodAdmin (ID 1).
// Credentials stored in .env.upress — UPRESS_WP_APP_USER / UPRESS_WP_APP_PASS


