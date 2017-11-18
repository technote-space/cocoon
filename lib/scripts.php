<?php //CSSやJSファイルの呼び出し

if ( !function_exists( 'cocoon_scripts' ) ):
function cocoon_scripts() {
////////////////////////////////////////////////////////////////
//
//スタイルシートの呼び出し
//
////////////////////////////////////////////////////////////////

  ///////////////////////////////////////////
  //テーマスタイルの呼び出し
  ///////////////////////////////////////////
  wp_enqueue_style( THEME_NAME.'-style', get_template_directory_uri() . '/style.css' );

  ///////////////////////////////////////////
  //設定変更に必要なCSS
  ///////////////////////////////////////////
  wp_add_css_custome_to_inline_style();


  ///////////////////////////////////////////
  //Font Awesome
  ///////////////////////////////////////////
  wp_enqueue_style( 'font-awesome-style', FONT_AWESOME_CDN_URL );


  ///////////////////////////////////////////
  //設定変更に必要なCSS
  ///////////////////////////////////////////
  wp_add_css_custome_to_inline_style();


  ///////////////////////////////////////////
  //IcoMoon
  ///////////////////////////////////////////
  wp_enqueue_style( 'icomoon-style', get_template_directory_uri() . '/webfonts/icomoon/style.css' );

  ///////////////////////////////////////////
  //ソースコードのハイライト表示
  ///////////////////////////////////////////
  wp_enqueue_highlight_js();

  ///////////////////////////////////
  //画像リンク拡大効果がLightboxのとき
  ///////////////////////////////////
  wp_enqueue_lightbox();

  ///////////////////////////////////
  //画像リンク拡大効果がLityのとき
  ///////////////////////////////////
  wp_enqueue_lity();

  ///////////////////////////////////
  //画像リンク拡大効果がbaguetteboxのとき
  ///////////////////////////////////
  wp_enqueue_baguettebox();

  ///////////////////////////////////
  //サイドバー追従領域やグローバルナビの追従用
  ///////////////////////////////////
  //wp_enqueue_clingify();

  ///////////////////////////////////
  //サイドバー追従領域やグローバルナビの追従用
  ///////////////////////////////////
  wp_enqueue_stickyfill();

  ///////////////////////////////////
  //カルーセル用
  ///////////////////////////////////
  wp_enqueue_slick();

  ///////////////////////////////////
  //ツリー型モバイルボタン
  ///////////////////////////////////
  wp_enqueue_slicknav();


  ///////////////////////////////////////////
  //Google Fonts
  ///////////////////////////////////////////
  wp_enqueue_google_fonts();

////////////////////////////////////////////////////////////////
//
//Wordpress関係スクリプトの呼び出し
//
////////////////////////////////////////////////////////////////

  ///////////////////////////////////////////
  //jQueryの読み込み
  ///////////////////////////////////////////
  wp_enqueue_script('jquery');

  ///////////////////////////////////////////
  //コメント返信時のフォームの移動（WPライブラリから呼び出し）
  ///////////////////////////////////////////
  if ( is_singular() ) wp_enqueue_script( 'comment-reply' );

  ///////////////////////////////////////////
  //テーマ内で使用するJavaScript関数をまとめて定義する外部ファイルを呼び出す（javascript.js）
  ///////////////////////////////////////////
  wp_enqueue_script( THEME_JS, get_template_directory_uri() . '/javascript.js', array( 'jquery' ), false, true );

  ///////////////////////////////////
  //はてブシェアボタン用のスクリプト呼び出し
  ///////////////////////////////////
  if ( is_bottom_hatebu_share_button_visible() && is_singular() ){
    wp_enqueue_script( 'st-hatena-js', '//b.st-hatena.com/js/bookmark_button.js', array(), false, true );
  }

}
endif;
add_action( 'wp_enqueue_scripts', 'cocoon_scripts', 1 );