<?php
/**
 * Template Name: About Page
 *
 * @package Sakurairo
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<?php
			while ( have_posts() ) : the_post();
            ?>
                <article id="post-<?php echo esc_attr(get_the_ID()); ?>" <?php post_class(); ?>>
                    <?php if (should_show_title()) { ?>
                    <header class="entry-header">
                        <h1 class="entry-title"><?php the_title(); ?></h1>
                    </header><!-- .entry-header -->
                    <?php } ?>

                    <div class="entry-content">
                        <!-- Custom About Layout -->
                        <div class="about-page-wrapper">
                            <div class="about-profile">
                                <div class="about-avatar">
                                    <?php echo get_avatar( get_the_author_meta( 'ID' ), 200 ); ?>
                                </div>
                                <div class="about-info">
                                    <h2><?php echo esc_html(get_the_author_meta('display_name')); ?></h2>
                                    <div class="about-description">
                                        <?php echo wpautop(get_the_author_meta('description')); ?>
                                    </div>
                                </div>
                            </div>
                            
                            <hr class="about-divider">

                            <div class="about-main-content">
                                <?php the_content(); ?>
                            </div>
                        </div>

                        <?php
                            wp_link_pages([
                                'before' => '<div class="page-links">' . esc_html__('Pages:', 'sakurairo'),
                                'after'  => '</div>',
                            ]);
                        ?>
                    </div><!-- .entry-content -->
                    
                    <footer class="entry-footer">
                        <?php
                            edit_post_link(
                                sprintf(
                                    /* translators: %s: Name of current post */
                                    esc_html__('Edit %s', 'sakurairo'),
                                    '<span class="screen-reader-text">' . esc_html(get_the_title()) . '</span>'
                                ),
                                '<span class="edit-link">',
                                '</span>'
                            );
                        ?>
                    </footer><!-- .entry-footer -->
                </article><!-- #post-## -->
            <?php
			endwhile; // End of the loop.
			?>

		</main><!-- #main -->
	</div><!-- #primary -->

    <style>
        .about-page-wrapper {
            max-width: 800px;
            margin: 0 auto;
        }
        .about-profile {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            text-align: center;
            margin-bottom: 40px;
        }
        .about-avatar img {
            border-radius: 50%;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        .about-avatar img:hover {
            transform: rotate(360deg);
        }
        .about-info h2 {
            margin-top: 20px;
            font-size: 2em;
        }
        .about-description {
            color: #666;
            margin-top: 10px;
        }
        .about-divider {
            border: 0;
            height: 1px;
            background: #eee;
            margin: 40px 0;
            background-image: linear-gradient(to right, rgba(0, 0, 0, 0), rgba(0, 0, 0, 0.1), rgba(0, 0, 0, 0));
        }
        /* Dark mode compatibility if the theme uses it */
        body.dark .about-info h2,
        body.dark .about-description {
            color: #ddd;
        }
        body.dark .about-divider {
             background-image: linear-gradient(to right, rgba(255, 255, 255, 0), rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0));
        }
    </style>

<?php
get_footer();
