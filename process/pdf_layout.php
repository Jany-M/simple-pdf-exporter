<?php 

if(!function_exists('create_pdf_layout')) {

	function create_pdf_layout($post, $term ) {
		global $post, $pdf_export_css_file;

		$html = '';

		ob_start();

		// HEADER
		echo '<!DOCTYPE html><html xmlns="http://www.w3.org/1999/xhtml" lang="en-US"><head><title>PDF EXPORT</title>';
		echo '<link rel="stylesheet" type="text/css" href="'.$pdf_export_css_file.'" />';
		echo '</head><body>';

		// CONTENT
		echo '<div class="main_div">';
		echo '<div class="post_wrapper" ><a href="'.get_permalink().'">'.get_permalink().'</a></div>';
			echo '<table cellspacing="0" width="100%"">';
				echo '<tr>';
				echo '<td>';
				echo '<h2>'.get_the_title().'</h2>';		
				echo '</td>';
				echo '</tr>';			

					echo '<table cellspacing="0" width="100%">';
					echo '<tr>';
					echo '<td>';
					echo '<p>'.$post->post_content.'</p>';
					echo '</td>';
					echo '</tr>';
					echo '</table>';

				echo "</td>";
				echo "</tr>";

			echo "</table>";		
		echo "</div>";

		// FOOTER
		echo "</body></html>";
		
		return ob_get_clean();
	}
	
}