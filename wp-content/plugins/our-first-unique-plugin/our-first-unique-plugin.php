<?php

/*
    Plugin Name: Word Count
    Description: A truly Amazing Pugin
    Varsion: 1.0
    Author: Brad
    Author URI: https://udemy.com
    Text Domain: wcpdomain
    Domain path: /languages
*/

#------------------------------------------
#I created the class to store my functions just so I don't have to worry about creating such long function names trying to ensure that they are unique, so I can call the class with a unique name and give the function a simple name that I want. 
#But I could do it normally without using the class
class WordCountAndTimePlugin{

    function __construct(){
        add_action('admin_menu', [$this, 'countWordAdminPage']); //I called it because my fn is in a class, them I need to explain: 1 param: 'search in this(inside this class).  2 param: the fn called ... '
        add_action('admin_init', [$this, 'settings']); //config my field settings
        add_filter('the_content', [$this, 'ifWrap']); // show my datas and counts
        add_action('init', [$this, 'languages']); //this will point to WP where is my translations
    }

    #path to my translation file
    function languages(){
        load_plugin_textdomain('wcpdomain', false, dirname(plugin_basename(__FILE__)) . '/languages');
    }

    #validating the content change only when I am on a blog post screen and if the user has selected any of the checkboxes
    function ifWrap($content){
        if(is_main_query() && is_single() && ( get_option('wcp_wordcount', '0') == '1' || get_option('wcp_charcount', '0') == '1' || get_option('wcp_readtime', '0') == '1' ) ){
            return $this->createHTML($content);
        }
        return $content;
    }

    #create our block with html show the datas
    function createHTML($content){
        $html = '<h3>' . esc_html(get_option('wcp_headline', 'Post Statistics')) . '</h3><p>';

        #get word count once because both wordcount and read time will need it
        if(get_option('wcp_wordcount', '1') || get_option('wcp_readtime', '1') ){
            $wordCount = str_word_count(strip_tags($content)); //calculate my content
        }

        if(get_option('wcp_wordcount', '1')){
           $html  .=  esc_html__('This post has:', 'wcpdomain') . ' ' . $wordCount . ' ' . __('words', 'wcpdomain') . '.<br>'; //show my count of words
        }

        if(get_option('wcp_charcount', '1')){
            $html  .= esc_html__('This post has:', 'wcpdomain') . ' ' . strlen(strip_tags($content)) . ' ' . __('words', 'wcpdomain') . '.<br>'; //show my count of words
        }

        if(get_option('wcp_readtime', '1')){
            #here I'm supposed that a reader read 255 words per minute and round() will round my result to the intege
            $html  .= 'This post will take about: ' . round($wordCount/255) . ' minute(s) to read. <br>'; //calculate my read time
        }

        $html .= '</p>';

        #define the position of my block
        if(get_option('wcp_location', '0') == '0'){
            return $html . $content;
        }
        return $content . $html;
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
        add_settings_field('wcp_wordcount', 'Word Count', [$this, 'checkboxHTML'], 'word-count-settings-page', 'wcp_first_section', ['theName' => 'wcp_wordcount']);
        register_setting('wordcountplugin', 'wcp_wordcount', ['sanitize_callback' => 'sanitize_text_field', 'default' => '1']);

        #character count
        add_settings_field('wcp_charcount', 'Character Count', [$this, 'checkboxHTML'], 'word-count-settings-page', 'wcp_first_section', ['theName' => 'wcp_charcount']);
        register_setting('wordcountplugin', 'wcp_charcount', ['sanitize_callback' => 'sanitize_text_field', 'default' => '1']);

        #read time
        add_settings_field('wcp_readtime', 'Read Time', [$this, 'checkboxHTML'], 'word-count-settings-page', 'wcp_first_section', ['theName' => 'wcp_readtime']);
        register_setting('wordcountplugin', 'wcp_readtime', ['sanitize_callback' => 'sanitize_text_field', 'default' => '1']);
    }

    #if my input has value != 1/0 then return error
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

    // reusable checkbox function for fields wcp_wordcount, wcp_charcount and wcp_readtime
    function checkboxHTML($args) { ?>
        <input type="checkbox" name="<?php echo $args['theName'] ?>" value="1" <?php checked(get_option($args['theName']), '1') ?>>
    <?php }

    #Here I want to add a new page inside my admin menu in settigns->my new page
    function countWordAdminPage(){
        #this function creates a new page in Settings main menu
        add_options_page(
            'Word Count Settings', //title of the page that appears in my browser tab
            __('Word Count', 'wcpdomain'),  //Name that appears in settings menu
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
                    //here we don't need call settings_errors() because if you is an a config page, WP call this for you
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