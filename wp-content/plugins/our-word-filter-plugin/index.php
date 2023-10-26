<?php 

/*
  Plugin Name: Word Filter
  Description: Replaces a list of words.
  Version 1.0
  Author: Brad
  Author URI: https://www.udemy.com
*/

if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly to prevent people from using a url to open that specific file

class OurWordFilterPlugin{

    function __construct()
    {
        add_action('admin_menu', [$this, 'ourMenu']); //Will Show our plugin in admin menu
        add_action('admin_init', [$this, 'ourSettings']);
        if(get_option('plugin_words_to_filter')){ //check if exists something in my field plugin_words_to_filter
            add_filter('the_content', [$this, 'filterlogic']); //if yes, called my fn to change my content and return my new content
        }
    }

    #Using WP form generator to create my Options page
    function ourSettings(){
        add_settings_section('replacement-text-section', null, null, 'word-filter-options');
        register_setting('replacementFields', 'replacementText');
        add_settings_field('replacemente-text', 'Filtered Text', [$this, 'replacementFieldHTML'], 'word-filter-options', 'replacement-text-section');
    }

    #create my config plugin form
    function replacementFieldHTML(){ ?>
        <input type="text" name="replacementText" value="<?= esc_attr(get_option('replacementText', '***'))?>">
        <p class="description">Leave Blank to simply remove the filtered words.</p>
    <?php }

    #Logic to replace my content
    function filterlogic($content){
        $badWords = explode(',', get_option('plugin_words_to_filter')); //transform my data in array
        $badWordsTrimmed =  array_map('trim', $badWords); //remove empty spaces
        return str_ireplace($badWordsTrimmed, esc_html(get_option('replacementText', '***')), $content); //replace my content
    }

    function ourMenu(){
        #create our tab Word Filter in admin menu
        $mainPageHook = add_menu_page(
           'Words to filter', //Document title, what appears in my browser tab
           'Word Filter', //Title that appears in my menu
           'manage_options', //permission / capability to see this page
           'ourwordfilter', //slug to this page
           [$this, 'wordFilterPage'], //fn that creates my HTML page 
           'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHZpZXdCb3g9IjAgMCAyMCAyMCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZmlsbC1ydWxlPSJldmVub2RkIiBjbGlwLXJ1bGU9ImV2ZW5vZGQiIGQ9Ik0xMCAyMEMxNS41MjI5IDIwIDIwIDE1LjUyMjkgMjAgMTBDMjAgNC40NzcxNCAxNS41MjI5IDAgMTAgMEM0LjQ3NzE0IDAgMCA0LjQ3NzE0IDAgMTBDMCAxNS41MjI5IDQuNDc3MTQgMjAgMTAgMjBaTTExLjk5IDcuNDQ2NjZMMTAuMDc4MSAxLjU2MjVMOC4xNjYyNiA3LjQ0NjY2SDEuOTc5MjhMNi45ODQ2NSAxMS4wODMzTDUuMDcyNzUgMTYuOTY3NEwxMC4wNzgxIDEzLjMzMDhMMTUuMDgzNSAxNi45Njc0TDEzLjE3MTYgMTEuMDgzM0wxOC4xNzcgNy40NDY2NkgxMS45OVoiIGZpbGw9IiNGRkRGOEQiLz4KPC9zdmc+', //icon that appears in my menu. I can use:  plugin_dir_url(__FILE__) . 'custom.svg' - using this my style is preserved, but using base64 WP fix my style
           100 //position  my page appears in menu, (the smaller the number, the higher the priority to display)
        );

        #create submenu inside the Word Filter menu (we are using the same slug as the main page because this block of code is only used for the parent and child menu to have different names, so I am using it to override the option that would be 'Word Filter' in the submenu)
        add_submenu_page(
            'ourwordfilter', //in which menu you want add this submenu
            'Word to Filter', //Document title, what appears in my browser tab
            'Words List', //Title that appears in my submenu
            'manage_options', //permission / capability to see this page
            'ourwordfilter', //slug to this page 
            [$this, 'wordFilterPage'] //fn that creates my HTML page
        );

        #create OPTION submenu inside the Word Filter menu
        add_submenu_page(
            'ourwordfilter', //in which menu you want add this submenu
            'Word filter Options', //Document title, what appears in my browser tab
            'Options', //Title that appears in my submenu
            'manage_options', //permission / capability to see this page
            'word-filter-options', //slug to this page
            [$this, 'optionsSubPage'] //fn that creates my HTML page
        );
        add_action("load-{$mainPageHook}", [$this, 'mainPageAssets']); // Will Called a style only for my page Word Filter
    }

    function mainPageAssets(){
        wp_enqueue_style('filterAdminCss', plugin_dir_url(__FILE__) . 'style.css');
    }

    function handleForm(){
        #validating the nonce sent by the form and if my user have admin permission 
        if(wp_verify_nonce(sanitize_text_field($_POST['ourNonce']), 'saveFilterWords') && current_user_can('manage_options')){
            update_option(
                'plugin_words_to_filter',  //name of the option in DB
                sanitize_text_field($_POST['plugin_words_to_filter']) // The value  I want store in the DB, you need to remember of sanitize the value
            ); ?>

            <div class="updated">
                <p>Your filtered words were saved.</p>
            </div>
        <?php } else { ?>
            <div class="error">
                <p>Sorry you don't have permission to perform that action. </p>
            </div>
        <?php }
    }

    #creates my HTML page to add my blocked words (with our custom php handler)
    function wordFilterPage(){ ?>
        <div class="wrap">
            <h1>Word Filter</h1>
            <?php 
                if(isset($_POST['justsubmitted']) && $_POST['justsubmitted'] == 'true'){
                    $this->handleForm(); //call the function that will save my form in my DB when I save and show me a message of success
                } 
            ?>
            <form method="post">
                <input type="hidden" name="justsubmitted" value="true">
                <?php wp_nonce_field('saveFilterWords', 'ourNonce')  //send a nonce to more security?>
                <label for="plugin_words_to_filter">
                    <p>Enter a <strong>comma-separated</strong> list of words to filter from yous site's content</p>
                </label>
                <div class="word-filter__flex-container">
                    <textarea name="plugin_words_to_filter" id="plugin_words_to_filter" placeholder="bad, mean, awful, horrible"><?= esc_textarea(get_option('plugin_words_to_filter')) ?></textarea>
                </div>
                <input class="button button-primary" type="submit" name="submit" id="submit" value="Save Changes">
            </form>
        </div>
    <?php }

    #creates my HTML page to config my replace (using WP form builder)
    function optionsSubPage(){ ?>
        <div class="wrap">
            <h1>Word Filter Options</h1>
            <!-- I send it to options.php because WP knows how to handle this and save in my DB, it's a WP file -->
            <form action="options.php" method="post">
                <?php 
                    settings_errors(); //here we need to call this function bacause we are not on a configuration page within the configuration menu
                    settings_fields('replacementFields');
                    do_settings_sections('word-filter-options');
                    submit_button();
                ?>
            </form>
        </div>
    <?php }
}

$ourWordFilterPlugin = new OurWordFilterPlugin();