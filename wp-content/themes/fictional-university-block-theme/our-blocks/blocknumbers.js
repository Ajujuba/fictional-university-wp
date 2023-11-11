wp.blocks.registerBlockType('ourblocktheme/blocknumbers', {
    title: "Fictional University BlockNumbers",
    edit: function(){
        return wp.element.createElement("div", {className: "our-placeholder-block"}, "BlockNumbers placeholder") //with this you can use React without JSX
    },
    save: function(){
        return null
    }
});