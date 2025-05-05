<?php
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
// load class file
require_once __DIR__ . '/src/Redirector.php';

use Starisian\Canopus\Redirector;

// runtime hook
add_action('template_redirect', [Redirector::class, 'enforce']);
