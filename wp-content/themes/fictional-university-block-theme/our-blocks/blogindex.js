wp.blocks.registerBlockType('ourblocktheme/blogindex', {
    title: "Fictional University Blog index",
    edit: function(){
        return wp.element.createElement("div", {className: "our-placeholder-block"}, "Blog index placeholder") //with this you can use React without JSX
    },
    save: function(){
        return null
    }
});