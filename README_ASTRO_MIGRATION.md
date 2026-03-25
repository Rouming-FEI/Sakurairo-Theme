# Sakurairo Astro Migration

This repository now includes an Astro implementation for the original WordPress theme.

## Quick start

1. Install dependencies:
   npm install
2. Run development server:
   npm run dev
3. Build static site:
   npm run build

## Migrated structure

- src/layouts/BaseLayout.astro: replacement of header.php + footer.php shell
- src/pages/index.astro: replacement of index.php post list
- src/pages/page/[page].astro: paginated list route
- src/pages/posts/[slug].astro: replacement of single.php
- src/pages/about.astro: replacement of user/template-about.php
- src/pages/archive/index.astro: archive route
- src/pages/categories/index.astro + src/pages/categories/[category].astro: category routes
- src/pages/tags/index.astro + src/pages/tags/[tag].astro: tag routes
- src/pages/authors/index.astro + src/pages/authors/[author].astro: author routes
- src/pages/search.astro + src/pages/search-data.json.ts: search route and data endpoint
- src/pages/404.astro: replacement of 404.php
- src/content/posts/*.md: markdown post source

## WordPress to Astro migration strategy

1. Keep visual identity via shared classes and color tokens.
2. Move dynamic data from WordPress API/template tags to content collections.
3. Replace PHP template partials with Astro components.
4. Replace plugin-dependent features with static-first alternatives.

## Current status

- Archive and tag routes are completed
- Category and author routes are completed
- Search is completed with static JSON endpoint
- Post page includes Giscus comment component integration

## Remaining tasks

- Existing JS effects modularization
- i18n routing
