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
            answers: {type: "array", default: ["orange", 'blue', 'red']} //We defined default="" because when loading the page for the first time, we can see at least 1 answer field
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
                            <Button>
                                <Icon  className="mark-as-correct" icon="star-empty"></Icon>
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