wp.blocks.registerBlockType(
    'ourplugin/are-you-paying-attention', // slug
    {
        title: "Are you Payins Attention?", //visual title
        icon: "smiley",
        category: "common", //block category
        attributes: {
            skyColor: {type: "string"},
            grassColor: {type: "string"}
        },
        edit: function (props) {
            function updateSkyColor(event){
                props.setAttributes({skyColor: event.target.value}) //this line will set my attr in my DB with my value?
            }

            function updateGrassColor(event){
                props.setAttributes({grassColor: event.target.value})
            }

            return (
                <div>
                    <input type="test" placeholder="Sky color" value={props.attributes.skyColor} onChange={updateSkyColor}/>
                    <input type="test" placeholder="grass color" value={props.attributes.grassColor} onChange={updateGrassColor}/>

                </div>
            )
        }, //Control what you see in the editor screen
        save: function (props) {
            return null; //let's remove from JS the responsibility of returning something and sending it to php, in the database we won't save anything static, we'll let php handle the values in real time
        }, //Controls what the public sees in the content
    } //configuration object 
)