<?php
/**
 * Filename: uninstall.php
 * Project: Wordpress PDF Templates
 * Copyright: (c) 2014 Seravo Oy
 * License: GPLv3
 *
 * This file gets called when the plugin is uninstalled from Wordpress.
*/

/*
 * If not called by Wordpress, do nothing
 */
if (!defined('WP_UNINSTALL_PLUGIN')) {
  exit();
}

$upload_dir = wp_upload_dir();

/*
 * Remove directories created by this plugin
 */
is_dir($upload_dir['basedir'] . '/dompdf-fonts') && rrmdir($upload_dir['basedir'] . '/dompdf-fonts');
is_dir($upload_dir['basedir'] . '/pdf-cache') && rrmdir($upload_dir['basedir'] . '/pdf-cache');

/*
 * Handles recursive remove.
 */
function rrmdir($dir) {
  foreach(glob($dir . '/*') as $file) {
    if(is_dir($file)) rrmdir($file); else unlink($file);
  } rmdir($dir);
}
