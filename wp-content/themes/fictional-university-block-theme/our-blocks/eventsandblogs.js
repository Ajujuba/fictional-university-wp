wp.blocks.registerBlockType('ourblocktheme/eventsandblogs', {
    title: "Fictional university Events and Blogs",
    edit: function(){
        return wp.element.createElement("div", {className: "our-placeholder-block"}, "Events and blogs placeholder") //with this you can use React without JSX
    },
    save: function(){
        return null
    }
});