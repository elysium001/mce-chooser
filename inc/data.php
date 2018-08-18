<?php

defined( 'ABSPATH' ) or die( 'Nope, not accessing this' );

class wp_tmc_data {

    /**
     * Start up
     */

    public function get_available_plugins()
    {
        return array_merge($this->get_default_plugins(),$this->get_custom_plugins());   
    }

    public function get_default_plugins()
    {
        $extra_plugins = array();
        $default_plugins = array(
            'print', 'preview', 'fullpage', 'powerpaste', 'searchreplace','autolink','directionality','advcode','visualblocks','visualchars','fullscreen','image','link','media','template','codesample','table','charmap','hr','pagebreak','nonbreaking','anchor','toc','insertdatetime','advlist','lists','textcolor','wordcount','tinymcespellchecker','a11ychecker','imagetools','mediaembed','linkchecker','contextmenu','colorpicker','textpattern','help'
        );
        $dir = includes_url() . "js/tinymce/plugins/";
        if( file_exists($dir) ){
            foreach(scandir($dir, 0) as $file)
            {
               $extra_plugins[] = $file;
            }
        }
        $plugins = array_merge($extra_plugins,$default_plugins);
        sort($plugins);
        return $plugins;        
    }

    public function get_custom_plugins()
    {
        $plugins = array();
        $dir = plugin_dir_path( __FILE__ ) . "custom/plugins/";
        if( file_exists($dir) ){
            foreach(scandir($dir, 0) as $file)
            {
               $plugins[] = $file;
            }
        }        
        return $plugins;
    }
    


  
}