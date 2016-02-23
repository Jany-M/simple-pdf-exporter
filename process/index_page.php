<?php

$wp_path = explode('wp-content', dirname(__FILE__));
require_once($wp_path[0].'wp-load.php');
require_once(PDF_PROCESS."/config.php");

use Dompdf\Dompdf;

/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED);
ini_set("error_log", "/var/log/php-fpm/pdf-gen.log");*/
function get_html_index_page() {
    global $saved_posts;

    ob_start();
    index_page_style();
    echo '<table width="100%">';
    echo '<tr style="padding:0; margin:0; height:5px"><th colspan="3" align="center" style="font-size:10px; font-family:Arial, sans-serif;">INDEX</th></tr>';
    echo '<tr><th style="font-size:10px; padding:5px; margin:0; font-family:Arial, sans-serif;">Rate Type</th><th style="font-family:Arial, sans-serif;">Rate Name</th><th style="font-family:Arial, sans-serif;">Page No</th></tr>';

    /*if($posts_per_page != -1 && !empty($saved_posts)) {

        $posts_for_index = new WP_Query(
            array(
                'posts_per_page' => $posts_per_page,
                'post__in' => $saved_posts
            )
        );

        if ($posts_for_index->have_posts()) : while ( $posts_for_index->have_posts() ) : $posts_for_index->the_post();

            $page_number = get_post_meta($post->ID, 'pdf_page_no', true);
            $pg_rate_type = wp_get_post_terms($post->ID, 'pg-rate-type', array("fields" => "names"));
            $post_title = get_post_field('post_title',$post->ID); //echo get_the_title($ID); 
            echo '<tr><td style="font-size:9px; font-family:Arial, sans-serif;">'.$pg_rate_type[0].'</td><td style="font-size:9px; font-family:Arial, sans-serif;">xxxx-'.$post_title.'</td><td style="font-size:9px; font-family:Arial, sans-serif; text-align:center;"><b>'.$page_number.'</b></td></tr>'; 

        endwhile; endif; wp_reset_query(); wp_reset_postdata();

    } else {*/

    $taxonomies = array('rate-type');
    $args = array(
        'orderby'           => 'name',
        'order'             => 'ASC',
        'hide_empty'        => true,
        'exclude'           => array(),
        'include'           => array(),
        'number'            => '',
        'fields'            => 'all',
        'slug'              => '',
        'parent'            => '',
        'hierarchical'      => true,
        'child_of'          => 0,
        'childless'         => false,
        'get'               => '',
        'pad_counts'        => false,
        'offset'            => '',
        'search'            => ''
    );
    $terms = get_terms($taxonomies, $args);

    foreach( $terms as $term ) {
        $args2 = array(
            'posts_per_page'   => -1, //$posts_per_page
            'post_type' => 'rate-plan',
            'order' => 'ASC',
            'orderby' => 'meta_value',
            'meta_key' => 'wpcf-rate-plan-id',
            'post_status'      => 'publish',
            // only get parents, not children - if hierarchical
            'post_parent'      => 0,
            'tax_query' => array(
                array(
                    'taxonomy' => 'rate-type',
                    'field' => 'name',
                    'terms' => $term->name
                )
            )
        );     
        $posts_array = get_posts( $args2 );

        foreach( $posts_array as $post ) : setup_postdata( $post );
            $page_number = get_post_meta($post->ID, 'pdf_page_no', true);
            $pg_rate_type = wp_get_post_terms($post->ID, 'rate-type', array("fields" => "names"));
            $post_title = get_post_field('post_title',$post->ID);
            echo '<tr><td style="font-size:9px; font-family:Arial, sans-serif;">'.$pg_rate_type[0].'</td><td style="font-size:9px; font-family:Arial, sans-serif;">'.$post_title.'</td><td style="font-size:9px; font-family:Arial, sans-serif; text-align:center;"><b>'.$page_number.'</b></td></tr>';                      
        endforeach;
        
        wp_reset_postdata();
    }
    
    //} // if posts per page != -\ (all)
    

    echo '</table>';
    return ob_get_clean();
   
}
function index_page_style() { ?>
    <style type="text/css">
        table { border-collapse: collapse; }
        table, th, td { border: 1px solid #999; }
        table, th {  padding:10px; }
        table, td {  padding-left:10px;  padding-top:3px; padding-bottom:3px; }
    </style>
    <?php
}
function index_page() {

$newpdfpathname = PDF_EXPORT.'index_page.pdf';

$html = get_html_index_page();
                
$dompdf = new DOMPDF(array(
    'enable_font_subsetting' => true,
    'default_media_type' => 'print',
    'default_paper_size' => 'A4',
    'font_height_ratio' => 1,
    'enable_remote' => 1,
    'dpi' => 72,
    //'enable_html5_parser' => 1,
));
//$dompdf->set_paper("A4");

$dompdf->load_html(stripslashes(preg_replace('/\s{2,}/', '', $html)));
$dompdf->render();
$file_to_save = $newpdfpathname;
file_put_contents($file_to_save, $dompdf->output());




}