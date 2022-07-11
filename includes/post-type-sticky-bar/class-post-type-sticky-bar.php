<?php
if (!defined('ABSPATH')) {
  exit;
}


class MTV_Sticky_Post_Type_Bar
{
  protected $mtv_sticky_options;
  protected $mtv_sticky_version;

  public function __construct()
  {
    $this->mtv_sticky_version = 0.2;
    $options = get_option('mtv_frontend_shorts') ?: array();
    $this->mtv_sticky_options = isset($options['mtv_sticky_bar']['positions']) ? $options['mtv_sticky_bar']['positions'] : array();
    add_action('mtv_frontend_shorts_filter', array($this, 'set_settings'));
    add_shortcode('mtv_sticky_bar', array($this, 'mtv_sticky_bar_short'));
    add_action('init', array($this, 'registerScripts'), 100);
    add_filter('the_content', array($this, 'add_short'), 100);
  }
  public function add_short($content)
  {
    $options = $this->get_active_status();
    $original_content = $content;
    if (isset($options['placement']) && !empty($options['placement'])) {
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
    $metas['mtv_frontend_shorts']['awm_tabs']['mtv_sticky_bar'] = $this->settings();
    return $metas;
  }

  public function settings()
  {
    $settings = array();
    $settings['label'] = __('Sticky Single Post Type Bar', mtv_fr_sh_textdomain);
    $settings['include'] = array(
      'positions' => array(
        'item_name' => __('Position', mtv_fr_sh_textdomain),
        'label' => __('Positions', mtv_fr_sh_textdomain),
        'case' => 'repeater',
        'explanation' => __('You can use the shortcode [mtv_sticky_bar] inside yous single-post_type.php file. If you have not configure the Positions settings nothing will appear', mtv_fr_sh_textdomain),
        'include' => array(
          'post_type' => array(
            'label' => __('Post types', mtv_fr_sh_textdomain),
            'case' => 'post_types',
            'label_class' => array('awm-needed'),
          ),
          'disable_sticky' => array(
            'label' => __('Disable sticky', mtv_fr_sh_textdomain),
            'case' => 'input',
            'type' => 'checkbox',
            'label_class' => array('awm-needed'),
          ),
          'placement' => array(
            'label' => __('Placement in single', mtv_fr_sh_textdomain),
            'case' => 'select',
            'removeEmpty' => true,
            'options' => array(
              'custom' => array('label' => __('Custom placement by user (copy and paste the shortcode)', mtv_fr_sh_textdomain)),
              'before' => array(
                'label' => __('Before content (no action required)', mtv_fr_sh_textdomain)
              ),
              'after' => array(
                'label' => __('After content (no action required)', mtv_fr_sh_textdomain)
              )
            ),
            'label_class' => array('awm-needed'),

          ),
          'navigations' => array(
            'label' => __('Navigation configuration', mtv_fr_sh_textdomain),
            'case' => 'repeater',
            'item_name' => __('Navigation', mtv_fr_sh_textdomain),
            'include' => array(
              'label' => array(
                'label' => __('Navigation label', mtv_fr_sh_textdomain),
                'case' => 'input',
                'type' => 'text',
                'label_class' => array('awm-needed'),
              ),
              'element' => array(
                'explanation' => __('Add the html element you want to attach the label with. ex: #content, .content p#main etc.', mtv_fr_sh_textdomain),
                'label' => __('Element', mtv_fr_sh_textdomain),
                'case' => 'input',
                'type' => 'text',
                'label_class' => array('awm-needed'),
              ),
              'top_offset' => array(
                'explanation' => __('Add a top offset. Just the number not the pixels. You can add negative values too', mtv_fr_sh_textdomain),
                'label' => __('Top offset', mtv_fr_sh_textdomain),
                'case' => 'input',
                'type' => 'number',
              ),
            )
          )
        )
      )
    );
    return $settings;
  }
}


new MTV_Sticky_Post_Type_Bar();
