<?php

/*
  Plugin Name: Featured Professor Block Type
  Version: 1.0
  Author: Brad
  Author URI: https://www.udemy.com/user/bradschiff/
*/

use function PHPSTORM_META\type;

if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

require_once plugin_dir_path(__FILE__) . 'inc/generateProfessorHTML.php';

class FeaturedProfessor {
  function __construct() {
    add_action('init', [$this, 'onInit']);
    add_action('rest_api_init', [$this, 'profHTML']);
  }

  //This method registers a route in the REST API that allows you to get a teacher's HTML based on profId.
  function profHTML(){
    register_rest_route('featuredProfessor/v1', 'getHTML', [
      'methods' => WP_REST_SERVER::READABLE,
      'callback' => [$this, 'getProfHTML']
    ]);
  }

  //This method is called when the REST API route is accessed. It calls the generateProfessorHTML function with the given profId and returns the generated HTML.
  function getProfHTML($data){
    return generateProfessorHTML($data['profId']);
  }

  //used to register the block in WordPress. It registers the script and style needed for the block and defines the callback function (renderCallback)
  function onInit() {
    //register my meta that I created in my .js
    register_meta(
      'post', // type of metadata
      'featuredprofessor', //name/slug of the meta. need be equals the name in .js
      [
        'show_in_rest' => true,
        'type' => 'number',
        'single' => false //If true it would try to save everything in one line, perhaps serializing an array. but for performance and practicality we use false to create new lines in the database with each entry
      ] //aray of options
    );

    wp_register_script('featuredProfessorScript', plugin_dir_url(__FILE__) . 'build/index.js', array('wp-blocks', 'wp-i18n', 'wp-editor'));
    wp_register_style('featuredProfessorStyle', plugin_dir_url(__FILE__) . 'build/index.css');

    register_block_type('ourplugin/featured-professor', array(
      'render_callback' => [$this, 'renderCallback'],
      'editor_script' => 'featuredProfessorScript',
      'editor_style' => 'featuredProfessorStyle'
    ));
  }

  //This method is called to render the block. It checks whether a profId was selected and, if so, loads the style and returns the generated HTML using the generateProfessorHTML function. If no profId is selected, returns NULL.
  function renderCallback($attributes) {
    if($attributes['profId']){
      wp_enqueue_style('featuredProfessorStyle');
      return generateProfessorHTML($attributes['profId']); //fn that create our HTML
    }else{
      return NULL;
    }
  }

}

$featuredProfessor = new FeaturedProfessor();