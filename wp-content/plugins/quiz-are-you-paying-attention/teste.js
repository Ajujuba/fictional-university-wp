// THIS FILE IS AN EXEMPLE ONLY, THE OFICIAL CODE IS IN SRC/INDEX.JS


// --------------------------------------------
// STANDARD APPROACH TO CREATE AN ELEMENT, BUT IT BECOMES DIFFICULT TO CREATE A BLOCK OF ELEMENTS, BUT THIS IS THE WP STANDARD
// ----------------------------------------------

wp.blocks.registerBlockType(
    'ourplugin/are-you-paying-attention', //name for our block type, slug
    {
        title: "Are you Payins Attention?", //visual title, that people will see 
        icon: "smiley",
        category: "common", //block category
        edit: function () {
            return wp.element.createElement(
                'h3', //type of HTML element you want to create
                null, //any args/propertys that describe you element, like classes or inline style
                'Hello is from editor screen' //the cildren or content for this element
            );
        }, //Control what you see in the editor screen
        save: function () {
            return wp.element.createElement('h1',null, 'Hello is front end screen'); // is not that efficient, so we will use JSX
        } //Controls what the public sees in the content
    } //configuration object - here we need use exact property names for WP to identify
)


// --------------------------------------------
// VERSION OF THE CODE WITH CREATING A BLOCK WITH INPUTS AND DISPLAYING THEM ON THE USER'S SCREEN: BUT THIS WAY IS 'STATIC', RECOMMENDED FOR WHEN YOU WILL NOT UPDATE YOUR BLOCK'S HTML OFTEN AND WON'T HAVE IT IN MANY DIFFERENT LOCATIONS. BECAUSE THIS WAY EVERYTHING WORKS, BUT WHENEVER YOU UPDATE THE BLOC CODE YOU NEED TO GO TO THE PAGES, AND SAVE THEM AGAIN FOR THEM TO UPDATE (HERE I USE JSX)
// ----------------------------------------------

wp.blocks.registerBlockType(
    'ourplugin/are-you-paying-attention', // slug
    {
        title: "Are you Payins Attention?", //visual title
        icon: "smiley",
        category: "common", //block category
        attributes: {
            skyColor: {type: "string"},
            grassColor: {type: "string"}
        },
        edit: function (props) {
            function updateSkyColor(event){
                props.setAttributes({skyColor: event.target.value}) //this line will set my attr in my DB with my value?
            }

            function updateGrassColor(event){
                props.setAttributes({grassColor: event.target.value})
            }

            return (
                <div>
                    <input type="test" placeholder="Sky color" value={props.attributes.skyColor} onChange={updateSkyColor}/>
                    <input type="test" placeholder="grass color" value={props.attributes.grassColor} onChange={updateGrassColor}/>

                </div>
            )
        }, //Control what you see in the editor screen
        save: function (props) {
            return (
                <h6> Today the sky is absoluty {props.attributes.skyColor} and the grass us {props.attributes.grassColor}</h6>
            )
        }, //Controls what the public sees in the content
        deprecated: [
            {
                attributes: {
                    skyColor: {type: "string"},
                    grassColor: {type: "string"}
                },
                save: function (props) {
                    return (
                        <h3> Today the sky is completly {props.attributes.skyColor} and the grass us {props.attributes.grassColor}</h3>
                        )
                }  
            },
            {
                attributes: {
                    skyColor: {type: "string"},
                    grassColor: {type: "string"}
                },
                save: function (props) {
                    return (
                        <p> Today the sky is {props.attributes.skyColor} and the grass us {props.attributes.grassColor}</p>
                    )
                } 
            }
        ]
    } //configuration object 
)

//-----------------------
// VERSION OF THE CODE THAT GENERATE MY STRUCTURE OF BLOCKS, BUT MY RESPONSE, MY RETURN, WAS DID IN MY PHP AND NOT HERE IN MY JS
//--------------------

