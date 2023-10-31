<?php

/*
    Plugin Name: Quiz - Are you paying attention
    Description: Give your readers a multiple choice question!
    Version: 1.0
    Author: Brad
    Author URI: https://udemy.com
*/

if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly to prevent people from using a url to open that specific file

class AreYouPayingAttention {
    function __construct() {
        // add_action('enqueue_block_editor_assets', array($this, 'adminAssets')); //I'm not going to use this anymore because I'm going to bring my rendering to PHP instead of calling it through JS
        add_action('init', array($this, 'adminAssetsPhp'));
    }

    #---- I only used this while my return was in JS, but now my PHP does my rendering
    // function adminAssets() {
    //     wp_enqueue_script(
    //         'ournewblocktype', //Name to identify this script, slug
    //         plugin_dir_url(__FILE__) . 'build/index.js', //path to my js file
    //         ['wp-blocks', 'wp-element']//list of dependencies that need to be loaded before my js
    //     );
    // }
    #----

    #Register my block here with the same slug of my JS
    function adminAssetsPhp() {
        wp_register_style('quizeditcss', plugin_dir_url(__FILE__) . 'build/index.css'); //here we register our css generated for our scss
        wp_register_script(
            'ournewblocktype', //Name to identify this script, slug
            plugin_dir_url(__FILE__) . 'build/index.js', //path to my js file
            ['wp-blocks', 'wp-element', 'wp-editor']//list of dependencies that need to be loaded before my js
        );
        register_block_type(
            'ourplugin/are-you-paying-attention',  //The same name of my slug in JS
            [
                'editor_script' => 'ournewblocktype', //name to my script of my block
                'editor_style' => 'quizeditcss', //called our css in our block
                'render_callback' => [$this, 'theHtml'] //This will call my function that render my block in front

            ]
        );
    }

    #With my PHP making my return, I can update here and in my front is updated too automatically
    function theHtml($attributes){

        //loading our script and style to the block in the frontend
        if(!is_admin()){
            wp_enqueue_script('attentionFrontend', plugin_dir_url(__FILE__) . 'build/frontend.js' , ['wp-element']);
            wp_enqueue_style('attentionFrontendStyle', plugin_dir_url(__FILE__) . 'build/frontend.css');
        }
        # I can return this:
        //return '<h2>Today all is completely' .  esc_html($attributes['skyColor']) . ' but I am ' . esc_html($attributes['grassColor']) . '.</h2>';
        # OR this block 'ob_start': says me that all things that are in between, will be returned. The diference is only in use HTML or string concatenated with many variables
        ob_start(); ?>
            <div class="paying-attention-update-me">
                <!-- Here I'm going to give display:none so it doesn't appear to the user, but it will be loaded in my DOM so my js can access the values -->
                <pre style="display:none;"> 
                    <?= wp_json_encode($attributes) //loading my datas of my DB that re json, but when we recevied in $attributes, e have an array, so we convert again in a json?> 
                </pre>
            </div>
    <?php return ob_get_clean();
    }
}

$areYouPayingAttention = new AreYouPayingAttention();