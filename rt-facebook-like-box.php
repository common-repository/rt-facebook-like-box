<?php 
/*
Plugin Name: RT Facebook Like Box
Plugin URI: https://wordpress.org/plugins/rt-facebook-like-box/
Description: This plugin for a facebook like box in your website.
Author: ShapedPlugin
Version: 2.1
Author URI: http://shapedplugin.com
Text Domain: shaped_plugin
*/


/* Adding Latest jQuery from Wordpress */
function rt_facebook_like_box_wp_latest_jquery() {
	wp_enqueue_script('jquery');
}
add_action('init', 'rt_facebook_like_box_wp_latest_jquery');


/* Adding Settings API */
require_once dirname( __FILE__ ) . '/class.settings-api.php';


/* WordPress settings API  */
if ( !class_exists('RT_Settings_API_file' ) ):
class RT_Settings_API_file {

    private $settings_api;

    function __construct() {
        $this->settings_api = new RT_Settings_API;

        add_action( 'admin_init', array($this, 'admin_init') );
        add_action( 'admin_menu', array($this, 'admin_menu') );
    }

    function admin_init() {

        //set the settings
        $this->settings_api->set_sections( $this->get_settings_sections() );
        $this->settings_api->set_fields( $this->get_settings_fields() );

        //initialize settings
        $this->settings_api->admin_init();
    }

    function admin_menu() {
        add_options_page( 'RT Facebook Like Box', 'RT Facebook Like Box', 'delete_posts', 'settings_api_rt_facebook_like_box', array($this, 'plugin_page') );
    }

    function get_settings_sections() {
        $sections = array(
            array(
                'id' => 'stting_basics',
                'title' => __( 'Settings', 'shaped_plugin' )
            ),
            array(
                'id' => 'stting_mobile',
                'title' => __( 'Mobile', 'shaped_plugin' )
            )
        );
        return $sections;
    }

    /**
     * Returns all the settings fields
     *
     * @return array settings fields
     */
    function get_settings_fields() {
        $settings_fields = array(
            'stting_basics' => array(
                
                array(
                    'name' => 'facebook_url',
                    'label' => __( 'Facebook Page User', 'shaped_plugin' ),
                    'desc' => __( 'Type your facebook page user ex: "shapedplugin"', 'shaped_plugin' ),
                    'default' => 'shapedplugin',
                    'type' => 'text'
                    
                ),
                array(
                    'name' => 'facebook_main_width',
                    'label' => __( 'Width', 'shaped_plugin' ),
                    'desc' => __( 'Type RT Facebook Like Box width ex: "220" ', 'shaped_plugin' ),
                    'default' => '220',
                    'type' => 'text'
                    
                ),
                array(
                    'name' => 'facebook_main_height',
                    'label' => __( 'Height', 'shaped_plugin' ),
                    'desc' => __( 'Type RT Facebook Like Box height ex: "210" ', 'shaped_plugin' ),
                    'default' => '210',
                    'type' => 'text'
                    
                ),
                array(
                    'name' => 'show_faces_area',
                    'label' => __( 'Show Faces', 'shaped_plugin' ),
                    'desc' => __( '', 'shaped_plugin' ),
                    'type' => 'select',
                    'default' => 'true',
                    'options' => array(
                        'true' => 'Yes',
                        'false' => 'No'
                    )
                ),
                array(
                    'name' => 'stream_area',
                    'label' => __( 'Stream', 'shaped_plugin' ),
                    'desc' => __( '', 'shaped_plugin' ),
                    'type' => 'select',
                    'default' => 'false',
                    'options' => array(
                        'true' => 'Yes',
                        'false' => 'No'
                    )
                ),
                array(
                    'name' => 'header_area',
                    'label' => __( 'Header', 'shaped_plugin' ),
                    'desc' => __( '', 'shaped_plugin' ),
                    'type' => 'select',
                    'default' => 'false',
                    'options' => array(
                        'true' => 'Yes',
                        'false' => 'No'
                    )
                ),
                array(
                    'name' => 'box_float',
                    'label' => __( 'Float', 'shaped_plugin' ),
                    'desc' => __( '', 'shaped_plugin' ),
                    'type' => 'select',
                    'default' => 'right',
                    'options' => array(
                        'left' => 'Left',
                        'right' => 'Right'
                    )
                )
            ),

        // Mobile
            'stting_mobile' => array(
                array(
                    'name' => 'facebook_mobile_width',
                    'label' => __( 'Width', 'shaped_plugin' ),
                    'desc' => __( 'Type RT Facebook Like Box width in mobile ex: "190" ', 'shaped_plugin' ),
                    'default' => '190',
                    'type' => 'text'
                ),

                array(
                    'name' => 'facebook_mobile_height',
                    'label' => __( 'Height', 'shaped_plugin' ),
                    'desc' => __( 'Type RT Facebook Like Box height in mobile ex: "75" ', 'shaped_plugin' ),
                    'default' => '75',
                    'type' => 'text'
                )
            ),
        );

        return $settings_fields;
    }