wp.blocks.registerBlockType(
    'ourplugin/are-you-paying-attention', // slug
    {
        title: "Are you Payins Attention?", //visual title
        icon: "smiley",
        category: "common", //block category
        attributes: {
            question: {type: "string"}
        },
        edit: EditComponent , //Control what you see in the editor screen
        save: function (props) {
            return null; //let's remove from JS the responsibility of returning something and sending it to php, in the database we won't save anything static, we'll let php handle the values in real time
        }, //Controls what the public sees in the content
    } //configuration object 
)

//Fn orginal to show my 2 test fields 
function EditComponent (props) {
    function updateSkyColor(event){
        props.setAttributes({skyColor: event.target.value}) //this line will set my attr in my DB with my value?
    }

    function updateGrassColor(event){
        props.setAttributes({grassColor: event.target.value})
    }

    return (
        <div>
            <input type="test" placeholder="Sky color" value={props.attributes.skyColor} onChange={updateSkyColor}/>
            <input type="test" placeholder="grass color" value={props.attributes.grassColor} onChange={updateGrassColor}/>

        </div>
    )
}

//-------------------------
// VERSION OF MY CODE 'COMPLETE' - SAVE QUESTION AND ANSWER, DELETE ANSWERS, AND CHECK THE CORRECT ANSWER. THIS IS ONLY A BACKUP BEFOR MAKE MY BUTTON 'UPDATE' DON'T WORK WHILE I DON'T CHECK A CORRECT ANSWER
//--------------------------
import "./index.scss" //This line will created our style css config from our scss
import {TextControl, Flex, FlexBlock, FlexItem, Button, Icon} from "@wordpress/components"

wp.blocks.registerBlockType(
    'ourplugin/are-you-paying-attention', // slug
    {
        title: "Are you Payins Attention?", //visual title
        icon: "smiley",
        category: "common", //block category
        attributes: {
            question: {type: "string"},
            answers: {type: "array", default: [""]}, //We defined default="" because when loading the page for the first time, we can see at least 1 answer field
            correctAnswer: {type: "number", default: undefined}
        },
        edit: EditComponent , //Control what you see in the editor screen
        save: function (props) {
            return null; //let's remove from JS the responsibility of returning something and sending it to php, in the database we won't save anything static, we'll let php handle the values in real time
        }, //Controls what the public sees in the content
    } //configuration object 
)

function EditComponent (props) {
    //This fn is linked to an input from the Wordpress components and not to a traditional input so we have facilities, here we don't need to receive the event and search within it, we can just receive 'value' and set it in the attribute.
    function updateQuestion(value){
        props.setAttributes({question: value})
    }

    function deleteAnswer(indexToDelete){
        //the filter return  a new array with true for each item in the array, except for the one we want to delete
        const newAnswers = props.attributes.answers.filter(function(x, index){
            return index != indexToDelete
        })
        props.setAttributes({answers: newAnswers})

        if(indexToDelete == props.attributes.correctAnswer){
            props.setAttributes({correctAnswer: undefined}) //If I delete my correctAnswer, I want set undefined again in correctAnswer
        } else if(indexToDelete < props.attributes.correctAnswer) {
            props.setAttributes({
              correctAnswer: props.attributes.correctAnswer - 1 //when I'm deleting an answer which has a smaller index number than the correct one, the correct answer will be shifted to the subsequent item in the array because of the new order. To resolve this add this elseif 
            });
        }
    }

    function markAsCorrect(index){
        props.setAttributes({correctAnswer: index})
    }

    return ( //our JSX:
        <div className="paying-attention-edit-block">
            <TextControl style={{fontSize: "20px"}} label="Question: " value={props.attributes.question} onChange={updateQuestion}></TextControl>
            <p style={{fontSize: "13px", margin: "20px 0px 8px 0px"}} >Answers: </p>
            {props.attributes.answers.map(function (answer, index){  //map will see my array, each element
                return (
                    <Flex>
                        <FlexBlock>
                            <TextControl value={answer} onChange={newValue => {
                                const newAnswers = props.attributes.answers.concat([]) //Created a copy of array
                                newAnswers[index] = newValue
                                props.setAttributes({answers: newAnswers})
                            }} 
                            autoFocus={answer == undefined} ></TextControl>
                        </FlexBlock>
                        <FlexItem>
                            <Button onClick={() => markAsCorrect(index) }>
                                <Icon  className="mark-as-correct" icon={props.attributes.correctAnswer == index ? "star-filled" : "star-empty"}></Icon>
                            </Button>
                        </FlexItem>
                        <FlexItem>
                            <Button variant="link" className="attention-delete" onClick={() => deleteAnswer(index)}>
                                Delete
                            </Button>
                        </FlexItem>
                    </Flex>
                )
            })}
            <Button variant="primary" onClick={() => {
                props.setAttributes({answers: props.attributes.answers.concat([undefined])}) //Set undefined to make my autofocus on this field when creating my TextControl
            }}> Add another answer</Button>
        </div>
    )
}

