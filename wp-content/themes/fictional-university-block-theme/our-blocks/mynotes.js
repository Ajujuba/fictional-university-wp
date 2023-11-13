wp.blocks.registerBlockType('ourblocktheme/mynotes', {
    title: "Fictional University My notes",
    edit: function(){
        return wp.element.createElement("div", {className: "our-placeholder-block"}, "My notes placeholder") //with this you can use React without JSX
    },
    save: function(){
        return null
    }
});