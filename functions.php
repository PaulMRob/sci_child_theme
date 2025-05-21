<?php
// enqueue parent styles
function people_child_enqueue_styles() {
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
}
add_action('wp_enqueue_scripts', 'people_child_enqueue_styles');

// custom post type for people @ sci
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
