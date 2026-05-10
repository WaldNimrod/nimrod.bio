<?php
/**
 * MailCheck.ai
 *
 * @package           MailCheckAI
 * @author            MailCheck.ai
 * @copyright         2024 MailCheck.ai
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       MailCheck.ai
 * Plugin URI:        https://www.mailcheck.ai
 * Description:       **Please replace with UserCheck** Prevents throwaway emails from signing up or commenting on your website using the MailCheck.ai API.
 * Version:           1.3.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            MailCheck.ai
 * Author URI:        https://www.mailcheck.ai
 * Text Domain:       mailcheck-ai
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

if (!class_exists('MailCheckAI')) {

    class MailCheckAI {

        /**
         * Initialize the plugin.
         */
        public static function init() {
            add_filter('is_email', array(__CLASS__, 'check_email'), 10, 2);
            add_action('admin_notices', array(__CLASS__, 'admin_notice'));
        }

        /**
         * Check if the email is disposable.
         *
         * @param bool   $is_email Whether the email address is valid.
         * @param string $email    The email address to check.
         * @return bool True if the email is valid and not disposable, false otherwise.
         */
        public static function check_email($is_email, $email) {
            // Return false if the email didn't pass is_email() checks
            if ($is_email === false) {
                return false;
            }

            // Split out the local and domain parts
            $parts = explode('@', $email, 2);
            if (count($parts) !== 2) {
                return false;
            }
            $domain = $parts[1];

            // Check if the domain is cached
            $transient_key = 'mailcheck_' . md5($domain);
            $json = get_transient($transient_key);

            if (false === $json) {
                $json = self::fetch_api_data($domain);
                if ($json === null) {
                    return true; // API request failed, don't block email
                }
                // Store the result with a cache expiration time of 1 day
                set_transient($transient_key, $json, HOUR_IN_SECONDS);
            }

            // If the disposable response doesn't exist, return true to not block the email validation
            if (!isset($json->disposable)) {
                return true;
            }

            // Return true if the domain is not disposable, false otherwise
            return !$json->disposable;
        }

        /**
         * Fetch data from the MailCheck.ai API.
         *
         * @param string $domain The domain to check.
         * @return object|null JSON decoded response or null on failure.
         */
        private static function fetch_api_data($domain) {
            $request_url = "https://api.usercheck.com/domain/" . urlencode($domain);
            $response = wp_remote_get($request_url, array('timeout' => 5));

            if (is_wp_error($response) || wp_remote_retrieve_response_code($response) != 200) {
                return null;
            }

            return json_decode(wp_remote_retrieve_body($response));
        }

        /**
         * Display admin notice to promote UserCheck plugin.
         */
        public static function admin_notice() {
            if (!current_user_can('manage_options')) {
                return;
            }

            $dismiss_option = 'mailcheck_ai_dismiss_usercheck_notice';
            if (get_option($dismiss_option)) {
                return;
            }

            if (isset($_GET['mailcheck_ai_dismiss_notice'])) {
                update_option($dismiss_option, true);
                return;
            }

            ?>
            <div class="notice notice-warning">
                <p>
                    <strong>MailCheck.ai is now UserCheck.com</strong><br>
                    Please install our new free plugin to continue protecting your site against temporary email addresses.<br>
                    You can safely delete MailCheck.ai after installing UserCheck.
                </p>
                <p>
                    <a href="/wp-admin/plugin-install.php?s=usercheck&tab=search&type=term" class="button button-primary">Install UserCheck</a>
                </p>
            </div>
            <?php
        }
    }

    // Initialize the plugin
    add_action('plugins_loaded', array('MailCheckAI', 'init'));
}
