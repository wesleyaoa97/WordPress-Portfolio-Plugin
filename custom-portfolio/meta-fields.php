<?php
// Add Meta Boxes
function custom_portfolio_add_meta_boxes() {
    add_meta_box('project_details_meta_box', 'Project Details', 'custom_portfolio_meta_box_callback', 'portfolio', 'normal', 'high');
}
add_action('add_meta_boxes', 'custom_portfolio_add_meta_boxes');

// Display Fields
function custom_portfolio_meta_box_callback($post) {
    wp_nonce_field('custom_portfolio_save_meta', 'custom_portfolio_nonce');

    $client_name = get_post_meta($post->ID, '_client_name', true);
    $project_date = get_post_meta($post->ID, '_project_date', true);
    $cta_url = get_post_meta($post->ID, '_cta_url', true);
    $tools_used = get_post_meta($post->ID, '_tools_used', true) ?: [];
    $project_type = get_post_meta($post->ID, '_project_type', true) ?: [];
    $project_overview = get_post_meta($post->ID, '_project_overview', true);
    $project_goals = get_post_meta($post->ID, '_project_goals', true) ?: [];
    $challenges_solutions = get_post_meta($post->ID, '_challenges_solutions', true) ?: [];
    $final_outcome = get_post_meta($post->ID, '_final_outcome', true);
    $testimonials = get_post_meta($post->ID, '_testimonials', true) ?: [];

    echo '<p><label>Client Name:</label><br><input type="text" name="client_name" value="' . esc_attr($client_name) . '" class="widefat" /></p>';
    echo '<p><label>Project Date:</label><br><input type="text" name="project_date" value="' . esc_attr($project_date) . '" class="widefat" /></p>';
    echo '<p><label>CTA URL:</label><br><input type="text" name="cta_url" value="' . esc_attr($cta_url) . '" class="widefat" /></p>';

    // Tools Used (Dynamic Fields)
    echo '<h3>Tools Used (with Percentage)</h3>';
    echo '<div id="tools-container">';
    foreach ($tools_used as $tool_name => $percentage) {
        echo '<p><input type="text" name="tools_used[' . esc_attr($tool_name) . ']" value="' . esc_attr($tool_name) . '" placeholder="Tool Name" class="widefat" />
              <input type="number" name="tools_used_percentages[' . esc_attr($tool_name) . ']" value="' . esc_attr($percentage) . '" placeholder="Percentage" class="widefat" min="0" max="100" /></p>';
    }
    echo '</div><button type="button" id="add-tool">+ Add Tool</button>';

    // Project Type (Dynamic Fields)
    echo '<h3>Project Type (with Percentage)</h3>';
    echo '<div id="project-type-container">';
    foreach ($project_type as $type_name => $percentage) {
        echo '<p><input type="text" name="project_type[' . esc_attr($type_name) . ']" value="' . esc_attr($type_name) . '" placeholder="Type" class="widefat" />
              <input type="number" name="project_type_percentages[' . esc_attr($type_name) . ']" value="' . esc_attr($percentage) . '" placeholder="Percentage" class="widefat" min="0" max="100" /></p>';
    }
    echo '</div><button type="button" id="add-project-type">+ Add Project Type</button>';

    // Project Overview
    echo '<h3>Project Overview</h3>';
    echo '<textarea name="project_overview" class="widefat">' . esc_textarea($project_overview) . '</textarea>';

    // Project Goals (Dynamic Fields)
    echo '<h3>Project Goals</h3>';
    echo '<div id="goals-container">';
    foreach ($project_goals as $goal) {
        echo '<p><input type="text" name="project_goals[]" value="' . esc_attr($goal) . '" class="widefat" /></p>';
    }
    echo '</div><button type="button" id="add-goal">+ Add Goal</button>';

    // Challenges & Solutions (Dynamic Fields)
    echo '<h3>Challenges & Solutions</h3>';
    echo '<div id="challenges-container">';
    foreach ($challenges_solutions as $challenge) {
        echo '<p><input type="text" name="challenges_solutions[title][]" value="' . esc_attr($challenge['title'] ?? '') . '" placeholder="Challenge Title" class="widefat" /></p>';
        echo '<p><textarea name="challenges_solutions[summary][]" placeholder="Challenge Summary" class="widefat">' . esc_textarea($challenge['summary'] ?? '') . '</textarea></p>';
        
        // Before Text
        echo '<p><input type="text" name="challenges_solutions[before_text][]" value="' . esc_attr($challenge['before_text'] ?? '') . '" placeholder="Before Description" class="widefat" /></p>';
        
        // Before Image
        echo '<p><input type="text" class="before-image-url" name="challenges_solutions[before][]" value="' . esc_attr($challenge['before'] ?? '') . '" placeholder="Before Image URL" class="widefat" />';
        echo '<button type="button" class="upload-before-image button">Upload</button></p>';

        // After Text
        echo '<p><input type="text" name="challenges_solutions[after_text][]" value="' . esc_attr($challenge['after_text'] ?? '') . '" placeholder="After Description" class="widefat" /></p>';
        
        // After Image
        echo '<p><input type="text" class="after-image-url" name="challenges_solutions[after][]" value="' . esc_attr($challenge['after'] ?? '') . '" placeholder="After Image URL" class="widefat" />';
        echo '<button type="button" class="upload-after-image button">Upload</button></p>';

        // Solution
        echo '<p><textarea name="challenges_solutions[solution][]" placeholder="Solution" class="widefat">' . esc_textarea($challenge['solution'] ?? '') . '</textarea></p>';
    }
    echo '</div><button type="button" id="add-challenge">+ Add Challenge</button>';

    // Final Outcome
    echo '<h3>Final Outcome</h3>';
    echo '<textarea name="final_outcome" class="widefat">' . esc_textarea($final_outcome) . '</textarea>';

    // Testimonials
    echo '<h3>Testimonials</h3>';
    echo '<div id="testimonials-container">';
    foreach ($testimonials as $testimonial) {
        echo '<p><textarea name="testimonials[text][]" placeholder="Testimonial" class="widefat">' . esc_textarea($testimonial['text'] ?? '') . '</textarea></p>';
        echo '<p><input type="text" name="testimonials[author][]" value="' . esc_attr($testimonial['author'] ?? '') . '" placeholder="Author Name" class="widefat" /></p>';
    }
    echo '</div><button type="button" id="add-testimonial">+ Add Testimonial</button>';

    // JavaScript for Adding Dynamic Fields
    echo '<script>
        document.addEventListener("DOMContentLoaded", function() {
            function addField(containerId, inputHtml) {
                var container = document.getElementById(containerId);
                var newInput = document.createElement("p");
                newInput.innerHTML = inputHtml;
                container.appendChild(newInput);
            }

            document.getElementById("add-tool").addEventListener("click", function() {
                addField("tools-container", "<input type=\'text\' name=\'tools_used[]\' placeholder=\'Tool Name\' class=\'widefat\' /> <input type=\'number\' name=\'tools_used_percentages[]\' placeholder=\'Percentage\' class=\'widefat\' min=\'0\' max=\'100\' />");
            });

            document.getElementById("add-project-type").addEventListener("click", function() {
                addField("project-type-container", "<input type=\'text\' name=\'project_type[]\' placeholder=\'Project Type\' class=\'widefat\' /> <input type=\'number\' name=\'project_type_percentages[]\' placeholder=\'Percentage\' class=\'widefat\' min=\'0\' max=\'100\' />");
            });

            document.getElementById("add-goal").addEventListener("click", function() {
                addField("goals-container", "<input type=\'text\' name=\'project_goals[]\' placeholder=\'Project Goal\' class=\'widefat\' />");
            });

            document.getElementById("add-challenge").addEventListener("click", function() {
                addField("challenges-container", 
                    "<input type=\'text\' name=\'challenges_solutions[title][]\' placeholder=\'Challenge Title\' class=\'widefat\' />" +
                    "<textarea name=\'challenges_solutions[summary][]\' placeholder=\'Challenge Summary\' class=\'widefat\'></textarea>" +
                    "<input type=\'text\' name=\'challenges_solutions[before_text][]\' placeholder=\'Before Description\' class=\'widefat\' />" +
                    "<p><label>Before Image:</label><br><input type=\'text\' class=\'before-image-url\' name=\'challenges_solutions[before][]\' placeholder=\'Before Image URL\' class=\'widefat\' />" +
                    "<button type=\'button\' class=\'upload-before-image button\'>Upload</button></p>" +
                    "<input type=\'text\' name=\'challenges_solutions[after_text][]\' placeholder=\'After Description\' class=\'widefat\' />" +
                    "<p><label>After Image:</label><br><input type=\'text\' class=\'after-image-url\' name=\'challenges_solutions[after][]\' placeholder=\'After Image URL\' class=\'widefat\' />" +
                    "<button type=\'button\' class=\'upload-after-image button\'>Upload</button></p>" +
                    "<textarea name=\'challenges_solutions[solution][]\' placeholder=\'Solution\' class=\'widefat\'></textarea>"
                );
            });

            function setupMediaUploader(buttonClass, inputClass) {
                document.addEventListener("click", function(event) {
                    if (event.target.classList.contains(buttonClass.replace(".", ""))) {
                        event.preventDefault();
                        var button = event.target;
                        var inputField = button.previousElementSibling;

                        var frame = wp.media({
                            title: "Select or Upload an Image",
                            library: { type: "image" },
                            button: { text: "Use this image" },
                            multiple: false
                        });

                        frame.on("select", function() {
                            var attachment = frame.state().get("selection").first().toJSON();
                            inputField.value = attachment.url;
                        });

                        frame.open();
                    }
                });
            }

            setupMediaUploader(".upload-before-image", ".before-image-url");
            setupMediaUploader(".upload-after-image", ".after-image-url");

            document.getElementById("add-testimonial").addEventListener("click", function() {
                addField("testimonials-container", "<textarea name=\'testimonials[text][]\' placeholder=\'Testimonial\' class=\'widefat\'></textarea><input type=\'text\' name=\'testimonials[author][]\' placeholder=\'Author Name\' class=\'widefat\' />");
            });
        });
    </script>';
}

