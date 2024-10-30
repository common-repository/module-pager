<?php

require __DIR__ . '/wp-module-pager-admin-kses-list.php';

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://github.com/yama-dev
 * @since      1.3.0
 *
 * @package    Wp_Module_Pager
 * @subpackage Wp_Module_Pager/admin/partials
 */

// ユーザーが必要な権限を持つか確認する必要がある
if (!current_user_can('manage_options')) {
  wp_die( __('You do not have sufficient permissions to access this page.') );
}

// Notice
$_html_notice = '';

// 認証用
$credential_action = $this->plugin_name.'-nonce-action';
$credential_name   = $this->plugin_name.'-nonce-key';

// フィールド名
$user_form_name_all      = $this->plugin_name.'_option_name_all';
$user_form_name_prev     = $this->plugin_name.'_option_name_prev';
$user_form_name_next     = $this->plugin_name.'_option_name_next';
$user_form_name_li       = $this->plugin_name.'_option_name_li';
$user_form_name_flg_omit = $this->plugin_name.'_option_name_flg_omit';
$user_form_name_range    = $this->plugin_name.'_option_name_range';

// オプション名
$db_slug_all      = $this->plugin_name.'_option_db_all';
$db_slug_prev     = $this->plugin_name.'_option_db_prev';
$db_slug_next     = $this->plugin_name.'_option_db_next';
$db_slug_li       = $this->plugin_name.'_option_db_li';
$db_slug_flg_omit = $this->plugin_name.'_option_db_flg_omit';
$db_slug_range    = $this->plugin_name.'_option_db_range';

// データベースから既存のオプション値を取得
$db_value_all      = get_option( $db_slug_all );
$db_value_prev     = get_option( $db_slug_prev );
$db_value_next     = get_option( $db_slug_next );
$db_value_li       = get_option( $db_slug_li );
$db_value_flg_omit = get_option( $db_slug_flg_omit );
$db_value_range    = get_option( $db_slug_range );

// Update DB
if($_SERVER['REQUEST_METHOD'] === 'POST') {
  // https://developer.wordpress.org/reference/functions/wp_nonce_field/
  if ( 
    !isset( $_POST[$credential_name] ) 
    || !wp_verify_nonce( $_POST[$credential_name], $credential_action ) 
  ) {
    echo 'failed to validate nonce...';
    exit;
  } else {
    // POST されたデータを取得
    $db_value_all      = htmlspecialchars( wp_kses( $_POST[ $user_form_name_all ],  $wp_kses_allowed_html ), ENT_QUOTES, 'UTF-8');
    $db_value_prev     = htmlspecialchars( wp_kses( $_POST[ $user_form_name_prev ],  $wp_kses_allowed_html ), ENT_QUOTES, 'UTF-8');
    $db_value_next     = htmlspecialchars( wp_kses( $_POST[ $user_form_name_next ],  $wp_kses_allowed_html ), ENT_QUOTES, 'UTF-8');
    $db_value_li       = htmlspecialchars( wp_kses( $_POST[ $user_form_name_li ], $wp_kses_allowed_html ), ENT_QUOTES, 'UTF-8');
    $db_value_flg_omit = htmlspecialchars( sanitize_text_field($_POST[ $user_form_name_flg_omit ]), ENT_QUOTES, 'UTF-8');
    $db_value_range    = htmlspecialchars( sanitize_text_field($_POST[ $user_form_name_range ]), ENT_QUOTES, 'UTF-8');

    // POST された値をデータベースに保存
    update_option( $db_slug_all, $db_value_all );
    update_option( $db_slug_prev, $db_value_prev );
    update_option( $db_slug_next, $db_value_next );
    update_option( $db_slug_li, $db_value_li );
    update_option( $db_slug_flg_omit, $db_value_flg_omit );
    update_option( $db_slug_range, $db_value_range );

    $_html_notice .= '<v-row><v-col cols="12">';
    $_html_notice .= '  <div class="updated"><p><strong>変更が保存されました。</strong></p></div>';
    $_html_notice .= '</v-col></v-row>';
  }
}

// Layoutが空の場合
if(empty($db_value_all)){
  $db_value_all = '';
  $db_value_all .= '<div class="c-pager">'."\n";
  $db_value_all .= '  <div class="c-pager__count">'."\n";
  $db_value_all .= '    {{current_page}} / {{max_page}}'."\n";
  $db_value_all .= '  </div>'."\n";
  $db_value_all .= '  {{ layout_prev }}'."\n";
  $db_value_all .= '  <ul class="c-pager__list">{{ layout_pager }}</ul>'."\n";
  $db_value_all .= '  {{ layout_next }}'."\n";
  $db_value_all .= '</div>'."\n";
  update_option( $db_slug_all, $db_value_all );
}

