<?php

require_once __DIR__ . '/../lib/Mustache/Autoloader.php';
Mustache_Autoloader::register();

require __DIR__ . '/../admin/partials/wp-module-pager-admin-kses-list.php';

/**
 * Register pager
 *
 * @link       https://github.com/yama-dev
 * @since      1.3.0
 *
 * @package    Wp_Module_Pager
 * @subpackage Wp_Module_Pager/includes
 *
 * @return $_html|str(html)
 */

class Wp_Module_Pager_Front {

  private $maxPage;
  private $currentPage;
  private $allPostCount;
  private $postsParPage;
  private $postType;
  private $paramAry;
  private $paramStr;
  private $pluginName;

  function __construct($query = null, $postType = '', $basePath = '', $pluginName = ''){
    global $wp_query, $wp_rewrite;

    $this->pluginName = $pluginName;

    if(!$query){
      $query = $wp_query;
    }

    if($postType){
      $this->postType = $postType;
    } elseif($basePath){
      $this->postType = preg_replace("/^\/|\/$/","", $basePath);
    } else {
      $this->postType = $query->get('post_type');
    }

    $this->postsParPage = $query->get('posts_per_page');
    $this->allPostCount = (int)$query->found_posts;

    // Query /?paged=[int] or /page/[int]/
    // $this->paramStr     = strstr($_SERVER["REQUEST_URI"], '?');
    $this->paramAry = wp_parse_args(trim($_SERVER["QUERY_STRING"], "/\?"));
    if(!$wp_rewrite->using_permalinks()){
      $this->paramAry['paged'] = 1;
    }
    if(!empty($this->paramAry)) {
      $this->paramStr = '?'.http_build_query($this->paramAry);
    }

    $this->maxPage = ceil($this->allPostCount / $this->postsParPage);
    $this->currentPage = $query->get('paged');
    if($this->currentPage === 0) $this->currentPage = 1;

    // output HTML
    if($this->maxPage != 1 && $this->maxPage != 0){
      $this->outputHtml($this->postType, $this->maxPage, $this->currentPage, $this->paramStr);
    }
  }

