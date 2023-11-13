wp.blocks.registerBlockType('ourblocktheme/singleprofessor', {
    title: "Fictional University Single Professor ",
    edit: function(){
        return wp.element.createElement("div", {className: "our-placeholder-block"}, "Single Professor placeholder") //with this you can use React without JSX
    },
    save: function(){
        return null
    }
});