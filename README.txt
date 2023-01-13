=== Simple PDF Exporter ===

Contributors: Shambix, Dukessa
Author URL: https://www.shambix.com
Requires at least: 5
Tested up to: 6.1.1
Stable tag: trunk
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html
Tags: pdf, dompdf, exporter, export, custom post types, export pdf, pdf collection, create pdf

== Description ==

Export a single PDF with all posts, or custom post types.
You can also export a single post, or the exact number you need.

> **IMPORTANT** This plugin requires at least 512MB of free RAM available, or it will timeout / return an error.

This plugin is NOT recommended for people with no tech knowledge.
The PDF layout/design is VERY basic and will require html/css/php technical knowledge to customize.
Read more about this below.

= The Basics =

The plugin checks if a pdf already exists with the same date (ddMonyear), if yes, the existing pdf will be served, otherwise a new will be generated. Since the PDF generation uses up a lot of resources, this will prevent too many runs of the plugin and the crashing of your server.
Check the example below or the FAQ for ways to force the PDF generation anyway.

**Depending on how many posts you have, it might take from a few seconds to several minutes for a new PDF to be generated.**

> If no PDF is generated you probably don't have enough server resources. **This can't be fixed, as PDF libraries are very resource-hungry.**
Ask your hosting to check how many resources you would need to run the plugin and if there is anything you can do, within your hosting limits, to make sure the plugin has enough or appropriate RAM/PHP settings.

If you don't use a custom url, hence you don't add the `post_type` parameter to the url, the default post type exported will always be WP default `post`.

= The PDF Template =

