<?php
/*
Plugin Name: LearnPress Stats Dashboard
Description: Show LearnPress statistics
Version: 1.0
Author: Student
*/
function lp_total_courses(){

    $courses = new WP_Query(array(
        'post_type' => 'lp_course',
        'post_status' => 'publish',
        'posts_per_page' => -1
    ));

    return $courses->found_posts;
}

function lp_total_students(){

    global $wpdb;

    $table = $wpdb->prefix . 'learnpress_user_items';

    $total = $wpdb->get_var("
        SELECT COUNT(DISTINCT user_id)
        FROM $table
        WHERE item_type='lp_course'
    ");

    return $total;
}
function lp_total_completed_courses(){

    global $wpdb;

    $table = $wpdb->prefix . 'learnpress_user_items';

    return $wpdb->get_var("
        SELECT COUNT(*)
        FROM $table
        WHERE status='completed'
        AND item_type='lp_course'
    ");
}
add_action('wp_dashboard_setup','lp_add_dashboard_widget');

function lp_add_dashboard_widget(){

    wp_add_dashboard_widget(
        'lp_stats_widget',
        'LearnPress Statistics',
        'lp_dashboard_widget_content'
    );
}
function lp_dashboard_widget_content(){

    echo "<h3>LearnPress Statistics</h3>";

    echo "<p>Total Courses: ".lp_total_courses()."</p>";

    echo "<p>Total Students: ".lp_total_students()."</p>";

    echo "<p>Completed Courses: ".lp_total_completed_courses()."</p>";
}
add_shortcode('lp_total_stats','lp_stats_shortcode');

function lp_stats_shortcode(){

    ob_start();
    ?>

    <div style="border:2px solid #000;padding:20px;">
        <h2>LearnPress Statistics</h2>

        <p>Total Courses: <?php echo lp_total_courses(); ?></p>

        <p>Total Students: <?php echo lp_total_students(); ?></p>

        <p>Completed Courses: <?php echo lp_total_completed_courses(); ?></p>

    </div>

    <?php

    return ob_get_clean();
}