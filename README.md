# Canopus – Canonical Domain Redirector

**Author:** Starisian Technologies  
**Version:** 1.0.0  
**License:** GPL-3.0  
**Compatible with:** WordPress Multisite  
**Requires:** WordPress 5.2+ / PHP 7.4+  

## Description

**Canopus** is a canonical domain enforcement plugin for WordPress Multisite. Its function is to force all frontend traffic to a specific primary domain (canonical host) by automatically redirecting any alternate domain requests to the designated domain using a permanent 301 redirect.

### 🔧 Key Features

- **Frontend-only enforcement:** Admin, AJAX, and CLI processes are excluded to avoid breaking backend functionality.
- **Domain consistency:** Ensures all content is served from the canonical domain (`vibe.sparxstar.com` in your initial setup), preventing mixed content issues and improving SEO.
- **Multisite-compatible:** Perfect for subsites with external domain aliases (e.g., `cellularvibrations.com`) pointing to mapped subsites.
- **Lightweight MU plugin:** Designed to drop into `mu-plugins/` and run network-wide with no activation required.
- **SEO-friendly:** Uses permanent 301 redirects for search engine compliance.

### How It Works

On every frontend page load, Canopus checks:

1. If the request is for the admin, AJAX, or CLI—if so, it skips redirection.
2. If the current host does **not** match the defined canonical host, it performs a 301 redirect to the canonical domain, **preserving the full request URI**.

---

## Installation

1. Create a file named `canopus.php` inside your `wp-content/mu-plugins/` directory.
2. Paste the plugin code into that file.
3. Replace the `$canonical_host` with your preferred canonical domain.
4. Done. No plugin activation is needed.

---

## License

This plugin is licensed under the GNU General Public License v3.0.  
See [LICENSE](https://www.gnu.org/licenses/gpl-3.0.txt) for full terms.

---

## Disclaimer

This software is provided **as is**, **without warranty of any kind**, express or implied.  
**Starisian Technologies makes no guarantees** regarding the performance, reliability, or suitability of this plugin for any particular purpose.

By using this plugin, you accept full responsibility for any outcomes, including potential misconfiguration, SEO impact, or site disruptions.

---

> “Canopus shines brightest when your domains align.”  
> — *Starisian Technologies*
