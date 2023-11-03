import "./index.scss"
import {useSelect} from "@wordpress/data" //This imports the useSelect function from the WordPress state management package. This function is used to fetch data from the WordPress global state.
import {useState, useEffect} from "react" //This imports the useState and useEffect functions from React. They are used to manage local state and side effects in the React component.
import apiFetch from "@wordpress/api-fetch" //This imports the apiFetch function, which is used to make requests to the WordPress REST API.

wp.blocks.registerBlockType("ourplugin/featured-professor", {
  title: "Professor Callout",
  description: "Include a short description and link to a professor of your choice",
  icon: "welcome-learn-more",
  category: "common",
  attributes: {
    profId: {type: "string"}
  },
  edit: EditComponent,
  save: function () {
    return null
  }
})

function EditComponent(props) {
  const [thePreview, setThePreview] = useState("") ; // Local state is managed with useState, and the initial value of thePreview is an empty string.
  
  //Fired when the props.attributes.profId property changes, and is used to fetch and update the teacher's HTML based on this ID
  useEffect(() => {
    updateTheMeta();
    async function go(){
      const response = await apiFetch({ // call to the WordPress REST API using apiFetch. It is looking for the teacher's HTML based on the ID provided in props.attributes.profId.
        path: `featuredProfessor/v1/getHTML?profId=${props.attributes.profId}`,
        method: "GET",
      })
      setThePreview(response) //updates thePreview local state with the HTML returned by the API.
    }
    go()
  }, [props.attributes.profId]); //The useEffect function is used to make a request to the WordPress REST API to get the teacher's HTML based on the profId provided in the block properties.

  //is executed once, when the component is unmounted, and is used to update the metadata of the "Featured Professor" block before it is unmounted. This ensures that metadata is always up to date in WordPress. + (continue in the next line)
  //here, you return a function that will be executed when the component is disassembled. React automatically calls this function when the component is removed from the DOM. This is a fundamental part of the React components lifecycle.
  useEffect(() => {
    return () => {
      updateTheMeta(); //This returns a function that is executed when the component is unmounted. In this case, the updateTheMeta function is called when the component is about to be unmounted, which updates the metadata before the component is removed.
    }
  },[]);// The absence of dependencies ([]) means that this callback function will be called only once, when the component is mounted, and again when the component is unmounted.

  //This function will update our meta, creating a new meta:
  function updateTheMeta(){
    const profsForMeta = wp.data.select("core/block-editor")
      .getBlocks()
      .filter(x => x.name == "ourplugin/featured-professor") // Filter only blocks of type "ourplugin/featured-professor"
      .map(x => x.attributes.profId) // Map to get the "profId" attribute of each block
      .filter((x, index, arr) => {
        return arr.indexOf(x) == index // Remove duplicate values
      })
    console.log(profsForMeta)

    // Trigger an action to update the post metadata
    wp.data.dispatch("core/editor").editPost({meta: {featuredprofessor: profsForMeta}})
  }

  const allProfs = useSelect(select => {
    return select("core").getEntityRecords("postType", "professor", {per_page: -1})
  }); //I'm getting the professors' data here
  console.log(allProfs)

  //as this solution of getting the teachers takes a few milliseconds to return a response, if the user accesses it without having loaded the block it appears as 'Loading...' 
  if(allProfs == undefined){
    return <p>Loading...</p>
  } 

  //and when it has allProfs defined, it returns the real block to me where the user can choose a teacher and a <div> where the selected teacher's HTML is rendered using dangerouslySetInnerHTML.
  return (
    <div className="featured-professor-wrapper">
      <div className="professor-select-container">
        <select onChange={ e => props.setAttributes({profId: e.target.value})}>
          <option value="Select a professor">Select a professor</option>
          {allProfs.map(prof => {
            return (
              <option value={prof.id} selected={props.attributes.profId == prof.id}> {prof.title.rendered} </option>
            )
          })}
        </select>
      </div>
      <div dangerouslySetInnerHTML={{__html: thePreview}}>
      </div>
    </div>
  )
}