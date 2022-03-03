<?php
if (!defined('ABSPATH')) {
 exit;
}


class MTV_SHORTS_SETUP
{
 public function __construct()
 {
  require_once 'setup_functions.php';
  add_action('plugins_loaded', array($this, 'textdomain'));
  add_action('admin_notices', array($this, 'check_awm'), 100);
  add_filter('awm_add_options_boxes_filter', array($this, 'mtv_fr_sh_settings'), 100);
 }


 function textdomain()
 {
  load_plugin_textdomain(mtv_fr_sh_textdomain, false, mtv_fr_sh_path . '/languages/');
 }


 public function check_awm()
 {
  if (!class_exists('AWM_Meta')) {
   $class = 'notice notice-error';
   $message = __('Please install AWM meta, so you can user MTV Frontend Shortocodes', mtv_fr_sh_textdomain);
   printf('<div class="%1$s"><p>%2$s</p></div>', esc_attr($class), esc_html($message));
  }
 }



 /**
  * add custom options pages
  * @param array $options all the options table
  */
 public function mtv_fr_sh_settings($options)
 {
  $options['mtv_frontend_shorts'] = array(
   'title' => __('MTV Frontend Shorts', 'filox'),
   'library' => $this->shortcode_settings(),
   'order' => 10
  );
  return $options;
 }

 function shortcode_settings()
 {
  $metas = array();
  $metas['mtv_frontend_shorts'] = array(
   'case' => 'awm_tab',
   'label' => __('Shortocode configuration', mtv_fr_sh_textdomain),
   'awm_tabs' => array(),
  );
  return apply_filters('mtv_frontend_shorts_filter', $metas);
 }
}


new MTV_SHORTS_SETUP();
