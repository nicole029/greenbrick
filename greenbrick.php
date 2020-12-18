<?php
    /**
     * Plugin Name: Green Brick Custom
     * Description: Custom codes such as shortcodes for Green Brick
     * Author: Nicole Gonzaga
     * Author URI: https://github.com/nicole029
     * Version: 1.1
     * 
     */
    require 'plugin-update-checker/plugin-update-checker.php';
    $myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
        'https://github.com/nicole029/greenbrick',
        __FILE__,
        'greenbrickcustom'
    );
    $myUpdateChecker->setBranch('production');
function ng_load_isotope(){
    $plugins_dir = plugin_dir_url(__FILE__);
    
    if( is_front_page() || is_page( 'past-work' ) ){
        // wp_enqueue_script( 'jquery-core' );
        
        wp_enqueue_script( 'isotope', $plugins_dir . 'vendor/isotope/isotope.min.js', array( 'jquery-core' ), null, true);
        wp_enqueue_script( 'greenbrick.isotope', $plugins_dir .'js/isotope.greenbrick.js', array( 'isotope' ), null, true );
        wp_enqueue_style( 'greenbrick.isotope', $plugins_dir . 'css/isotope.greenbrick.css' );
    }
}
add_action( 'wp_enqueue_scripts','ng_load_isotope' );

function pull_projects( $atts ){
    $atts = shortcode_atts([
        'per_page' => -1
    ],$atts);
    $taxonomy = 'project_category';
    $projects = new WP_Query([
        'post_type' => 'project',
        'per_page' => $atts['per_page']
    ]);
    $project_tax_args = [
        'taxonomy' => $taxonomy
    ];
    ob_start();
    if( $projects->have_posts() ){
        $project_tax = get_terms( $project_tax_args );

    ?>
        <div id="projects-grid-filters" class="button-group filter-button-group">
            <button class="button grid-filter is-checked" data-filter="*">All</button>
            <?php foreach( $project_tax as $ptax ): ?>
                <button class="button grid-filter" data-filter=".<?php echo $ptax->slug; ?>"><?php echo $ptax->name; ?></button>
            <?php endforeach; ?>
        </div>
        <div id="projects-grid" class="grid">
    <?php
        while( $projects->have_posts() ){
            $projects->the_post();
            $project_cats = get_the_terms( get_the_ID(), $taxonomy );
            $cats_string = join( ' ', wp_list_pluck( $project_cats, 'slug' ) );
            ?>
                <div class="grid-item <?php echo $cats_string; ?>">
                    <a class="grid-link" href="<?php echo get_permalink(); ?>" title="View <?php echo get_the_title(); ?>">
                        <figure class="grid-figure">
                            <img src="<?php echo get_the_post_thumbnail_url(); ?>" alt="<?php echo get_the_title(); ?>" class="grid-image">
                        </figure>
                        <div class="grid-content">
                            <h3 class="grid-title"><?php the_title(); ?></h3>
                            <?php the_excerpt(); ?>
                        </div>
                    </a>
                </div>
            <?php
        }
    ?>
        </div><!-- eo #projects-grid.grid -->
    <?php
    }

    wp_reset_postdata();

    return ob_get_clean();
}
add_shortcode( 'pull_projects','pull_projects' );