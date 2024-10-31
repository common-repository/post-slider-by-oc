<?php
/**
 * Plugin Name: Post Slider By OC
 * Description: This plugin allows you to display your posts with a slider ( All slider customize options ).
 * Version: 1.0
 * Author: Ocean Infotech
 * Author URI: https://www.xeeshop.com
 * Copyright: 2019 
 */


if (!defined('ABSPATH')) {
    die('-1');
}
if (!defined('OCPC_PLUGIN_NAME')) {
    define('OCPC_PLUGIN_NAME', 'Post Carousel');
}
if (!defined('OCPC_PLUGIN_VERSION')) {
    define('OCPC_PLUGIN_VERSION', '1.0.0');
}
if (!defined('OCPC_PLUGIN_FILE')) {
    define('OCPC_PLUGIN_FILE', __FILE__);
}
if (!defined('OCPC_PLUGIN_DIR')) {
    define('OCPC_PLUGIN_DIR',plugins_url('', __FILE__));
}
if (!defined('OCPC_BASE_NAME')) {
    define('OCPC_BASE_NAME', plugin_basename(OCPC_PLUGIN_FILE));
}
if (!defined('OCPC_DOMAIN')) {
    define('OCPC_DOMAIN', 'ocpc');
}

//Main class
//Load required js,css and other files

if (!class_exists('OCPC')) {

    class OCPC {

        protected static $instance;

        //Load all includes files
        function includes() {

            //Admn site Layout
            include_once('includes/ocpc-backend.php');

            //Update all Option Data
            include_once('includes/ocpc-backend-updatemeta.php');

            //create shortcode for display post slider
            include_once('includes/ocpc-shortcode.php');
        }


        function init() {
            add_action('admin_enqueue_scripts', array($this, 'OCPC_load_admin_script_style'));
            add_action( 'wp_enqueue_scripts',  array($this, 'OCPC_load_script_style'));
            add_image_size( 'post_slider_img', 350, 270, false ); // (cropped)
            add_filter( 'plugin_row_meta', array( $this, 'plugin_row_meta' ), 10, 2 );
        }


        function plugin_row_meta( $links, $file ) {
            if ( OCPC_BASE_NAME === $file ) {
                $row_meta = array(
                    'rating'    =>  '<a href="https://wordpress.org/support/plugin/post-slider-by-oc/reviews/#new-post" target="_blank"><img src="'.OCPC_PLUGIN_DIR.'/asset/images/star.png" class="ocpc_rating_div"></a>',
                );

                return array_merge( $links, $row_meta );
            }

            return (array) $links;
        }


        //Add JS and CSS on Frontend
        function OCPC_load_script_style() {
            
            wp_enqueue_style( 'owlcarousel-min', OCPC_PLUGIN_DIR . '/asset/owlcarousel/assets/owl.carousel.min.css', false, '1.0.0' );
            wp_enqueue_style( 'owlcarousel-theme', OCPC_PLUGIN_DIR . '/asset/owlcarousel/assets/owl.theme.default.min.css', false, '1.0.0' );
            wp_enqueue_script( 'owlcarousel', OCPC_PLUGIN_DIR . '/asset/owlcarousel/owl.carousel.js', false, '1.0.0' );
            wp_enqueue_script( 'masonrypost', OCPC_PLUGIN_DIR . '/asset/js/masonry.pkgd.min.js', false, '1.0.0' );
            wp_enqueue_script( 'ocpcfront_js', OCPC_PLUGIN_DIR . '/asset/js/ocpc-front-js.js', false, '1.0.0' );
            wp_enqueue_style( 'ocpcfront_css', OCPC_PLUGIN_DIR . '/asset/css/ocpc-front-style.css', false, '1.0.0' );
            wp_enqueue_script('masonrypostimage',OCPC_PLUGIN_DIR . '/asset/js/imagesloaded.pkgd.min.js', false,'1.0.0');
            
        }


        //Add JS and CSS on Backend
        function OCPC_load_admin_script_style() {
            wp_enqueue_style( 'ocpcadmin_css', OCPC_PLUGIN_DIR . '/asset/css/ocpc-admin-style.css', false, '1.0.0' );
            wp_enqueue_script( 'ocpcadmin_js', OCPC_PLUGIN_DIR . '/asset/js/ocpc-admin-js.js', false, '1.0.0' );
            wp_enqueue_script( 'media_uploader', OCPC_PLUGIN_DIR . '/asset/js/media-uploader.js', false, '1.0.0' );
        }


        //Plugin Rating
        public static function do_activation() {
            set_transient('ocpc-first-rating', true, MONTH_IN_SECONDS);
        }


        public static function instance() {
            if (!isset(self::$instance)) {
                self::$instance = new self();
                self::$instance->init();
                self::$instance->includes();
            }
            return self::$instance;
        }
    }
    add_action('plugins_loaded', array('OCPC', 'instance'));
    register_activation_hook(OCPC_PLUGIN_FILE, array('OCPC', 'do_activation'));
}
