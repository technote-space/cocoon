<?php
require_once 'lib/defins.php'; //定数を定義


//本文部分の冒頭を綺麗に抜粋する
if ( !function_exists( 'get_content_excerpt' ) ):
function get_content_excerpt($content, $length = 70){
  $content =  preg_replace('/<!--more-->.+/is', '', $content); //moreタグ以降削除
  $content =  strip_shortcodes($content);//ショートコード削除
  $content =  strip_tags($content);//タグの除去
  $content =  str_replace('&nbsp;', '', $content);//特殊文字の削除（今回はスペースのみ）
  $content =  preg_replace('/\[.+?\]/i', '', $content); //ショートコードを取り除く
  $content =  preg_replace(URL_REG, '', $content); //URLを取り除く
  // $content =  preg_replace('/\s/iu',"",$content); //余分な空白を削除
  $over    =  intval(mb_strlen($content)) > intval($length);
  $content =  mb_substr($content, 0, $length);//文字列を指定した長さで切り取る

  return $content;
}
endif;

//WP_Queryの引数を取得
if ( !function_exists( 'get_related_wp_query_args' ) ):
function get_related_wp_query_args(){
  global $post;
  if ( true ) {
  //if ( is_related_entry_association_category() ) {
    //カテゴリ情報から関連記事をランダムに呼び出す
    $categories = get_the_category($post->ID);
    $category_IDs = array();
    foreach($categories as $category):
      array_push( $category_IDs, $category->cat_ID);
    endforeach ;
    if ( empty($category_IDs) ) return;
    return $args = array(
      'post__not_in' => array($post->ID),
      'posts_per_page'=> intval(10),
      //'posts_per_page'=> intval(get_related_entry_count()),
      'category__in' => $category_IDs,
      'orderby' => 'rand',
    );
  } else {
    //タグ情報から関連記事をランダムに呼び出す
    $tags = wp_get_post_tags($post->ID);
    $tag_IDs = array();
    foreach($tags as $tag):
      array_push( $tag_IDs, $tag->term_id);
    endforeach ;
    if ( empty($tag_IDs) ) return;
    return $args = array(
      'post__not_in' => array($post -> ID),
      'posts_per_page'=> intval(10),
      //'posts_per_page'=> intval(get_related_entry_count()),
      'tag__in' => $tag_IDs,
      'orderby' => 'rand',
    );
  }
}
endif;

//images/no-image.pngを使用するimgタグに出力するサイズ関係の属性
if ( !function_exists( 'get_noimage_sizes_attr' ) ):
function get_noimage_sizes_attr($image = null){
  if (!$image) {
    $image = get_template_directory_uri().'/images/no-image-160.png';
  }
  $sizes = ' srcset="'.$image.' 160w" width="160" height="90" sizes="(max-width: 160px) 160vw, 90px"';
  return $sizes;
}
endif;

//投稿ナビのサムネイルタグを取得する
if ( !function_exists( 'get_post_navi_thumbnail_tag' ) ):
function get_post_navi_thumbnail_tag($id){
  $thumb = get_the_post_thumbnail( $id, array(120, 67), array('alt' => '') );
  if ( !$thumb ) {
    $thumb = '<img src="'.get_template_directory_uri().'/images/no-image.png" alt="NO IMAGE" class="no-image post-navi-no-image" />';
  }
  return $thumb;
}
endif;

///////////////////////////////////////
// グローバルナビに説明文を加えるウォーカークラス
///////////////////////////////////////
class menu_description_walker extends Walker_Nav_Menu {
  function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
    global $wp_query;
    $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

    $class_names = $value = '';

    $classes = empty( $item->classes ) ? array() : (array) $item->classes;
    if ($item->description) {
      $classes[] = 'menu-item-has-description';
    }
    //var_dump($classes);

    $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) );
    $class_names = ' class="'. esc_attr( $class_names ) . '"';
    $output .= $indent . '<li id="menu-item-'. $item->ID . '"' . $value . $class_names .'>';

    $attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
    $attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
    $attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
    $attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';

    $prepend = '<div class="item-label">';
    $append = '</div>';
    $description  = ! empty( $item->description ) ? '<div class="item-description">'.esc_attr( $item->description ).'</div>' : '';

    if($depth != 0) {
      $description = $append = $prepend = "";
    }

    $item_output = $args->before;
    $item_output .= '<a'. $attributes .'>';
    $item_output .= $args->link_before .$prepend.apply_filters( 'the_title', $item->title, $item->ID ).$append;
    $item_output .= $description.$args->link_after;
    $item_output .= '</a>';
    $item_output .= $args->after;

    $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
  }
}

//アップデートチェックの初期化
require 'theme-update-checker.php'; //ライブラリのパス
$example_update_checker = new ThemeUpdateChecker(
  strtolower(THEME_NAME), //テーマフォルダ名
  'http://example.com/example-theme/update-info.json' //JSONファイルのURL
);

//アーカイブタイトルの取得
if ( !function_exists( 'get_archive_chapter_title' ) ):
function get_archive_chapter_title(){
  $chapter_title = null;
  if( is_category() ) {//カテゴリページの場合
    $chapter_title .= single_cat_title( '<span class="fa fa-folder-open"></span>', false );
  } elseif( is_tag() ) {//タグページの場合
    $chapter_title .= single_tag_title( '<span class="fa fa-tags"></span>
', false );
  } elseif( is_tax() ) {//タクソノミページの場合
    $chapter_title .= single_term_title( '', false );
  } elseif (is_day()) {
    //年月日のフォーマットを取得
    $chapter_title .= '<span class="fa fa-calendar"></span>
'.get_the_time('Y-m-n');
  } elseif (is_month()) {
    //年と月のフォーマットを取得
    $chapter_title .= '<span class="fa fa-calendar"></span>
'.get_the_time('Y-m');
  } elseif (is_year()) {
    //年のフォーマットを取得
    $chapter_title .= '<span class="fa fa-calendar"></span>
'.get_the_time('Y');
  } elseif (is_author()) {//著書ページの場合
    $chapter_title .= esc_html(get_queried_object()->display_name);
  } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) {
    $chapter_title .= 'Archives';
  } else {
    $chapter_title .= 'Archives';
  }
  return $chapter_title;
}
endif;

//アーカイブ見出しの取得
if ( !function_exists( 'get_archive_chapter_text' ) ):
function get_archive_chapter_text(){
  $chapter_text = null;
  //アーカイブタイトル前
  //$chapter_text .= '<span class="archive-title-pb">'.__( '"', THEME_NAME ).'</span><span class="archive-title-text">';
  //アーカイブタイトルの取得
  $chapter_text .= get_archive_chapter_title();
  //アーカイブタイトル後
  //$chapter_text .= '</span><span class="archive-title-pa">'.__( '"', THEME_NAME );//.'</span><span class="archive-title-list-text">'.get_theme_text_list().'</span>';
  //返り値として返す
  return $chapter_text;
}
endif;