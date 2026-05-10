<?php
/**
 * sfagent-allow-json.php — allow JSON + HTML uploads via WP REST API
 * For SFA-S002-P001-WP007 (price index publish pipeline)
 */

function sfagent_allow_extra_mimes($mime_types) {
    $mime_types['json'] = 'application/json';
    $mime_types['html'] = 'text/html';
    $mime_types['htm']  = 'text/html';
    return $mime_types;
}
add_filter('upload_mimes', 'sfagent_allow_extra_mimes');

function sfagent_fix_filetype_check($data, $file, $filename, $mimes) {
    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    if ($ext === 'json') {
        $data['ext']  = 'json';
        $data['type'] = 'application/json';
    } elseif (in_array($ext, ['html', 'htm'])) {
        $data['ext']  = $ext;
        $data['type'] = 'text/html';
    }
    return $data;
}
add_filter('wp_check_filetype_and_ext', 'sfagent_fix_filetype_check', 10, 4);