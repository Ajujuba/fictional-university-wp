import ourColors from "../inc/ourColors"
import {link} from "@wordpress/icons" //This packege I need to install with NPM
import {ToolbarGroup, ToolbarButton, Popover, Button, PanelBody, PanelRow, ColorPalette} from "@wordpress/components" //Used to create buttons ang tools on the block toolbar
import {RichText, BlockControls, __experimentalLinkControl as LinkControl, InspectorControls, getColorObjectByColorValue} from "@wordpress/block-editor" // Add rich Text field and Block controls
import {useState} from "@wordpress/element"

wp.blocks.registerBlockType('ourblocktheme/genericbutton', {
    title: "Generic Button",
    attributes: {
        text: {type: "string"},
        size: {type: "string", default: "large"},
        linkObject: {type: "object", default: {url: ""}},
        colorName: {type: "string", default: "blue"}
    },
    edit: EditComponent,
    save: SaveComponent
});

function EditComponent(props){

    const [isLinkPickerVisible, SetIsLinkPickerVisible] = useState(false);

    //updates the block's text attribute with new text entered by the user, allowing the text to be updated and reflected in the block as the user makes changes. This is part of the WordPress Block Editor editing and responsiveness engine.
    function handleTextChange(x){
        props.setAttributes({text: x})
    }
    
    // buttonHandler function to toggle picker link visibility
    function buttonHandler(){
        SetIsLinkPickerVisible(prev => !prev);
    }

    // handleLinkchange function to update the block's 'linkObject' attribute
    function handleLinkchange(newLink){
        props.setAttributes({linkObject: newLink})
    }

    // update our colorName attribute
    function handleColorChange(colorCode){
        //from the hex value the color palette gives us, we need to find the color name
        const {name} = getColorObjectByColorValue(ourColors, colorCode)
        props.setAttributes({colorName: name})
    }

    //is looking for an object in the ourColors array whose name property matches the value of the colorName attribute passed via props. Once the object is found, it extracts the value of the color property from that object and stores it in the currentColorValue variable
    const currentColorValue = ourColors.filter(color => {
        return color.name == props.attributes.colorName 
    })[0].color

    return ( //JSX
        <>
            <BlockControls> {/* used to add block controls such as buttons to change header size */}
                <ToolbarGroup>
                    <ToolbarButton onClick={buttonHandler} icon={link} />
                </ToolbarGroup>
                <ToolbarGroup> {/* Group of buttons on the toolbar */}
                    {/* isPressed={props.attributes.size === "X"}: Defines whether the "X" button is pressed based on the value of the size attribute. onClick={() => props.setAttributes({ size: "X" })}: When the "X" button is clicked, it calls the setAttributes function to set the value of the size attribute to "X".*/}
                    <ToolbarButton isPressed={props.attributes.size === "large"} onClick={() => props.setAttributes({size: "large"})}>Large</ToolbarButton>
                    <ToolbarButton isPressed={props.attributes.size === "medium"} onClick={() => props.setAttributes({size: "medium"})}>Medium</ToolbarButton>
                    <ToolbarButton isPressed={props.attributes.size === "small"} onClick={() => props.setAttributes({size: "small"})}>Small</ToolbarButton>
                </ToolbarGroup>
            </BlockControls>
            {/*Create a Color pallet to user choose the button color  */}
            <InspectorControls>
                <PanelBody title="Color" initialOpen={true}>
                    <PanelRow>
                        <ColorPalette disableCustomColors={true} clearable={false} colors={ourColors} value={currentColorValue} onChange={handleColorChange} />
                    </PanelRow>
                </PanelBody>
            </InspectorControls>
            {/* used to create a rich text field for the header. It accepts the value of the text attribute, allows bold and italic formatting, and calls the handleTextChange function when the text changes. */}
            <RichText allowedFormats={[]} tagName="a" className={`btn btn--${props.attributes.size} btn--${props.attributes.colorName} `} value={props.attributes.text} onChange={handleTextChange} />
            {/* checks if isLinkPickerVisible is true. If true,The "link picker" allows the user to choose a link, and the "Confirm Link" button allows you to confirm the selection and hide the popover when clicked. The isLinkPickerVisible variable controls the visibility of this component based on user actions. */}
            {isLinkPickerVisible && ( 
                //onFocusOutside={() => setIsLinkPickerVisible(false)} makes my popover closes when loses focus
                <Popover position="middle center" onFocusOutside={() => setIsLinkPickerVisible(false)}>
                   <LinkControl settings={[]} value={props.attributes.linkObject} onChange={handleLinkchange} />
                   <Button variant="primary" onClick={() => SetIsLinkPickerVisible(false)} style={{display:"block", width: "100%" }} > Confirm Link </Button>
                </Popover>
            )}
        </>
    )
}

// this function will save all blocks there are inside my Banner block
function SaveComponent(props){
    return null
}