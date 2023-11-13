wp.blocks.registerBlockType('ourblocktheme/pastevents', {
    title: "Fictional University Past events",
    edit: function(){
        return wp.element.createElement("div", {className: "our-placeholder-block"}, "Past events placeholder") //with this you can use React without JSX
    },
    save: function(){
        return null
    }
});