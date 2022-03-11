<?php
if (!defined('ABSPATH')) {
 exit;
}


class MTV_Cards_Carousel_Content
{
 protected $mtv_sticky_options;
 protected $mtv_sticky_version;

 public function __construct()
 {
  $this->mtv_sticky_version = 0.2;
  $options = get_option('mtv_frontend_shorts') ?: array();
  $this->mtv_sticky_options = isset($options['mtv_cards_content_carousel']['positions']) ? $options['mtv_cards_content_carousel']['positions'] : array();
  add_action('mtv_frontend_shorts_filter', array($this, 'set_settings'));
  //add_shortcode('mtv_sticky_bar', array($this, 'mtv_sticky_bar_short'));
  // add_action('init', array($this, 'registerScripts'), 100);
  // add_filter('the_content', array($this, 'add_short'), 100);
 }
 public function add_short($content)
 {
  $options = $this->get_active_status();
  $original_content = $content;
  switch ($options['placement']) {
   case 'before':
    $content = do_shortcode('[mtv_sticky_bar]') . $original_content;
    break;
   case 'after':
    $content .= do_shortcode('[mtv_sticky_bar]');
    break;
   default:
    break;
  }
  return $content;
 }

 public function get_active_status()
 {
  if (empty($this->mtv_sticky_options)) {
   return false;
  }
  $single_options = $this->mtv_sticky_options;
  $post_type = (is_single() || is_front_page()) ? get_post_type() : false;

  if (!$post_type) {
   return false;
  }
  foreach ($single_options as $option) {
   if ($option['post_type'] == $post_type) {
    return $option;
   }
  }
  return false;
 }

 /**
  * register styles and script for tippy
  */
 public function registerScripts()
 {

  wp_register_script('mtv-frontend-sticky', mtv_fr_sh_url . 'assets/js/post-type-sticky-bar/script.js', array(), $this->mtv_sticky_version, true);
  wp_register_script('mtv-frontend-smooth-scroll', mtv_fr_sh_url . 'assets/js/post-type-sticky-bar/smoothscroll.js', array(), $this->mtv_sticky_version, true);

  wp_register_style('mtv-frontend-sticky', mtv_fr_sh_url . 'assets/css/post-type-sticky-bar/style.min.css', false, $this->mtv_sticky_version);
 }


 public function mtv_sticky_bar_short()
 {
  $options = $this->get_active_status();
  if (!$options) {
   return '';
  }

  global $mtv_option;
  $mtv_option = $options;
  wp_enqueue_style('mtv-frontend-sticky');
  wp_enqueue_script('mtv-frontend-smooth-scroll');
  wp_enqueue_script('mtv-frontend-sticky');
  ob_start();
  include mtv_fr_sh_path . 'templates/post-type-sticky-bar/template.php';
  $content = ob_get_contents();
  ob_end_clean();
  return $content;
 }



 public function set_settings($metas)
 {
  $metas['mtv_frontend_shorts']['awm_tabs']['mtv_cards_content_carousel'] = $this->settings();
  return $metas;
 }

 public function settings()
 {
  $settings = array();
  $settings['label'] = __('Cards Carousel', mtv_fr_sh_textdomain);
  $settings['include'] = array(
   'positions' => array(
    'item_name' => __('Shortcode', mtv_fr_sh_textdomain),
    'label' => __('Shortcode Generator', mtv_fr_sh_textdomain),
    'case' => 'repeater',
    'explanation' => __('Copy and paste the shortcode produced and put it in the content or .php file you want.', mtv_fr_sh_textdomain),
    'include' => array(
     'post_type' => array(
      'label' => __('Post types', mtv_fr_sh_textdomain),
      'case' => 'post_types',
      'label_class' => array('awm-needed'),
      'attributes' => array('multiple' => 1)
     ),
     'order_by' => array(
      'label' => __('Order by', mtv_fr_sh_textdomain),
      'case' => 'select',
      'removeEmpty' => true,
      'options' => array(
       'post_title' => array('label' => __('Title', mtv_fr_sh_textdomain)),
       'date' => array('label' => __('Date published', mtv_fr_sh_textdomain)),
      ),
     ),
     'order' => array(
      'label' => __('Order', mtv_fr_sh_textdomain),
      'case' => 'select',
      'removeEmpty' => true,
      'options' => array(
       'asc' => array('label' => __('ASC', mtv_fr_sh_textdomain)),
       'desc' => array('label' => __('DESC', mtv_fr_sh_textdomain)),
      ),
     ),
     'terms_ids' => array(
      'label' => __('Term ids', mtv_fr_sh_textdomain),
      'case' => 'input',
      'type' => 'text',
      'explanation' => __('Seperate by commas', mtv_fr_sh_textdomain)
     ),
    )
   )
  );
  return $settings;
 }
}


new MTV_Cards_Carousel_Content();
