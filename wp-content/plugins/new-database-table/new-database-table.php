<?php

/*
  Plugin Name: Pet Adoption (New DB Table)
  Version: 1.0
  Author: Brad
  Author URI: https://www.udemy.com/user/bradschiff/
*/

if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
require_once plugin_dir_path(__FILE__) . 'inc/generatePet.php';

class PetAdoptionTablePlugin {
  function __construct() {
    global $wpdb; //global variable $wpdb, which is the main instance of the WordPress $wpdb class used to interact with the database
    $this->charset = $wpdb->get_charset_collate(); //This line is getting the character set and collation from the database
    $this->tablename = $wpdb->prefix . "pets"; //This is defining the name of the table to be created in the database. It uses the database prefix configured in WordPress

    add_action(
      'activate_new-database-table/new-database-table.php', //here the hook is: activate_yourPluginFolderName_YourMainFileName.php
      array($this, 'onActivate') //fn that will be called when activating the plugin
    ); //This hook is executed 1x only when my plugin is activated 
    //add_action('admin_head', array($this, 'onAdminRefresh')); //run in Admin when I reaload the page to add my pets, if If call the function onAdminRefresh only 1 pet will be added, but if I run PopulatedFast many pets will be added
    add_action('admin_post_createpet', array($this, 'createPet')); //Here I'm using the hook that I created in my template-pets.php
    add_action('admin_post_nopriv_createpet', array($this, 'createPet')); //this 'nopriv' would allow the action to be accessible even to unauthenticated visitors
    add_action('admin_post_deletepet', array($this, 'deletePet')); //Here I'm using the hook that I created in my template-pets.php
    add_action('admin_post_nopriv_deletepet', array($this, 'deletePet')); //this 'nopriv' would allow the action to be accessible even to unauthenticated visitors
    add_action('wp_enqueue_scripts', array($this, 'loadAssets')); //run in my front
    add_filter('template_include', array($this, 'loadTemplate'), 99); //This is a hook that allows you to modify the page template used when loading a page in WordPress. It calls the loadTemplate() function and sets the priority to 99
  }

  #Add a new pet in DB
  function createPet(){
    if(current_user_can('administrator')){
      $pet = generatePet(); //create my random pet
      $pet['petname'] = sanitize_text_field($_POST['incomingpetname']); //change the name 
      global $wpdb;

      $wpdb->insert($this->tablename, $pet); //save in my DB

      wp_safe_redirect(site_url('/index.php/pet-adoption')); //redirect to initial pet-adoption page after add pet
    }else{
      wp_safe_redirect(site_url()); //redirect to homepage if you aren't admin
    }
  }

  #Delete a new pet in DB
  function deletePet(){
    if(current_user_can('administrator')){
      $id = sanitize_text_field($_POST['idtodelete']); //get the id to delete 
      global $wpdb;

      $wpdb->delete($this->tablename, ['id' => $id]); //delete in my DB

      wp_safe_redirect(site_url('/index.php/pet-adoption')); //redirect to initial pet-adoption page after delete pet
    }else{
      wp_safe_redirect(site_url()); //redirect to homepage if you aren't admin
      exit();
    }
  }

  function onActivate() {
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php'); //This includes the WordPress upgrade.php file, which contains the dbDelta() function. This function is used to update the database structure.
    dbDelta("CREATE TABLE $this->tablename (
      id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
      birthyear smallint(5) NOT NULL DEFAULT 0,
      petweight smallint(5) NOT NULL DEFAULT 0,
      favfood varchar(60) NOT NULL DEFAULT '',
      favhobby varchar(60) NOT NULL DEFAULT '',
      favcolor varchar(60) NOT NULL DEFAULT '',
      petname varchar(60) NOT NULL DEFAULT '',
      species varchar(60) NOT NULL DEFAULT '',
      PRIMARY KEY  (id)
    ) $this->charset;");//this fn dbDelta is very sensitive, so is important follow all rules of WP documentation to create new tables
  }

  function onAdminRefresh() {
    global $wpdb;
    $wpdb->insert(
      $this->tablename, //name of your table
      generatePet() //datas that you want to save in the DB
    );
  }

  function loadAssets() {
    if (is_page('pet-adoption')) {
      wp_enqueue_style('petadoptioncss', plugin_dir_url(__FILE__) . 'pet-adoption.css');
    }
  }

  function loadTemplate($template) {
    if (is_page('pet-adoption')) {
      return plugin_dir_path(__FILE__) . 'inc/template-pets.php'; //if my user open the page with slug 'pet-adoption' I'll call my custom template, else I'll call the default template
    }
    return $template;
  }

  function populateFast() {
    $query = "INSERT INTO $this->tablename (`species`, `birthyear`, `petweight`, `favfood`, `favhobby`, `favcolor`, `petname`) VALUES ";
    $numberofpets = 100;
    for ($i = 0; $i < $numberofpets; $i++) {
      $pet = generatePet();
      $query .= "('{$pet['species']}', {$pet['birthyear']}, {$pet['petweight']}, '{$pet['favfood']}', '{$pet['favhobby']}', '{$pet['favcolor']}', '{$pet['petname']}')";
      if ($i != $numberofpets - 1) {
        $query .= ", ";
      }
    }
    /*
    Never use query directly like this without using $wpdb->prepare in the
    real world. I'm only using it this way here because the values I'm 
    inserting are coming fromy my innocent pet generator function so I
    know they are not malicious, and I simply want this example script
    to execute as quickly as possible and not use too much memory.
    */
    global $wpdb;
    $wpdb->query($query);
  }

}

$petAdoptionTablePlugin = new PetAdoptionTablePlugin();