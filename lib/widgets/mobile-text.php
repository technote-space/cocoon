<?php
///////////////////////////////////////////////////
//モバイル用テキストウイジェットの追加
///////////////////////////////////////////////////
class MobileTextWidgetItem extends WP_Widget {
  function __construct() {
    parent::__construct(
      'mobile_text',
      WIDGET_NAME_PREFIX.__( 'モバイル用テキスト', THEME_NAME ),
      array('description' => __( 'モバイルのみで表示されるテキストウィジェットです。768px以下で表示されます。', THEME_NAME )),
      array( 'width' => 400, 'height' => 350 )
    );//ウイジェット名
  }
  function widget($args, $instance) {
    extract( $args );
    //タイトル名を取得
    $title = apply_filters( 'widget_title_mobile_text', $instance['title_mobile_text'] );
    $widget_text = isset( $instance['text_mobile_text'] ) ? $instance['text_mobile_text'] : '';
    $text = apply_filters( 'widget_text_mobile_text', $widget_text, $instance, $this );

    if ( !is_404() ): //404ページでないとき
      echo $args['before_widget'];
      if ($title) {//タイトルが設定されている場合は使用する
        echo $args['before_title'].$title.$args['after_title'];
      } ?>
      <div class="text-mobile">
        <?php echo $text; ?>
      </div>
    <?php echo $args['after_widget'];
    endif //!is_404 ?>
    <?php
  }
  function update($new_instance, $old_instance) {
    $instance = $old_instance;
    $instance['title_mobile_text'] = strip_tags($new_instance['title_mobile_text']);
    $instance['text_mobile_text'] = $new_instance['text_mobile_text'];
      return $instance;
  }
  function form($instance) {
    if(empty($instance)){//notice回避
      $instance = array(
        'title_mobile_text' => null,
        'text_mobile_text' => null,
      );
    }
    $title = esc_attr($instance['title_mobile_text']);
    $text = esc_attr($instance['text_mobile_text']);
    ?>
    <?php //タイトル入力フォーム ?>
    <p>
      <label for="<?php echo $this->get_field_id('title_mobile_text'); ?>">
      <?php _e( 'タイトル', THEME_NAME ) ?>
      </label>
      <input class="widefat" id="<?php echo $this->get_field_id('title_mobile_text'); ?>" name="<?php echo $this->get_field_name('title_mobile_text'); ?>" type="text" value="<?php echo $title; ?>" />
    </p>
    <?php //テキスト入力フォーム ?>
    <p>
      <label for="<?php echo $this->get_field_id('text_mobile_text'); ?>">
      <?php _e( 'テキスト', THEME_NAME ) ?>
      </label>
      <textarea class="widefat" id="<?php echo $this->get_field_id('text_mobile_text'); ?>" name="<?php echo $this->get_field_name('text_mobile_text'); ?>" cols="20" rows="16"><?php echo $text; ?></textarea>
    </p>
    <?php
  }
}
add_action('widgets_init', create_function('', 'return register_widget("MobileTextWidgetItem");'));