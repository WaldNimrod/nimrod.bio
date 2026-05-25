<?php
/**
 * Plugin Name: NB V200 Runtime Redirects
 * Description: Runtime 301/410 enforcement for V200 migration on nginx-served hosts.
 * Version: 1.0.0
 * Author: team_10
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!function_exists('nb_v200_normalize_request_path')) {
    function nb_v200_normalize_request_path(string $request_uri): string {
        $path = (string) parse_url($request_uri, PHP_URL_PATH);
        if ($path === '') {
            $path = '/';
        }
        $decoded = rawurldecode($path);
        $trimmed = trim($decoded, '/');
        if ($trimmed === '') {
            return '/';
        }
        $segments = explode('/', $trimmed);
        $encoded = array_map(static function (string $segment): string {
            return strtolower(rawurlencode($segment));
        }, $segments);
        return '/' . implode('/', $encoded) . '/';
    }
}

add_action('parse_request', static function (): void {
    if (is_admin()) {
        return;
    }

    // Legacy page_id alias that used to point to heritage source content.
    if (isset($_GET['page_id']) && (string) $_GET['page_id'] === '2516') {
        wp_safe_redirect('/about/heritage/', 301, 'NB-V200-runtime');
        exit;
    }

    $redirects = [
        '/%d7%94%d7%96%d7%9e%d7%a0%d7%aa-%d7%a1%d7%9c-%d7%99%d7%a8%d7%a7%d7%95%d7%aa-%d7%90%d7%95%d7%a8%d7%92%d7%90%d7%a0%d7%99-%d7%a4%d7%a8%d7%93%d7%a1-%d7%97%d7%a0%d7%94-2-2-2-2-2/' => '/about/heritage/',
        '/%d7%9e%d7%93%d7%a8%d7%99%d7%9a-%d7%94%d7%9e%d7%a7%d7%99%d7%a3-%d7%9c%d7%92%d7%99%d7%93%d7%95%d7%9c-%d7%93%d7%9c%d7%95%d7%a2%d7%99%d7%9d-%d7%90%d7%95%d7%a8%d7%92%d7%a0%d7%99%d7%99%d7%9d-%d7%91%d7%91-2/' => '/blog/מדריך-המקיף-לגידול-דלועים-אורגניים-בב-2/',
        '/%d7%a9%d7%aa%d7%99%d7%9c%d7%95%d7%aa-%d7%97%d7%95%d7%a8%d7%a3-%d7%94%d7%93%d7%a8%d7%9b%d7%94-%d7%9c%d7%92%d7%a0%d7%99-%d7%99%d7%9c%d7%93%d7%99%d7%9d/' => '/blog/שתילות-חורף-הדרכה-לגני-ילדים/',
        '/%d7%a1%d7%93%d7%a0%d7%aa-%d7%9c%d7%99%d7%a7%d7%95%d7%98-%d7%a2%d7%9d-%d7%90%d7%91%d7%99%d7%91%d7%99%d7%aa-%d7%91%d7%a8%d7%a7%d7%95%d7%91%d7%99%d7%a5/' => '/blog/סדנת-ליקוט-עם-אביבית-ברקוביץ/',
        '/%d7%96%d7%99%d7%95-%d7%a8%d7%99%d7%99%d7%a3-%d7%9b%d7%90%d7%9f-11-%d7%9e%d7%91%d7%a7%d7%a8-%d7%91%d7%92%d7%99%d7%a0%d7%94/' => '/blog/זיו-רייף-כאן-11-מבקר-בגינה/',
        '/%d7%9e%d7%91%d7%95%d7%90-%d7%9c%d7%92%d7%99%d7%93%d7%95%d7%9c-%d7%94%d7%99%d7%93%d7%a8%d7%95%d7%a4%d7%95%d7%a0%d7%99/' => '/blog/מבוא-לגידול-הידרופוני/',
        '/transpantphotoindex/' => '/blog/transpantphotoindex/',
        '/%d7%9e%d7%93%d7%a8%d7%99%d7%9a-%d7%a9%d7%9c%d7%99%d7%a4%d7%aa-%d7%a9%d7%aa%d7%99%d7%9c%d7%99%d7%9d/' => '/blog/מדריך-שליפת-שתילים/',
        '/transplantinfo2020/' => '/blog/transplantinfo2020/',
        '/%d7%a2%d7%93%d7%9b%d7%95%d7%9f-%d7%a9%d7%91%d7%95%d7%a2%d7%99-%d7%9e%d7%94%d7%92%d7%99%d7%a0%d7%94/' => '/blog/עדכון-שבועי-מהגינה/',
        '/%d7%9e%d7%95%d7%a2%d7%93%d7%99-%d7%96%d7%a8%d7%99%d7%a2%d7%94-%d7%95%d7%a9%d7%aa%d7%99%d7%9c%d7%94/' => '/blog/מועדי-זריעה-ושתילה/',
        '/%d7%9e%d7%93%d7%a8%d7%99%d7%9a-%d7%a9%d7%aa%d7%99%d7%9c%d7%94-%d7%a0%d7%9b%d7%95%d7%a0%d7%94/' => '/blog/מדריך-שתילה-נכונה/',
        '/spaces_dtm-winter/' => '/blog/spaces_dtm-winter/',
        '/transplant-spread/' => '/blog/transplant-spread/',
        '/%d7%a4%d7%98%d7%a8%d7%99%d7%95%d7%aa-%d7%99%d7%a2%d7%a8-%d7%91%d7%92%d7%99%d7%a0%d7%94/' => '/blog/פטריות-יער-בגינה/',
        '/transplants2020/' => '/blog/transplants2020/',
        '/direct-seeding/' => '/blog/direct-seeding/',
        '/%d7%a7%d7%94%d7%99%d7%9c%d7%94_%d7%97%d7%a7%d7%9c%d7%90%d7%99%d7%aa/' => '/blog/קהילה-חקלאית/',
        '/%d7%a6%d7%9e%d7%97%d7%99%d7%9d-%d7%97%d7%91%d7%a8%d7%99%d7%9d/' => '/blog/צמחים-חברים/',
        '/harish2021/' => '/blog/harish2021/',
        '/%d7%90%d7%95%d7%9b%d7%9e%d7%a0%d7%99%d7%95%d7%aa/' => '/blog/אוכמניות/',
        '/video1/' => '/blog/יום-בגינה/',
        '/common/' => '/blog/common/',
    ];

    $drops = [
        '/%d7%a9%d7%99%d7%aa%d7%95%d7%a3-%d7%a4%d7%a2%d7%95%d7%9c%d7%94-%d7%97%d7%93%d7%a9-%d7%91%d7%99%d7%9f-%d7%94%d7%92%d7%99%d7%a0%d7%94-%d7%95%d7%9e%d7%97%d7%a1%d7%a0%d7%99-%d7%94%d7%98%d7%91%d7%a2%d7%95/',
        '/%d7%97%d7%93%d7%a9-%d7%94%d7%96%d7%9e%d7%a0%d7%95%d7%aa-%d7%9e%d7%94%d7%92%d7%99%d7%a0%d7%94-%d7%91%d7%90%d7%a4%d7%9c%d7%99%d7%a7%d7%a6%d7%99%d7%99%d7%aa-farmer/',
        '/%d7%a2%d7%93%d7%9b%d7%95%d7%9f-%d7%9e%d7%99%d7%a0%d7%99%d7%9d-%d7%95%d7%96%d7%a0%d7%99%d7%9d/',
        '/smallfarmsagent/',
        '/%d7%98%d7%91%d7%9c%d7%aa-%d7%9e%d7%99%d7%a0%d7%99%d7%9d/',
        '/grow/',
    ];

    $path = nb_v200_normalize_request_path($_SERVER['REQUEST_URI'] ?? '/');
    if (isset($redirects[$path])) {
        wp_safe_redirect($redirects[$path], 301, 'NB-V200-runtime');
        exit;
    }

    if (in_array($path, $drops, true)) {
        status_header(410);
        nocache_headers();
        header('X-Redirect-By: NB-V200-runtime');
        echo '410 Gone';
        exit;
    }
}, 0);
