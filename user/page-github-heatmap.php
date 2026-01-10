<?php
/*
  Template Name: GitHub Style Timeline
*/
get_header();

// 获取过去一年的文章数据
global $wpdb;
$one_year_ago = date('Y-m-d', strtotime('-1 year'));
$sql = $wpdb->prepare("
    SELECT DATE(post_date) as date, COUNT(ID) as count 
    FROM $wpdb->posts 
    WHERE post_status = 'publish' 
    AND post_type = 'post' 
    AND post_date >= %s 
    GROUP BY date
", $one_year_ago);

$posts_data = $wpdb->get_results($sql, OBJECT_K);

// 准备日历数据
$end_date = new DateTime();
$start_date = new DateTime('-1 year');
// 调整开始日期到之前的星期日，以保持网格对齐
$start_date->modify('last sunday');

$period = new DatePeriod(
    $start_date,
    new DateInterval('P1D'),
    $end_date->modify('+1 day') // 包含今天
);

// 计算最大文章数，用于颜色分级
$max_count = 0;
foreach ($posts_data as $data) {
    if ($data->count > $max_count) $max_count = $data->count;
}
$max_count = $max_count > 0 ? $max_count : 1; // 避免除零

?>

<style>
    .github-heatmap-container {
        max-width: 1000px;
        margin: 40px auto;
        padding: 20px;
        background: rgba(255, 255, 255, 0.8);
        border-radius: 15px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
        backdrop-filter: blur(10px);
        overflow-x: auto; /* 允许在小屏幕上横向滚动 */
    }

    .dark .github-heatmap-container {
        background: rgba(40, 40, 40, 0.8);
        color: #eee;
    }

    .heatmap-header {
        margin-bottom: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .heatmap-title {
        font-size: 20px;
        font-weight: bold;
        display: flex;
        align-items: center;
    }
    
    .heatmap-stats {
        font-size: 14px;
        color: #666;
    }
    
    .dark .heatmap-stats {
        color: #aaa;
    }

    /* 网格布局 */
    .heatmap-graph {
        display: grid;
        grid-template-rows: repeat(7, 12px); /* 7行，对应星期 */
        grid-auto-flow: column; /* 按列填充 */
        gap: 3px;
        width: max-content;
        padding-bottom: 20px;
    }

    .day-cell {
        width: 12px;
        height: 12px;
        border-radius: 2px;
        background-color: #ebedf0; /* Level 0 */
        position: relative;
        cursor: pointer;
        transition: transform 0.1s;
    }
    
    .day-cell:hover {
        transform: scale(1.2);
        border: 1px solid rgba(0,0,0,0.1);
        z-index: 2;
    }

    .dark .day-cell {
        background-color: #2d333b; /* Dark mode Level 0 */
    }

    /* 颜色等级 - 使用主题色变量 */
    /* 我们使用 opacity 来模拟不同深浅，这样能自动适配任何主题色 */
    .day-cell[data-level="1"] { background-color: var(--theme-skin, #fe9600); opacity: 0.4; }
    .day-cell[data-level="2"] { background-color: var(--theme-skin, #fe9600); opacity: 0.6; }
    .day-cell[data-level="3"] { background-color: var(--theme-skin, #fe9600); opacity: 0.8; }
    .day-cell[data-level="4"] { background-color: var(--theme-skin, #fe9600); opacity: 1.0; }

    /* Tooltip */
    .day-cell::after {
        content: attr(data-tooltip);
        position: absolute;
        bottom: 100%;
        left: 50%;
        transform: translateX(-50%);
        padding: 5px 10px;
        background: rgba(0,0,0,0.8);
        color: #fff;
        font-size: 12px;
        border-radius: 4px;
        white-space: nowrap;
        pointer-events: none;
        opacity: 0;
        transition: opacity 0.2s;
        margin-bottom: 5px;
        z-index: 10;
        visibility: hidden;
    }

    .day-cell:hover::after {
        opacity: 1;
        visibility: visible;
    }

    /* 星期标签 */
    .week-days {
        display: grid;
        grid-template-rows: repeat(7, 12px);
        gap: 3px;
        margin-right: 5px;
        font-size: 10px;
        color: #999;
        line-height: 12px;
        text-align: right;
        padding-right: 5px;
    }
    
    .graph-wrapper {
        display: flex;
    }

    /* 图例 */
    .heatmap-legend {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        font-size: 12px;
        color: #666;
        margin-top: 10px;
        gap: 4px;
    }
    
    .legend-item {
        width: 12px;
        height: 12px;
        border-radius: 2px;
        display: inline-block;
    }

    /* 最近文章列表 */
    .recent-posts-list {
        max-width: 1000px;
        margin: 0 auto 40px;
        padding: 0 20px;
    }
    
    .recent-post-item {
        background: rgba(255,255,255,0.6);
        padding: 15px;
        margin-bottom: 10px;
        border-radius: 8px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: all 0.3s;
    }
    
    .dark .recent-post-item {
        background: rgba(40,40,40,0.6);
    }
    
    .recent-post-item:hover {
        background: rgba(255,255,255,0.9);
        transform: translateX(5px);
    }
    
    .dark .recent-post-item:hover {
        background: rgba(50,50,50,0.9);
    }

    .recent-post-date {
        font-size: 12px;
        color: #999;
        margin-left: 10px;
    }

</style>

<div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">

        <div class="github-heatmap-container">
            <div class="heatmap-header">
                <div class="heatmap-title">
                    <i class="fa-solid fa-chart-simple" style="margin-right: 10px; color: var(--theme-skin, #fe9600);"></i>
                    <?php _e('Contribution Activity', 'sakurairo'); ?>
                </div>
                <div class="heatmap-stats">
                    <?php 
                    $total_posts = 0;
                    foreach($posts_data as $p) $total_posts += $p->count;
                    echo sprintf(__('%s posts in the last year', 'sakurairo'), $total_posts);
                    ?>
                </div>
            </div>

            <div class="graph-wrapper">
                <div class="week-days">
                    <span></span>
                    <span>Mon</span>
                    <span></span>
                    <span>Wed</span>
                    <span></span>
                    <span>Fri</span>
                    <span></span>
                </div>
                
                <div class="heatmap-graph">
                    <?php
                    foreach ($period as $dt) {
                        $date_str = $dt->format('Y-m-d');
                        $count = isset($posts_data[$date_str]) ? $posts_data[$date_str]->count : 0;
                        
                        // 计算等级 (0-4)
                        $level = 0;
                        if ($count > 0) {
                            if ($count >= $max_count) $level = 4;
                            elseif ($count >= $max_count * 0.75) $level = 3;
                            elseif ($count >= $max_count * 0.5) $level = 2;
                            else $level = 1;
                        }

                        $tooltip = $date_str . ': ' . $count . ' ' . __('posts', 'sakurairo');
                        
                        echo sprintf(
                            '<div class="day-cell" data-level="%d" data-date="%s" data-tooltip="%s"></div>',
                            $level,
                            $date_str,
                            $tooltip
                        );
                    }
                    ?>
                </div>
            </div>

            <div class="heatmap-legend">
                <span>Less</span>
                <span class="legend-item" style="background-color: #ebedf0;"></span>
                <span class="legend-item" style="background-color: var(--theme-skin, #fe9600); opacity: 0.4;"></span>
                <span class="legend-item" style="background-color: var(--theme-skin, #fe9600); opacity: 0.6;"></span>
                <span class="legend-item" style="background-color: var(--theme-skin, #fe9600); opacity: 0.8;"></span>
                <span class="legend-item" style="background-color: var(--theme-skin, #fe9600); opacity: 1.0;"></span>
                <span>More</span>
            </div>
        </div>

        <!-- 最近文章列表 -->
        <div class="recent-posts-list">
            <h3 style="margin-bottom: 20px; padding-left: 10px; border-left: 4px solid var(--theme-skin, #fe9600);"><?php _e('Recent Updates', 'sakurairo'); ?></h3>
            <?php
            $recent_query = new WP_Query(array(
                'posts_per_page' => 10,
                'post_status' => 'publish'
            ));

            if ($recent_query->have_posts()) :
                while ($recent_query->have_posts()) : $recent_query->the_post();
            ?>
                <a href="<?php the_permalink(); ?>" class="recent-post-item">
                    <span class="recent-post-title"><?php the_title(); ?></span>
                    <span class="recent-post-date"><?php echo get_the_date(); ?></span>
                </a>
            <?php
                endwhile;
                wp_reset_postdata();
            endif;
            ?>
        </div>

    </main>
</div>

<?php get_footer(); ?>