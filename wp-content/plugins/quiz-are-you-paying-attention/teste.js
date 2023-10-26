// THIS FILE IS AN EXEMPLE ONLY, THE OFICIAL CODE IS IN SRC/INDEX.JS


// --------------------------------------------
// STANDARD APPROACH TO CREATE AN ELEMENT, BUT IT BECOMES DIFFICULT TO CREATE A BLOCK OF ELEMENTS, BUT THIS IS THE WP STANDARD
// ----------------------------------------------

wp.blocks.registerBlockType(
    'ourplugin/are-you-paying-attention', //name for our block type, slug
    {
        title: "Are you Payins Attention?", //visual title, that people will see 
        icon: "smiley",
        category: "common", //block category
        edit: function () {
            return wp.element.createElement(
                'h3', //type of HTML element you want to create
                null, //any args/propertys that describe you element, like classes or inline style
                'Hello is from editor screen' //the cildren or content for this element
            );
        }, //Control what you see in the editor screen
        save: function () {
            return wp.element.createElement('h1',null, 'Hello is front end screen'); // is not that efficient, so we will use JSX
        } //Controls what the public sees in the content
    } //configuration object - here we need use exact property names for WP to identify
)


// --------------------------------------------
// VERSION OF THE CODE WITH CREATING A BLOCK WITH INPUTS AND DISPLAYING THEM ON THE USER'S SCREEN: BUT THIS WAY IS 'STATIC', RECOMMENDED FOR WHEN YOU WILL NOT UPDATE YOUR BLOCK'S HTML OFTEN AND WON'T HAVE IT IN MANY DIFFERENT LOCATIONS. BECAUSE THIS WAY EVERYTHING WORKS, BUT WHENEVER YOU UPDATE THE BLOC CODE YOU NEED TO GO TO THE PAGES, AND SAVE THEM AGAIN FOR THEM TO UPDATE (HERE I USE JSX)
// ----------------------------------------------

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
            return (
                <h6> Today the sky is absoluty {props.attributes.skyColor} and the grass us {props.attributes.grassColor}</h6>
            )
        }, //Controls what the public sees in the content
        deprecated: [
            {
                attributes: {
                    skyColor: {type: "string"},
                    grassColor: {type: "string"}
                },
                save: function (props) {
                    return (
                        <h3> Today the sky is completly {props.attributes.skyColor} and the grass us {props.attributes.grassColor}</h3>
                        )
                }  
            },
            {
                attributes: {
                    skyColor: {type: "string"},
                    grassColor: {type: "string"}
                },
                save: function (props) {
                    return (
                        <p> Today the sky is {props.attributes.skyColor} and the grass us {props.attributes.grassColor}</p>
                    )
                } 
            }
        ]
    } //configuration object 
)