if(empty($db_value_prev)){
  $db_value_prev = '';
  $db_value_prev .= '<div class="c-pagination__btn c-pagination__btn--prev {{# is_disable }}is-disable{{/ is_disable }}">'."\n";
  $db_value_prev .= '  <a href="/{{ post_type }}/page/{{ prev_page }}/{{ url_parameter }}" class="c-pagination__btn-link"></a>'."\n";
  $db_value_prev .= '</div>'."\n";
  update_option( $db_slug_prev, $db_value_prev );
}

if(empty($db_value_next)){
  $db_value_next = '';
  $db_value_next .= '<div class="c-pagination__btn c-pagination__btn--next {{# is_disable }}is-disable{{/ is_disable }}">'."\n";
  $db_value_next .= '  <a href="/{{ post_type }}/page/{{ next_page }}/{{ url_parameter }}" class="c-pagination__btn-link"></a>'."\n";
  $db_value_next .= '</div>'."\n";
  update_option( $db_slug_next, $db_value_next );
}

if(empty($db_value_li)){
  $db_value_li = '';
  $db_value_li .= '<li class="c-pagination__item {{# is_current }}is-current{{/ is_current }}">'."\n";
  $db_value_li .= '  <a class="c-pagination__item-link" href="/{{ post_type }}/page/{{ _i }}/{{ url_parameter }}">'."\n";
  $db_value_li .= '    {{ _i }}'."\n";
  $db_value_li .= '  </a>'."\n";
  $db_value_li .= '</li>'."\n";
  $db_value_li .= ''."\n";
  $db_value_li .= '{{^ is_omit }}'."\n";
  $db_value_li .= '  {{# is_current }}'."\n";
  $db_value_li .= '    <li class="is-current">'."\n";
  $db_value_li .= '      {{ _i }}'."\n";
  $db_value_li .= '    </li>'."\n";
  $db_value_li .= '  {{/ is_current }}'."\n";
  $db_value_li .= '  {{^ is_current }}'."\n";
  $db_value_li .= '    <li>'."\n";
  $db_value_li .= '      {{ _i }}<a href="/{{ post_type }}/page/{{ _i }}/{{ url_parameter }}"></a>'."\n";
  $db_value_li .= '    </li>'."\n";
  $db_value_li .= '  {{/ is_current }}'."\n";
  $db_value_li .= '{{/ is_omit }}'."\n";
  $db_value_li .= ''."\n";
  $db_value_li .= '{{# is_omit }}'."\n";
  $db_value_li .= '  <li class="is-omit">...</li>'."\n";
  $db_value_li .= '{{/ is_omit }}'."\n";
  update_option( $db_slug_li, $db_value_li );
}

if(empty($db_value_range)){
  $db_value_range = 3;
  update_option( $db_slug_range, $db_value_range );
}

?>

<div id="app">
  <v-app>
    <v-main>
      <v-container fluid>
