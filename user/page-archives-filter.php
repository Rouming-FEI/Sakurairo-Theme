<?php 
/*
  Template Name: Filter Archives Template
*/
get_header();
?>

<style>
    .filter-archives-container {
        max-width: 1000px;
        margin: 0 auto;
        padding: 40px 20px;
        animation: main 1s;
    }

    .filter-section {
        background: rgba(255, 255, 255, 0.8);
        border-radius: 15px;
        padding: 30px;
        margin-bottom: 40px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
        backdrop-filter: blur(10px);
    }

    .dark .filter-section {
        background: rgba(40, 40, 40, 0.8);
        color: #eee;
    }

    .section-title {
        font-size: 24px;
        margin-bottom: 25px;
        border-bottom: 2px solid var(--theme-skin, #fe9600);
        padding-bottom: 10px;
        display: inline-block;
        font-weight: bold;
    }

    .filter-list {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
    }

    .filter-item {
        display: inline-flex;
        align-items: center;
        padding: 8px 16px;
        background: #fff;
        border: 1px solid #eee;
        border-radius: 50px;
        text-decoration: none !important;
        color: #666;
        transition: all 0.3s ease;
        font-size: 14px;
    }

    .dark .filter-item {
        background: #333;
        border-color: #444;
        color: #ccc;
    }

    .filter-item:hover {
        background: var(--theme-skin, #fe9600);
        color: #fff;
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(254, 150, 0, 0.3);
        border-color: var(--theme-skin, #fe9600);
    }

    .filter-item .count {
        background: rgba(0, 0, 0, 0.05);
        padding: 2px 8px;
        border-radius: 10px;
        margin-left: 8px;
        font-size: 12px;
        color: #999;
    }

    .filter-item:hover .count {
        background: rgba(255, 255, 255, 0.3);
        color: #fff;
    }

    .tag-cloud-section .filter-item {
        font-size: 13px;
        padding: 6px 12px;
    }
    
    /* 搜索框样式 */
    .archive-search {
        margin-bottom: 40px;
        text-align: center;
    }
    
    .archive-search form {
        position: relative;
        max-width: 500px;
        margin: 0 auto;
    }
    
    .archive-search input {
        width: 100%;
        padding: 15px 25px;
        border-radius: 50px;
        border: 2px solid transparent;
        background: rgba(255,255,255,0.9);
        box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        transition: all 0.3s;
        outline: none;
    }
    
    .archive-search input:focus {
        border-color: var(--theme-skin, #fe9600);
        background: #fff;
    }

    .dark .archive-search input {
        background: rgba(50,50,50,0.9);
        color: #fff;
    }

</style>

<div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">
        
        <div class="filter-archives-container">
            
            <!-- 搜索区域 -->
            <div class="archive-search">
                <form method="get" action="<?php echo esc_url(home_url('/')); ?>">
                    <input type="text" name="s" placeholder="<?php _e('Search...', 'sakurairo'); ?>" value="<?php echo get_search_query(); ?>">
                </form>
            </div>

            <!-- 分类区域 -->
            <div class="filter-section">
                <h3 class="section-title"><i class="fa-solid fa-folder-open" style="margin-right: 10px;"></i><?php _e('Categories', 'sakurairo'); ?></h3>
                <div class="filter-list">
                    <?php
                    $categories = get_categories(array(
                        'orderby' => 'count',
                        'order'   => 'DESC'
                    ));
                    foreach($categories as $category) {
                        echo '<a href="' . get_category_link($category->term_id) . '" class="filter-item">';
                        echo '<span class="name">' . $category->name . '</span>';
                        echo '<span class="count">' . $category->count . '</span>';
                        echo '</a>';
                    }
                    ?>
                </div>
            </div>

            <!-- 标签区域 -->
            <div class="filter-section tag-cloud-section">
                <h3 class="section-title"><i class="fa-solid fa-tags" style="margin-right: 10px;"></i><?php _e('Tags', 'sakurairo'); ?></h3>
                <div class="filter-list">
                    <?php
                    $tags = get_tags(array(
                        'orderby' => 'count',
                        'order'   => 'DESC'
                    ));
                    if ($tags) {
                        foreach($tags as $tag) {
                            echo '<a href="' . get_tag_link($tag->term_id) . '" class="filter-item">';
                            echo '<span class="name">' . $tag->name . '</span>';
                            echo '<span class="count">' . $tag->count . '</span>';
                            echo '</a>';
                        }
                    } else {
                        echo '<p>' . __('No tags found.', 'sakurairo') . '</p>';
                    }
                    ?>
                </div>
            </div>

            <!-- 日期归档区域 -->
            <div class="filter-section">
                <h3 class="section-title"><i class="fa-regular fa-calendar-check" style="margin-right: 10px;"></i><?php _e('Date Archives', 'sakurairo'); ?></h3>
                <div class="filter-list">
                    <?php
                    global $wpdb, $wp_locale;
                    $where = "WHERE post_type = 'post' AND post_status = 'publish'";
                    $sql = "SELECT YEAR(post_date) AS `year`, MONTH(post_date) AS `month`, count(ID) as posts FROM $wpdb->posts $where GROUP BY YEAR(post_date), MONTH(post_date) ORDER BY post_date DESC";
                    $archives = $wpdb->get_results($sql);
                    
                    if ($archives) {
                        foreach($archives as $archive) {
                            $url = get_month_link($archive->year, $archive->month);
                            $text = sprintf(__('%1$s %2$d'), $wp_locale->get_month($archive->month), $archive->year);
                            echo '<a href="' . $url . '" class="filter-item">';
                            echo '<span class="name">' . $text . '</span>';
                            echo '<span class="count">' . $archive->posts . '</span>';
                            echo '</a>';
                        }
                    } else {
                        echo '<p>' . __('No archives found.', 'sakurairo') . '</p>';
                    }
                    ?>
                </div>
            </div>

        </div>

    </main><!-- #main -->
</div><!-- #primary -->

<?php
get_footer();
?>