import apiFetch from "@wordpress/api-fetch"
import {Button, PanelBody, PanelRow} from "@wordpress/components"
import {InnerBlocks, InspectorControls, MediaUpload, MediaUploadCheck} from "@wordpress/block-editor" //This module allows blocks inside other blocks
import {useEffect} from "@wordpress/element"

wp.blocks.registerBlockType('ourblocktheme/banner', {
    title: "Banner",
    supports: {
        align: ["full"]
    },
    attributes: {
        align: {type: "string", default: "full"},
        imgID: {type: "number"},
        imgURL: {type: "string", default: window.banner.fallbackimage} // I'm getting this param that my PHP sent
    },
    edit: EditComponent,
    save: SaveComponent
});

//This fn make my content editable in mt editor
function EditComponent(props){

    useEffect(function(){ // hook to fetch the image URL when the imgID attribute changes.
        if(props.attributes.imgID){
            async function go(){
                const response = await apiFetch({ //apiFetch function is used to make a request to the WordPress REST API to get information about the selected media (image).
                    path: `/wp/v2/media/${props.attributes.imgID}`,
                    method: 'GET'
                })
                props.setAttributes({imgURL: response.media_details.sizes.pageBanner.source_url}) //attribute is then updated with the URL of the selected image, so I can use this in my PHP
            }
            go()
        }
    }, [props.attributes.imgID]);

    //This function sets the imgID attribute when a media file is selected.
    function onFileSelect(x){
        props.setAttributes({imgID: x.id}); 
    }
    return (
        <>
            {/* Crete an area to change my background image */}
            <InspectorControls> {/** It allows you to add custom block controls in the right side panel of the block editor */}
                <PanelBody title="Background" initialOpen={true}> {/**component that creates a controls section in the inspection panel. In this case, the section has a title "Background" and is initially opened */}
                    <PanelRow> {/** creates a row of controls within a section. */}
                        <MediaUploadCheck> {/** is a component that checks whether the user has permission to upload media. It is used to wrap the <MediaUpload> component and ensure permissions are checked before displaying the upload buttons. */}
                            <MediaUpload 
                                onSelect={onFileSelect} 
                                value={props.attributes.imgID} 
                                render={({ open }) => { {/**  A render function that takes an open function to open the media selector. In the example, it renders a "Choose Image" button which, when clicked, opens the media picker. */}
                                    return <Button onClick={open} >Choose Image</Button>
                                }
                            } /> {/** allows you to upload media, such as images or files, and associate them with the block. */}
                        </MediaUploadCheck>
                    </PanelRow>
                </PanelBody>
            </InspectorControls>
            <div className="page-banner">
                {/* I needed use the full path to image, with relative path doesn't worked */}
                <div className="page-banner__bg-image" style={{backgroundImage: `url('${props.attributes.imgURL}')` }}></div>
                <div className="page-banner__content container t-center c-white">
                    {/* <InnerBlocks allowedBlocks={["core/paragraph", "core/heading", "core/list"]} />  If We want allow only some core blocks we can set this like here */}
                    <InnerBlocks allowedBlocks={["ourblocktheme/genericheading", "ourblocktheme/genericbutton"]} />
                </div>
            </div>
        </>
    );
}

// this function will save all blocks there are inside my Banner block to show in my frontend
function SaveComponent(){
    //change here to return my content, I'll make my render in my PHP
    return <InnerBlocks.Content />
}