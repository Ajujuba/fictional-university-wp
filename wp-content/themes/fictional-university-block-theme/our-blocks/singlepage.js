wp.blocks.registerBlockType('ourblocktheme/singlepage', {
    title: "Fictional University Single page",
    edit: function(){
        return wp.element.createElement("div", {className: "our-placeholder-block"}, "Single page placeholder") //with this you can use React without JSX
    },
    save: function(){
        return null
    }
});