<?php
if (!defined('ABSPATH')) {
  exit;
}
global $mtv_option;
$items = count($mtv_option['navigations'])
?>
<div id="mtv-sticky-bar" class="<?php echo $mtv_option['disable_sticky'] ? '' : 'mtv_is_sticky'; ?>">
  <div class="mtv-sticky-wrapper">
    <div class="mtv-left-trigger" onclick="scrollHrzntl('left');"><span class="mtv-arrow left"></span></div>
    <div class="nav_wrapper">

      <?php
      foreach ($mtv_option['navigations'] as $navigation) {
      ?><div class="item" data-check="<?php echo $navigation['element']; ?>" data-top="<?php echo $navigation['top_offset']; ?>">
          <div onclick=" mtv_scroll_to('<?php echo $navigation['element']; ?>','<?php echo $navigation['top_offset']; ?>')"><?php echo __($navigation['label'], mtv_fr_sh_textdomain); ?></div>
        </div><?php
            } ?>
    </div>
    <div class="mtv-right-trigger" onclick="scrollHrzntl('right');"><span class="mtv-arrow right"></span></div>
  </div>
</div>