<?php
// Notice.
if(!empty($_html_notice)) echo wp_kses_post($_html_notice);
?>
        <v-form method="POST" action="" v-cloak>
          <?php wp_nonce_field( $credential_action, $credential_name ); ?>
          <v-container>
            <div class="text-h5 mb-10 font-weight-medium"><?php _e( 'ページネーションの設定', 'wp-module-pager' ); ?></div>
            <v-row>
              <v-col
                cols="3"
              >
                <v-banner
                  single-line
                  sticky
                  tile
                  class="mb-4"
                >
                  <?php _e('省略', 'wp-module-pager' ); ?>
                </v-banner>
              </v-col>
              <v-col
                cols="3"
              >
                <v-checkbox
                  v-model="flg_omit"
                  value="flg_omit"
                  name="<?php echo esc_html($user_form_name_flg_omit); ?>"
                  label="<?php _e('省略を利用する', 'wp-module-pager' ); ?>"
                ></v-checkbox>
              </v-col>
              <v-col
                cols="3"
              >
                <v-text-field
                  outlined
                  name="<?php echo esc_html($user_form_name_range); ?>"
                  label="<?php _e('省略時に表示するページ範囲', 'wp-module-pager' ); ?>"
                  v-model="range"
                ></v-text-field>
              </v-col>
            </v-row>

            <v-row>
              <v-col
                cols="3"
              >
                <v-banner
                  single-line
                  sticky
                  tile
                  class="mb-4"
                >
                  <?php _e('テンプレート（全体）', 'wp-module-pager' ); ?>
                </v-banner>
              </v-col>
              <v-col
                cols="9"
              >
                <v-textarea
                  outlined
                  rows="5"
                  name="<?php echo esc_html($user_form_name_all); ?>"
                  label="<?php _e('テンプレート（全体）', 'wp-module-pager' ); ?>"
                  v-model="name_all"
                ></v-textarea>
              </v-col>

              <v-col
                cols="3"
              >
                <v-banner
                  single-line
                  sticky
                  tile
                  class="mb-4"
                >
                  <?php _e('テンプレート（PREVボタン）', 'wp-module-pager' ); ?><br>
                </v-banner>
                <div v-pre>
                  <code>{{ layout_prev }}</code>
                </div>
              </v-col>
              <v-col
                cols="9"
              >
                <v-textarea
                  outlined
                  rows="5"
                  name="<?php echo esc_html($user_form_name_prev); ?>"
                  label="<?php _e('テンプレート（PREVボタン）', 'wp-module-pager' ); ?>"
                  v-model="name_prev"
                ></v-textarea>
              </v-col>

              <v-col
                cols="3"
              >
                <v-banner
                  single-line
                  sticky
                  tile
                  class="mb-4"
                >
                  <?php _e('テンプレート（NEXTボタン）', 'wp-module-pager' ); ?>
                </v-banner>
                <div v-pre class="mb-4">
                  <code>{{ layout_next }}</code>
                </div>
              </v-col>
              <v-col
                cols="9"
              >
                <v-textarea
                  outlined
                  rows="5"
                  name="<?php echo esc_html($user_form_name_next); ?>"
                  label="<?php _e('テンプレート（NEXTボタン）', 'wp-module-pager' ); ?>"
                  v-model="name_next"
                ></v-textarea>
              </v-col>

              <v-col
                cols="3"
              >
                <v-banner
                  single-line
                  sticky
                  tile
                  class="mb-4"
                >
                  <?php _e('テンプレート（リスト）', 'wp-module-pager' ); ?>
                </v-banner>
                <div v-pre>
                  <code>{{ layout_pager }}</code>
                </div>
              </v-col>
              <v-col
                cols="9"
              >
                <v-textarea
                  outlined
                  rows="7"
                  name="<?php echo esc_html($user_form_name_li); ?>"
                  label="<?php _e('テンプレート（リスト）', 'wp-module-pager' ); ?>"
                  v-model="name_li"
                ></v-textarea>
              </v-col>

              <v-col>
                <v-btn
                  class="mr-4"
                  color="primary"
                  type="submit"
                >
                  submit
                </v-btn>
                <v-btn v-on:click="clear">clear</v-btn>
              </v-col>
            </v-row>
          </v-container>
        </v-form>
      </v-container>
    </v-main>
  </v-app>
</div>
<script>
(function(){
  const unescapeHtml = function(target) {
    if (typeof target !== 'string') return target;

    const patterns = {
      '&lt;'   : '<',
      '&gt;'   : '>',
      '&amp;'  : '&',
      '&quot;' : '"',
      '&#x27;' : '\'',
      '&#x60;' : '`'
    };

    return target.replace(/&(lt|gt|amp|quot|#x27|#x60);/g, function(match) {
      return patterns[match];
    });
  };
  const eventHandler = ()=>{
    new Vue({
      el: '#app',
      vuetify: new Vuetify({
        icons: {
          iconfont: 'md',
        },
      }),
      data: {
        name_all: unescapeHtml(`<?php echo esc_html($db_value_all); ?>`),
        name_prev: unescapeHtml(`<?php echo esc_html($db_value_prev); ?>`),
        name_next: unescapeHtml(`<?php echo esc_html($db_value_next); ?>`),
        name_li: unescapeHtml(`<?php echo esc_html($db_value_li); ?>`),
        flg_omit: '<?php echo esc_html($db_value_flg_omit); ?>',
        range: <?php echo esc_html($db_value_range); ?>,
      },
      methods: {
        clear: function(){
          this.name_all = '';
          this.name_prev = '';
          this.name_next = '';
          this.name_li = '';
          this.range = 3;
        }
      }
    });
  }

  if(document.readyState !== 'loading'){
    eventHandler();
  } else {
    document.addEventListener('DOMContentLoaded', eventHandler);
  }
})();
</script>
