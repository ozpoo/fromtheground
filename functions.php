<?php
/*
 *  Author: Eric Andren | @ericandren
 */

if (!isset($content_width)) {
  $content_width = 900;
}

if (function_exists('add_theme_support')) {

  add_theme_support('menus');
  add_theme_support('automatic-feed-links');

  add_theme_support('post-thumbnails');
  add_image_size('xx-large', 3200, '', true);
  add_image_size('x-large', 2400, '', true);
  add_image_size('large', 1200, '', true);
  add_image_size('large-square', 1200, 1200, true);
  add_image_size('medium', 600, '', true);
  add_image_size('medium-square', 600, 600, true);
  add_image_size('small', 300, '', true);
  add_image_size('small-square', 300, 300, true);
  add_image_size('x-small', 150, '', true);
  add_image_size('x-small-square', 150, 150, true);
}

function header_scripts () {
  if ($GLOBALS['pagenow'] != 'wp-login.php' && !is_admin()) {

  	wp_register_script('conditionizr',
      get_template_directory_uri() . '/assets/js/lib/conditionizr-4.3.0.min.js',
      array(), '4.3.0');
    wp_enqueue_script('conditionizr');

    wp_register_script('modernizr',
      get_template_directory_uri() . '/assets/js/lib/modernizr-2.7.1.min.js',
      array(), '2.7.1');
    wp_enqueue_script('modernizr');

    wp_register_script('jscookie',
      get_template_directory_uri() . '/node_modules/js-cookie/src/js.cookie.js',
      array(), '1.0.0');
    wp_enqueue_script('jscookie');

    wp_register_script('color',
      get_template_directory_uri() . '/node_modules/tinycolor2/tinycolor.js',
      array(), '2.7.1');
    wp_enqueue_script('color');

    wp_register_script('stickybits',
      get_template_directory_uri() . '/node_modules/stickybits/dist/stickybits.min.js',
      array(), '2.7.1');
    wp_enqueue_script('stickybits');

    wp_register_script('script',
      get_template_directory_uri() . '/assets/js/build/script.js?v='.time(),
      array('jquery'), '1.0.0');
    wp_enqueue_script('script');
  }
}
add_action('init', 'header_scripts');

function conditional_scripts () {

  if (is_page('pagenamehere')) {

    wp_register_script('scriptname',
      get_template_directory_uri() . '/assets/js/scriptname.js',
      array('jquery'), '1.0.0');
    wp_enqueue_script('scriptname');
  }
}
add_action('wp_print_scripts', 'conditional_scripts');

function styles () {

    // wp_register_style('Sailec',
    // get_template_directory_uri() . '/assets/fonts/Sailec/stylesheet.css',
    // array(), '1.0', 'all');
    // wp_enqueue_style('Sailec');

    // wp_register_style('Apercu',
    // get_template_directory_uri() . '/assets/fonts/Apercu/stylesheet.css',
    // array(), '1.0', 'all');
    // wp_enqueue_style('Apercu');

    wp_register_style('Neutrif',
    get_template_directory_uri() . '/assets/fonts/Neutrif Studio/stylesheet.css',
    array(), '1.0', 'all');
    wp_enqueue_style('Neutrif');

    wp_register_style('Maison',
    get_template_directory_uri() . '/assets/fonts/Maison Neue/stylesheet.css',
    array(), '1.0', 'all');
    wp_enqueue_style('Maison');

    wp_register_style('style',
    get_template_directory_uri() . '/assets/css/build/style.css?v='.time(),
    array(), '1.0', 'all');
    wp_enqueue_style('style');
}
add_action('wp_enqueue_scripts', 'styles');

function pagination () {
  global $wp_query;
  $big = 999999999;
  echo paginate_links(array(
    'base' => str_replace($big, '%#%', get_pagenum_link($big)),
    'format' => '?paged=%#%',
    'current' => max(1, get_query_var('paged')),
    'total' => $wp_query->max_num_pages
  ));
}
add_action('init', 'pagination');

function wp_rest_api_alter () {
  register_api_field ( 'selfies', 'fields',
    array (
      'get_callback'    => function($data, $field, $request, $type){
        if (function_exists('get_fields')) {
          return get_fields($data['id']);
        }
        return [];
      },
      'update_callback' => null,
      'schema'          => null,
    )
  );
}
add_action ( 'rest_api_init', 'wp_rest_api_alter');

function my_prefix_add_rest_orderby_params( $params ) {
    $params['orderby']['enum'][] = 'menu_order';
    return $params;
}
add_filter( 'rest_page_collection_params', 'my_prefix_add_rest_orderby_params', 10, 1 );
add_filter( 'rest_post_collection_params', 'my_prefix_add_rest_orderby_params', 10, 1 );

