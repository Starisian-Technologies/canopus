# Instructions.md — Operating Guidelines for AI Codex

## 1) Intake Checklist

* Confirm **plugin identifiers**: `STAR_PLUGIN_NAME` (PascalCase), slug, text domain, REST namespace.
* Identify CPTs/taxonomies, REST endpoints, storage (options/custom tables), and required roles/caps.
* Note offline interactions (recording, forms), data sensitivity, and deletion/opt‑out requirements.

## 2) Scaffolding

Generate:

```
plugin-slug/
├─ plugin-slug.php
├─ src/<PluginName>/Core/PluginCore.php
├─ src/<PluginName>/{Admin,Frontend,Rest,Services,Support}/...
├─ assets/{js/{modern,legacy},css,img}
├─ templates/
├─ languages/
├─ tests/{unit,integration,e2e}
├─ config/{phpcs.xml,phpstan.neon}
├─ composer.json (optional)
└─ README.md, CHANGELOG.md, CONTRIBUTING.md
```

Include bootstrap guards (PHP/WP versions), PSR‑4 autoload (Composer + fallback), and `load_plugin_textdomain`.

## 3) Naming Conventions (Enforced)

* Namespace: `Starisian\\<PluginName>\\…` (PSR‑4 maps to `src/<PluginName>/`).
* Classes: PascalCase; one per file; filename mirrors class.
* Methods/props: camelCase; constants: SCREAMING\_SNAKE\_CASE.
* Handles/routes/options/meta: `star-<slug>-*`; REST: `star-<slug>/v1`.

## 4) Error Handling

* Internals may throw; **translate to `WP_Error`** at boundaries (hooks/REST/shortcodes).
* Use capped exponential backoff with jitter for network I/O; default timeouts ≤ 10s.
* JS responses standardize to `{ ok, code, message, data }`; offline triggers queue‑retry, not failure.

## 5) Offline‑First

* IndexedDB (preferred) with localStorage fallback; entries are UUID‑keyed with statuses: `pending|uploading|complete|failed`.
* Chunked uploads; resume from last byte; FIFO queue; user feedback for each state.
* Idempotency keys (UUID) for dedupe; “Export to File” fallback for hand‑delivery.

## 6) Accessibility & i18n

* WCAG 2.1 AA: keyboard, focus, contrast, live regions; no color‑only meaning.
* All strings localized via `__()`; add translator comments where ambiguous.

## 7) Security

* Capability checks + nonces; sanitize on input, escape on output; `$wpdb->prepare` for SQL; strict upload validation.
* Rate‑limit sensitive REST; never echo stack traces or paths.

## 8) REST API

* Register routes under `star-<slug>/v1`; permission callbacks must be explicit.
* ACF/SCF: do **not** rename vendor namespaces; **wrap** with custom endpoints if you need a `star-` prefix.
* Provide OpenAPI‑style route docs (path, methods, params, responses, error codes).

## 9) Builds & Budgets

* Dual bundles: `modern` (ES modules) + `legacy` (transpiled, no modern syntax).
* Budget gates: ≤ 60KB JS, ≤ 25KB CSS gz; system fonts; no heavy frameworks by default.
* Lint: PHPCS (WordPress + PSR‑12), PHPStan ≥ level 6, ESLint, Stylelint.

## 10) Testing

* PHPUnit unit tests; REST integration tests (auth, caps, schema).
* E2E tests for JS‑off baseline and offline queue (2G/3G throttling).
* Include fixtures and idempotency tests.

## 11) Documentation

* README (purpose, constraints, offline model), CHANGELOG (Keep‑a‑Changelog), CONTRIBUTING (conventions, branches, CI).
* Admin Help tab to explain consent/offline behavior.

## 12) Release & Maintenance

* SemVer; store DB version in option; idempotent migrations.
* Safe uninstall; configurable data cleanup.
* Deprecation policy with timelines; backward‑compatible filters/actions.

## 13) Response Templates (Use As‑Is)

**REST Endpoint (PHP)**

```php
register_rest_route( 'star-' . STAR_PLUGIN_SLUG . '/v1', '/queue/(?P<uuid>[A-Za-z0-9-]+)', [
  'methods'             => 'POST',
  'permission_callback' => function () { return current_user_can( 'upload_files' ); },
  'args'                => [ 'uuid' => [ 'required' => true ] ],
  'callback'            => [ $service, 'receive_chunk' ],
] );
```

**Bootstrap Guards (PHP)**

```php
if ( version_compare( PHP_VERSION, '8.2', '<' ) || version_compare( get_bloginfo('version'), '6.4', '<' ) ) {
  add_action('admin_notices', function(){ echo '<div class="notice notice-error"><p>' . esc_html__( 'Requires PHP 8.2+ and WP 6.4+.', 'star-slug' ) . '</p></div>'; });
  return;
}
```

**JS Error Envelope**

```js
function ok(data={}){return{ok:true,code:'OK',message:'',data}};function fail(code,msg,data={}){return{ok:false,code:code||'ERR',message:msg||'Failed',data}};
```

**Queue Status Model**

```js
const STATUS={PENDING:'pending',UPLOADING:'uploading',COMPLETE:'complete',FAILED:'failed'};
```

## 14) ACF/SCF REST Namespacing Notes

* You **cannot** rename ACF’s native `acf/v3` (or vendor) routes.
* Expose needed data via **your own** `star-<slug>/v1/...` endpoints that **wrap** ACF/SCF CRUD.
* Ensure CPTs have `show_in_rest` and field schemas are mirrored in your responses.

---

## Appendix — REST Namespace Quickstart (STAR Prefix)

1. Define identifiers in `plugin-slug.php` (constants for name/slug).
2. In `Rest/Routes.php`, register routes under `star-<slug>/v1`.
3. Use explicit `permission_callback` and idempotency keys in headers or params.

```php
namespace Starisian\Recorder\Rest;
class Routes{
  public function register(){
    add_action('rest_api_init', function(){
      register_rest_route('star-' . STAR_PLUGIN_SLUG . '/v1','/health',[
        'methods'=>'GET',
        'permission_callback'=>'__return_true',
        'callback'=> function(){ return rest_ensure_response([ 'status'=>'ok','ts'=>time() ]); }
      ]);
    });
  }
}
```
