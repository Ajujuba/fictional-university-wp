import "./index.scss" //This line will created our style css config from our scss
import {TextControl, Flex, FlexBlock, FlexItem, Button, Icon, PanelBody, PanelRow, ColorPicker} from "@wordpress/components"
import {InspectorControls, BlockControls, AlignmentToolbar, useBlockProps} from "@wordpress/block-editor" //our package JS is smart to search this in  our browser global scope

function ourStartFunction(){

    let locked = false; 

    //WP calls this function each and every time any of the data in the block changes, the advantage is that the data is always updated because the function is constantly called
    wp.data.subscribe(function() {
        //searches within all blocks on the page for any 'correctAnswer' set to 'undefined'
        const results = wp.data.select("core/block-editor").getBlocks().filter(function(block){
            return block.name == "ourplugin/are-you-paying-attention" && block.attributes.correctAnswer == undefined
        });
        //If the 'CorrectAnswer' property is undefined, then I want to block the page from being saved
        if(results.length && locked == false){
            locked = true; 
            wp.data.dispatch("core/editor").lockPostSaving("noanswer")
        }
        //If the 'CorrectAnswer' property is not undefined, I want to allow the page to be saved
        if(!results.length && locked){
            locked = false; 
            wp.data.dispatch("core/editor").unlockPostSaving("noanswer")
        }
    });
}

ourStartFunction();

wp.blocks.registerBlockType(
    'ourplugin/are-you-paying-attention', // slug
    {
        title: "Are you Payins Attention?", //visual title
        icon: "smiley",
        category: "common", //block category
        attributes: {
            question: {type: "string"},
            answers: {type: "array", default: [""]}, //We defined default="" because when loading the page for the first time, we can see at least 1 answer field
            correctAnswer: {type: "number", default: undefined},
            bgColor: {type: "string", default: "#EBEBEB"}, 
            theAlignment: {type: "string", default: "left"}
        },
        description: "Give your audience a chance to prove their comprehension.",
        example: {
            attributes: {
                question: "What is my name?",
                correctAnswer: 3,
                answers: ["Meowsalot", "Barksalot", "Purrsloud", "Brad"],
                theAlignment: "center",
                bgColor: "#CFE8F1"
            }
        }, //Here we create our preview
        edit: EditComponent , //Control what you see in the editor screen
        save: function (props) {
            return null; //let's remove from JS the responsibility of returning something and sending it to php, in the database we won't save anything static, we'll let php handle the values in real time
        }, //Controls what the public sees in the content
    } //configuration object 
)

function EditComponent (props) {
    const blockProps = useBlockProps({
        className: "paying-attention-edit-block" ,
        style: {backgroundColor: props.attributes.bgColor}
    }); //To use block.json we'll give ...blockprops to our div, and here we can send an object, and in that object pass our styles, and anything we pass here, WP will know how to merge that into its properties

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
        //to use block.json add here BlockProps and called {...blockProps} because whatever props that live inside blockProps,each one will be apply to this wrap element
        <div {...blockProps} >
            {/* This part is about my text aligment of the block, when the people choose, nothing change here, only in my front*/}
            <BlockControls>
                <AlignmentToolbar value={props.attributes.theAlignment} onChange={x => props.setAttributes({theAlignment: x})}></AlignmentToolbar>
            </BlockControls>
            {/* Our adm tab of the block in admin page - If we used this sintax like here, WP knows exactly to do with this */}
            <InspectorControls>
                <PanelBody title="Background Color: " initialOpen={true}>
                    <PanelRow> 
                        {/* here I am creating a ColorPicker component that will open my color palette, by default it is gray, and as I already pass the attribute, if it changes it already saves what I choose in the DB */}
                        <ColorPicker color={props.attributes.bgColor} onChangeComplete={x => props.setAttributes({bgColor: x.hex})}></ColorPicker>
                    </PanelRow>
                </PanelBody>
            </InspectorControls>
            {/* Our block inside the admin page */}
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