  public function outputHtml($postType, $maxPage, $currentPage, $paramStr){

    $template = new Mustache_Engine;

    $templateOption = array(
      'url_page_base' => 'page', // /page/[int]

      'post_type' => $postType,

      'current_page' => $currentPage,
      'prev_page' => $currentPage - 1,
      'next_page' => $currentPage + 1,

      'min_page' => 1,
      'max_page' => $maxPage,

      'url_parameter' => $paramStr,

      'is_disable' => false, // 非活性化の出し分け用
      'is_current' => false, // ページの現在地出し分け用
    );

    // データベースから既存のオプション値を取得
    $db_value_all = get_option( $this->pluginName.'_option_db_all', ENT_QUOTES );
    $db_value_prev = get_option( $this->pluginName.'_option_db_prev', ENT_QUOTES );
    $db_value_next = get_option( $this->pluginName.'_option_db_next', ENT_QUOTES );
    $db_value_li = get_option( $this->pluginName.'_option_db_li', ENT_QUOTES );
    $db_value_flg_omit = get_option( $this->pluginName.'_option_db_flg_omit', ENT_QUOTES );
    $db_value_range = get_option( $this->pluginName.'_option_db_range', ENT_QUOTES );

    $_html_layout = stripslashes( htmlspecialchars_decode( $db_value_all, ENT_QUOTES ) )."\n";
    $_html_layout_prev = stripslashes( htmlspecialchars_decode( $db_value_prev, ENT_QUOTES ) )."\n";
    $_html_layout_next = stripslashes( htmlspecialchars_decode( $db_value_next, ENT_QUOTES ) )."\n";
    $_html_layout_li = stripslashes( htmlspecialchars_decode( $db_value_li, ENT_QUOTES ) )."\n";

    if($currentPage < $db_value_range){
      // Left
      $countDiffMin = $db_value_range * -1;
      $countDiffMax = $db_value_range + ($db_value_range - ($currentPage));
    } elseif($currentPage > ($maxPage - $db_value_range + 1)){
      // Right
      $countDiffMin = $db_value_range * -1 - ($db_value_range - 1 - ($maxPage - $currentPage));
      $countDiffMax = $db_value_range;
    } else {
      // Center
      $countDiffMin = $db_value_range * -1;
      $countDiffMax = $db_value_range;
    }

    // <div class="c-pagination__btn {{ classnmae_prev_next_btn }}">
    //   <a href="/{{ post_type }}/{{ url_page_base }}/{{ target_page }}/{{ url_parameter }}">{{ text_link_prev_next_btn }}</a>
    // </div>

    $_template_layout_prev = '';
    if($maxPage >= 1 && $currentPage >= 2){
      $_template_layout_prev = $template->render(
        $_html_layout_prev,
        array_merge( $templateOption )
      );
    } else {
      $_template_layout_prev = $template->render(
        $_html_layout_prev,
        array_merge( $templateOption, array('is_disable' => true) )
      );
    }

    $_template_layout_li = '';
    $_page_dot_flg = true;
    for ($_i = 1; $_i <= $maxPage; $_i++) {

      $_i2 = str_pad($_i, 2, '0', STR_PAD_LEFT);

      if($_i == 1){
        // first item.
        if($currentPage == $_i || $maxPage <= 1 || $currentPage == null && $_i == 1){
          $_template_layout_li .= $template->render(
            $_html_layout_li,
            array_merge( $templateOption, array('_i' => $_i, '_i2' => $_i2, 'is_current' => true) )
          );
        } else {
          $_template_layout_li .= $template->render(
            $_html_layout_li,
            array_merge( $templateOption, array('_i' => $_i, '_i2' => $_i2) )
          );
        }
      } elseif($_i == $maxPage){
        // last item.
        if($currentPage == $_i || $maxPage <= 1 || $currentPage == null && $_i == 1){
          $_template_layout_li .= $template->render(
            $_html_layout_li,
            array_merge( $templateOption, array('_i' => $_i, '_i2' => $_i2, 'is_current' => true) )
          );
        } else {
          $_template_layout_li .= $template->render(
            $_html_layout_li,
            array_merge( $templateOption, array('_i' => $_i, '_i2' => $_i2) )
          );
        }
      } elseif(
        ($_i >= ($currentPage + $countDiffMax) || $_i <= ($currentPage + $countDiffMin))
        && $db_value_flg_omit
      ){
        // 3dot.
        if($_page_dot_flg){
          $_page_dot_flg = false;
          $_template_layout_li .= $template->render(
            $_html_layout_li,
            array_merge( $templateOption, array('is_omit' => true) )
          );
        }
      } else {
        // other.
        if($currentPage == $_i || $maxPage <= 1 || $currentPage == null && $_i == 1){
          $_template_layout_li .= $template->render(
            $_html_layout_li,
            array_merge( $templateOption, array('_i' => $_i, '_i2' => $_i2, 'is_current' => true) )
          );
        } else {
          $_template_layout_li .= $template->render(
            $_html_layout_li, array_merge( $templateOption, array('_i' => $_i, '_i2' => $_i2) )
          );
        }
        $_page_dot_flg = true;
      }
    }

    $_template_layout_next = '';
    if($maxPage >= 1 && $currentPage <= ($maxPage-1)){
      $_template_layout_next = $template->render(
        $_html_layout_next,
        array_merge( $templateOption )
      );
    } else {
      $_template_layout_next = $template->render(
        $_html_layout_next,
        array_merge( $templateOption, array('is_disable' => true) )
      );
    }

    $_html_layout = $template->render(
      $_html_layout,
      array_merge(
        $templateOption,
        array(
          'layout_pager'=>$_template_layout_li,
          'layout_prev'=>$_template_layout_prev,
          'layout_next'=>$_template_layout_next
        )
      ) 
    );

    echo wp_kses_post( htmlspecialchars_decode( $_html_layout, ENT_QUOTES ) );
  }

}
