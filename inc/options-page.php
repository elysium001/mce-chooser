<?php

defined( 'ABSPATH' ) or die( 'Nope, not accessing this' );
require_once "data.php";

class wp_tinymce_options_page extends wp_tmc_data {
/**
     * Holds the values to be used in the fields callbacks
     */
    private $options;
    private $option_name = "tmc_plugin_options";

    /**
     * Start up
     */
    public function __construct()
    {
        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );
    }

    /**
     * Add options page
     */
    public function add_plugin_page()
    {
        // This page will be under "Settings"
        add_options_page(
            'Settings Admin', 
            'TinyMCE Config Settings', 
            'manage_options', 
            $this->option_name, 
            array( $this, 'create_admin_page' )
        );
    }

    /**
     * Options page callback
     */
    public function create_admin_page()
    {
        // Set class property
        $this->options = get_option( $this->option_name );
        ?>
        <div class="wrap">
            <h1>TinyMCE Config Settings</h1>
            <form method="post" action="options.php">
            <?php
                // This prints out all hidden setting fields
                settings_fields( 'tmc_group' );
                do_settings_sections( $this->option_name );
                submit_button();
            ?>
            </form>
        </div>
        <?php
    }

    /**
     * Register and add settings
     */
    public function page_init()
    {        
        register_setting(
            'tmc_group', // Option group
            $this->option_name, // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        add_settings_section(
            $this->option_name, // ID
            'My Custom Settings', // Title
            array( $this, 'print_section_info' ), // Callback
            $this->option_name // Page
        );  

        add_settings_field(
            'r1',
            'First Row',
            array( $this, 'print_checkboxes' ),
            $this->option_name,
            $this->option_name,
            'first_row'
        );

        add_settings_field(
            'r2',
            'Second Row',
            array( $this, 'print_checkboxes' ),
            $this->option_name,
            $this->option_name,
            'second_row'
        );

        add_settings_field(
            'r3',
            'Third Row',
            array( $this, 'print_checkboxes' ),
            $this->option_name,
            $this->option_name,
            'third_row'
        );

        add_settings_field(
            'r4',
            'Fourth Row',
            array( $this, 'print_checkboxes' ),
            $this->option_name,
            $this->option_name,
            'fourth_row'
        );

        add_settings_field(
            'first_row', // ID
            'first row order', // Title 
            array( $this, 'row_order_callback' ), // Callback
            $this->option_name, // Page
            $this->option_name, // Section
            'first_row'           
        );

        add_settings_field(
            'second_row', // ID
            'second row order', // Title 
            array( $this, 'row_order_callback' ), // Callback
            $this->option_name, // Page
            $this->option_name, // Section
            'second_row'           
        );          
        
        add_settings_field(
            'third_row', // ID
            'third row order', // Title 
            array( $this, 'row_order_callback' ), // Callback
            $this->option_name, // Page
            $this->option_name, // Section
            'third_row'           
        );          
        
        add_settings_field(
            'fourth_row', // ID
            'fourth row order', // Title 
            array( $this, 'row_order_callback' ), // Callback
            $this->option_name, // Page
            $this->option_name, // Section
            'fourth_row'           
        ); 
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input )
    {
        $new_input = array();
        if( isset( $input['first_row'] ) )
            $new_input['first_row'] = absint( $input['first_row'] );
        if( isset( $input['second_row'] ) )
            $new_input['second_row'] = absint( $input['second_row'] );
        if( isset( $input['third_row'] ) )
            $new_input['third_row'] = absint( $input['third_row'] );
        if( isset( $input['fourth_row'] ) )
            $new_input['fourth_row'] = absint( $input['fourth_row'] );

        if( isset( $input['title'] ) )
            $new_input['title'] = sanitize_text_field( $input['title'] );

        return $new_input;
    }

    /** 
     * Print the Section text
     */
    public function print_section_info()
    {
        print 'Enter your settings below:';
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function row_order_callback($row_number = "")
    {
        printf(
            '<input type="text" id="%s" name="%s[%s]" value="%s" />',
            $this->option_name,
            $this->option_name,
            $row_number,
            isset( $this->options[$row_number] ) ? esc_attr( $this->options[$row_number]) : ''
        );
    }

    public function print_checkboxes($row = "")
    {
        echo '<ul id="available-plugins">';
        foreach ($this->get_available_plugins() as $option_value => $option_name) {
			$checked = " ";
			if (get_option($this->option_name.'['.$row.']['.$option_name.']')) {
				$checked = " checked='checked' ";
			}
			echo "<li>\n";
			echo '<input type="checkbox" name="'.$this->option_name.'['.$row.']['.$option_name.']'.'" value="true" '.$checked.' class="'.$option_name.'" />'.$option_name."\n";
			echo "</li>\n";
        }
        echo '</ul>';
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function title_callback()
    {
        printf(
            '<input type="text" id="title" name="my_option_name[title]" value="%s" />',
            isset( $this->options['title'] ) ? esc_attr( $this->options['title']) : ''
        );
    }
}