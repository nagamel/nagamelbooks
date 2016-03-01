<?php
// NOTE Habakiri の 子テーマで CSS や JS を読みこませる
function habakiri_child_theme_setup() {
    class Habakiri extends Habakiri_Base_Functions {

        // wp_enqueue_scripts メソッドは Habakiri で既に定義されているため
        // __construct() でフックさせる必要はありません。
        public function wp_enqueue_scripts() {
            // Habakiri の wp_enqueue_scripts をまず実行する
            parent::wp_enqueue_scripts();

            // Habakiri が自動的にロードする子テーマの style.css を
            // 解除し、代わりに style.min.css を読み込む場合の例
            /*wp_deregister_style( get_stylesheet() );
            wp_enqueue_style(
                get_stylesheet(),
                get_stylesheet_directory_uri() . '/style.min.css',
                array( get_template() )
            );*/

            $assets_name = 'habakiri-assets';
            wp_deregister_style( $assets_name ); 
            wp_enqueue_style(
                'parent-stylesheet',
                home_url() .'/wp-content/themes/habakiri/style.min.css'
            ); 
            wp_enqueue_style(
                'stylesheet',
                get_stylesheet_directory_uri() .'/style.css'
            );
            wp_enqueue_style(
                'child-stylesheet',
                get_stylesheet_directory_uri() .'/css/nagamelbooks.css'
            );
            
            
            // jquery.scroll.js という js を読み込む場合の例
            /*wp_enqueue_script(
                'jquery.scroll',
                get_stylesheet_directory_uri() . '/js/jquery.scroll.js',
                array( 'jquery' ),
                null,
                true
            );*/
        }
        
        
        //記事本文（.entry-content)の前に出力するモジュール
        public function __construct() {
            parent::__construct();

            add_action(
                'habakiri_before_entry_content',
                array( $this, 'habakiri_before_entry_content' )
            );
        }
        
        public function habakiri_before_entry_content() {
            get_template_part( 'modules/habakiri_before_entry_content' );
        }
    }
}
add_action( 'after_setup_theme', 'habakiri_child_theme_setup' );


// NOTE SVGをアップロードできるようにする
function my_ext2type($ext2types) {
    array_push($ext2types, array('image' => array('svg', 'svgz')));
    return $ext2types;
}
add_filter('ext2type', 'my_ext2type');

function my_mime_types($mimes){
    $mimes['svg'] = 'image/svg+xml';
    $mimes['svgz'] = 'image/svg+xml';
    return $mimes;
}
add_filter('upload_mimes', 'my_mime_types');

function my_mime_to_ext($mime_to_ext) {
    $mime_to_ext['image/svg+xml'] = 'svg';
    return $mime_to_ext;
}
add_filter('getimagesize_mimes_to_exts', 'my_mime_to_ext');


/* NOTE My Functions*/

// NOTE カスタム投稿を取得してリスト化する
/*
* @param string $prefix 接頭辞
* @param array $arg_bookfield カスタムフィールドのキーの配列(接頭辞を除いたスラッグ=>タイトル)
* @param integer $count_bookfield
**/
function list_bookfield($prefix,$arg_bookfield){
    $count_bookfield = 0;
    
    $custom_fields = get_post_custom( $post->ID );
    $book_info = array();
    
    foreach($arg_bookfield as $key => $title_fields){
        $key_thisfield = $prefix .$key;

        $my_custom_field = $custom_fields[ $key_thisfield ];
        if($my_custom_field){
            foreach ( $my_custom_field as $key => $value ) {
                if($value){
                    $book_info[] = '<li class="book' .$key_thisfield .'">' .$title_fields .':' .$value .'</li>' ."\n";
                    $count_bookfield++;
                }
            }
        }
    }
    
    if($count_bookfield>0){
        return $book_info;
    }
}
