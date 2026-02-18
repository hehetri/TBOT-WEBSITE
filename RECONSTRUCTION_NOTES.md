# TBOT 2011 Reconstruction (text-only safe PR)

This revision addresses review feedback about binary-heavy placeholder commits.

## What was changed
- Removed binary placeholder strategy entirely.
- Kept historical HTML/CSS/JS structure intact.
- Rewrote internal absolute routes to depth-correct relative links (for static hosts).
- Removed any remaining Wayback wrappers/references in HTML.
- Added required static deployment directories:
  - `images/`
  - `js/`
  - `assets/`
- Added placeholder HTML pages **only** for missing internal pages referenced by navigation routes:
  - `news/`
  - `downloads/ss/`
  - `gameinfo/character/`
  - `gameinfo/howtoplay/`
  - `gameinfo/install/`
  - `gameinfo/newbie/sub/1/`
  - `gameinfo/newbie/sub/4/`
  - `itemmall/part/`
  - `itemmall/skill/`
  - `itemmall/spec/`

Placeholder content:
> This page was not archived in 2011.

## Deployment
Publish the repository root as a static site.
Compatible with GitHub Pages, Netlify, Vercel static, Apache, and Nginx.

