<?php
require_once dirname(__FILE__).'/inc/install.php';
require_once dirname(__FILE__).'/inc/enqueue.php';
require_once dirname(__FILE__).'/inc/widgets.php';
if (!function_exists('onetheme_the_posts_navigation')) {
    function onetheme_the_posts_navigation(){
        the_posts_pagination();
    }
}
class Onetheme_theme
{

    function __construct()
    {
        add_action(    'init'              , array($this,'sclear_wp'), 9999 );
        remove_action( 'wp_head'           , 'feed_links_extra', 3 );
        remove_action( 'wp_head'           , 'feed_links', 2 );
        remove_action( 'wp_head'           , 'rsd_link' );
        remove_action( 'wp_head'           , 'wlwmanifest_link' );
        remove_action( 'wp_head'           , 'index_rel_link' );
        remove_action( 'wp_head'           , 'parent_post_rel_link', 10, 0 );
        remove_action( 'wp_head'           , 'start_post_rel_link', 10, 0 );
        remove_action( 'wp_head'           , 'adjacent_posts_rel_link', 10, 0 );
        remove_action( 'wp_head'           , 'wp_generator' );
    }
    function sclear_wp() {
        global $wp;
        if(isset($wp->public_query_vars)){
            $embed                 = array('embed');
            $wp->public_query_vars = array_diff( $wp->public_query_vars,$embed);
        }
        // Remove emoji
        remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
        remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
        remove_action( 'wp_print_styles', 'print_emoji_styles' );
        remove_action( 'admin_print_styles', 'print_emoji_styles' );
        remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
        remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
        remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
        add_filter( 'tiny_mce_plugins', array($this,'disable_emojis_tinymce') );
        // Remove comment script
        wp_deregister_script( 'comment-reply' );
        // Remove rest_api
        remove_action( 'rest_api_init'            , 'wp_oembed_register_route' );
        remove_action( 'init'                     , 'rest_api_init' );
        remove_action( 'rest_api_init'            , 'rest_api_default_filters', 10 );
        remove_action( 'parse_request'            , 'rest_api_loaded' );
        add_filter( 'rest_enabled'                , 'wppb_disable_rest' );
        remove_action( 'wp_head'                  , 'rest_output_link_wp_head', 10 );
        remove_action( 'wp_head'                  , 'wp_oembed_add_discovery_links' );
        remove_filter( 'rest_pre_serve_request'   , '_oembed_rest_pre_serve_request', 10 );
        remove_action( 'wp_head'                  , 'wp_oembed_add_host_js' );
        add_filter( 'rest_endpoints'              , array( $this,'disable_embeds_remove_embed_endpoint' ) );
        add_filter( 'oembed_response_data'        , array( $this,'disable_embeds_filter_oembed_response_data' ) );
        add_filter( 'embed_oembed_discover'       , '__return_false' );
        remove_filter( 'oembed_dataparse'         , 'wp_filter_oembed_result', 10 );
        add_filter( 'tiny_mce_plugins'            , array($this,'disable_embeds_tiny_mce_plugin') );
        add_filter( 'rewrite_rules_array'         , array($this,'disable_embeds_rewrites') );
        remove_filter( 'pre_oembed_result'        , 'wp_filter_pre_oembed_result', 10 );
        add_action( 'wp_default_scripts'          , array($this,'disable_embeds_remove_script_dependencies') );
        // Remove feeds
        add_action('do_feed'                      , array($this,'wp_remove_rss_feed'), 1);
        add_action('do_feed_rdf'                  , array($this,'wp_remove_rss_feed'), 1);
        add_action('do_feed_rss'                  , array($this,'wp_remove_rss_feed'), 1);
        add_action('do_feed_rss2'                 , array($this,'wp_remove_rss_feed'), 1);
        add_action('do_feed_atom'                 , array($this,'wp_remove_rss_feed'), 1);
        add_action('do_feed_rss2_comments'        , array($this,'wp_remove_rss_feed'), 1);
        add_action('do_feed_atom_comments'        , array($this,'wp_remove_rss_feed'), 1);
    }
    function disable_emojis_tinymce($plugins){
        if ( is_array( $plugins ) ) {
            return array_diff( $plugins, array( 'wpemoji' ) );
        } else {
            return array();
        }
    }
    function wp_remove_rss_feed(){
        die( __('No feed available, Please visit our '. get_bloginfo('url')) );
    }

    function disable_embeds_tiny_mce_plugin( $plugins ) {
        return array_diff( $plugins, array( 'wpembed' ) );
    }
    function disable_embeds_remove_embed_endpoint( $endpoints ) {
        unset( $endpoints['/oembed/1.0/embed'] );
        return $endpoints;
    }
    function disable_embeds_filter_oembed_response_data( $data ) {
        if ( defined( 'REST_REQUEST' ) && REST_REQUEST ) {
            return false;
        }
        return $data;
    }
    function disable_embeds_rewrites( $rules ) {
        foreach ( $rules as $rule => $rewrite ) {
            if ( false !== strpos( $rewrite, 'embed=true' ) ) {
                unset( $rules[ $rule ] );
            }
        }
        return $rules;
    }
    function disable_embeds_remove_script_dependencies( $scripts ) {
        if ( ! empty( $scripts->registered['wp-edit-post'] ) ) {
            $scripts->registered['wp-edit-post']->deps = array_diff(
                $scripts->registered['wp-edit-post']->deps,
                array( 'wp-embed' )
            );
        }
    }
}
new Onetheme_theme();