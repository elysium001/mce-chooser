<?php
/*
Plugin Name: WP TinyMCE Config
Plugin URI:  https://github.com/simonrcodrington/Introduction-to-WordPress-Plugins---Location-Plugin
Description: TinyMCE Config provides a way to easily add/remove available tinymce plugins, as well as custom ones! Just add them to your plugins folder > custom-plugins and then enable them from the settings!
Version:     1.0.0
Author:      Andres O. Serrano
Author URI:  https://github.com/elysium001/
License:     MIT
License URI: https://github.com/elysium001/tinymce-config/blob/master/LICENSE
*/
defined( 'ABSPATH' ) or die( 'Nope, not accessing this' );
require_once "inc/options-page.php";

class wp_tinymce_config extends wp_tinymce_options_page {

    private $option;

    public function __construct()
    {

        // Load the TinyMCE plugin : editor_plugin.js (wp2.5)
        //add_filter( 'mce_external_plugins', array($this,'tmc_custom_mce_buttons') );
        $this->option = get_option( $this->option_name );
        $wp_tinymce_options_page = new wp_tinymce_options_page();

        add_filter( 'tiny_mce_before_init', array($this,'tmc_tinymce_settings') );

        add_filter( 'mce_buttons', array($this,'tmc_mce_buttons_1') );
        add_filter( 'mce_buttons_2', array($this,'tmc_mce_buttons_2') );
        add_filter( 'mce_buttons_3', array($this,'tmc_mce_buttons_3') );
        add_filter( 'mce_buttons_4', array($this,'tmc_mce_buttons_4') );


        register_activation_hook(__FILE__, array($this,'plugin_activate')); //activate hook
        register_deactivation_hook(__FILE__, array($this,'plugin_deactivate')); //deactivate hook

        
    }

    public function plugin_activate()
    {
        //flush permalinks
        flush_rewrite_rules();
    }
    public function plugin_deactivate()
    {
        //flush permalinks
        flush_rewrite_rules();
    }

    function tmc_custom_mce_buttons( $plugin_array )
    {
        $plugin_array['myplugin'] = plugins_url( '/js/tinymce-plugin.js',__FILE__ );
        return $plugin_array;
    }

    public function tmc_mce_buttons_1( $buttons )
    {	
        /**
         * Add in a core button that's disabled by default
         */
        $buttons = [];
        $buttons[] = 'superscript';
        $buttons[] = 'subscript';

        foreach ($this as $key => $value) {
            
        }
    
        return $buttons;
    }

    public function tmc_mce_buttons_2( $buttons )
    {	
        /**
         * Add in a core button that's disabled by default
         */
        $buttons[] = 'superscript';
        $buttons[] = 'subscript';
    
        return $buttons;
    }

    public function tmc_mce_buttons_3( $buttons )
    {	
        /**
         * Add in a core button that's disabled by default
         */
        $buttons[] = 'superscript';
        $buttons[] = 'subscript';
    
        return $buttons;
    }

    // add new buttons
    function tmc_mce_buttons_4( $buttons )
    {
        array_push( $buttons, 'separator', 'myplugin' );
        return $buttons;
    }

    function tmc_tinymce_settings( $settings ) {
        //First, we define the styles we want to add in format 'Style Name' => 'css classes'
        $classes = array(
            __('Test style 1', 'mytheme') => 'teststyle1',
            __('Test style 2', 'mytheme') => 'teststyle2',
            __('Test style 3', 'mytheme') => 'teststyle3',
        );
    
        //Delimit styles by semicolon in format 'Title=classes;' so TinyMCE can use it
        if ( ! empty( $settings['theme_advanced_styles'] ) ) {
            $settings['theme_advanced_styles'] .= ';';
        } else {
            //If there's nothing defined yet, define it
            $settings['theme_advanced_styles'] = '';
        }
    
        //Loop through our newly defined classes and add them to TinyMCE
        $class_settings = '';
        foreach ( $classes as $name => $value ) {
            $class_settings .= "{$name}={$value};";
        }
    
        //Add our new class settings to the TinyMCE $settings array
        $settings['theme_advanced_styles'] .= trim( $class_settings, '; ' );
    
        return $settings;
    }

}

if( is_admin() ){
    $wp_tinymce_config = new wp_tinymce_config;
}