function my_wp_nav_menu_args ($args = '') {
  $args['container'] = false;
  return $args;
}
add_filter ('wp_nav_menu_args', 'my_wp_nav_menu_args');

function my_css_attributes_filter ($var) {
  return is_array($var) ? array() : '';
}

function remove_category_rel_from_category_list ($thelist) {
  return str_replace('rel="category tag"', 'rel="tag"', $thelist);
}
add_filter ('the_category', 'remove_category_rel_from_category_list');

function add_slug_to_body_class ($classes) {
  global $post;
  if (is_home()) {
    $key = array_search('blog', $classes);
      if ($key > -1) {
        unset($classes[$key]);
      }
  } elseif (is_page()) {
    $classes[] = sanitize_html_class($post->post_name);
  } elseif (is_singular()) {
    $classes[] = sanitize_html_class($post->post_name);
  }

  return $classes;
}
add_filter ('body_class', 'add_slug_to_body_class');

function index ($length) {
  return 20;
}

function custom_post ($length) {
  return 40;
}

function excerpt ($length_callback = '', $more_callback = '') {
  global $post;
  if (function_exists($length_callback)) {
    filter('excerpt_length', $length_callback);
  }
  if (function_exists($more_callback)) {
    add_filter('excerpt_more', $more_callback);
  }
  $output = get_the_excerpt();
  $output = apply_filters('wptexturize', $output);
  $output = apply_filters('convert_chars', $output);
  $output = '<p>' . $output . '</p>';
  echo $output;
}

function view_article ($more) {
  global $post;
  return '... <a class="view-article" href="' . get_permalink($post->ID) . '">' . __('View Article', 'html5blank') . '</a>';
}
add_filter ('excerpt_more', 'view_article');

function remove_admin_bar () {
  return false;
}
add_filter ('show_admin_bar', 'remove_admin_bar');

function style_remove ($tag) {
  return preg_replace('~\s+type=["\'][^"\']++["\']~', '', $tag);
}
add_filter ('style_loader_tag', 'style_remove');

function remove_thumbnail_dimensions ( $html ) {
  $html = preg_replace('/(width|height)=\"\d*\"\s/', "", $html);
  return $html;
}
add_filter ('post_thumbnail_html', 'remove_thumbnail_dimensions', 10);
add_filter ('image_send_to_editor', 'remove_thumbnail_dimensions', 10);

function fb_unautop_4_img ( $content ) {
  $content = preg_replace (
    '/<p>\\s*?(<a rel=\"attachment.*?><img.*?><\\/a>|<img.*?>)?\\s*<\\/p>/s',
    '<figure>$1</figure>',
    $content
  );
  return $content;
}
add_filter ( 'the_content', 'fb_unautop_4_img', 99 );

function alx_embed_html ( $html ) {
  return '<figure>' . $html . '</figure>';
}
add_filter ( 'embed_oembed_html', 'alx_embed_html', 10, 3 );
add_filter ( 'video_embed_html', 'alx_embed_html' );

remove_action('wp_head', 'feed_links_extra', 3);
remove_action('wp_head', 'feed_links', 2);
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'index_rel_link');
remove_action('wp_head', 'parent_post_rel_link', 10, 0);
remove_action('wp_head', 'start_post_rel_link', 10, 0);
remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0);
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
remove_action('wp_head', 'rel_canonical');
remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);

remove_filter ('the_excerpt', 'wpautop');

function wpsites_query( $query ) {
  if ( (is_post_type_archive("movements") || is_post_type_archive("workouts") ) && $query->is_main_query() && !is_admin() ) {
    $query->set( 'posts_per_page', -1 );
  }
}
add_action( 'pre_get_posts', 'wpsites_query' );

function my_acf_admin_head() {
	?>
	<style type="text/css">
    .acf-fields > .acf-field.half{
      width: 50%;
      box-sizing: border-box;
      float: left;
      clear: none;
    }

    .acf-fields > .acf-field.third{
      width: 33.33%;
      box-sizing: border-box;
      float: left;
      clear: none;
    }

    .acf-fields > .acf-field.no-top-border{
      border-top: none;
    }
	</style>
	<?php
}

add_action('acf/input/admin_head', 'my_acf_admin_head');

function my_orderby_filter($orderby, &$query){
    global $wpdb;
    if (get_query_var("post_type") == "movements" || get_query_var("post_type") == "workouts") {
         return "$wpdb->posts.post_title ASC";
    }
    return $orderby;
 }

add_filter("posts_orderby", "my_orderby_filter", 10, 2);

?>
