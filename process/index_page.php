<?php
require_once(PDF_PROCESS.'config.php');
use Dompdf\Dompdf;

/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED);
ini_set("error_log", "/var/log/php-fpm/pdf-gen.log");*/

function get_html_index_page() {
    global $pdf_export_all_posts, $dompdf_settings, $pdf_export_css_file;

    ob_start();
    
    //index_page();
   echo '<link rel="stylesheet" type="text/css" href="'.$pdf_export_css_file.'" />';

    /*echo '<table class="index_page" width="100%">';
    echo '<tr style="padding:0; margin:0; height:5px"><th colspan="3" align="center" style="font-size:10px; font-family:Arial, sans-serif;">INDEX</th></tr>';
    echo '<tr><th style="font-size:10px; padding:5px; margin:0; font-family:Arial, sans-serif;">Rate Type</th><th style="font-family:Arial, sans-serif;">Rate Name</th><th style="font-family:Arial, sans-serif;">Page No</th></tr>';*/

    echo '<table class="index_page" width="100%">';
    echo '<tr><th colspan="3" align="center">INDEX</th></tr>';
    echo '<tr><th>Rate Type</th><th>Rate Name</th><th>Page</th></tr>';

    $terms = get_transient('simple_pdf_export_taxterms');

    foreach( $terms as $term ) {

        $term_transient = 'simple_pdf_export_taxterms'.$term->term_id;
        //delete_transient($term_transient);
        $posts_array = get_transient($term_transient);

        foreach( $posts_array as $post ) : setup_postdata( $post );

            $page_number = get_post_meta($post->ID, 'pdf_page_no', true);
            $pg_rate_type = wp_get_post_terms($post->ID, 'rate-type', array("fields" => "names"));
            $post_title = get_post_field('post_title',$post->ID);
            echo '<tr><td style="font-size:9px; font-family:Arial, sans-serif;">'.$pg_rate_type[0].'</td><td style="font-size:9px; font-family:Arial, sans-serif;">'.$post_title.'</td><td style="font-size:9px; font-family:Arial, sans-serif; text-align:center;"><b>'.$page_number.'</b></td></tr>';                      
        endforeach;
        
        wp_reset_postdata();
    }
    
    

    echo '</table>';
    return ob_get_clean();
   
}

/*function index_page_style() { ?>
    <style type="text/css">
        table { border-collapse: collapse; }
        table, th, td { border: 1px solid #999; }
        table, th {  padding:10px; }
        table, td {  padding-left:10px;  padding-top:3px; padding-bottom:3px; }
    </style>
    <?php
}*/

function index_page() {

$newpdfpathname = PDF_EXPORT.'index_page.pdf';

$html = get_html_index_page();
                
$dompdf = new DOMPDF(array($dompdf_settings));
$dompdf->load_html(stripslashes(preg_replace('/\s{2,}/', '', $html)));
$dompdf->render();
$file_to_save = $newpdfpathname;
file_put_contents($file_to_save, $dompdf->output());




}