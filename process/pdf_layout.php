<?php 
require_once(PDF_PROCESS.'config.php');

/*function pdfstyle() { 
	
	?>
		<!DOCTYPE html>
		<html xmlns="http://www.w3.org/1999/xhtml" lang="en-US">
		<head>
		<link rel="stylesheet" href="<?php echo $pdf_export_css_file; ?>" type="text/css" media="print" />
		</head>
		<body>
	<?php		
}*/

function get_all_rate_plan($post_id, $term ) {
		global $pdf_export_css_file;

		$html = '';
		$post = get_post($post_id);
		ob_start();

		//pdfstyle();
		echo '<!DOCTYPE html>
		<html xmlns="http://www.w3.org/1999/xhtml" lang="en-US">
		<head>
		<link rel="stylesheet" type="text/css" href="'.$pdf_export_css_file.'" />
		</head>
		<body>';

		echo '<div class="main_div">';
			//echo '<div class="left"><a  href="'.get_home_url().'">'.get_home_url().'</a></div>';
			echo '<div class="right margin0 padding0" style="height:1px overflow:hidden;"><a class="margin0 padding0" href="'.get_permalink($post->ID).'">'.get_permalink($post->ID).'</a></div><br/>';
			//echo '<hr class="hr" />';
			echo '<div class="right">EAME RM - CONFIDENTIAL</div>';
		  echo '<table cellspacing="0" width="100%" class="margin0 padding0">';
		  echo '<tr>';
			echo '<td>';
			$pg_rate_type = wp_get_post_terms($post->ID, 'rate-type', array("fields" => "names"));
			$pg_madatory_optional = wp_get_post_terms($post->ID, 'mandatory-optional', array("fields" => "names"));
			$pg_rate_category = wp_get_post_terms($post->ID, 'rate-category', array("fields" => "names"));
			$pg_market_segment = wp_get_post_terms($post->ID, 'market-segment', array("fields" => "names"));
			$pg_yieldability_setting = wp_get_post_terms($post->ID, 'yieldability-setting', array("fields" => "names"));
			$meta_values = get_post_meta( $post->ID);			
			//echo "<br/>";
			
			echo '<h2 class="margin_bottom3 margin_top0" ><a href="'.get_term_link( $term, 'rate-type' ).'">'.$pg_rate_type[0]."</a> - ".$post->post_title.'</h2>';
			echo '<p class="fwbold inner_txt margin_top0 margin_bottom5">'.$meta_values['wpcf-rate-plan-name-internal'][0] .' - <a href="'.get_term_link( $term, 'mandatory-optional' ).'">'. $pg_madatory_optional[0] . "</a> - Stand Alone: ".$meta_values['wpcf-stand-alone-rate-plan'][0].'</p>';			
			echo '</td>';
			echo '</tr>';			
			echo "<tr>";
			echo "<td>";
			
			// Rate Plan Header
			echo '<table cellspacing="0" width="100%" class="tableborder5" >';

			echo '<tr>';
			echo '<td colspan="4"  class="w100percent border_bottom1" >';
			echo '<h3 class="padding_left10 margin_bottom3 margin_top3 upper"> Rate Plan Header </h3>';
			echo '</td>';

			echo '</tr>';
			echo '<tr>';

			echo '<td>';
			echo '<div class="padding_left10 w90percent">';
			echo '<div class="border_bottom1 lightgrey padding_bottom1 padding_top3">Parent Rate Plan</div>';
			echo '<p class="fwbold inner_txt margin_top3">'.$meta_values['wpcf-parent-rate-plan-name'][0].'&nbsp;</p>';
			echo '</div>';
			echo '</td>';

			echo '<td>';
			echo '<div class="padding_left10 w90percent">';
			echo '<div class="border_bottom1 lightgrey padding_bottom1 padding_top3">Rate Plan ID</div>';
			echo '<p class="fwbold inner_txt margin_top3">'.$meta_values['wpcf-rate-plan-id'][0].'&nbsp;</p>';
			echo '</div>';
			echo '</td>';

			echo '<td>';
			echo '<div class="padding_left10 w90percent">';
			echo '<div class="border_bottom1 lightgrey padding_bottom1 padding_top3"> Rate Plan Name </div>';
			echo '<p class="fwbold inner_txt margin_top3">'.$meta_values['wpcf-rate-plan-name-internal'][0].'&nbsp;</p>';
			echo '</div>';
			echo '</td>';

			echo '<td>';
			echo '<div class="padding_left10 w90percent">';
			echo '<div class="border_bottom1 lightgrey padding_bottom1 padding_top3">Consumer Facing Name</div>';
			echo '<p class="fwbold inner_txt margin_top3"><a href="'.get_term_link( $term, 'market-segment' ).'">'.$meta_values['wpcf-consumer-facing-name'][0].'</a>&nbsp;</p>';
			echo '</div>';
			echo '</td>';

			echo '</tr>';
			echo '<tr>';

			echo '<td>';
			echo '<div class="padding_left10 w90percent">';
			echo '<div class="border_bottom1 lightgrey padding_bottom1"> Rate Category </div>';
			echo '<p class="fwbold inner_txt margin_top3"><a href="'.get_term_link( $term, 'rate-category' ).'">'.$pg_rate_category[0].'</a>&nbsp;</p>';
			echo '</div>';			
			echo '</td>';	
			echo '<td>';
			echo '<div class="padding_left10 w90percent">';
			echo '<div class="border_bottom1 lightgrey padding_bottom1"> SW Market Segment </div>';
			echo '<p class="fwbold inner_txt margin_top3"><a href="'.get_term_link( $term, 'market-segment' ).'">'.$pg_market_segment[0].'</a>&nbsp;</p>';
			echo '</div>';
			echo '</td>';
			echo '<td>';
			echo '<div class="padding_left10 w90percent">';
			echo '<div class="border_bottom1 lightgrey padding_bottom1"> SW Market Code </div>';
			echo '<p class="fwbold inner_txt margin_top3">'.$meta_values['wpcf-property-market-code'][0].'&nbsp;</p>';
			echo '</div>';
			echo '</td>';
			echo '<td>';
			echo '<div class="padding_left10 w90percent">';
			echo '<div class="border_bottom1 lightgrey padding_bottom1"> Rate Marketing Field </div>';
			echo '<p class="fwbold inner_txt margin_top3">'.$meta_values['wpcf-rate-marketing-field'][0].'&nbsp;</p>';
			echo '</div>';			
			echo '</td>';
		
			echo '</tr>';
			echo '<tr>';

			echo '<td>';
			echo '<div class="padding_left10 w90percent">';
			echo '<div class="border_bottom1 lightgrey padding_bottom1"> Description </div>';
			echo '<p class="fwbold inner_txt margin_top3">'.$meta_values['wpcf-description-300-characters'][0].'&nbsp;</p>';
			echo '</div>';			
			echo '</td>';
			echo '<td>';
			echo '<div class="padding_left10 w90percent">';
			echo '<div class="border_bottom1 lightgrey padding_bottom1"> Short Description</div>';
			echo '<p class="fwbold inner_txt margin_top3">'.$meta_values['wpcf-short-rate-plan-description'][0].'&nbsp;</p>';
			echo '</div>';			
			echo '</td>';
			echo '<td>';
			echo '<div class="padding_left10 w90percent">';
			echo '<div class="border_bottom1 lightgrey padding_bottom1"> GDS OTA </div>';
			echo '<p class="fwbold inner_txt margin_top3">'.$meta_values['wpcf-gds-ota'][0].'&nbsp;</p>';
			echo '</div>';			
			echo '</td>';	
			echo '<td>';
			echo '<div class="padding_left10 w90percent">';
			echo '<div class="border_bottom1 lightgrey padding_bottom1"> Floating </div>';
			echo '<p class="fwbold">'.$meta_values['wpcf-if-floating-discount'][0].'&nbsp;</p>';
			echo '</div>';
			echo '</td>';

			echo '</tr>';
			echo '<tr>';
			
			echo '<td class="w45percent padding_top5" >';
			echo '<div class="padding_left10 w90percent">';
			echo '<div class="border_bottom1 lightgrey padding_bottom1"> Per Stay Pkg </div>';
			echo '<p class="fwbold inner_txt margin_top3">&nbsp;</p>';
			echo '</div>';			
			echo '</td>';	
			echo '<td>';
			echo '<div class="padding_left10 w90percent">';
			echo '<div class="border_bottom1 lightgrey padding_bottom1"> Locked Rate Plan </div>';
			echo '<p class="fwbold inner_txt margin_top3">&nbsp;</p>';
			echo '</div>';
			echo '</td>';
			echo '<td>';
			echo '<div class="padding_left10 w90percent">';
			echo '<div class="border_bottom1 lightgrey padding_bottom1"> Transaction Code </div>';
			echo '<p class="fwbold inner_txt margin_top3">'.$meta_values['wpcf-transaction-code'][0].'&nbsp;</p>';
			echo '</div>';			
			echo '</td>';	
			echo '<td>';
			echo '<div class="padding_left10 w90percent">';
			echo '<div class="border_bottom1 lightgrey padding_bottom1"> Assoc. - Pkg. Elements </div>';
			echo '<p class="fwbold inner_txt margin_top3">'.$meta_values['wpcf-associations-package-elements'][0].'&nbsp;</p>';
			echo '</div>';
			echo '</td>';

			echo '</tr>';		
						
			echo '</table>';
			echo "</td>";
			echo "</tr>";

			/*
			<tr>
<td class="w95percent padding_top5" colspan="2">
<div class="padding_left10 w95percent">
<div class="border_bottom1 lightgrey padding_bottom1"> Description (300 chars) </div>
<p class="fwbold inner_txt margin_top3">Describe Property Specific Components Here - see Package Elements below </p>
</div>
</td>
</tr>
*/
						
			echo '<tr>';
			echo '<td>';

			// Additional Rate Plan Information
			echo '<table cellspacing="0" width="100%" class="tableborder5">';
			echo '<tr>';
			echo '<td colspan="4"  class="w100percent border_bottom1" >';
			echo '<h3 class="padding_left10 margin_bottom3 margin_top3 upper"> Additional Rate Plan Information </h3>';
			echo '</td>';				
			echo '</tr>';
			
			
			
			echo '<tr>';
			echo '<td>';
			echo '<div class="padding_left10 w90percent">';
			echo '<div class="border_bottom1 lightgrey padding_bottom1 taligncenter padding_top3"> Yieldability Setting </div>';
			echo '<div class="fwbold taligncenter"><a href="'.get_term_link( $term, 'yieldability-setting' ).'">'.$pg_yieldability_setting[0].'</a>&nbsp;</div>';
			echo '</div>';
			echo '</td>';
			echo '<td>';
			echo '<div class="padding_left10 w90percent">';
			echo '<div class="border_bottom1 lightgrey padding_bottom1 taligncenter padding_top3"> RMS Net Rate Offset </div>';
			echo '<p class="fwbold inner_txt margin_top3 taligncenter">'.$meta_values['wpcf-rms-net-rate-offset'][0].'&nbsp;</p>';
			echo '</div>';
			echo '</td>';
			echo '<td>';
			echo '<div class="padding_left10 w90percent">';
			echo '<div class="border_bottom1 lightgrey padding_bottom1 taligncenter padding_top3"> SPG Points </div>';
			echo '<p class="fwbold inner_txt margin_top3 taligncenter">'.$meta_values['wpcf-spg-points'][0].'&nbsp;</p>';
			echo '</div>';
			echo '</td>';
			echo '<td>';
			echo '<div class="padding_left10 w90percent">';
			echo '<div class="border_bottom1 lightgrey padding_bottom1 taligncenter padding_top3"> ID Required </div>';
			echo '<p class="fwbold inner_txt margin_top3 taligncenter">'.$meta_values['wpcf-id-required'][0].'&nbsp;</p>';
			echo '</div>';
			echo '</td>';
			echo '</tr>';
			
			echo '<tr>';
			echo '<td>';
			echo '<div class="padding_left10 w90percent">';
			echo '<div class="border_bottom1 lightgrey padding_bottom1 taligncenter padding_top3"> Loc. Display Seq. </div>';
			echo '<p class="fwbold inner_txt margin_top3 taligncenter">'.$meta_values['wpcf-local-display-seq'][0].'&nbsp;</p>';
			echo '</div>';
			echo '</td>';
			echo '<td>';
			echo '<div class="padding_left10 w90percent">';
			echo '<div class="border_bottom1 lightgrey padding_bottom1 taligncenter padding_top3"> TA Commission </div>';
			echo '<p class="fwbold inner_txt margin_top3 taligncenter">'.$meta_values['wpcf-ta-commission'][0].'&nbsp;</p>';
			echo '</div>';
			echo '</td>';
			echo '<td>';
			echo '<div class="padding_left10 w90percent">';
			echo '<div class="border_bottom1 lightgrey padding_bottom1 taligncenter padding_top3"> Suppress Rate </div>';
			echo '<p class="fwbold inner_txt margin_top3 taligncenter">'.$meta_values['wpcf-suppressed-rate'][0].'&nbsp;</p>';
			echo '</div>';
			echo '</td>';
			echo '<td>';
			echo '<div class="padding_left10 w90percent">';
			echo '<div class="border_bottom1 lightgrey padding_bottom1 taligncenter padding_top3"> Source Code </div>';
			echo '<p class="fwbold inner_txt margin_top3 taligncenter">'.$meta_values['wpcf-source-code'][0].'&nbsp;</p>';
			echo '</div>';
			echo '</td>';
			echo '</tr>';			
			
			echo '<tr>';
			echo '<td>';
			echo '<div class="padding_left10 w90percent">';
			echo '<div class="border_bottom1 lightgrey padding_bottom1 taligncenter padding_top3"> ID Information </div>';
			echo '<p class="fwbold inner_txt margin_top3 taligncenter">'.$meta_values['wpcf-id-information'][0].'&nbsp;</p>';
			echo '</div>';
			echo '</td>';
		
			echo '<td>';
			echo '<div class="padding_left10 w90percent">';
			echo '<div class="border_bottom1 lightgrey padding_bottom1 taligncenter padding_top3"> Commission Code </div>';
			echo '<p class="fwbold inner_txt margin_top3 taligncenter">'.$meta_values['wpcf-ta-commission'][0].'&nbsp;</p>';
			echo '</div>';
			echo '</td>';
			echo '<td>';
			echo '<div class="padding_left10 w90percent">';
			echo '<div class="border_bottom1 lightgrey padding_bottom1 taligncenter padding_top3"> Print Rate </div>';
			echo '<p class="fwbold inner_txt margin_top3 taligncenter">'.$meta_values['wpcf-print-rate'][0].'&nbsp;</p>';
			echo '</div>';
			echo '</td>';
			echo '<td>';
			echo '<div class="padding_left10 w90percent">';
			echo '<div class="border_bottom1 lightgrey padding_bottom1 taligncenter padding_top3">Gateway (Associate to Channels)</div>';
			echo '<p class="fwbold inner_txt margin_top3 taligncenter">'.$meta_values['wpcf-gateway'][0].'&nbsp;</p>';
			echo '</div>';
			echo '</td>';
			echo '</tr>';
		
			echo '</table>';
			echo '</td>';
			echo '</tr>';
			
			
			echo '<tr>';
			echo '<td>';
			
			// Rate Plan Allowability
			echo '<table cellspacing="0" class="tableborder5 w100percent" >';
			echo '<tr>';
			echo '<td colspan="4" class="border_bottom1 w100percent" >';
			echo '<h3 class="padding_left10 margin_bottom3 margin_top3 upper"> Rate Plan Allowability </h3>';
			echo '</td>';				
			echo '</tr>';
			
			echo '<tr>';
			echo '<td>';
			echo '<div class="padding_left10 w90percent">';
			echo '<div class="border_bottom1 lightgrey"> Allow. Arrival Days </div>';			
			echo '</div>';
			echo '</td>';		
			echo '<td>';
			echo '<div class="padding_left10 w90percent">';
			echo '<p class="fwbold inner_txt">'.$meta_values['wpcf-allowable-arrival-days'][0].'&nbsp;</p>';			
			echo '</div>';
			echo '</td>';	
			echo '<td>';
			echo '<div class="padding_left10 w90percent">';
			echo '<div class="border_bottom1 lightgrey taligncenter padding_bottom1 padding_top3"> Min Length of Stay </div>';
			echo '<p class="fwbold inner_txt taligncenter">'.$meta_values['wpcf-min-length-of-stay'][0].'&nbsp;</p>';
			echo '</div>';
			echo '</td>';
			echo '<td>';
			echo '<div class="padding_left10 w90percent">';
			echo '<div class="border_bottom1 lightgrey taligncenter"> Min Advance Booking </div>';
			echo '<p class="fwbold inner_txt taligncenter">'.$meta_values['wpcf-minimum-advance-booking'][0].'&nbsp;</p>';
			echo '</div>';
			echo '</td>';			
			echo '</tr>';
			
			
			
			echo '<tr>';
			echo '<td>';
			echo '<div class="padding_left10 w90percent">';
			echo '<div class="border_bottom1 lightgrey"> Allow. Stay Days </div>';			
			echo '</div>';
			echo '</td>';		
			echo '<td>';
			echo '<div class="padding_left10 w90percent">';
			echo '<p class="fwbold inner_txt">'.$meta_values['wpcf-allowable-stay-days'][0].'&nbsp;</p>';			
			echo '</div>';
			echo '</td>';	
			echo '<td>';
			echo '<div class="padding_left10 w90percent">';
			echo '<div class="border_bottom1 lightgrey taligncenter"> Max Length of Stay </div>';
			echo '<p class="fwbold inner_txt margin_top3 taligncenter">'.$meta_values['wpcf-max-length-of-stay'][0].'&nbsp;</p>';
			echo '</div>';
			echo '</td>';
			echo '<td>';
			echo '<div class="padding_left10 w90percent">';
			echo '<div class="border_bottom1 lightgrey taligncenter"> Max Advance Booking </div>';
			echo '<p class="fwbold inner_txt margin_top3 taligncenter">'.$meta_values['wpcf-maximum-advance-booking'][0].'&nbsp;</p>';
			echo '</div>';
			echo '</td>';			
			echo '</tr>';
			
			echo '<tr>';
			echo '<td>';
			echo '<div class="padding_left10 w90percent">';
			echo '<div class="border_bottom1 lightgrey"> Required Stay Days </div>';			
			echo '</div>';
			echo '</td>';		
			echo '<td>';
			echo '<div class="padding_left10 w90percent">';
			echo '<p class="fwbold inner_txt">'.$meta_values['wpcf-required-stay-days'][0].'&nbsp;</p>';
			echo '</div>';
			echo '</td>';

			echo '<td>';
			echo '<div class="padding_left10 w90percent">';
			echo '<div class="border_bottom1 lightgrey taligncenter"> Max Rooms per Res </div>';
			echo '<p class="fwbold inner_txt margin_top3 taligncenter">'.$meta_values['wpcf-max-rooms-per-reservation'][0].'&nbsp;</p>';
			echo '</div>';
			echo '</td>';

			echo '<td>';
			echo '<div class="padding_left10 w90percent">';
			echo '<div class="border_bottom1 lightgrey taligncenter"> Special Service Codes </div>';
			echo '<p class="fwbold inner_txt margin_top3 taligncenter">'.$meta_values['wpcf-special-service-codes'][0].'&nbsp;</p>';
			echo '</div>';
			echo '</td>';

			echo '</tr>';

			echo '<tr>';
			echo '<td>';
			echo '<div class="padding_left10 w90percent">';
			echo '<div class="border_bottom1 lightgrey"> Selling Days of Week </div>';			
			echo '</div>';
			echo '</td>';		
			echo '<td>';
			echo '<div class="padding_left10 w90percent">';
			echo '<p class="fwbold inner_txt">'.$meta_values['wpcf-selling-days-of-the-week'][0].'&nbsp;</p>';
			echo '</div>';
			echo '</td>';

			echo '<td>';
			echo '<div class="padding_left10 w90percent">';
			echo '<div class="border_bottom1 lightgrey taligncenter"> Occupancy Dates </div>';
			echo '<p class="fwbold inner_txt margin_top3 taligncenter">'.$meta_values['wpcf-occupancy-dates-from'][0].' - '.$meta_values['wpcf-occupancy-dates-to'][0].'&nbsp;</p>';
			echo '</div>';
			echo '</td>';

			echo '<td>';
			echo '<div class="padding_left10 w90percent">';
			echo '<div class="border_bottom1 lightgrey taligncenter"> Selling Dates </div>';
			echo '<p class="fwbold inner_txt margin_top3 taligncenter">'.$meta_values['wpcf-sell-dates-from'][0].' - '.$meta_values['wpcf-sell-dates-to'][0].'&nbsp;</p>';
			echo '</div>';
			echo '</td>';			
			echo '</tr>';
					
			echo '</table>';
			echo '</td>';
			echo '</tr>';
			
			
			echo '<tr>';
			echo '<td>';
			
			// Cancellation
			echo '<table cellspacing="0" class="tableborder5 w100percent">';
			echo '<tr>';
			echo '<td colspan="3" class="border_bottom1 w100percent">';
			echo '<h3 class="padding_left10 margin_bottom3 margin_top3 upper"> Cancellation & Deposit Policy, Taxes </h3>';
			echo '</td>';				
			echo '</tr>';			
			echo '<tr>';
			echo '<td>';
			echo '<div class="padding_left10 w90percent">';
			echo '<div class="border_bottom1 lightgrey taligncenter padding_bottom1 padding_top3"> Cancellation Policy </div>';
			echo '<p class="fwbold inner_txt margin_top3 taligncenter margin_bottom3">'.$meta_values['wpcf-cancellation-policy'][0].'&nbsp;</p>';
			echo '</div>';			
			echo '</td>';
			echo '<td>';
			echo '<div class="padding_left10 w90percent">';
			echo '<div class="border_bottom1 lightgrey taligncenter padding_bottom1 padding_top3"> Deposit Policy </div>';
			echo '<p class="fwbold inner_txt margin_top3 taligncenter margin_bottom3">'.$meta_values['wpcf-deposit-policy'][0].'&nbsp;</p>';
			echo '</div>';			
			echo '</td>';
			echo '<td>';
			echo '<div class="padding_left10 w90percent">';
			echo '<div class="border_bottom1 lightgrey taligncenter padding_bottom1 padding_top3"> Rate Plan Taxes </div>';
			echo '<p class="fwbold inner_txt margin_top3 taligncenter margin_bottom3">'.$meta_values['wpcf-rate-plan-taxes'][0].'&nbsp;</p>';
			echo '</div>';			
			echo '</td>';		
			echo '</tr>';			
			echo '</table>';			
			echo '</td>';
			echo '</tr>';	

			echo '<tr>';
			echo '<td>';

			// Rate Season Pricing
			echo '<table cellspacing="0" class="tableborder5 w100percent">';
				echo '<tr>';
					echo '<td colspan = 4 width="100%" class="border_bottom1" >';
						echo '<h3 class="padding_left10 margin_bottom3 margin_top3 upper"> Rate Season Pricing </h3>';
					echo '</td>';				
				echo '</tr>';
				
				
				echo '<tr>';
					echo '<td >';
						echo '<div class="margin_top5 margin_left10 margin_bottom3 border_bottom1">Pricing Room Classes / Types</div>';
					echo '</td>';			
					echo '<td >';
						echo '<div class="margin_top5 padding_left10 fwbold inner_txt margin_top3 margin_bottom3">';
							echo $meta_values['wpcf-pricing-room-classes-room-types'][0];
						echo '&nbsp;</div>';
					echo '</td>';			
					echo '<td align="center" class="">';			
						echo '<div class="w80percent taligncenter">';			
							echo '<div class="border_bottom1">Promo Code</div>';
							echo '<div class="fwbold inner_txt margin_top3">'.$meta_values['wpcf-promo-code'][0].'&nbsp;</div>';			
						echo '</div>';		
					echo '</td>';			
					echo '<td align="center" class="">';
						echo '<div class="w80percent taligncenter">';			
							echo '<div class="border_bottom1">IID Code</div>';
							echo '<div class="fwbold inner_txt margin_top3">'.$meta_values['wpcf-iid-code'][0].'&nbsp;</div>';			
						echo '</div>';			
					echo '</td>';			
				echo '</tr>';					
				echo '<tr>';
					echo '<td>';
						echo '<div class="margin_top5 margin_left10 margin_bottom3 border_bottom1">Single Rate / Differential</div>';
					echo '</td>';			
					echo '<td >';
						echo '<div class="margin_top5 padding_left10 fwbold inner_txt margin_top3 margin_bottom3">';
							echo $meta_values['wpcf-single-rate-differential'][0];
						echo '&nbsp;</div>';
					echo '</td>';			
					echo '<td align=center colspan=2 >';		
						echo '<div class="w90percent">';						
							echo '<div class="taligncenter">';
								echo '<div class="border_bottom1">Set Number</div>';
								echo $meta_values['wpcf-set-number'][0];
							echo '&nbsp;</div>';			
						echo '</div>';
					echo '</td>';
				echo '</tr>';					
				echo '<tr>';
					echo '<td >';
						echo '<div class="margin_top5 margin_left10 margin_bottom3 border_bottom1">Status Value</div>';
					echo '</td>';		
					echo '<td>';
						echo '<div class="margin_top5 padding_left10 fwbold inner_txt margin_top3 margin_bottom3">';
							echo $meta_values['wpcf-status-value'][0];
						echo '&nbsp;</div>';
					echo '</td>';			
					echo '<td colspan=2>&nbsp;</td>';
				echo '</tr>';				
				echo '<tr>';
					echo '<td>';
						echo '<div class="margin_top5 margin_left10 margin_bottom3 border_bottom1">RMS Net Rate Offset</div>';
					echo '</td>';			
					echo '<td>';
						echo '<div class="margin_top5 padding_left10 fwbold inner_txt margin_top3 margin_bottom3">';
							echo $meta_values['wpcf-rms-net-rate-offset'][0];
						echo '&nbsp;</div>';
					echo '</td>';			
					echo '<td colspan=2>&nbsp;</td>';
				echo '</tr>';		
			echo '</table>';
			echo '</td>';
			echo '</tr>';


			echo '<tr>';
			echo '<td>';

			// Distribution		
			echo '<table cellspacing="0" class="tableborder5 w100percent">';
			echo '<tr>';
			echo '<td colspan="4" class="border_bottom1" width="100%">';
			echo '<h3 class="padding_left10 margin_bottom3 margin_top3 upper"> Distribution </h3>';
			echo '</td>';				
			echo '</tr>';	

			echo '<tr>';
			echo '<td class="padding_left10">';				
			echo '<div class="margin_top10 padding_left10 margin_bottom10 w85percent border_bottom1 taligncenter">CCC</div>';			
			echo '</td>';
			echo '<td>';				
			echo '<div class="margin_top10 padding_left10 margin_bottom10 w85percent border_bottom1 taligncenter ">GDS</div>';			
			echo '</td>';
			echo '<td>';				
			echo '<div class="margin_top10 padding_left10 margin_bottom10 w85percent border_bottom1 taligncenter ">Other</div>';
			echo '</td>';
			echo '<td>';				
			echo '<div class="margin_top10 padding_left10 margin_bottom10 w85percent border_bottom1 taligncenter ">Branded Web Site</div>';
			echo '</td>';
			echo '</tr>';

			echo '<tr>';

			echo '<td class="w25percent padding_left10">';				
			echo '<div class="margin_bottom10 padding_bottom10 w85percent">';
			echo '<div class="margin_bottom3">';
			echo '<span class="dib w90">General</span>';
			echo '<p class="dib w10">'.$meta_values['wpcf-ccc-general-availability'][0].'</p>';
			echo '</div>';
			echo '<div class="margin_bottom3">';
			echo '<span class="dib w90">Ask For</span>';
			echo '<p class="dib w10">'.$meta_values['wpcf-ccc-ask-for'][0].'</p>';
			echo '</div>';
			echo '<div class="margin_bottom3">';
			echo '<span class="dib w90">SET#</span>';
			echo '<p class="dib w10">'.$meta_values['wpcf-ccc-with-set-number'][0].'</p>';
			echo '</div>';
			echo '<div class="margin_bottom3">';
			echo '<span class="dib w90">General</span>';
			echo '<p class="dib w10">'.$meta_values['wpcf-ccc-general-availability'][0].'</p>';
			echo '</div>';
			echo '</div>';			
			echo '</td>';
			
			echo '<td class="w25percent">';
			echo '<div class="margin_bottom10 padding_bottom10 w85percent">';
			echo '<div class="margin_bottom3">';
			echo '<span class="dib w90">General</span>';
			echo '<p class="dib w10">'.$meta_values['wpcf-gds-general-availability'][0].'</p>';
			echo '</div>';
			echo '<div class="margin_bottom3">';
			echo '<span class="dib w90">Ask For</span>';
			echo '<p class="dib w10">'.$meta_values['wpcf-gds-ask-for'][0].'</p>';
			echo '</div>';
			echo '<div class="margin_bottom3">';
			echo '<span class="dib w90">Designated TA</span>';
			echo '<p class="dib w10">'.$meta_values['wpcf-gds-designated-agencies'][0].'</p>';
			echo '</div>';
			echo '<div class="margin_bottom3">';
			echo '<span class="dib w90">Promo Table</span>';
			echo '<p class="dib w10">'.$meta_values['wpcf-gds-promo-table'][0].'</p>';
			echo '</div>';
			echo '</div>';			
			echo '</td>';

			echo '<td class="w25percent">';
			echo '<div class="margin_bottom10 padding_bottom10 w85percent">';
			echo '<div class="margin_bottom3">';
			echo '<span class="dib w90">Property Direct</span>';
			echo '<p class="dib w10">'.$meta_values['wpcf-property-direct'][0].'</p>';
			echo '</div>';
			echo '<div class="margin_bottom3">';
			echo '<span class="dib w90">OTA</span>';
			echo '<p class="dib w10">'.$meta_values['wpcf-ota'][0].'</p>';
			echo '</div>';
			echo '<div class="margin_bottom3">';
			echo '<span class="dib w90">Wholesale</span>';
			echo '<p class="dib w10">'.$meta_values['wpcf-wholesale'][0].'</p>';
			echo '</div>';
			echo '</div>';
			echo '</td>';

			echo '<td class="w25percent">';
			echo '<div class="margin_bottom10 padding_bottom10 w85percent">';
			echo '<div class="margin_bottom3">';
			echo '<span class="dib w90">General</span>';
			echo '<p class="dib w10">'.$meta_values['wpcf-brand-site-general-availability'][0].'</p>';
			echo '</div>';
			echo '<div class="margin_bottom3">';
			echo '<span class="dib w90">Promo Table</span>';
			echo '<p class="dib w10">'.$meta_values['wpcf-brand-site-promo-table'][0].'</p>';
			echo '</div>';
			echo '<div class="margin_bottom3">';
			echo '<span class="dib w90">Landing Page</span>';
			echo '<p class="dib w10">'.$meta_values['wpcf-brand-site-landing-page'][0].'</p>';
			echo '</div>';
			echo '<div class="margin_bottom3">';
			echo '<span class="dib w90">SET#</span>';
			echo '<p class="dib w10">'.$meta_values['wpcf-brand-site-with-set-number'][0].'</p>';
			echo '</div>';
			echo '</div>';			
			echo '</td>';
			
			echo '</tr>';		
			echo '</table>';

			echo '</td>';
			echo '</tr>';	

			// Notes
			if($meta_values['wpcf-guideline-description'][0] != '') {
				echo '<tr>';
				echo '<td>';
				echo '<table cellspacing="0"  class="tableborder5 w100percent" >';
				echo '<tr>';
				echo '<td class="border_bottom1 w100percent" >';
				echo '<h3 class="padding_left10 margin_bottom3 margin_top3 upper"> Notes </h3>';
				echo '</td>';				
				echo '</tr>';
				echo '<tr>';
				echo '<td class="w100percent">';
				echo '<p class="margin_top5 margin_left10 margin_right10 margin_bottom3 fwnormal">'.$meta_values['wpcf-guideline-description'][0].'&nbsp;</p>';
				echo '</td>';
				echo '</tr>';
				echo '</table>';
				echo '</td>';
				echo '</tr>';	
			}
			/*
			if($meta_values['wpcf-pricing-guidelines'][0] != '') {
				echo '<tr>';
				echo '<td>';
				echo '<table cellspacing="0"  class="tableborder5 w100percent" >';
				echo '<tr>';
				echo '<td class="border_bottom1 w100percent" >';
				echo '<h3 class="padding_left10 margin_bottom3 margin_top3"> Pricing Guidelines </h3>';
				echo '</td>';				
				echo '</tr>';
				echo '<tr>';
				echo '<td class="w100percent">';
				echo '<p class="margin_top5 margin_left10 margin_right10 margin_bottom3 fwnormal">'.$meta_values['wpcf-pricing-guidelines'][0].'&nbsp;</p>';
				echo '</td>';
				echo '</tr>';
				echo '</table>';
				echo '</td>';
				echo '</tr>';
			}
			
			if($meta_values['wpcf-scoop'][0] != '') {
				echo '<tr>';
				echo '<td>';
				echo '<table cellspacing="0"  class="tableborder5 w100percent" >';
				echo '<tr>';
				echo '<td class="border_bottom1 w100percent" >';
				echo '<h3 class="padding_left10 margin_bottom3 margin_top3"> Business Objectives Outline </h3>';
				echo '</td>';				
				echo '</tr>';
				echo '<tr>';
				echo '<td class="w100percent">';
				echo '<p class="margin_top5 margin_left10 margin_right10 margin_bottom3 fwnormal">'.$meta_values['wpcf-scoop'][0].'&nbsp;</p>';
				echo '</td>';
				echo '</tr>';
				echo '</table>';
				echo '</td>';
				echo '</tr>';
			}			
			if($meta_values['wpcf-online-resources2'][0] != '') {
				echo '<tr>';
				echo '<td>';
				echo '<table cellspacing="0" class="tableborder5 w100percent" >';
				echo '<tr>';
				echo '<td class="border_bottom1 w100percent" >';
				echo '<h3 class="padding_left10 margin_bottom3 margin_top3"> Other Resources </h3>';
				echo '</td>';				
				echo '</tr>';
				echo '<tr>';
				echo '<td class="w100percent">';
				echo '<div class="margin_top5 margin_left10 margin_right10 fwbold inner_txt margin_top3  margin_bottom3 fwnormal">'.$meta_values['wpcf-online-resources2'][0].'&nbsp;</div>';
				echo '</td>';
				echo '</tr>';
				echo '</table>';
				echo '</td>';
				echo '</tr>';
			}*/
			
			/*$connected_posts = get_posts( array(
				'connected_type' => 'rp_docs',
				'connected_items' => $post->ID,
				'suppress_filters' => false,
				'order' => 'title',
				'posts_per_page' => -1,
				'nopaging' => true
			) );
			
			if($connected_posts) {
				echo '<tr>';
				echo '<td>';
				echo '<table cellspacing="0"  class="tableborder5 w100percent" >';
				echo '<tr>';
				echo '<td class="border_bottom1 w100percent" >';
				echo '<h3 class="padding_left10 margin_bottom3 margin_top3"> Supporting Documents </h3>';
				echo '</td>';				
				echo '</tr>';
				echo '<tr>';
				echo '<td class="w100percent">';
				echo '<div class="margin_top15 padding_left10 fwbold inner_txt margin_top3  margin_bottom20" >';
				foreach( $connected_posts as $connected_post) {
				echo 	get_permalink($connected_post->ID);
				echo '<br/>';
				}
				echo '</div>';
				echo '</td>';
				echo '</tr>';
				echo '</table>';
				echo '</td>';
				echo '</tr>';
			}*/
					
			echo "</table>";		
		echo "</div>";
		echo "</body></html>";
		return ob_get_clean();		
	}
