<?php
if (!defined('ABSPATH')) exit;


if (!function_exists('mtv_fr_sh_slick_object')) {
 /**
  * with this class we create a slick object everywhere in the frontend
  */
 function mtv_fr_sh_slick_object($atts = array())
 {
  $data = array();
  $display = isset($atts['display']) ? $atts['display'] : 'carousel';
  $atts['columns'] = (isset($atts['total']) && !empty($atts['total']) && $atts['total'] < $atts['columns']) ? $atts['total'] : (isset($atts['columns']) ? $atts['columns'] : 1);
  $data['prevArrow'] = isset($atts['prevArrow']) ? $atts['prevArrow'] : '<i class="myslick-arrow sbp_icon sbp-icon-arrow-left slick-left"></i>';
  $data['nextArrow'] = isset($atts['nextArrow']) ? $atts['nextArrow'] : '<i class="myslick-arrow sbp_icon sbp-icon-arrow-right slick-right"></i>';
  switch ($display) {
   case 'carousel':
    $data['slidesToShow'] = $atts['columns'];
    $data['dots'] = isset($atts['dots']) ? $atts['dots'] : false;
    $data['speed'] = isset($atts['speed']) ? $atts['speed'] : 400;
    $data['infinite'] = true;
    $data['swipeToSlide'] = true;
    $data['variableWidth'] = isset($atts['variableWidth']) ? $atts['variableWidth'] : false;
    $data['centerMode'] = isset($atts['mode']) ? $atts['mode'] : false;
    $data['fade'] = isset($atts['fade']) ? $atts['fade'] : false;
    $data['centerPadding'] = isset($atts['centerPadding']) ? $atts['centerPadding'] : '0';
    $data['autoplay'] = isset($atts['autoplay']) ? $atts['autoplay'] : false;
    $data['autoplaySpeed'] = isset($atts['autoplaySpeed']) ? $atts['autoplaySpeed'] : 4000;
    $data['responsive'] = array(
     array(
      'breakpoint' => 1100,
      'settings' => array(
       'slidesToShow' => isset($atts['mcolumns']) ? $atts['mcolumns'] : 3,
       'slidesToScroll' => 1,
       'autoplay' => false,
      ),
     ),
     array(
      'breakpoint' => 750,
      'settings' => array(
       'slidesToShow' => isset($atts['scolumns']) ? $atts['scolumns'] : 2,
       'slidesToScroll' => 1,
       'autoplay' => false,
       'centerMode' => isset($atts['mmode']) ? $atts['mmode'] : false,
      ),
     ),
     array(
      'breakpoint' => 550,
      'settings' => array(
       'slidesToShow' => isset($atts['vscolumns']) ? $atts['vscolumns'] : 1,
       'slidesToScroll' => 1,
       'autoplay' => false,
       'fade' => true,
       'centerMode' => isset($atts['mmode']) ? $atts['mmode'] : false,
      ),
     )
    );
    break;
   case 'grid':
    $data['slidesToShow'] = $atts['total'];
    $data['slidesToScroll'] = 1;
    $data['zIndex'] = 100000;
    $data['centerMode'] = true;
    $data['centerPadding'] = '0px';
    $data['autoplay'] = false;
    $data['responsive'] = array(
     array(
      'breakpoint' => 767,
      'settings' => array(
       'slidesToShow' => 2,
       'slidesToScroll' => 1,
      ),
     ),
     array(
      'breakpoint' => 550,
      'settings' => array(
       'slidesToShow' => 1,
       'slidesToScroll' => 1,
       'centerMode' => true,
       'fade' => true
      )
     ),
    );
    break;
   default:
    break;
  }

  return str_replace('"', '\'', json_encode(apply_filters('sbp_slick_object_filter', $data, $atts)));
 }
}
