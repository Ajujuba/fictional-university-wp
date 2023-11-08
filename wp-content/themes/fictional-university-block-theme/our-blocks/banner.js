import {InnerBlocks} from "@wordpress/block-editor" //This module allows blocks inside other blocks

wp.blocks.registerBlockType('ourblocktheme/banner', {
    title: "Banner",
    edit: EditComponent,
    save: SaveComponent
});

//This fn make my content editable in mt editor
function EditComponent(){
    return (
        <div className="page-banner">
            {/* I needed use the full path to image, with relative path doesn't worked */}
            <div className="page-banner__bg-image" style={{backgroundImage: "url('http://localhost/fictional-university-wp/wp-content/themes/fictional-university-block-theme/images/library-hero.jpg')" }}></div>
            <div className="page-banner__content container t-center c-white">
               {/* <InnerBlocks allowedBlocks={["core/paragraph", "core/heading", "core/list"]} />  If We want allow only some core blocks we can set this like here */}
               <InnerBlocks allowedBlocks={["ourblocktheme/genericheading", "ourblocktheme/genericbutton"]} />
            </div>
        </div>
    );
}

// this function will save all blocks there are inside my Banner block to show in my frontend
function SaveComponent(){
    return (
        <div className="page-banner">
            {/* I needed use the full path to image, with relative path doesn't worked */}
            <div className="page-banner__bg-image" style={{backgroundImage: "url('http://localhost/fictional-university-wp/wp-content/themes/fictional-university-block-theme/images/library-hero.jpg')" }}></div>
            <div className="page-banner__content container t-center c-white">
                <InnerBlocks.Content />
            </div>
        </div>
    );
}