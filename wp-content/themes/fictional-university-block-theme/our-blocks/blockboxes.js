wp.blocks.registerBlockType('ourblocktheme/blockboxes', {
    title: "Fictional University BlockBoxes",
    edit: function(){
        return wp.element.createElement("div", {className: "our-placeholder-block"}, "BlockBoxes placeholder") //with this you can use React without JSX
    },
    save: function(){
        return null
    }
});