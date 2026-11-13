---
name: rest-sites-blackbox
description: Exploratory black-box test of the `wp/v2/sites` REST endpoint (RestSitesEndpoint module, Trac #40365) against the running wp-env multisite — real curl requests per route/arg plus wp-cli side-effect checks — producing a ranked findings report with test matrices. Use when asked to "test the sites endpoint", "black-box/smoke test /wp/v2/sites", reproduce a Trac test report, or verify a fix in modules/RestSitesEndpoint/patches/ end to end.
---

# Black-box test: `/wp/v2/sites`

Tests the controller in `modules/RestSitesEndpoint/patches/class-wp-rest-sites-controller.php` over HTTP rather than through PHPUnit. The regression suite stays in `modules/RestSitesEndpoint/tests/core/`. This skill finds what that suite misses. The goal is to find bugs, not to confirm expectations, so record actual codes and side effects exactly as observed.

## 1. Setup

```bash
npm run wp-env start                                   # dev: http://localhost:8888
npx wp-env run cli sh -c 'mkdir -p wp-content/mu-plugins && cat > wp-content/mu-plugins/rest-site-meta-test.php' \
  < .claude/skills/rest-sites-blackbox/rest-site-meta-test.php
PW=$(npx wp-env run cli wp user application-password create admin rest-blackbox --porcelain 2>/dev/null | tail -1)
B=http://localhost:8888/wp-json/wp/v2/sites
```

Keep `$PW` in a scratchpad file, because shell state doesn't persist between calls. Basic auth over HTTP works because wp-env runs with `WP_ENVIRONMENT_TYPE=local`.

Seed: create 3–4 subsites (`wp site create --slug=…`). At least one of them needs a second user (`wp user create` and then `wp user set-role --url=…`) for the soft-delete check.

The mu-plugin registers `rest_test_{string,boolean,integer,array,multi}` plus `rest_test_hidden` (`show_in_rest => false`, the negative control).

## 2. Matrix

Read the args from the schema first (`curl -s -X OPTIONS -u admin:$PW $B`, and the same for `$B/<id>`) and don't work from memory. For **every** arg of every method, cover:

- valid value
- wrong type (string/array/int as applicable)
- out of range or invalid enum
- unauthenticated

Request pattern: `curl -s -u "admin:$PW" -H 'Content-Type: application/json' -X <M> -d '<json>' -w '\n%{http_code}\n' <url>`

Sections:
- `GET /sites` (collection): all query params, `HEAD`, pagination bounds (`page`, `per_page`, `offset` ≤ 0)
- `GET /sites/{id}`: contexts, non-existent/negative/zero/float/non-numeric id, meta visibility (hidden key absent)
- `POST /sites`: missing/invalid each field, **duplicate domain+path**, invalid user, meta of each type with wrong values, hidden/unregistered meta keys
- `PUT`/`PATCH /sites/{id}`: each editable field, non-editable fields (create-only args) ignored, domain change → check `siteurl`/`home`, main site
- `DELETE /sites/{id}`: main site (with and without force), soft delete (users before/after, then `PUT {"deleted":false}`: are the users back?), repeat soft delete, `force=true` (GET → 404, `wp_blogmeta` rows gone), invalid `force` values

**Order matters:**
- Soft delete: check `wp user list --url=…` *before* any `force=true` on the same site. After a force delete there's nothing left to inspect.
- Main-site writes (`PUT /sites/1` with `archived`/`spam`/`deleted`/`mature`/`path`/`domain`) can take the **whole network** down, the API included, so the API can't undo them. Run them **last**, one at a time, and restore right after each one. `wp site unarchive 1` and similar commands refuse to touch the main site, so use:
  ```bash
  npx wp-env run cli wp eval 'update_blog_status(1,"archived","0");'   # same for spam/deleted/mature
  npx wp-env run cli wp db query "UPDATE wp_blogs SET path='/', domain='localhost:8888' WHERE blog_id=1"
  npx wp-env run cli wp cache flush
  ```
  Stop testing a flag once its guard is shown to be missing. A second outage doesn't add information.
- `POST {"path":"/wp-json/"}` takes the whole REST API down: every `/wp-json/…` request is routed to the new site, and only `?rest_route=` still works. Treat it like a main-site write: run it last, then restore right away with `npx wp-env run cli wp site delete <id> --yes`.
- Use paths that are unique to this run (e.g. a per-run prefix like `/r<date>-…/`), because the env keeps the sites from earlier runs. A 400 `rest_site_taken` on a path you expected to be rejected for validation reasons most likely comes from a leftover, not from validation. Retest it with a fresh path before you record it as ✅.

If you script the matrix, use `|` as the field separator and not tabs. `read` collapses empty tab fields, and that silently shifts the auth column.

**After every write, check the side effects. A 4xx doesn't prove nothing changed.**

```bash
npx wp-env run cli wp site list --fields=blog_id,url,deleted
npx wp-env run cli wp user list --url=<site-url> --fields=ID,user_login
npx wp-env run cli wp db query "SELECT * FROM wp_blogmeta WHERE blog_id=<id>"
```

If a previous report exists, start by re-probing its findings.

## 3. Report

Write the report to the scratchpad as `rest-sites-report-<date>.md`:

1. Header: date, env (`wp-env`, WP version via `wp core version`), branch/commit, auth method, setup
2. **Findings**, ranked 🔴 HIGH, 🟠 MEDIUM, 🟡 LOW, ℹ️ Info. Each finding gets **Repro** (exact request plus observed response), **Cause** (`file:line` in the controller) and **Impact**.
3. **Test matrices**, one table per section: `Test case | Request | Expected | Actual | Result` (✅ / ⚠️ with a finding reference)
4. Scope notes: what wasn't tested

## 4. Teardown

```bash
npx wp-env run cli rm wp-content/mu-plugins/rest-site-meta-test.php
npx wp-env run cli wp user application-password delete admin --all
# full reset: npm run wp-env clean development
```
