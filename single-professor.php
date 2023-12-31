<?php
require_once 'functions.php';

get_header();


while (have_posts()) {
    the_post();

    pageBanner() ?>


    <div class="container container--narrow page-section">

        <div class="generic-content">
            <div class="raw group">
                <div class="one-third">
                    <?php
                    // professorPortrait is the name of the custom image size we created in functions.php
                    the_post_thumbnail('professorPortrait');
                    ?>

                </div>
                <div class="two-thirds">
                    <?php
                    // not completed, but we are trying to display the like button and the number of likes
                    $likeCount = new WP_Query(array(
                        'post_type' => 'like',
                        'meta_query' => array(
                            array(
                                'key' => 'liked_professor_id',
                                'compare' => '=',
                                'value' => get_the_ID()
                            )
                        )
                    ));
                    // check if the current user has liked the current professor
                    // if yes, then we want to display the full heart icon
                    $existStatus = 'no';
                    if (is_user_logged_in()) {
                        $existQuery = new WP_Query(array(
                            'author' => get_current_user_id(),
                            'post_type' => 'like',
                            'meta_query' => array(
                                array(
                                    'key' => 'liked_professor_id',
                                    'compare' => '=',
                                    'value' => get_the_ID()
                                )
                            )
                        ));
                        if ($existQuery->found_posts) {
                            $existStatus = 'yes';
                        }
                    }

                    ?>
                    <span class="like-box" data-exists="<?php echo $existStatus; ?>" data-professor="<?php echo get_the_ID(); ?>" data-like="<?php if (isset($existQuery->posts[0]->ID)) echo $existQuery->posts[0]->ID; ?>">


                        <i class="fa fa-heart-o" aria-hidden="true"></i>
                        <i class="fa fa-heart" aria-hidden="true"></i>
                        <span class="like-count">
                            <?php echo $likeCount->found_posts; ?>
                        </span>

                    </span> <?php the_content(); ?>
                </div>
            </div>
            <?php
            // get the array of pages that are related to the current page
            // we added the relation through custom fields in the admin panel
            $relatedPrograms = get_field('related_programs');
            if ($relatedPrograms) {
                echo '<hr class="section-break">';
                echo '<h2 class="headline headline--medium">Subjects Taught(s)</h2>';
                echo '<ul class="link-list min-list">';
                foreach ($relatedPrograms as $program) { ?>
                    <li>
                        <a href="<?php echo get_the_permalink($program); ?>">
                            <?php echo get_the_title($program); ?>
                        </a>
                    </li>
            <?php }
                echo '</ul>';
            } ?>
        </div>
    <?php }



get_footer();
