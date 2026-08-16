# Papelooo — Brand Guide

Quick reference for colors, type, and logo usage across the app.

---

## Logo

| File | Use it for |
|---|---|
| `papelooo-lockup.svg` | Primary logo — nav bars, headers, marketing pages |
| `papelooo-mark.svg` | Icon alone — tight spaces, mobile header, loading states |
| `papelooo-wordmark.svg` | Text alone — footer, legal pages, anywhere the icon feels redundant |
| `papelooo-icon-tile.svg` | Source for app icons / favicons (square, full-bleed) |

**Clear space:** leave at least the height of the pencil-tip circle empty around the mark on all sides.
**Minimum size:** don't run the lockup below ~120px wide, or the mark alone below ~24px — use `favicon-16x16.png` at that scale instead of shrinking the SVG.

---

## Color Palette

| Name | Hex | Role |
|---|---|---|
| Paper | `#F5F1E6` | Base background |
| Ink | `#22314A` | Primary text, logo, headings |
| Teal | `#3F7D6B` | Primary accent — buttons, links, the bubble mark |
| Margin Red | `#B5514A` | Secondary accent, used sparingly (exercise-book margin line) |
| Gold | `#C79A46` | Rare highlight — badges, streaks, achievements only |

Don't introduce new brand colors without checking here first — if something needs a new color, it's usually a sign the palette should flex (e.g. a lighter teal tint) rather than adding a new hue.

---

## Typography

| Typeface | Use | Weight / settings |
|---|---|---|
| **Fraunces** | Logo, page headings, hero text | Variable font — opsz 56–60, weight 450–500, SOFT 10–15, WONK 0 |
| **Inter** | Body text, UI, forms, buttons | Regular 400 / Medium 500 / Semibold 600 |

Google Fonts import:
```html
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght,SOFT,WONK@9..144,300..700,0..100,0..1&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
```

Fraunces is a display face — use it for anything above ~24px. Below that (labels, captions, table text), always use Inter; Fraunces gets muddy at small sizes.

---

## Background Treatments

Ruled-paper + margin-line motif, in three intensities. Add to `resources/css/app.css`:

```css
:root {
  --paper: #F5F1E6;
  --ink: #22314A;
  --teal: #3F7D6B;
  --margin-red: #B5514A;
  --gold: #C79A46;
  --rule-line: rgba(34, 49, 74, 0.07);
  --rule-line-quiet: rgba(34, 49, 74, 0.045);
  --rule-line-ambient: rgba(34, 49, 74, 0.025);
}

.bg-examsheet-hero {
  background-color: var(--paper);
  background-image:
    repeating-linear-gradient(180deg, var(--rule-line) 0px, var(--rule-line) 1px, transparent 1px, transparent 32px),
    linear-gradient(90deg, transparent 0, transparent 63px, var(--margin-red) 63px, var(--margin-red) 64px, transparent 64px);
}

.bg-examsheet-quiet {
  background-color: var(--paper);
  background-image: repeating-linear-gradient(180deg, var(--rule-line-quiet) 0px, var(--rule-line-quiet) 1px, transparent 1px, transparent 32px);
}

.bg-examsheet-ambient {
  background-color: #F7F4EC;
  background-image: repeating-linear-gradient(180deg, var(--rule-line-ambient) 0px, var(--rule-line-ambient) 1px, transparent 1px, transparent 32px);
}
```

| Class | Where |
|---|---|
| `.bg-examsheet-hero` | Marketing / landing layout (`<body>`) |
| `.bg-examsheet-quiet` | Auth layout — login/register (`<body>`) |
| `.bg-examsheet-ambient` | Dashboard `<main>` content area only — not the sidebar |

---

## Quick copy-paste: Tailwind color tokens

If you're extending Tailwind's theme instead of using raw CSS vars:

```js
colors: {
  paper: '#F5F1E6',
  ink: '#22314A',
  teal: '#3F7D6B',
  'margin-red': '#B5514A',
  gold: '#C79A46',
}
```