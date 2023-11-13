wp.blocks.registerBlockType('ourblocktheme/eventarchive', {
    title: "Fictional University Event archive",
    edit: function(){
        return wp.element.createElement("div", {className: "our-placeholder-block"}, "Event archive placeholder") //with this you can use React without JSX
    },
    save: function(){
        return null
    }
});