    function plugin_page() {
        echo '<div class="wrap">';

        $this->settings_api->show_navigation();
        $this->settings_api->show_forms();

        echo '</div>';
    }

    /**
     * Get all the pages
     *
     * @return array page names with key value pairs
     */
    function get_pages() {
        $pages = get_pages();
        $pages_options = array();
        if ( $pages ) {
            foreach ($pages as $page) {
                $pages_options[$page->ID] = $page->post_title;
            }
        }

        return $pages_options;
    }

}
endif;

$settings = new RT_Settings_API_file();

/* Get the value of a settings field */



function shaped_plugin_option( $option, $section, $default = '' ) {
 
    $options = get_option( $section );
 
    if ( isset( $options[$option] ) ) {
        return $options[$option];
    }
 
    return $default;
}


function rt_facebook_like_box_main() { ?>

<div style="position: fixed; <?php echo shaped_plugin_option( 'box_float', 'stting_basics', 'right' ); ?>: 0; margin-bottom: 0; border: 1px solid #eee; background:#fff; z-index: 999; bottom: 0px; display: block; text-align:left" id="facebook_likebox" class="rt-facebook-like-box">

<div id="<?php echo shaped_plugin_option( 'box_float', 'stting_basics', 'right' ); ?>">
<span id="closefacebook_likebox" style="cursor:pointer"><?php
echo '<img height="20" src="' . plugins_url( 'images/close.png' , __FILE__ ) . '" > ';
?></span>
</div>
<div id="fb-root"></div><script src="http://connect.facebook.net/en_US/all.js#xfbml=1"></script><fb:like-box href="http://www.facebook.com/<?php echo shaped_plugin_option( 'facebook_url', 'stting_basics', 'shapedplugin' ); ?>" width="<?php echo shaped_plugin_option( 'facebook_main_width', 'stting_basics', '220' ); ?>" height="<?php echo shaped_plugin_option( 'facebook_main_height', 'stting_basics', '210' ); ?>" show_faces="<?php echo shaped_plugin_option( 'show_faces_area', 'stting_basics', 'true' ); ?>" border_color="#fff" stream="<?php echo shaped_plugin_option( 'stream_area', 'stting_basics', 'false' ); ?>" header="<?php echo shaped_plugin_option( 'header_area', 'stting_basics', 'false' ); ?>"></fb:like-box>
</div>

<!-- Style CSS -->
<style type="text/css">
    #right{
        position: absolute; 
        left: -25px; 
        margin: -15px 0 0 10px; 
        z-index: 1000;
    }
    #left{
        position: absolute; 
        right: -14px; 
        margin: -15px 0 0 10px; 
        z-index: 1000;
    }

    /* xs */
    @media (max-width: 767px) {
        .rt-facebook-like-box{
            height: <?php echo shaped_plugin_option( 'facebook_mobile_height', 'stting_mobile', '75' ); ?>px !important;
            width: <?php echo shaped_plugin_option( 'facebook_mobile_width', 'stting_mobile', '190' ); ?>px !important;
        }

    }
</style>

<?php
}
add_action ('wp_enqueue_scripts', 'rt_facebook_like_box_main');



function rt_facebook_like_box_script() { ?>

    <script type="text/javascript">
    	jQuery(document).ready(function () {
    	jQuery('#facebook_likebox').slideDown(5000);
    	jQuery('#closefacebook_likebox').click(function(){
    	jQuery(this).fadeOut();
    	jQuery('#facebook_Likebox').css('display','none');
    	jQuery('#facebook_likebox').slideUp(1500);
    	});
    	});
    </script>

<?php
}
add_action ('wp_footer', 'rt_facebook_like_box_script')
?>