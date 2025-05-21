<?php
// enqueue parent styles
function people_child_enqueue_styles() {
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
}
add_action('wp_enqueue_scripts', 'people_child_enqueue_styles');

// PEOPLE cpt @ sci
function sci_register_person_cpt() {
    register_post_type('person', [
        'labels' => [
            'name' => 'People',
            'singular_name' => 'Person',
            'add_new_item' => 'Add New Person',
            'edit_item' => 'Edit Person',
            'new_item' => 'New Person',
            'view_item' => 'View Person',
            'search_items' => 'Search People',
        ],
        'public' => true,
        'has_archive' => false,
        'rewrite' => ['slug' => 'people'],
        'menu_icon' => 'dashicons-id',
        'supports' => ['title', 'editor', 'thumbnail'],
        'show_in_rest' => true,
    ]);
}
add_action('init', 'sci_register_person_cpt');

// PUBLICATIONS cpt @ sci.
function sci_register_publication_cpt() {
    register_post_type('publication', [
        'labels' => [
            'name' => 'Publications',
            'singular_name' => 'Publication',
            'add_new_item' => 'Add New Publication',
            'edit_item' => 'Edit Publication',
            'new_item' => 'New Publication',
            'view_item' => 'View Publication',
            'search_items' => 'Search Publications',
        ],
        'public' => true,
        'publicly_queryable' => false,
        'has_archive' => true,
        'rewrite' => ['slug' => 'publications'],
        'menu_icon' => 'dashicons-book-alt',
        'supports' => ['title', 'editor', 'thumbnail'],
        'show_in_rest' => true,
    ]);
}
add_action('init', 'sci_register_publication_cpt');

function sci_disable_single_publication_view() {
    if (is_singular('publication')) {
        wp_redirect(home_url('/publications/'), 301);
        exit;
    }
}
add_action('template_redirect', 'sci_disable_single_publication_view');