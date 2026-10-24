<?php
// Standalone regression checks; not loaded by WordPress.
function add_filter(...$args) {}
function add_action(...$args) {}
function is_admin() { return $GLOBALS['admin'] ?? false; }
function is_single() { return $GLOBALS['single'] ?? true; }
function in_the_loop() { return true; }
function get_queried_object_id() { return 100; }
function get_post_type($id = null) { return 'tribe_events'; }
function get_post_meta($id, $key, $single) { return $GLOBALS['url'] ?? 'https://example.org/tickets'; }
function esc_url($url) { return htmlspecialchars($url, ENT_QUOTES); }
require __DIR__ . '/event-title-url-appender.php';
function check($condition, $label) {
    if (!$condition) { throw new RuntimeException($label); }
    echo "PASS: $label\n";
}
check(str_contains(append_url_to_event_title('Current event', 100), '(Site)'), 'Main event retains source link');
check(append_url_to_event_title('Previous event', 99) === 'Previous event', 'Previous title has no nested source anchor');
check(append_url_to_event_title('Next event', 101) === 'Next event', 'Next title has no nested source anchor');
$GLOBALS['admin'] = true;
check(append_url_to_event_title('Admin title', 100) === 'Admin title', 'Admin titles unchanged');
$GLOBALS['admin'] = false;
$GLOBALS['single'] = false;
check(append_url_to_event_title('Search title', 100) === 'Search title', 'Search titles unchanged');
$GLOBALS['single'] = true;
$GLOBALS['url'] = '';
check(append_url_to_event_title('No website', 100) === 'No website', 'Absent source URL adds no link');
