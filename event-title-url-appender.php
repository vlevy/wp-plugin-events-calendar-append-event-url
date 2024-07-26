<?php
/*
Plugin Name: Event Title Link Appender
Description: Appends a "Link" anchor to event titles in the content that directs users to the original event listing.
Version: 1.1
Author: vlevy
*/

// Hook into 'the_content' filter to modify the event content output.
add_filter('the_content', 'append_url_to_event_title_content');

function append_url_to_event_title_content($content)
{
    // Check if we're inside the main loop and if it's a single Event post of The Events Calendar plugin.
    if (is_singular(Tribe__Events__Main::POSTTYPE) && in_the_loop() && is_main_query()) {
        // Retrieve the URL from the post meta.
        $event_url = get_post_meta(get_the_ID(), '_EventURL', true);

        // Append the anchor tag to the title if the URL is not empty.
        if (!empty($event_url)) {
            $title = get_the_title();
            $title_with_link = $title . ' <a href="' . esc_url($event_url) . '" class="event-url-link" target="_blank">(Site)</a>';
            // Replace the title in the content
            $content = str_replace($title, $title_with_link, $content);
        }
    }

    return $content;
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
