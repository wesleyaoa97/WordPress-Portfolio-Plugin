<?php
// Register Portfolio Post Type
function custom_portfolio_post_type() {
    $labels = array(
        'name'               => 'Portfolio',
        'singular_name'      => 'Project',
        'menu_name'          => 'Portfolio',
        'add_new'            => 'Add New',
        'add_new_item'       => 'Add New Project',
        'edit_item'          => 'Edit Project',
        'view_item'          => 'View Project',
        'all_items'          => 'All Projects',
        'search_items'       => 'Search Projects',
        'not_found'          => 'No projects found',
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'show_ui'            => true,
        'has_archive'        => true,
        'rewrite'            => array('slug' => 'portfolio-item'),
        'menu_position'      => 5,
        'supports'           => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'comments'),
        'taxonomies'         => array('portfolio_category', 'portfolio_tag'), // Add tags here

    );

    register_post_type('portfolio', $args);

    register_taxonomy(
        'portfolio_category',
        'portfolio',
        array(
            'label' => 'Categories',
            'rewrite' => array('slug' => 'portfolio-category'),
            'hierarchical' => true,
        )
    );
    register_taxonomy(
        'portfolio_tag',
        'portfolio',
        array(
            'label'             => 'Tags',
            'rewrite'           => array('slug' => 'portfolio-tag'),
            'hierarchical'      => false, // False means they work like normal post tags
            'show_admin_column' => true,
            'public'            => true, // Ensure it is publicly available
            'query_var'         => true, // Enable querying via URL
            'show_in_rest'      => true, // Enable for Gutenberg & API
        )
    );
}
add_action('init', 'custom_portfolio_post_type');