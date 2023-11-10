wp.blocks.registerBlockType('ourblocktheme/header', {
    title: "Fictional University Header",
    edit: function(){
        return wp.element.createElement("div", {className: "our-placeholder-block"}, "Header placeholder") //with this you can use React without JSX
    },
    save: function(){
        return null
    }
});