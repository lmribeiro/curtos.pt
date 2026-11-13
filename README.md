<img src="https://curtos.pt/img/name.svg" alt="Curtos.pt" />

A free, open source URL shortener built with PHP and Yii 2.

[Website](https://curtos.pt) · [API documentation](https://curtos.pt/api-v1) · [CLI documentation](https://curtos.pt/cli)

## Requirements

The current development checks have been run with **PHP 7.4.33**, MySQL 8.2 and
Composer 2. The PHP constraint in `composer.json` is `>=7.0`; this is not a
compatibility guarantee for every PHP version and locked development dependency.
PHP 7.4 is an end-of-life runtime; production runtime upgrades require separate
compatibility testing.

- Application: PDO with `pdo_mysql`, `mbstring`, `ctype`, JSON, sessions and
  OpenSSL with a working CA certificate store.
- Geolocation uses PHP's HTTPS stream wrapper and requires `allow_url_fopen`.
- Focused analytics tests also require `pdo_sqlite`.
- Development dependencies require extensions including `curl`, `dom`, `libxml`,
  `phar`, `tokenizer`, `xml` and `xmlwriter`; let Composer check the locked requirements.
- The web-server user needs write access to `runtime/` and `web/assets/`.

After installing the dependencies, check the actual environment:

```bash
composer check-platform-reqs
php requirements.php
```

The Yii requirement checker includes optional features; for this application,
`pdo_mysql` is required even though that generic checker marks it optional.

## Local installation

```bash
git clone git@github.com:lmribeiro/curtos.pt.git
cd curtos.pt
composer install
```

Use `composer install` with the committed lock file. Do not run `composer update`
as a setup step.

### Select PHP for this project

On a Mac with Homebrew PHP 7.4 already installed, use its binary without changing
the global PHP version:

```bash
/usr/local/opt/php@7.4/bin/php -v
/usr/local/opt/php@7.4/bin/php /usr/local/bin/composer install
/usr/local/opt/php@7.4/bin/php yii serve 127.0.0.1 -p=8181
```

These paths are examples for the verified local installation; adjust them to your
machine. Alternatively, prepend the installed PHP directory to `PATH` only in
the terminal used for this project. Subsequent `php` and `composer` examples must
use the same selected PHP runtime.

The existing `docker-compose.yml` uses `yiisoftware/yii2-php:7.1-apache`, exposes
port 8000 and does not provide MySQL. It is a legacy configuration, not the
verified PHP 7.4 environment. It has not been upgraded or validated as part of
the current setup.

### Database

Create a local database and a dedicated account using an administrative MySQL
session. The names and password below are placeholders for local development:

```sql
CREATE DATABASE curtos_local CHARACTER SET utf8;
CREATE USER 'curtos_local'@'localhost' IDENTIFIED BY 'REPLACE_WITH_LOCAL_PASSWORD';
GRANT ALL PRIVILEGES ON curtos_local.* TO 'curtos_local'@'localhost';
```

Create `config/db.php`:

```php
<?php
return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=127.0.0.1;dbname=curtos_local',
    'username' => 'curtos_local',
    'password' => 'REPLACE_WITH_LOCAL_PASSWORD',
    'charset' => 'utf8',
];
```

`config/db.php` is ignored by Git. Never point development or test configuration
at production. Keep database dumps outside the repository; they may contain
account information, API keys, password hashes and visitor IP addresses.

Apply the schema:

```bash
php yii migrate
```

For a server dump, import into a separate local database, update `config/db.php`
and then apply pending migrations. Do not import over an existing database unless
replacing its contents is intended. Yii does not create the database itself.

### Cookies, email and local configuration

- Set a unique random `cookieValidationKey` in `config/web.php` for each deployment.
  This file is tracked; do not commit deployment secrets.
- `config/web.php` loads `config/db.php`, `config/mailer.php`, `config/log.php`
  and `config/params.php`. The console application also loads the database and parameters.
- For local email, configure `config/mailer.php` to use file transport:

```php
<?php
return [
    'class' => 'yii\swiftmailer\Mailer',
    'useFileTransport' => true,
];
```

Messages are written to `runtime/mail` instead of being sent. SMTP delivery needs
an explicitly configured transport and `useFileTransport => false`. Configure
sender addresses in `config/params.php`. The mailer and parameters files are
tracked; keep real SMTP credentials out of Git and supply deployment-specific
configuration through your deployment process. Check `config/log.php` as well:
it configures both file and email log targets.

### Run

```bash
php yii serve 127.0.0.1 -p=8181
```

Open <http://127.0.0.1:8181/>. `composer serve` also uses port 8181, but the
explicit command above restricts the development server to loopback. This server
is for local development only.

## Accounts and link lifecycle

Public signup is currently disabled: `SiteController::actionSignup()` redirects
to the home page. An empty installation has no account seeding command; the only
console controller currently provided is the example `HelloController`. Existing
active accounts can sign in. A new installation needs an operator-managed account
provisioning process; this repository does not provide a verified bootstrap procedure.

API keys belong to active accounts. Obtain or regenerate the key from the account
page. Regeneration invalidates the previous key. Treat the key as a credential;
never embed it in browser code, public examples or committed files.

`config/params.php` sets the default expiration interval to 30 days. The API's
optional `expires_after` input is an interval in **days**, not a timestamp. A
successful create response returns the calculated expiration date. When omitted, the default interval is used. When provided, it must be a positive
JSON integer: null, zero, negative numbers, booleans, numeric strings and fractional
values are rejected. The calculated date must fit the current MySQL TIMESTAMP
column (through 2038-01-19 03:14:07 UTC). No additional business quota in days is imposed. Renewal
adds 30 days to the stored expiration date.

The UI reports active/expired status from that date. However, the redirect action
does not currently enforce expiration, and this repository has no scheduled job or
command that automatically deletes expired links. Do not rely on expiration as
an access-control or automatic cleanup mechanism.

Deleting a link removes its associated `link_stats` rows through `ON DELETE CASCADE`.
Historical rows whose `link_id` was already null are not removed by that migration.

## API integration

The maintained public documentation is rendered from `views/site/_api/` at
`/api-v1`. The v1 operations are:

| Method | Path | Input |
| --- | --- | --- |
| POST | `/api/v1/create` | JSON `target`; optional `expires_after` in days |
| GET | `/api/v1/stats?code=...` | Link code in the query string |
| DELETE | `/api/v1/delete` | JSON `code` in the request body |

Send the raw API key in `Authorization`, **without** a `Bearer` prefix. Request
bodies must be UTF-8 JSON. Creation requires a syntactically valid HTTPS URL string with at most 255 Unicode
characters. Link codes must be strings containing 1–255 ASCII letters, digits or
underscores. Existing generated codes remain six hexadecimal characters.

### Complete cURL examples

Use a local account and disposable links when trying creation or deletion:

```bash
API_BASE='http://127.0.0.1:8181'
API_KEY='REPLACE_WITH_YOUR_API_KEY'

curl --include --request POST "$API_BASE/api/v1/create" \
  --header "Authorization: $API_KEY" \
  --header 'Content-Type: application/json' \
  --data '{"target":"https://example.com/","expires_after":60}'

# Replace a1b2c3 with the code returned by creation.
curl --include "$API_BASE/api/v1/stats?code=a1b2c3" \
  --header "Authorization: $API_KEY"

# Permanently removes the link and its associated visit statistics.
curl --include --request DELETE "$API_BASE/api/v1/delete" \
  --header "Authorization: $API_KEY" \
  --header 'Content-Type: application/json' \
  --data '{"code":"a1b2c3"}'
```

For the hosted service, replace `API_BASE` with `https://curtos.pt`. These are
examples, not operations that have been executed against the hosted service.

### Response and error handling

`ApiController::sendOk()` and `sendError()` currently send **HTTP 200**, even when
the JSON `code` is 201, 400, 401 or 404. Check both the transport status and the
JSON envelope's `status` and `code`. Framework, proxy and web-server errors can
still use other HTTP statuses or return a different response shape.

Illustrative missing-key response, with HTTP 200:

```json
{
  "name": "Unauthorized",
  "message": "You must be authorized to performe this request.",
  "code": 401,
  "status": "Error",
  "data": []
}
```

Illustrative missing-code response for an authenticated delete request:

```json
{
  "name": "Bad Request",
  "message": "Missing required field 'code'",
  "code": 400,
  "status": "Error",
  "data": []
}
```

Missing/invalid credentials produce JSON code 401. After authentication, malformed
JSON, a body that is not a JSON object, missing required fields and invalid field
types/values produce JSON code 400 with the same envelope and HTTP 200. A missing
or invalid stats query code also produces 400; a well-formed but unknown code
produces 404 for stats or deletion. Framework, proxy and web-server errors may
still use a different response shape.

Creation has no idempotency key: retrying may create another link. Repeating a
successful deletion normally produces a not-found response. No request quota or
`Retry-After` contract is currently enforced by the v1 controller. CORS is handled by Yii before content negotiation, verb filtering and API-key
authentication. Preflight OPTIONS requests return HTTP 200 with no application
body and require no API key. Actual create/delete/stats requests still require
the raw Authorization key. Any origin is allowed, as before, without cross-origin
cookie credentials. Allowed methods are advertised per endpoint: POST/OPTIONS
for create, DELETE/OPTIONS for delete and GET/OPTIONS for stats. Allowed request
headers are Authorization, Content-Type, X-Requested-With, X-XMLHttpRequest and
AuthToken. Unsupported methods/headers are not advertised by preflight; actual
unsupported verbs remain subject to Yii's verb filter.

For example, inspect DELETE preflight without sending a key:

```bash
curl --include --request OPTIONS "$API_BASE/api/v1/delete" \
  --header 'Origin: https://client.example' \
  --header 'Access-Control-Request-Method: DELETE' \
  --header 'Access-Control-Request-Headers: authorization, content-type'
```

Browser clients must omit cross-origin cookie credentials and check CORS behavior
at their deployed proxy/web-server boundary as well as the application.

The CLI is maintained in [a separate repository](https://github.com/curtos-pt/cli).
The website's CLI page contains legacy commands; check that repository for the
installed version's authentication/configuration instructions before relying on it.

## Link analytics and collected data

The link details page `/curto/<code>` shows full history by default and supports
7, 30 and 90-day filters. It includes daily/hourly/weekday visits, distinct IP
counts, devices, operating systems, city/region rankings and referral domains,
as well as browser and country charts. Last recorded visit includes history
outside the selected period. Rankings display the top ten entries.

The v1 stats API still returns only `code`, `target`, `short`, `expires_after`,
`visits`, `byBrowser` and `byCountry`. It does **not** expose the new UI indicators
or implement the UI's `days` filter.

- The historical visit counter and detailed statistics may differ: older records
  can be missing, and the counter is updated before detailed statistics are saved.
- Distinct non-empty IPs are an estimate, not a count of people. Multiple visitors
  can share an IP; one visitor can use multiple IPs.
- New visits store the client IP and user-agent-derived OS, platform, browser
  name/version. Tablet detection depends on the supplied user agent and applies
  to newly collected visits.
- New visits store only the Referer hostname, without credentials, path, query
  or fragment. A null referrer means it was not collected historically; an empty
  string means direct access or an unavailable/invalid header. Referer and user-agent
  values are supplied by clients and are not reliable identity or attribution proofs.
- Available geolocation fields include city, region, country and coordinates.
  They describe an approximate IP location, not a verified visitor address.
- The application uses Yii's request IP resolution. Check trusted proxy settings
  for the actual deployment before relying on visitor IP-based statistics.

### Geolocation dependency

[IPWho.is documentation](https://ipwhois.io/documentation) currently describes a
free endpoint with 1,000 requests per day per calling IP, commercial use allowed,
HTTPS and no API key. Verify provider terms and quotas before deployment; this
is a third-party limit, not the Curtos.pt API's rate limit.

`components/Stats.php` queries `https://ipwho.is/<ip>`, sending public visitor IPs
to that provider. Private, reserved and invalid IPs are skipped. Lookups have a
2-second timeout; successful responses are cached per IP for 24 hours, failures
for 5 minutes using the configured Yii cache. Repeated cached IPs reduce calls,
but many distinct IPs can still exhaust the provider quota.

Network failures, provider errors, invalid responses and missing location data
do not prevent otherwise valid visit statistics from being saved. Such visits
have no country/location. Localhost visits therefore normally have browser/device
statistics but no geolocation. Existing historical locations are not backfilled.

Detailed records have no automatic retention/purge job in this repository. Restrict
access to database backups and align the deployment's privacy information and
retention process with the data actually collected and the external IP lookup.

## Tests

### Focused analytics and API tests

These PHPUnit tests create their own SQLite in-memory schema and simulate the
geolocation HTTP stream; they need no production/local MySQL data or real API key:

```bash
php vendor/bin/phpunit --bootstrap tests/_bootstrap.php tests/unit/components
php vendor/bin/phpunit --bootstrap tests/_bootstrap.php tests/unit/api
```

They cover statistics persistence, provider failures/cache, referral hostname
handling, tablet detection, period aggregation, full-history defaults and safe
view rendering. API tests use a synthetic account and isolated SQLite schema to
exercise JSON/field validation, successful operations, response compatibility and
CORS/preflight through the Yii controllers. They do not prove live provider
availability or every application flow.

### Documentation sidebar

The documentation sidebar also has dependency-free interaction checks using
Node.js's built-in test runner (verified locally with Node.js 26):

```bash
node --test tests/js/docs-navigation.test.cjs
```

These cover active-section accessibility, responsive index state and anchor
activation. Browser layout and Bootstrap scroll tracking still need browser checks.

### Legacy Codeception suites

`codeception.yml` loads **`config/test.php`**, which is ignored by Git and is not
supplied by this checkout. To prepare a separate test environment:

1. Create a dedicated disposable MySQL database; never reuse application or
   production data. Apply the same migrations to that database using explicitly
   test-targeted console configuration.
2. Create ignored `config/test_db.php` with a Yii database connection array
   targeting that test database.
3. Create ignored `config/test.php` returning a Yii **web application** config.
   It must load `config/test_db.php`, configure request cookie validation and the
   `app\models\User` identity class, and use `yii\swiftmailer\Mailer` with file transport.
   It should also provide the application's formatter, URL rules, parameters and
   any other components exercised by the selected suite. Use `config/web.php` as a
   structural reference, not an unreviewed include of local application credentials
   or email log targets.
4. Supply isolated test data and adapt the legacy assertions to the current
   models, routes and Portuguese messages. The existing model tests assume specific
   users/tokens; some functional tests still refer to Yii template contact/login
   behavior. Merely creating `config/test.php` does not make those suites valid.
5. Generate actor support and run the relevant suite:

```bash
php vendor/bin/codecept build
php vendor/bin/codecept run unit,functional
```

Acceptance tests are disabled by default. `tests/acceptance.suite.yml.example`
requires a separately configured WebDriver/browser environment and references
`index-test.php`, which is not supplied in `web/`. Before enabling it, provide a
reviewed test-only entry point, verify the WebDriver module is installed, and
configure the actual server URL/browser. No Selenium/browser version or complete
passing acceptance setup is currently maintained here.

Do not run `composer update` or install old Selenium images merely to follow the
legacy test examples. Verify required tools against the committed dependency versions.

## Updates and operations

For an existing installation:

1. Back up the database and preserve the currently deployed code/configuration.
2. Install the new code's locked dependencies with the selected runtime using
   `composer install` (production normally adds `--no-dev`).
3. **Apply pending migrations before switching traffic to the updated code**:
   `php yii migrate`. In particular, analytics code expects the `referrer` column.
4. Deploy/switch to the matching application code. The document root must be `web/`;
   preserve the deployment's existing rewrite/PHP-FPM configuration.
5. Verify login, a disposable link redirect, statistics and API success/error handling.
   File logs are under `runtime/logs`; the web log configuration also contains an
   email target. Confirm its deployment-specific delivery configuration.

`web/index.php` currently defaults to development/debug mode; production needs
explicitly reviewed production flags and must not expose the debug/Gii modules.
There is no verified deployment automation in this repository. The development
server, legacy Docker image and Vagrant Nginx example are not production deployment
instructions.

Recent migrations:

| Migration | Effect | Rollback consideration |
| --- | --- | --- |
| `m261112_130000_cascade_link_stats_on_delete` | Link deletion cascades to associated visit records | Reverting restores `SET NULL` for future deletions; it cannot recover deleted statistics |
| `m261112_140000_add_link_stats_referrer` | Adds nullable referral hostname and `(link_id, created_at)` index | Reverting drops collected referral hostnames/index, but retains visit rows |

Review `php yii migrate/history` and migration dependencies before selecting a
rollback. Prefer switching to compatible previous code while keeping additive
schema changes. Do not blindly run `migrate/down`: dropping a column or deleting
visit data is not undone by restoring application files. Recover lost data from a
verified backup where required.
