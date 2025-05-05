<?php
namespace Starisian\Canopus;
/**
 * @package           Canopus – Canonical Domain Redirector
 * @author            Starisian Technologies
 * @copyright         2025 Starisian Technologies
 * @license           GPL-3.0-or-later
 * 
 * Plugin Name:       Canopus-Conical-Domain-Redirector
 * Plugin URI:        https://github.com/Starisian-Technologies/canopus-canonical-name-redirector
 * Description:       Forces front-end traffic to alias domains to maintain canonical domain across aliases.
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Starisian Technologies (Max Barrett)
 * Author URI:        https://starisian.com
 * Update URI:        https://github.com/Starisian-Technologies/canopus-canonical-name-redirector
 * Text Domain:       canopus-conical-name-redirector
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 */

/**
 * Class Redirector
 *
 * Canonical Domain Redirector for WordPress Multisite.
 * Redirects all frontend requests from alias domains to the designated canonical domain.
 * Improves SEO, prevents duplicate content, and standardizes user access.
 *
 * Instructions:
 * 1. Place this file inside a mu-plugin or load it manually in your theme/plugin.
 * 2. Update the $canonical_map with your domain-to-canonical mappings.
 * 3. Ensure DNS and WordPress multisite alias domains resolve properly.
 * 4. Avoid applying this in admin or AJAX to prevent unintended redirects.
 * 5. The hook below activates redirection during runtime: 
 *      add_action('template_redirect', ['Redirector', 'enforce']);
 */
class Redirector {
  /**
   * Enforce canonical domain redirection on frontend requests.
   *
   * This function is hooked into the `template_redirect` action.
   * It checks if the current host matches a known alias and,
   * if so, performs a 301 redirect to the canonical domain.
   * Admin, CLI, and AJAX requests are explicitly excluded.
   */
  public static function enforce() {
    if (is_admin() || php_sapi_name() === 'cli' || (defined('DOING_AJAX') && DOING_AJAX)) {
        return;
    }
    
    // Map of alias domains to their canonical counterparts
    // NOTE: Edit the domains below!!
    $canonical_map = [
        'barbarabarrett.org' => 'barbarabarrett.sparxstar.com',
        'casanovaandrosetta.com' => 'casanovaandrosetta.sparxstar.com',
        'aiwestafrica.com' => 'aiwa.sparxstar.com',
        'contribute.aiwestafrica.com' => 'contribute.sparxstar.com',
        'mandinka.aiwestafrica.com' => 'mandinka.sparxstar.com',
        'muhammeddibbasey.cellularvibrations.com' => 'md.sparxstar.com',
        'cellularvibrations.com' => 'vibe.sparxstar.com',
    ];

    // Get current domain
    $current_host = $_SERVER['HTTP_HOST'] ?? '';
    $canonical_host = $canonical_map[$current_host] ?? '';

    // Redirect to canonical domain if needed
    if ($canonical_host && $current_host !== $canonical_host) {
        $scheme = is_ssl() ? 'https' : 'http';
        $uri = $_SERVER['REQUEST_URI'];
        wp_redirect("{$scheme}://{$canonical_host}{$uri}", 301);
        exit;
    }
  }
}


