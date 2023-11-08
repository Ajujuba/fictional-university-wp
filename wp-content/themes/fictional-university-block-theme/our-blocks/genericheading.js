import {ToolbarGroup, ToolbarButton} from "@wordpress/components" //Used to create buttons ang tools on the block toolbar
import {RichText, BlockControls} from "@wordpress/block-editor" // Add rich Text field and Block controls

wp.blocks.registerBlockType('ourblocktheme/genericheading', {
    title: "Generic Heading",
    attributes: {
        text: {type: "string"},
        size: {type: "string", default: "large"},
    },
    edit: EditComponent,
    save: SaveComponent
});

function EditComponent(props){
    //updates the block's text attribute with new text entered by the user, allowing the text to be updated and reflected in the block as the user makes changes. This is part of the WordPress Block Editor editing and responsiveness engine.
    function handleTextChange(x){
        props.setAttributes({text: x})
    }
    
    return ( //JSX
        <>
            <BlockControls> {/* used to add block controls such as buttons to change header size */}
                <ToolbarGroup> {/* Group of buttons on the toolbar */}
                    {/* isPressed={props.attributes.size === "X"}: Defines whether the "X" button is pressed based on the value of the size attribute. onClick={() => props.setAttributes({ size: "X" })}: When the "X" button is clicked, it calls the setAttributes function to set the value of the size attribute to "X".*/}
                    <ToolbarButton isPressed={props.attributes.size === "large"} onClick={() => props.setAttributes({size: "large"})}>Large</ToolbarButton>
                    <ToolbarButton isPressed={props.attributes.size === "medium"} onClick={() => props.setAttributes({size: "medium"})}>Medium</ToolbarButton>
                    <ToolbarButton isPressed={props.attributes.size === "small"} onClick={() => props.setAttributes({size: "small"})}>Small</ToolbarButton>
                </ToolbarGroup>
            </BlockControls>
            {/* used to create a rich text field for the header. It accepts the value of the text attribute, allows bold and italic formatting, and calls the handleTextChange function when the text changes. */}
            <RichText allowedFormats={["core/bold", "core/italic"]} tagName="h1" className={`headline headline--${props.attributes.size}`} value={props.attributes.text} onChange={handleTextChange} />
        </>
    )
}

// this function will save all blocks there are inside my Banner block
function SaveComponent(props){
    //returns the HTML tag based on the value of the size attribute
    function createTagName(){
        switch(props.attributes.size){
            case "large":
                return "h1"
            case "medium":
                return "h2"
            case "small":
                return "h3"
        }
    }
    //RichText.Content is used to render header text in saved content. It receives the HTML tag returned by the createTagName function and the value of the text attribute.
    return <RichText.Content tagName={createTagName()} value={props.attributes.text} className={`headline headline--${props.attributes.size}`}  /> 
}

// --------------- NOTE:
// On onChange={handelTextChange} we only only want to reference the function to run WHEN the onChange event triggers. So on change run the function handelTextChange .
// On tagName={createTagName()} we want the function to run the immediately, and not just referrence it when the element loads, so we add the open and closing parentheses at the end