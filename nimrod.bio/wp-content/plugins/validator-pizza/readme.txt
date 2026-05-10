=== MailCheck.ai ===
Contributors: tompec
Tags: email validation, disposable email, spam prevention, security, user registration
Requires at least: 5.2
Tested up to: 6.6.1
Requires PHP: 7.2
Stable tag: 1.3.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Prevent disposable email addresses from registering or commenting on your site with MailCheck.ai.

== Description ==

**MailCheck.ai is now UserCheck.com**

**Please install the [new version](https://wordpress.org/plugins/usercheck/) of this plugin.**

---

MailCheck.ai is a powerful WordPress plugin that prevents disposable or throwaway email addresses from registering or commenting on your site. This helps to protect your site from spam and maintain the quality of your user base.

= Key Features =

* Automatically checks email addresses against a constantly updated database of disposable email domains
* Works out of the box with no configuration required
* No API key needed
* Caches results for improved performance
* Seamlessly integrates with WordPress registration and comment forms

The plugin uses the API provided by [MailCheck.ai](https://www.mailcheck.ai), which is constantly updated to include the latest disposable email domains. This ensures your site stays protected against new disposable email providers.

MailCheck.ai is free to use and starts working immediately after installation. No registration or configuration is required.

== Installation ==

1. Upload the `mailcheck-ai` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. That's it! Your site is now protected against disposable email addresses.

== Frequently Asked Questions ==

= Does MailCheck.ai require an API key? =

No, MailCheck.ai does not require an API key. It works out of the box after installation without any additional configuration.

= Is MailCheck.ai compatible with other email validation plugins? =

Yes, MailCheck.ai is designed to work seamlessly with other email validation plugins. However, please note that using multiple email validation plugins might cause conflicts or unexpected behavior.

= Can I customize the error message displayed to users with disposable email addresses? =

Currently, MailCheck.ai does not provide an option to customize the error message. The default WordPress error message for invalid email addresses will be displayed.

= How often is the disposable email domain database updated? =

The MailCheck.ai API is updated in real-time. Your plugin will always check against the most current database of disposable email domains.

= Does this plugin slow down my website? =

MailCheck.ai is designed to have minimal impact on your website's performance. It uses caching to store results for 1 hour, reducing the number of API calls and improving response times.

== Changelog ==

= 1.2.6 =
* Update the API endpoint

= 1.2.5 =
* Improved code structure and organization
* Enhanced error handling and security checks
* Updated to follow WordPress coding standards
* Improved caching mechanism
* Added compatibility with WordPress 5.2+
* Added minimum PHP version requirement (7.2)

= 1.2.4 =
* Update WordPress compatibility
* Rename the cache key

= 1.2.3 =
* Improved URL encoding when constructing the request URL
* Added a condition to check the API response code
* Added an expiration time to the API response cache

= 1.2.0 =
* Update WordPress compatibility and rebrand from Validator.pizza to MailCheck.ai

= 1.1.1 =
* Update WordPress compatibility

= 1.1.0 =
* Cache domains

= 1.0.2 =
* Update WordPress compatibility

= 1.0.1 =
* Update WordPress compatibility

= 1.0 =
* First version