// Save Meta Fields
function custom_portfolio_save_meta($post_id) {
    if (!isset($_POST['custom_portfolio_nonce']) || !wp_verify_nonce($_POST['custom_portfolio_nonce'], 'custom_portfolio_save_meta')) {
        return;
    }

    update_post_meta($post_id, '_client_name', sanitize_text_field($_POST['client_name']));
    update_post_meta($post_id, '_project_date', sanitize_text_field($_POST['project_date']));
    update_post_meta($post_id, '_cta_url', esc_url($_POST['cta_url']));

    // Handle Tools Used
    if (isset($_POST['tools_used']) && isset($_POST['tools_used_percentages'])) {
        $tools_data = [];
        foreach ($_POST['tools_used'] as $key => $tool_name) {
            if (!empty($tool_name) && isset($_POST['tools_used_percentages'][$key])) {
                $tools_data[sanitize_text_field($tool_name)] = intval($_POST['tools_used_percentages'][$key]);
            }
        }
        update_post_meta($post_id, '_tools_used', $tools_data);
    }

    // Handle Project Type
    if (isset($_POST['project_type']) && isset($_POST['project_type_percentages'])) {
        $type_data = [];
        foreach ($_POST['project_type'] as $key => $type_name) {
            if (!empty($type_name) && isset($_POST['project_type_percentages'][$key])) {
                $type_data[sanitize_text_field($type_name)] = intval($_POST['project_type_percentages'][$key]);
            }
        }
        update_post_meta($post_id, '_project_type', $type_data);
    }

    update_post_meta($post_id, '_project_overview', sanitize_textarea_field($_POST['project_overview']));
    update_post_meta($post_id, '_project_goals', array_map('sanitize_text_field', $_POST['project_goals'] ?? []));
    update_post_meta($post_id, '_final_outcome', sanitize_textarea_field($_POST['final_outcome']));
    
    // Handle Challenges & Solutions
    if (isset($_POST['challenges_solutions'])) {
        $challenges_data = [];
        foreach ($_POST['challenges_solutions']['title'] as $key => $title) {
            if (!empty($title)) {
                $challenges_data[] = [
                    'title'       => sanitize_text_field($title),
                    'summary'     => sanitize_textarea_field($_POST['challenges_solutions']['summary'][$key]),
                    'before_text' => sanitize_text_field($_POST['challenges_solutions']['before_text'][$key]),
                    'before'      => esc_url($_POST['challenges_solutions']['before'][$key]),
                    'after_text'  => sanitize_text_field($_POST['challenges_solutions']['after_text'][$key]),
                    'after'       => esc_url($_POST['challenges_solutions']['after'][$key]),
                    'solution'    => sanitize_textarea_field($_POST['challenges_solutions']['solution'][$key]),
                ];
            }
        }
        update_post_meta($post_id, '_challenges_solutions', $challenges_data);
    }
    
    // Handle Testimonials
    if (isset($_POST['testimonials']['text']) && isset($_POST['testimonials']['author'])) {
        $testimonials_data = [];
        foreach ($_POST['testimonials']['text'] as $key => $text) {
            if (!empty($text)) {
                $testimonials_data[] = [
                    'text' => sanitize_textarea_field($text),
                    'author' => sanitize_text_field($_POST['testimonials']['author'][$key]),
                ];
            }
        }
        update_post_meta($post_id, '_testimonials', $testimonials_data);
    }
}
add_action('save_post', 'custom_portfolio_save_meta');
