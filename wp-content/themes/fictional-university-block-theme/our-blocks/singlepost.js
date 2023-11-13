wp.blocks.registerBlockType('ourblocktheme/singlepost', {
    title: "Fictional University Single post",
    edit: function(){
        return wp.element.createElement("div", {className: "our-placeholder-block"}, "Single post placeholder") //with this you can use React without JSX
    },
    save: function(){
        return null
    }
});