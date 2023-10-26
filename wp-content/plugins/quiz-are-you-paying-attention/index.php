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
        wp_register_script(
            'ournewblocktype', //Name to identify this script, slug
            plugin_dir_url(__FILE__) . 'build/index.js', //path to my js file
            ['wp-blocks', 'wp-element']//list of dependencies that need to be loaded before my js
        );
        register_block_type(
            'ourplugin/are-you-paying-attention',  //The same name of my slug in JS
            [
                'editor_script' => 'ournewblocktype', //name to my script of my block
                'render_callback' => [$this, 'theHtml'] //This will call my function that render my block in front
            ]
        );
    }

    #With my PHP making my return, I can update here and in my front is updated too automatically
    function theHtml($attributes){
        # I can return this:
        //return '<h2>Today all is completely' .  esc_html($attributes['skyColor']) . ' but I am ' . esc_html($attributes['grassColor']) . '.</h2>';
        # OR this block 'ob_start': says me that all things that are in between, will be returned. The diference is only in use HTML or string concatenated with many variables
        ob_start(); ?>
        <h3>Today all is completely <?= esc_html($attributes['skyColor']) ?>  but I am  <?= esc_html($attributes['grassColor']) ?> !!!</h3>
    <?php return ob_get_clean();
    }
}

$areYouPayingAttention = new AreYouPayingAttention();