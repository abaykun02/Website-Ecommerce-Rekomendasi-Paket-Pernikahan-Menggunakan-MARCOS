---
version: alpha
name: Dribbble Minimal
description: A bright, creator-first marketplace system with crisp typography, rounded pills, and soft tonal accents.
colors:
  primary: "#0D0C22"
  secondary: "#6E6D7A"
  tertiary: "#F7F7F9"
  neutral: "#FFFFFF"
  surface: "#FAFAFB"
  on-surface: "#0D0C22"
  accent: "#FFAD48"
  highlight: "#EA4C89"
  border: "#E7E7E9"
  border-strong: "#E5E7EB"
  muted: "#F4F4F6"
  success: "#EAF8EF"
  warning: "#FFF4E5"
  error: "#E5484D"
typography:
  headline-display:
    fontFamily: Mona Sans
    fontSize: 52px
    fontWeight: 600
    lineHeight: 62.4px
    letterSpacing: 0px
  headline-lg:
    fontFamily: Mona Sans
    fontSize: 40px
    fontWeight: 600
    lineHeight: 48px
    letterSpacing: 0px
  headline-md:
    fontFamily: Mona Sans
    fontSize: 24px
    fontWeight: 600
    lineHeight: 29px
    letterSpacing: 0px
  headline-sm:
    fontFamily: Mona Sans
    fontSize: 20px
    fontWeight: 600
    lineHeight: 24px
    letterSpacing: 0px
  body-lg:
    fontFamily: Mona Sans
    fontSize: 18px
    fontWeight: 500
    lineHeight: 27px
    letterSpacing: 0px
  body-md:
    fontFamily: Mona Sans
    fontSize: 14px
    fontWeight: 500
    lineHeight: 19.6px
    letterSpacing: 0px
  body-sm:
    fontFamily: Mona Sans
    fontSize: 12px
    fontWeight: 400
    lineHeight: 16px
    letterSpacing: 0px
  label-lg:
    fontFamily: Mona Sans
    fontSize: 14px
    fontWeight: 700
    lineHeight: 16px
    letterSpacing: 0px
  label-md:
    fontFamily: Mona Sans
    fontSize: 12px
    fontWeight: 700
    lineHeight: 14px
    letterSpacing: 0px
  label-sm:
    fontFamily: Mona Sans
    fontSize: 12px
    fontWeight: 500
    lineHeight: 14px
    letterSpacing: 0px
  caption:
    fontFamily: Mona Sans
    fontSize: 11px
    fontWeight: 500
    lineHeight: 13px
    letterSpacing: 0px
rounded:
  none: 0px
  sm: 4px
  md: 8px
  lg: 12px
  xl: 20px
  full: 9999px
spacing:
  xs: 12px
  sm: 20px
  md: 34px
  lg: 60px
  xl: 128px
components:
  button-primary:
    backgroundColor: "{colors.primary}"
    textColor: "{colors.neutral}"
    typography: "{typography.label-md}"
    rounded: "{rounded.full}"
    padding: "0px 16px"
    height: "32px"
  button-primary-hover:
    backgroundColor: "{colors.highlight}"
    textColor: "{colors.neutral}"
    typography: "{typography.label-md}"
    rounded: "{rounded.full}"
    padding: "0px 16px"
    height: "32px"
  button-secondary:
    backgroundColor: "{colors.neutral}"
    textColor: "{colors.primary}"
    typography: "{typography.label-md}"
    rounded: "{rounded.full}"
    padding: "0px 16px"
    height: "32px"
  button-tertiary:
    backgroundColor: "transparent"
    textColor: "{colors.primary}"
    typography: "{typography.body-sm}"
    rounded: "{rounded.none}"
    padding: "0px"
  card:
    backgroundColor: "{colors.neutral}"
    textColor: "{colors.on-surface}"
    rounded: "{rounded.md}"
    padding: "16px"
  card-quiet:
    backgroundColor: "{colors.surface}"
    textColor: "{colors.on-surface}"
    rounded: "{rounded.md}"
    padding: "16px"
  input:
    backgroundColor: "{colors.muted}"
    textColor: "{colors.on-surface}"
    typography: "{typography.body-md}"
    rounded: "{rounded.full}"
    padding: "0px 20px"
    height: "52px"
  chip:
    backgroundColor: "{colors.neutral}"
    textColor: "{colors.secondary}"
    typography: "{typography.body-sm}"
    rounded: "{rounded.full}"
    padding: "0px 16px"
    height: "32px"
  banner:
    backgroundColor: "#F8F0FA"
    textColor: "{colors.on-surface}"
    rounded: "{rounded.full}"
    padding: "0px 20px"
    height: "64px"
  badge-accent:
    backgroundColor: "{colors.highlight}"
    textColor: "{colors.neutral}"
    typography: "{typography.label-sm}"
    rounded: "{rounded.full}"
    padding: "0px 12px"
    height: "24px"
# Dribbble Minimal

## Overview
Dribbble feels polished, airy, and creator-led, with a strong marketplace energy rather than a corporate one. The visual tone is friendly and aspirational, balancing playful color accents with a very clean white canvas and confident dark typography. The layout is spacious and editorial, designed to help browsing feel fast and visually rewarding.

