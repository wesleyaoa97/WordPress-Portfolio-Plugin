<?php
// Shortcode to Display Portfolio Grid
function custom_portfolio_grid_shortcode() {
    ob_start();

    $query = new WP_Query(array(
        'post_type'      => 'portfolio',
        'posts_per_page' => -1, // Show all portfolio items
    ));

    if ($query->have_posts()) {
        ?>
        <div class="portfolio-container">
            <!-- Category Filters -->
            <div class="portfolio-category-container">
                <ul class="portfolio-filters">
                    <li class="portfolio-filter active" data-filter="all">
                        <a href="#" class="portfolio-category-link">All</a>
                    </li>
                    <?php
                    $categories = get_terms('portfolio_category');
                    if (!empty($categories) && !is_wp_error($categories)) {
                        foreach ($categories as $category) {
                            echo '<li class="portfolio-filter" data-filter="' . esc_attr($category->slug) . '">
                                <a href="#" class="portfolio-category-link">' . esc_html($category->name) . '</a>
                            </li>';
                        }
                    }
                    ?>
                </ul>
            </div>

            <!-- Portfolio Grid -->
            <div class="portfolio-grid">
                <?php while ($query->have_posts()) : $query->the_post(); ?>
                    <?php
                    $terms = get_the_terms(get_the_ID(), 'portfolio_category');
                    $category_slugs = [];
                    $category_names = [];

                    if ($terms && !is_wp_error($terms)) {
                        foreach ($terms as $term) {
                            $category_slugs[] = $term->slug;
                            $category_names[] = $term->name;
                        }
                    }

                    $category_classes = implode(" ", $category_slugs);
                    $category_display = implode(", ", $category_names);
                    ?>

                    <div class="portfolio-item" data-categories="<?php echo esc_attr(!empty($category_classes) ? $category_classes : 'uncategorized'); ?>">
                        <div class="portfolio-image">
                            <?php if (has_post_thumbnail()) : ?>
                                <img src="<?php echo esc_url(get_the_post_thumbnail_url(get_the_ID(), 'medium')); ?>" alt="<?php the_title(); ?>">
                            <?php else : ?>
                                <img src="https://via.placeholder.com/300x200" alt="Placeholder Image">
                            <?php endif; ?>
                            <div class="overlay">
                                <a href="<?php the_permalink(); ?>" class="view-project">View Project</a>
                            </div>
                        </div>
                        <div class="portfolio-content">
                            <h3 class="portfolio-title"><?php the_title(); ?></h3>
                            <p class="portfolio-category"><?php echo esc_html($category_display); ?></p>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
        <?php
    } else {
        echo '<p>No portfolio items found.</p>';
    }
    wp_reset_postdata();
    
    return ob_get_clean();
}
add_shortcode('portfolio_grid', 'custom_portfolio_grid_shortcode');

function portfolio_tag_shortcode($atts) {
    $atts = shortcode_atts(array(
        'tag' => ''
    ), $atts);

    if (empty($atts['tag'])) {
        return '<p>No portfolio tag provided.</p>';
    }

    $query_args = array(
        'post_type' => 'portfolio',
        'tax_query' => array(
            array(
                'taxonomy' => 'portfolio_tag',
                'field'    => 'slug',
                'terms'    => $atts['tag'],
            ),
        ),
        'posts_per_page' => -1 // Show all tagged portfolio items
    );

    $query = new WP_Query($query_args);
    ob_start();

    if ($query->have_posts()) {
        ?>
        <div class="portfolio-container">
            <!-- Portfolio Grid -->
            <div class="portfolio-grid">
                <?php while ($query->have_posts()) : $query->the_post(); ?>
                    <?php
                    $terms = get_the_terms(get_the_ID(), 'portfolio_category');
                    $category_slugs = [];
                    $category_names = [];

                    if ($terms && !is_wp_error($terms)) {
                        foreach ($terms as $term) {
                            $category_slugs[] = $term->slug;
                            $category_names[] = $term->name;
                        }
                    }

                    $category_classes = implode(" ", $category_slugs);
                    $category_display = implode(", ", $category_names);
                    ?>

                    <div class="portfolio-item" data-categories="<?php echo esc_attr(!empty($category_classes) ? $category_classes : 'uncategorized'); ?>">
                        <div class="portfolio-image">
                            <?php if (has_post_thumbnail()) : ?>
                                <img src="<?php echo esc_url(get_the_post_thumbnail_url(get_the_ID(), 'medium')); ?>" alt="<?php the_title(); ?>">
                            <?php else : ?>
                                <img src="https://via.placeholder.com/300x200" alt="Placeholder Image">
                            <?php endif; ?>
                            <div class="overlay">
                                <a href="<?php the_permalink(); ?>" class="view-project">View Project</a>
                            </div>
                        </div>
                        <div class="portfolio-content">
                            <h3 class="portfolio-title"><?php the_title(); ?></h3>
                            <p class="portfolio-category"><?php echo esc_html($category_display); ?></p>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
        <?php
    } else {
        echo '<p>No portfolio items found under this tag.</p>';
    }

    wp_reset_postdata();
    return ob_get_clean();
}
add_shortcode('portfolio_tag_grid', 'portfolio_tag_shortcode');

