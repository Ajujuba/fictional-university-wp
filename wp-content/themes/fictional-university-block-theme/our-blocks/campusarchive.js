wp.blocks.registerBlockType('ourblocktheme/campusarchive', {
    title: "Fictional University Campus archive",
    edit: function(){
        return wp.element.createElement("div", {className: "our-placeholder-block"}, "Campus archive placeholder") //with this you can use React without JSX
    },
    save: function(){
        return null
    }
});