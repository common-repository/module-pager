=== Module Pager ===
Contributors: yamadev
Tags: pager, pagination, paginate, navigation, nav, navi, page, index, link, all-in-one, custom, html, css, mustache, custom, plugin
Requires at least: 5.8
Tested up to: 6.0.1
Stable tag: 1.6.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Requires PHP: 7.2

Management tools for any custom pagination.

== Description ==

Management tools for any custom pagination.

= Features =

* You can manage any pagination from the management screen
* A sample description is prepared in advance (you can use it as it is after installation)
* You can write the next button and the previous button individually.
* Since there are restrictions on the HTML that can be registered, it is also taken into consideration in case of emergency.
* Can be easily extended with Mustache notation.

* 管理画面から、あらゆるページネーションを管理できます
* 予めサンプルの記述が用意されています（インストール後にそのまま利用できます）
* 次へボタン、前へボタン、を個別に記載できます
* 登録できるHTMLに制限があるので、万が一の場合も考慮されています
* ムスタッシュ記法で簡単に拡張することができます

= How to use =

~~~
<?php
$pager = new Wp_Module_Pager();
$pager->render();
?>
~~~

~~~
[wp-module-pager base_path=/]
[wp-module-pager base_path=/news/category/'.$term_obj->slug.'/]
~~~

~~~
<?php
// Archive Page.
echo do_shortcode('[wp-module-pager base_path=/]');
?>


<?php
// Tax Page.
echo do_shortcode('[wp-module-pager base_path=/news/category/'.$term_obj->slug.'/]');
?>
~~~

== Installation ==

1. From the WP admin panel, click "Plugins" -> "Add new".
2. In the browser input box, type "WP Module Pager".
3. Select the "WP Module Pager" plugin and click "Install".
4. Activate the plugin.

OR…

1. Download the plugin from this page.
2. Save the .zip file to a location on your computer.
3. Open the WP admin panel, and click "Plugins" -> "Add new".
4. Click "upload".. then browse to the .zip file downloaded from this page.
5. Click "Install".. and then "Activate plugin".

== Frequently Asked Questions ==



== Screenshots ==



== Changelog ==



== Upgrade Notice ==



