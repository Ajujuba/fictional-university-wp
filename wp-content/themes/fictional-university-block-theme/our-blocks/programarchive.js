wp.blocks.registerBlockType('ourblocktheme/programarchive', {
    title: "Fictional University Program archive",
    edit: function(){
        return wp.element.createElement("div", {className: "our-placeholder-block"}, "Program archive placeholder") //with this you can use React without JSX
    },
    save: function(){
        return null
    }
});