//--------------------------
// VERSION OF MY INDEX.PHP BEFORE I CHANGE TO CALL MY FILES THAT CREATE MY BLOCK IN MY FRONTEND
//---------------------------
// <?php

// /*
//     Plugin Name: Quiz - Are you paying attention
//     Description: Give your readers a multiple choice question!
//     Version: 1.0
//     Author: Brad
//     Author URI: https://udemy.com
// */

// if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly to prevent people from using a url to open that specific file

// class AreYouPayingAttention {
//     function __construct() {
//         // add_action('enqueue_block_editor_assets', array($this, 'adminAssets')); //I'm not going to use this anymore because I'm going to bring my rendering to PHP instead of calling it through JS
//         add_action('init', array($this, 'adminAssetsPhp'));
//     }

//     #---- I only used this while my return was in JS, but now my PHP does my rendering
//     // function adminAssets() {
//     //     wp_enqueue_script(
//     //         'ournewblocktype', //Name to identify this script, slug
//     //         plugin_dir_url(__FILE__) . 'build/index.js', //path to my js file
//     //         ['wp-blocks', 'wp-element']//list of dependencies that need to be loaded before my js
//     //     );
//     // }
//     #----

//     #Register my block here with the same slug of my JS
//     function adminAssetsPhp() {
//         wp_register_style('quizeditcss', plugin_dir_url(__FILE__) . 'build/index.css'); //here we register our css generated for our scss
//         wp_register_script(
//             'ournewblocktype', //Name to identify this script, slug
//             plugin_dir_url(__FILE__) . 'build/index.js', //path to my js file
//             ['wp-blocks', 'wp-element', 'wp-editor']//list of dependencies that need to be loaded before my js
//         );
//         register_block_type(
//             'ourplugin/are-you-paying-attention',  //The same name of my slug in JS
//             [
//                 'editor_script' => 'ournewblocktype', //name to my script of my block
//                 'editor_style' => 'quizeditcss', //called our css in our block
//                 'render_callback' => [$this, 'theHtml'] //This will call my function that render my block in front

//             ]
//         );
//     }

//     #With my PHP making my return, I can update here and in my front is updated too automatically
//     function theHtml($attributes){
//         # I can return this:
//         //return '<h2>Today all is completely' .  esc_html($attributes['skyColor']) . ' but I am ' . esc_html($attributes['grassColor']) . '.</h2>';
//         # OR this block 'ob_start': says me that all things that are in between, will be returned. The diference is only in use HTML or string concatenated with many variables
//         ob_start(); ?>
//         <h3>Today all is completely <?= esc_html($attributes['skyColor']) ?>  but I am  <?= esc_html($attributes['grassColor']) ?> !!!</h3>
//     <?php return ob_get_clean();
//     }
// }

// $areYouPayingAttention = new AreYouPayingAttention();