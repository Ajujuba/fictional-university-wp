<?php

/*
    Plugin Name: Word Count
    Description: A truly Amazing Pugin
    Varsion: 1.0
    Author: Brad
    Author URI: https://udemy.com
*/

#------------------------------------------
#I created the class to store my functions just so I don't have to worry about creating such long function names trying to ensure that they are unique, so I can call the class with a unique name and give the function a simple name that I want. 
#But I could do it normally without using the class
class WordCountAndTimePlugin{

    function __construct(){
        add_action('admin_menu', [$this, 'countWordAdminPage']); //I called it because my fn is in a class, them I need to explain: 1 param: 'search in this(inside this class).  2 param: the fn called ... '
        add_action('admin_init', [$this, 'settings']);
    }

    #This function will config our fields of the form
    function settings(){
        #create our section
        add_settings_section(
            'wcp_first_section', //name of the section
            null, //title of the section
            null, //content in top section
            'word-count-settings-page' //page slug where I want add this section

        );

        #create our field display location
        add_settings_field(
            'wcp_location', //option that we will link
            'Display Location', // HTML label text
            [$this, 'locationHTML'], //fn that will create our HTML
            'word-count-settings-page', //page slug for this page
            'wcp_first_section' // section name 
        );
        #Saving our config display location
        register_setting('wordcountplugin', 'wcp_location', [
            // 'sanitize_callback' => 'sanitize_text_field', // called a WP function to sanitize our field
            'sanitize_callback' => [$this, 'sanitizeLocation'],
            'default' => '0' // 0 = start of file - 1 = end of file
        ]);

        #headline text
        add_settings_field('wcp_headline', 'Headline Text', [$this, 'headlineHTML'], 'word-count-settings-page', 'wcp_first_section');
        register_setting('wordcountplugin', 'wcp_headline', ['sanitize_callback' => 'sanitize_text_field', 'default' => 'Post Statistics']);

        #word count
        add_settings_field('wcp_wordcount', 'Word Count', [$this, 'wordcountHTML'], 'word-count-settings-page', 'wcp_first_section');
        register_setting('wordcountplugin', 'wcp_wordcount', ['sanitize_callback' => 'sanitize_text_field', 'default' => '1']);

        #character count
        add_settings_field('wcp_charcount', 'Character Count', [$this, 'charcountHTML'], 'word-count-settings-page', 'wcp_first_section');
        register_setting('wordcountplugin', 'wcp_charcount', ['sanitize_callback' => 'sanitize_text_field', 'default' => '1']);

        #read time
        add_settings_field('wcp_readtime', 'Read Time', [$this, 'readtimeHTML'], 'word-count-settings-page', 'wcp_first_section');
        register_setting('wordcountplugin', 'wcp_readtime', ['sanitize_callback' => 'sanitize_text_field', 'default' => '1']);
    }


    function sanitizeLocation($input){
        if($input != '0' && $input != '1'){
            add_settings_error('wcp_location', 'wcp_location_error', 'Display location must be either beggining or end.');
            return get_option('wcp_location');
        }

        return $input;
    }

    #Field wcp_location
    function locationHTML(){?>
        <select name="wcp_location">
            <option value="0" <?php selected(get_option('wcp_location'), '0' )?>>Beginning of post</option>
            <option value="1" <?php selected(get_option('wcp_location'), '1' )?>>End of post</option>
        </select>
    <?php }

    #field wcp_headline
    function headlineHTML(){ ?>
        <input type="text" name="wcp_headline" value="<?= get_option('wcp_headline') ? esc_attr(get_option('wcp_headline')) : ''?>">
    <?php }

    #field wcp_wordcount
    function wordcountHTML(){ ?>
        <input type="checkbox" name="wcp_wordcount" value="1" <?php checked(get_option('wcp_wordcount', '1'))?>>
    <?php }

    #field wcp_charcount
    function charcountHTML(){ ?>
        <input type="checkbox" name="wcp_charcount" value="1" <?php checked(get_option('wcp_charcount', '1'))?>>
    <?php }

    #field wcp_readtime
    function readtimeHTML(){ ?>
        <input type="checkbox" name="wcp_readtime" value="1" <?php checked(get_option('wcp_readtime', '1'))?>>
    <?php }

    #Here I want to add a new page inside my admin menu in settigns->my new page
    function countWordAdminPage(){
        #this function creates a new page in Settings main menu
        add_options_page(
            'Word Count Settings', //title of the page that appears in my browser tab
            'Word Count',  //Name that appears in settings menu
            'manage_options', //what permission can see this new page
            'word-count-settings-page', //the slug used at the end of my URL, must be unique
            [$this, 'ourHTML'] //our function that is in $this(inside this class) called 'ourHTML'
        );
    }

    #Show our HTML
    function ourHTML(){ ?>
        <div class="wrap">
            <h1>Word Count settings</h1>
            <form action="options.php" method="post">
                <?php 
                    settings_fields('wordcountplugin'); //By placing the line, WP will do all the work looking at this group of fields that we defined above... it will place nonce and everything else that is necessary
                    do_settings_sections('word-count-settings-page');
                    submit_button();
                ?>
            </form>
        </div>

    <?php }
}

$wordCountAndTimePlugin = new WordCountAndTimePlugin();


# --------------- EXEMPLE ONLY ----------- #
// #this function gets my real content from the database through my hook and I can manipulare it inside my function
// function addToEndOfPost($content){

//     #this if says: if you stay in a post type 'page' and if is your main query
//     if(is_page() && is_main_query()){
//         return $content . '<p>Testing my first plugin that add this text in the end of file</p>';
//     }

//     return $content;
// }
// add_filter('the_content', 'addToEndOfPost'); #This will filter the content of a post
# --------------- EXEMPLE ONLY ----------- #