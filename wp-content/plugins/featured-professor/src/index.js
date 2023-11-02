import "./index.scss"
import {useSelect} from "@wordpress/data"

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
  const allProfs = useSelect(select => {
    return select("core").getEntityRecords("postType", "professor", {per_page: -1})
  }); //I'm getting the professors' data here
  console.log(allProfs)

  //as this solution of getting the teachers takes a few milliseconds to return a response, if the user accesses it without having loaded the block it appears as 'Loading...' 
  if(allProfs == undefined){
    return <p>Loading...</p>
  } 

  //and when it has allProfs defined, it returns the real block to me
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
      <div>
        The HTML preview of the selected professor will appear here.
      </div>
    </div>
  )
}