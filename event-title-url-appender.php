<?php
/*
Plugin Name: Event Title URL Appender
Description: Appends a "Link" anchor to event titles that directs users to the original event listing.
Version: 1.0
Author: vlevy
*/

// Hook into 'the_title' filter to modify the event title output.
add_filter('the_title', 'append_url_to_event_title', 10, 2);

function append_url_to_event_title($title, $id)
{
    // Check if we're inside the main loop in a single Event post of The Events Calendar plugin.
    if (is_single() && get_post_type() == Tribe__Events__Main::POSTTYPE && in_the_loop()) {
        // Retrieve the URL from the post meta.
        $event_url = get_post_meta($id, '_EventURL', true);

        // Append the anchor tag to the title if the URL is not empty.
        if (!empty($event_url)) {
            $title .= ' <a href="' . esc_url($event_url) . '" class="event-url-link" target="_blank">(Site)</a>';
        }
    }

    return $title;
}

// Add some basic styling for the link.
add_action('wp_head', 'add_event_title_link_styles');
function add_event_title_link_styles()
{
    echo '<style>
    .event-url-link {
        font-size: 75%; /* Decrease this percentage to make the font smaller */
        text-decoration: none;
        margin-left: 0px;
    }
    </style>';
}
?>