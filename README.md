# Event Title URL Appender

A WordPress plugin for PIANYC that adds a **(Site)** link beside the main title on
an individual [The Events Calendar](https://theeventscalendar.com/) event page.
The link opens the original event listing in a new tab.

Current version: **1.0.3**.

## Behavior

The plugin filters WordPress's `the_title` output. It appends the link only when
rendering the currently queried `tribe_events` post, on a single-event page,
inside the WordPress loop, outside the administration area.

The destination comes from that event's `_EventURL` metadata and is escaped with
`esc_url()`. If the field is empty, no link is appended. The link has the
`event-url-link` CSS class and uses `target="_blank"` with `rel="noopener"`.

Previous/next event titles are excluded because their post IDs differ from the
currently queried event's ID. Search results and ordinary posts are also
unaffected. This is a rendering change; it does not modify stored titles or URLs.

The title filter is not restricted to a particular heading element: a theme that
renders the current event's title more than once inside the loop may receive the
link more than once. Verify the output when changing themes or templates.

## Why previous/next titles are excluded

The Events Calendar also passes neighboring event titles through `the_title`
before placing those titles inside PIANYC navigation links. Appending an external
anchor at that point creates invalid nested links. Version 1.0.3 keeps the source
link beside the main event title and leaves neighboring titles as normal PIANYC
links, without a separate **(Site)** link.

## Installation

1. Create `wp-content/plugins/event-title-url-appender/` in the WordPress site.
2. Copy `event-title-url-appender.php` into that directory. `test.php` is for local
   checks and does not need to be deployed.
3. Activate **Event Title URL Appender** in WordPress's Plugins screen.

The plugin is designed for WordPress with The Events Calendar. Its PHP code does
not depend on Kadence; the optional CSS below does depend on Kadence and TEC markup.

Deactivate the plugin to remove its title link and built-in styling. Theme CSS
saved separately in WordPress remains until removed there.

## CSS ownership and Kadence integration

The plugin emits a small style block through `wp_head` for `.event-url-link`:

```css
.event-url-link {
    font-size: 75%;
    text-decoration: none;
    margin-left: 0px;
}
```

It does not set the link color or truncate navigation titles. On staging, those
presentation choices are managed separately in **Appearance → Customize →
Additional CSS**. Installing or updating this plugin does not install that CSS.
When migrating the theme configuration to production, carry over the applicable
Additional CSS separately. The snippets below document the rules used during the
Kadence migration; review them against the site's saved CSS before deploying.

### Main title's source-link color

```css
/* Use the theme's link colors for event source links */
.single-tribe_events a.event-url-link {
    color: var(--global-palette-highlight);
}

.single-tribe_events a.event-url-link:hover,
.single-tribe_events a.event-url-link:focus {
    color: var(--global-palette-highlight-alt);
}
```

These variables follow Kadence's theme palette. Another theme may require
different variables or explicit colors.

### Compact previous/next navigation

```css
/* Give previous/next events equal, bounded columns */
.single-tribe_events #tribe-events-footer .tribe-events-sub-nav {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 24px;
}

.single-tribe_events #tribe-events-footer .tribe-events-sub-nav > li {
    width: auto;
    min-width: 0;
    float: none;
}

/* Truncate long titles visually; preserve their full link text */
.single-tribe_events #tribe-events-footer .tribe-events-sub-nav > li > a {
    display: block;
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
}
```

This CSS affects bottom navigation only. Long displayed titles end in an ellipsis;
their full text remains in the HTML, and their event URLs are unchanged. The plugin
controls whether **(Site)** is appended; this CSS controls layout. CSS alone does
not correct nested anchors. Check narrow screens and long titles after theme or
calendar updates.

Other site adjustments, such as event-page widths, spacing, metadata font sizes,
and calendar-button width, are outside this plugin's scope.

## Checks

Run the standalone regression checks with PHP 8 or later:

```bash
php test.php
php -l event-title-url-appender.php
```

The checks use WordPress stubs and cover the main title link, exclusion of previous
and next titles, admin/search contexts, and an absent source URL. They do not boot
WordPress or test browser styling.

After installation, verify on staging:

1. An event with an original website has a working **(Site)** link by its main title.
2. An event without that field has no appended link.
3. Previous/next titles link to PIANYC events without **(Site)** links.
4. Long navigation titles truncate when the optional CSS is present.
5. Article and search-result titles remain unchanged.

## Version notes

1. **1.0.3:** Keep the main-title source link, exclude neighboring titles, and use
  `rel="noopener"` for the external link. This is the final staging-tested version.
2. **1.0.2:** Intermediate version that added separate source links after neighboring
  navigation links; superseded by 1.0.3.
3. **1.0.1:** Initial scoped-title correction prepared during testing.
4. **1.0:** Original title-appending implementation.
