<?php get_header(); ?>

<?php
$portfolio_tags = get_the_terms(get_the_ID(), 'portfolio_tag');
if ($portfolio_tags && !is_wp_error($portfolio_tags)) {
    $tag_names = array();
    foreach ($portfolio_tags as $tag) {
        $tag_names[] = $tag->name;
    }
    echo '<meta name="keywords" content="' . implode(', ', $tag_names) . '">';
}
?>
    <?php
    // Fetch stored meta fields
    $client_name = get_post_meta(get_the_ID(), '_client_name', true);
    $project_date = get_post_meta(get_the_ID(), '_project_date', true);
    $cta_url = get_post_meta(get_the_ID(), '_cta_url', true);
    $tools_used = get_post_meta(get_the_ID(), '_tools_used', true) ?: [];
    $project_type = get_post_meta(get_the_ID(), '_project_type', true) ?: [];
    $project_overview = get_post_meta(get_the_ID(), '_project_overview', true);
    $project_goals = get_post_meta(get_the_ID(), '_project_goals', true) ?: [];
    $challenges_solutions = get_post_meta(get_the_ID(), '_challenges_solutions', true) ?: [];
    $final_outcome = get_post_meta(get_the_ID(), '_final_outcome', true);
    $testimonials = get_post_meta(get_the_ID(), '_testimonials', true) ?: [];

    // Remove empty entries
    $tools_used = array_filter($tools_used, function ($percentage) {
        return $percentage !== '' && $percentage !== null;
    });
    $project_type = array_filter($project_type, function ($percentage) {
        return $percentage !== '' && $percentage !== null;
    });
    $project_goals = array_filter($project_goals);
    $challenges_solutions = array_filter($challenges_solutions, function ($challenge) {
        return !empty(array_filter($challenge));
    });
    $testimonials = array_filter($testimonials, function ($testimonial) {
        return !empty($testimonial['text']) || !empty($testimonial['author']);
    });

    // Fetch featured image (optimized size)
    $featured_image_url = get_the_post_thumbnail_url(get_the_ID(), 'portfolio-hero');
    ?>

    <div class="hero" style="background-image: url('<?php echo esc_url($featured_image_url); ?>'); background-size: cover; background-position: center;">
        <h1 class="hero-project-title"><?php the_title(); ?></h1>
    </div>

    <div class="project-container">
        <!-- Project Details -->
        <div class="project-details">
            <?php if (!empty($client_name) || !empty($project_date)) : ?>
                <h3>Project Details</h3>
                <ul>
                    <?php if (!empty($client_name)) : ?>
                        <li><strong>Client:</strong> <?php echo esc_html($client_name); ?></li>
                    <?php endif; ?>
                    <?php if (!empty($project_date)) : ?>
                        <li><strong>Date:</strong> <?php echo esc_html($project_date); ?></li>
                    <?php endif; ?>
                </ul>
            <?php endif; ?>

            <?php if (!empty($tools_used)) : ?>
                <!-- Tools Used -->
                <h3>Tools Used</h3>
                <div class="progress-container">
                    <?php foreach ($tools_used as $tool_name => $percentage) : ?>
                        <div class="progress-bar">
                            <div class="progress">
                                <div class="progress-fill" style="width: <?php echo esc_attr($percentage); ?>%;" data-percent="<?php echo esc_attr($percentage); ?>%">
                                    <?php echo esc_html($tool_name); ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($project_type)) : ?>
                <!-- Project Type -->
                <h3>Project Type</h3>
                <div class="progress-container">
                    <?php foreach ($project_type as $type_name => $percentage) : ?>
                        <div class="progress-bar">
                            <div class="progress">
                                <div class="progress-fill" style="width: <?php echo esc_attr($percentage); ?>%;" data-percent="<?php echo esc_attr($percentage); ?>%">
                                    <?php echo esc_html($type_name); ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <?php if ($project_overview || $project_goals || $challenges_solutions || $final_outcome || $testimonials) : ?>
        <!-- Project Overview -->
        <div class="project-overview">
            <?php if (!empty($project_overview)) : ?>
                <h2>Project Overview</h2>
                <p class="overview-intro"><?php echo esc_html($project_overview); ?></p>
            <?php endif; ?>

            <?php if (!empty($project_goals)) : ?>
                <!-- Project Goals -->
                <div class="project-goals">
                    <h3>📌 Project Goals</h3>
                    <ul>
                        <?php foreach ($project_goals as $goal) : ?>
                            <li><?php echo esc_html($goal); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?php if (!empty($challenges_solutions)) : ?>
                <!-- Challenges & Solutions -->
                <div class="project-challenges-solutions">
                    <h3>🚀 Challenges & Solutions</h3>

                    <?php foreach ($challenges_solutions as $challenge) : ?>
                        <div class="challenge-solution">
                            <?php if (!empty($challenge['title'])) : ?>
                                <h4>🛠 <?php echo esc_html($challenge['title']); ?></h4>
                            <?php endif; ?>
                            <?php if (!empty($challenge['summary'])) : ?>
                                <p><?php echo esc_html($challenge['summary']); ?></p>
                            <?php endif; ?>

                            <?php if (!empty($challenge['before']) || !empty($challenge['after'])) : ?>
                                <div class="before-after">
                                    <?php if (!empty($challenge['before'])) : ?>
                                        <div class="before">
                                            <h5><?php echo esc_html($challenge['before_text'] ?? 'Before'); ?></h5>
                                            <img src="<?php echo esc_url($challenge['before']); ?>" alt="Before Image">
                                        </div>
                                    <?php endif; ?>
                                    <?php if (!empty($challenge['after'])) : ?>
                                        <div class="after">
                                            <h5><?php echo esc_html($challenge['after_text'] ?? 'After'); ?></h5>
                                            <img src="<?php echo esc_url($challenge['after']); ?>" alt="After Image">
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>

                            <?php if (!empty($challenge['solution'])) : ?>
                                <p><strong>✅ Solution:</strong> <?php echo esc_html($challenge['solution']); ?></p>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($final_outcome) || !empty($testimonials)) : ?>
                <!-- Final Outcome -->
                <div class="final-outcome">
                    <?php if (!empty($final_outcome)) : ?>
                        <h3>🏆 Final Outcome</h3>
                        <p><?php echo esc_html($final_outcome); ?></p>
                    <?php endif; ?>

                    <?php if (!empty($testimonials)) : ?>
                        <!-- Testimonials -->
                        <div class="testimonials">
                            <h3>💬 What Our Partners Say</h3>
                            <?php foreach ($testimonials as $testimonial) : ?>
                                <div class="testimonial">
                                    <?php if (!empty($testimonial['text'])) : ?>
                                        <p>"<?php echo esc_html($testimonial['text']); ?>"</p>
                                    <?php endif; ?>
                                    <?php if (!empty($testimonial['author'])) : ?>
                                        <span>- <?php echo esc_html($testimonial['author']); ?></span>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <!-- Call to Action -->
                    <a href="#contact" class="cta-btn">Start Your Project</a>
                </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <!-- Related Projects Gallery -->
        <div class="gallery">
            <?php
            $current_project_id = get_the_ID();
            $current_categories = wp_get_post_terms($current_project_id, 'portfolio_category', array('fields' => 'ids'));
            $current_tools_used = get_post_meta($current_project_id, '_tools_used', true) ?: [];
            

            $args = array(
                'post_type'      => 'portfolio',
                'posts_per_page' => 6, // Max 6 related projects
                'post__not_in'   => array($current_project_id), // Exclude the current project
                'tax_query'      => array(
                    array(
                        'taxonomy' => 'portfolio_category',
                        'field'    => 'id',
                        'terms'    => $current_categories, // Match categories
                    ),
                ),
                'meta_query'     => array(
                    array(
                        'key'     => '_tools_used',
                        'compare' => 'EXISTS', // Ensure projects have tools used
                    ),
                ),
            );

            $related_projects = new WP_Query($args);

            if ($related_projects->have_posts()) :
                while ($related_projects->have_posts()) : $related_projects->the_post();
                    $related_thumbnail = get_the_post_thumbnail_url(get_the_ID(), 'medium');
                    ?>
                    <div class="gallery-item">
                        <div class="gallery-image">
                            <img src="<?php echo esc_url($related_thumbnail); ?>" alt="<?php the_title(); ?>">
                            <div class="gallery-overlay">
                                <a href="<?php the_permalink(); ?>" class="view-project-btn">View Project</a>
                            </div>
                        </div>
                    </div>
            <?php
                endwhile;
                wp_reset_postdata();
            else :
                echo '<p>No related projects found.</p>';
            endif;
            ?>
        </div>

        <!-- Call to Action: View More Projects -->
        <div class="cta-projects">
            <a href="<?php echo site_url('/portfolio/'); ?>">View More Projects</a>
        </div>
    </div>

<?php get_footer(); ?>

        <!-- 
            Testing workflow set up (local) -> (commit) -> (push) -> (git + staging)
        -->