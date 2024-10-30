<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://github.com/yama-dev
 * @since      1.5.0
 *
 * @package    Wp_Module_Pager
 * @subpackage Wp_Module_Pager/admin/partials
 */

// setting.
$wp_kses_allowed_html = array(
  'a' => array(
    'id' => array (),
    'class' => array (),
    'href' => array (),
    'target' => array(),
    'rel' => array()
  ),
  'div' => array(
    'id' => array (),
    'class' => array (),
    'data-index' => array (),
    'data-tab' => array (),
    'data-id' => array (),
  ),
  'ul' => array(
    'id' => array (),
    'class' => array (),
  ),
  'ol' => array(
    'id' => array (),
    'class' => array (),
  ),
  'li' => array(
    'id' => array (),
    'class' => array (),
  ),
  'span' => array(
    'id' => array (),
    'class' => array (),
  ),
  'p' => array(
    'id' => array (),
    'class' => array (),
  ),
  'img' => array(
    'id' => array (),
    'class' => array (),
    'src' => array (),
    'alt' => array (),
  ),
  'br' => array(
    'id' => array (),
    'class' => array (),
  ),

  'svg' => array(
    'id' => array (),
    'class' => array (),
    'style' => array (),
    'xmlns' => array (),
    'viewbox' => array(),
    'transform' => array()
  ),
  'mask' => array(
    'id' => array (),
    'class' => array (),
    'style' => array (),
    'x' => array(),
    'y' => array(),
    'transform' => array()
  ),
  'g' => array(
    'id' => array (),
    'class' => array (),
    'style' => array (),
    'fill' => array(),
    'stroke' => array(),
    'stroke-width' => array()
  ),
  'defs' => array(),

  'circle' => array(
    'id' => array (),
    'class' => array (),
    'style' => array (),
    'cx' => array (),
    'cy' => array(),
    'r' => array(),
    'transform' => array()
  ),
  'line' => array(
    'id' => array (),
    'class' => array (),
    'style' => array (),
    'x1' => array(),
    'y1' => array(),
    'x2' => array(),
    'y2' => array(),
    'stroke' => array(),
    'transform' => array()
  ),
  'path' => array(
    'id' => array (),
    'class' => array (),
    'style' => array (),
    'd' => array(),
    'transform' => array()
  ),
  'rect' => array(
    'id' => array (),
    'class' => array (),
    'style' => array (),
    'fill' => array(),
    'stroke' => array(),
    'x' => array(),
    'y' => array(),
    'width' => array(),
    'height' => array(),
    'transform' => array()
  ),
);

