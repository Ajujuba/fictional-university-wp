wp.blocks.registerBlockType('ourblocktheme/singleevent', {
    title: "Fictional University Single Event ",
    edit: function(){
        return wp.element.createElement("div", {className: "our-placeholder-block"}, "Single Event placeholder") //with this you can use React without JSX
    },
    save: function(){
        return null
    }
});