## Colors
- **Primary (#0D0C22):** A deep ink used for major headlines, navigation, and the most important interactive text. It gives the interface its crisp, high-contrast backbone.
- **Accent (#FFAD48):** A warm amber used sparingly for emphasis and calls to action. It adds energy without overwhelming the page.
- **Highlight (#EA4C89):** A vivid pink used for brand moments, active emphasis, and standout UI details. It carries the playful Dribbble personality.
- **Neutral (#FFFFFF):** The dominant base color for the page and cards. It keeps the experience open, bright, and gallery-like.
- **Surface (#FAFAFB):** A soft off-white for subtle separation and background layering. Use it when white needs gentle distinction.
- **Secondary (#6E6D7A):** A muted cool gray for supportive text, metadata, and less prominent controls. It reduces visual noise while staying readable.
- **Border (#E7E7E9) and Border-strong (#E5E7EB):** Light neutral lines for pills, cards, and form fields. Borders do most of the structural work since shadows are minimal.
- **Muted (#F4F4F6):** A quiet gray used for input shells and low-emphasis UI regions. It creates soft containment without feeling heavy.
- **Success (#EAF8EF), Warning (#FFF4E5), Error (#E5484D):** Utility colors should remain restrained and functional, supporting states rather than becoming decorative.

## Typography
Mona Sans is the core family across the system, with Helvetica Neue, Helvetica, Arial, and generic sans-serif as fallbacks. Headings are semi-bold and compact, creating a modern, editorial feel that works well at large sizes. Body text is slightly heavier than typical UI defaults, improving clarity in a dense browsing environment.

`headline-display` and `headline-lg` are for hero statements and section leaders; they should feel bold but not oversized for the page. `headline-md` and `headline-sm` support cards, modules, and subsection titles. `body-md` is the main reading size for navigation, helper copy, and search prompts, while `body-sm` and the label styles are used for pills, chips, and utility controls.

There is little visible uppercase treatment or expanded tracking; the system relies more on weight and spacing than on letter-case theatrics. Labels should stay tight and legible, with minimal letter spacing.

## Layout
The page uses a wide, fluid desktop layout with generous left-right breathing room and a centered content rhythm. Sections are separated by large vertical gaps rather than strong dividers, which gives the UI a premium, scrollable feel. Use the spacing scale as a loose rhythm: `xs` for tight elements, `sm` for compact clusters, `md` for standard section grouping, and `lg` to `xl` for major page breaks.

Cards and panels should typically use 16px internal padding, with pill controls relying on horizontal padding and fixed heights rather than large content boxes. Search, chips, and filter bars should feel horizontally expansive and lightly inset, while large hero modules can occupy broader, asymmetric space.

## Elevation & Depth
Depth is intentionally restrained. The system depends more on tonal layers, thin borders, and contrast than on dramatic shadow stacks. When shadows appear, they are soft and subtle; otherwise, cards and controls remain mostly flat to preserve the clean marketplace aesthetic.

Use white surfaces against the off-white or muted background to create hierarchy. Border lines are more important than elevation for separating elements, especially in chips, buttons, and card containers.

## Shapes
The shape language is soft and approachable, with rounded pills dominating interactive elements. Buttons, chips, input bars, and banners often use `rounded.full` to create a friendly, modern feel. Cards are less rounded than controls, typically using `rounded.md`, so content surfaces feel structured while actions feel tactile.

Avoid sharp angles unless a component is purely structural or image-led. The balance should remain playful, smooth, and easy to scan.

## Components
Buttons are compact and pill-shaped. `button-primary` should be used for the strongest actions, with dark fill, white text, and 32px height. `button-secondary` is visually lighter but still pill-based, using a white background and neutral border. `button-tertiary` should be text-only or transparent, reserved for low-emphasis actions. Hover states can lean into `button-primary-hover` with the pink highlight color, but should remain restrained.

Cards should use `card` for standard content blocks: white background, 1px border, 16px padding, and minimal shadow. `card-quiet` is appropriate for softer content areas or secondary modules that sit on a muted field.

Inputs should be broad, low-profile, and highly legible. Use `input` with a muted background, full rounding, and comfortable horizontal padding. Search fields should feel like soft containers rather than heavy form controls.

Chips and filters should use `chip`, staying small, rounded, and lightly bordered. Active chips may use darker text and a white fill, while inactive chips should remain subdued in `secondary`. `badge-accent` can be used for attention moments, count markers, or promotional tags.

Banners should follow `banner`, with a soft tinted background and a wide pill shape. They should feel informational and optimistic rather than alert-like. Image-led feature cards may layer labels or mini-badges on top, but keep the text treatment restrained and high contrast.

## Do's and Don'ts
- Do keep most interactive elements pill-shaped and compact.
- Do rely on typography hierarchy and spacing for emphasis instead of heavy shadows.
- Do use dark text on white or near-white surfaces for maximum clarity.
- Do reserve pink and amber for accents, state changes, and calls to action.
- Don't introduce sharp, angular components that fight the soft marketplace feel.
- Don't overuse colored fills; the page should stay predominantly white and airy.
- Don't make buttons tall or bulky; the UI favors concise, efficient controls.
- Don't add decorative effects that reduce the clean, curated browsing experience.