# WordPress starter theme by Evan Buckiewicz

## Overview
This repository contains my personal starter theme for building modern, performant, and accessible block-based WordPress websites.  It is designed to be a flexible "mycelium" from which custom, high-quality themes can grow. Built on the minimal Underscores base, with a focus on enqueuing only what is necessary.

## Key Features

### Multilingual Support
- Integrates with Polylang plugin
- Fully localized
- Theme specific localized strings internationalized to Canadian French
- Language-specific templates for footer and 404 pages
- Automatic language switching in navigation

### Navigation Systems
- **Page Sidebar Navigation**: Auto-generates for page hierarchies
- **Post Category Navigation**: Shows related posts in sidebar
- Both support accordion-style submenus
- **Primary Navigation**: Mobile-responsive with custom walker for A11Y

### Search Functionality
- Toggle-able search bar in header
- Typeahead autocomplete via WordPress REST API
- Searches posts and categories

### Block Editor Customizations
- Custom block patterns (registration in block-patterns.php)
- Editor-specific styles and scripts
- Cover template with live background preview
- Disabled wide/full alignments for sidebar layouts

### Custom Shortcodes
Available shortcodes (see inc/shortcodes.php):
- `[article_card_carousel]` - Display post cards with filtering
- Various parameters for category, tag, count, layout options

### Responsive Design
- Mobile-first approach
- Breakpoints defined in SASS abstracts
- Touch-friendly navigation and interactions

### Performance Optimizations
- Conditional script loading (Contact Form 7)
- Minimal dependencies

## Template Hierarchy

### Pages
1. Pages with parent/child relationships → Sidebar navigation displayed
2. Cover template (tmpl-cover.php) → Special layout with featured image
3. Default → Standard page layout

### Posts
1. Single posts → Category sidebar with related posts
2. Category listing with optional footer content
3. Custom Structured Data for optimized Articles

### Custom Post Types
The theme checks for custom templates:
- Registered in `/inc/model`
- `template-parts/content-single-{post-type}.php`
- Falls back to default page template if not found

## Customization Guide

### Styling
All styles are in SASS. Compile after changes:
- Source: `/sass/style.scss`
- Output: `style.css`
- Source: `/sass/css/`
- Output `/css/`

### SVG Icons
Multiple SVG functions in `/inc/svg.php` for icons throughout theme

## Plugin Integration

### Recommended Plugins
- **Polylang** - Multilingual support
- **Advanced Custom Fields (ACF)** - Streamlined field management
- **Contact Form 7** - Contact forms 
- **Events Manager** - Event management (optional)
- **WooCommerce** - E-commerce (optional, has template override)

### Plugin-Specific Code
- WooCommerce check in single.php
- Polylang language checks throughout
- ACF JSON folder for field synchronization

## Accessibility Features
- Skip to content link
- ARIA labels on navigation
- ARIA expanded/hidden states on toggles
- Screen reader text where appropriate
- Semantic HTML structure

## Security Considerations
- Theme file editor disabled
- Sanitization functions used throughout
- Escaped output where appropriate

## Notes for Customization
- Text domain: `sbtl`
- Version defined in functions.php as `SBTL_VERSION`
- Block locking is disabled by default
- Default WordPress block patterns are removed (theme-specific only)
