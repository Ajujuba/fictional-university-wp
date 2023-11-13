wp.blocks.registerBlockType('ourblocktheme/singleprogram', {
    title: "Fictional University Single Program ",
    edit: function(){
        return wp.element.createElement("div", {className: "our-placeholder-block"}, "Single Program placeholder") //with this you can use React without JSX
    },
    save: function(){
        return null
    }
});