Currently, the template and design for the exported pdf, is **very** basic (and posts are rendered as a table, since floating doesn't play nicely with the DOMPDF library).

You can copy the plugin's basic structure from `wp-content/plugins/simple-pdf-exporter/assets/pdf_layout.php` inside your current theme and edit it.
Your layout **must** be echoed in php, eg. `echo '<div>the content goes here</div>';` or it won't show up in the PDF.

You can copy the plugin's basic CSS from `wp-content/plugins/simple-pdf-exporter/assets/pdf_export.css` inside your current theme and edit it.

Do not edit directly the plugin's files `pdf_layout.php` and `pdf_export.css`, they will be overwritten with the next update.

= Questions? =

Check the FAQ before opening new threads in the forum!

> Contact me if you want a **custom version of the plugin**, for a fee (email on [shambix.com](https://www.shambix.com)).

= Libraries & Credits =

* [DOMPDF](https://github.com/dompdf/dompdf)
* [FPDF](http://www.fpdf.org/)
* [FPDI](https://www.setasign.com/products/fpdi/about/)
* [FPDI ADDON](https://gist.github.com/2020422)

== Installation ==

1. Download and activate the plugin.
2. In order to export a pdf, you can do it from backend from Tools -> PDF Export or enter `http://yoursite.com/?export=pdf` in your browser. You can also make the url into a link or button on your site, to use on frontend, please check the FAQ.

== Frequently Asked Questions ==

= I activated the plugin but can't see any difference. What do I do? =
In order to export a pdf, you can do it from backend from Tools -> PDF Export or enter `http://yoursite.com/?export=pdf` in your browser.
You can also make the url into a link or button on your site, to use on frontend.

= How do I make a button or link for the frontend? =
You can add a frontend link or button on your frontend and customize it, like in this example for Twitter Bootstrap:
Change the parameters to suit your needs.

`<?php // Check if PDF Export Plugin exists first
if( function_exists('simple_pdf_export_process')) { ?>
	<ul class="nav nav-tabs pull-right pdf_export_menu">
		<li role="presentation" class="dropdown">
		<a class="dropdown-toggle btn" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Export Posts to PDF <span class="caret"></span></a>
			<ul class="dropdown-menu">
				<?php
					$post_type_to_export = 'portfolio';
					$final_pdf = SIMPLE_PDF_EXPORTER_EXPORT.$post_type_to_export.SIMPLE_PDF_EXPORTER_EXTRA_FILE_NAME.date('dMY').'.pdf';
					if(file_exists($final_pdf)) {
						$file_date = date("d M Y - H:i", filemtime($final_pdf));
						?>
						<li><a class="" href="?export=pdf&post_type=portfolio&num=3" target="_blank">Download Existing Version <small>(<?php echo $file_date; ?> GMT)</small></a></li>
				<?php } ?>
				<li><a class="" href="?export=pdf&post_type=portfolio&num=3&force" target="_blank">Generate New Version <small>(might take several minutes)</small></a></li>
			</ul>
		</li>
	</ul>
<?php } ?>`

= I want to limit the amount of posts =
Use `&num=x` in your url.
eg. `http://yoursite.com/?export=pdf&num=3`

= I want to export a specific post =
Use `&post_id=x` with the ID of the post or custom post type you need, in your url.
eg. `http://yoursite.com/?export=pdf&post_id=3`
In this case, don't use the `post_type` parameter. The `num` parameter will be ignored for obvious reasons.

= I want to create a PDF from a custom post type =
Use `&post_type=x`.
eg. `http://yoursite.com/?export=pdf&post_type=your-post-type-slug`

= How do I customize the layout of the posts in the pdf? =
You need to change the file `pdf_layout.php` inside the plugin folder `wp-content/plugins/simple-pdf-exporter/process/pdf_layout.php`
Your layout must be echoed in php. eg. `echo '<div>the content goes here</div>';` or it won't show up in the PDF.

= I want to override the existing file / generate a new pdf no matter what =
Use `&force` after your url
eg. `http://yoursite.com/?export=pdf&force`
This will also invalidate the internal cache, hence it will take more time to generate the PDF. Avoid if possible.

= Can I use a custom CSS file? =
You can use a custom CSS to customize the layout.
Create a file named pdf_export.css in your theme folder, otherwise the plugin's default (and really basic) CSS will be used (you can find it here `wp-content/plugins/simple-pdf-exporter/assets/pdf_export.css`)

= Is there some other option I can change? =
Yes there's a few. In order to change them, add them to your `wp-config.php` file.
eg. define(SIMPLE_PDF_EXPORTER_PAGINATION', true);

Here's the full list and what they are set to, by default:

* define('SIMPLE_PDF_EXPORTER_DEBUG', false); // if true, the output will be in html for debugging purposes
* define('SIMPLE_PDF_EXPORTER_PAGINATION', false);
* define('SIMPLE_PDF_EXPORTER_HTML_OUTPUT', false);
* define(SIMPLE_PDF_EXPORTER_CSS_FILE', get_stylesheet_directory().'/pdf_export.css');
* define('SIMPLE_PDF_EXPORTER_LAYOUT_FILE', get_stylesheet_directory().'/pdf_layout.php');
* define('SIMPLE_PDF_EXPORTER_EXTRA_FILE_NAME', '-');
* define('DOMPDF_PAPER_SIZE', 'A4');
* define('DOMPDF_PAPER_ORIENTATION', 'portrait');
* define('DOMPDF_DPI', 72);
* define('DOMPDF_ENABLE_REMOTE', true);
* define('DOMPDF_ENABLE_HTML5', true); // true by default, since v.1.9.2
* define('DOMPDF_ENABLE_FONTSUBSETTING', true);
* define('DOMPDF_MEDIATYPE', 'print');
* define('DOMPDF_FONTHEIGHTRATIO', 1);

= How do I debug the layout in HTML form instead of PDF? =
You can set define('SIMPLE_PDF_EXPORTER_HTML_OUTPUT', true); in your `wp-config.php` file, just remember to comment the line or set to `false`, when you are done debugging.


== Changelog ==

= 2.0 (13 jan 2023) =
* Fixed some issues with pdf layout and pdf css. Current theme css is now loading before the pdf one (so you can easily overwrite its style and you don't get an unstyled pdf out of the box)
* Tested with WP 6.6.1
* Tested with PHP 8.1 (credits to Marijn Lomix @ Lomix)
* Updated DOMPDF to v.2.0.1
* Updated FPDF to v.1.82
* Updated FPDI to v.2.3.1

= 1.9.2 = 
* Fixed bug `Warning: call_user_func() expects parameter 1 to be a valid callback, class 'SIMPLE_PDF_EXPORT_SETTINGS' does not have a method 'settings_section_simple_pdf_export_settings' in /home/larucheiiz/www/wp-admin/includes/template.php on line 1643`
* Updated some constants and added a debug one `SIMPLE_PDF_EXPORTER_DEBUG` which will output in html for debugging purposes
* Updated DOMPDF to v.0.8.5
* Tested with WP 5.4.1
* Tested with PHP 7.3

= 1.9.1 = 
* Fixed a bug where the the plugin was looking for file `pdf_export.php` in the current theme, instead of the correct filename `pdf_layout.php` - the file `pdf_export.php` will still work until the next update, please rename it asap (Thanks to `artifexmedia` for reporting the bug)
* Added a line in the FAQ, explaining how to debug the layout in HTML

= 1.9 =
* Added new option 'Download PDF or open in Tab'

= 1.8.9 =
* Tested with WP 5.3

= 1.8.8 (9 mar 2017) =
* Fixed issue with missing libraries

= 1.8.7 (6 mar 2017) =
* Fixed bug (pdf_layout.php not found)
* Updated dompdf library to [v.0.8.0](https://github.com/dompdf/dompdf/releases/tag/v0.8.0)

= 1.8.6 (29 jun 2016) =
* Improvements to caching
* Small fixes

= 1.8.4 (14 apr 2016) =
* Fixed path for custom CSS and Layout file
* Fixed CSS var in layout and check for existing CSS and Layout custom file in template

= 1.8.2 (28 mar 2016) =
* Added constant SIMPLE_PDF_EXPORTER_LAYOUT_FILE so you can put it in theme and wont get overwritten

= 1.8 (11 mar 2016) =
* Added parameter `post_id` (for exporting specific post)
* Added better checks to save exec time
* Added url examples in Settings page
* Cleaned up

= 1.7.6 (10 mar 2016) =
* Fixed bugs with wrong/obsolete vars & constants
* Added constant SIMPLE_PDF_EXPORTER_EXTRA_FILE_NAME to customize the final pdf name (after post type, before the date)

= 1.7 (3 mar 2016) =
* Cleaned up everything
* Added constants i/o vars
* Added more options
* Simplified process

= 1.5 (25 feb 2016) =
* Updated DOMPDF 0.7
* Updated FPDF 1.81
* Updated FPDI 1.6.1

= 1.4 =
* Added support for custom post types
* Added force generation files
* Added check if file already exists in the same day (dont create it again)
* Added caching in wp queries
* Heavy use of single config
* Re-wrote plugin activation - deactivation
* Create html versions too, on request (config)
* Added custom css file, as well as pre-made one (instead of inline css)
* Merged posts pdf creation with page number and posts merge

= 1.0 (23 feb 2016) =
* Initial release on Github
