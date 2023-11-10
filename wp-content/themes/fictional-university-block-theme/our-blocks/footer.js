wp.blocks.registerBlockType('ourblocktheme/footer', {
    title: "Fictional University Footer",
    edit: function(){
        return wp.element.createElement("div", {className: "our-placeholder-block"}, "Footer placeholder") //with this you can use React without JSX
    },
    save: function(){
        return null
    }
});