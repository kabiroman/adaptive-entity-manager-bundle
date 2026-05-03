# RFC: Symfony 8 support for Adaptive Entity Manager Bundle

## Status

Draft — **not implemented**. Intended for maintainers; revise when Symfony 8 is stable and [Upgrade Guides](https://github.com/symfony/symfony/blob/8.0/UPGRADE-8.0.md) (or equivalent) are final.

## Context

This bundle currently constrains Symfony components to **`^6.4 || ^7.0`** and tests CI on PHP 8.1–8.4 with Symfony 6.4 and 7.x.

The core package `kabiroman/adaptive-entity-manager` depends on **`symfony/cache`** and **`symfony/string`** **`^6.0 || ^7.0`**. Symfony Framework 8 applications will expect compatible major versions of those components and of **`symfony/framework-bundle`** / **`symfony/event-dispatcher`** used by this bundle.

## Goals

- Allow **composer install/update** in applications using **Symfony 8** without version conflicts with AEM core or this bundle.
- Extend **GitHub Actions** with at least one **PHP × Symfony 8** job (within Symfony’s stated minimum PHP for 8.x).
- Document **bundle + core** version pairings and any **minimum PHP** changes for consumers.

## Non-goals

- Dropping Symfony 6.4 or 7.x support in the **same** change set as adding 8 (unless maintainers choose a major bundle release and explicit deprecation policy).
- Rewriting bundle internals for Symfony 8-only APIs unless required by upstream BC breaks.

## Constraints (verify at Symfony 8 GA)

- **Minimum PHP** for Symfony 8: confirm in official docs (often raises floor relative to Symfony 7).
- **Dev dependencies**: `symfony/phpunit-bridge`, `symfony/yaml`, `symfony/monolog-bundle` (and any other pinned Symfony packages) must allow **`^8.0`** where applicable.
- **Core first**: extend `kabiroman/adaptive-entity-manager` **`symfony/cache`** and **`symfony/string`** to include **`^8.0`**, run core **PHPUnit** and **PHPStan** on a tree resolved with Symfony 8, tag a core release if needed before or with bundle release.

## Proposed work

### 1. `kabiroman/adaptive-entity-manager`

- In `composer.json`, widen:
  - `symfony/cache`: `^6.0 || ^7.0 || ^8.0`
  - `symfony/string`: `^6.0 || ^7.0 || ^8.0`
- Add CI matrix jobs (or local verification) that resolve **Symfony 8** components alongside the library.
- Update **README** / **CHANGELOG** with supported Symfony component majors.

### 2. `kabiroman/adaptive-entity-manager-bundle`

- In `composer.json` **`require`**:
  - `symfony/event-dispatcher`, `symfony/framework-bundle`: e.g. `^6.4 || ^7.0 || ^8.0` (exact lower bound for 8.x to match Symfony’s semver).
- In **`require-dev`**, align:
  - `symfony/phpunit-bridge`, `symfony/yaml` (and `symfony/monolog-bundle` if constrained) with **`^8.0`**.
- Revisit bundle **`php`** constraint: if Symfony 8 requires PHP > 8.1, either raise **`>=8.x`** or document that Symfony 8 installs imply a higher runtime floor than the bundle’s generic minimum.
- Run **PHPUnit** and fix any deprecations or API removals reported under Symfony 8.
- Update **README** compatibility table (PHP × Symfony).
- Update **CHANGELOG** with a clear entry (minor vs major per SemVer rules below).

### 3. CI (`.github/workflows/ci.yml`)

- Add matrix includes, e.g. `symfony-deps: "8"` with PHP version(s) allowed by Symfony 8.
- Extend the install step so `SF='^8.0'` when `symfony-deps` is `8`, consistent with existing `6.4` / `7` branches.

## Versioning (bundle)

- **Minor** (e.g. 3.2.0): add Symfony 8 to **existing** supported Symfony lines without removing 6.4/7 and without breaking public bundle API.
- **Major** (e.g. 4.0.0): drop Symfony 6.x and/or raise minimum PHP for the bundle as a whole.

## Rollout checklist

- [ ] Symfony 8.0 **released**; read **UPGRADE-8.0** and release notes.
- [ ] Core: widen `symfony/cache` + `symfony/string`; CI green; tagged core release if constraint change shipped separately.
- [ ] Bundle: widen Symfony deps; dev deps; `composer update` on SF8; tests green.
- [ ] CI matrix updated; README + CHANGELOG updated.
- [ ] Packagist / tags published; optional blog or GitHub release notes for consumers.

## References

- [Symfony 8.0 upgrade notes](https://github.com/symfony/symfony/blob/8.0/UPGRADE-8.0.md) — when branch/tag is available.
- This bundle: [README](../../README.md), [CHANGELOG](../../CHANGELOG.md).
- Core: [adaptive-entity-manager](https://github.com/kabiroman/adaptive-entity-manager) `composer.json` and CI.
