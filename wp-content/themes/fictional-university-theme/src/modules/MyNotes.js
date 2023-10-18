import $ from 'jquery';

class MyNotes{
    constructor(){
        this.events()
    }

    events(){
        $(".delete-note").on("click", this.deleteNote);
        $(".edit-note").on("click", this.editNote.bind(this)); //bind(this) is important here, otherwise js will modify the value of 'this' and set the equal whatever object that has been clicked on

    }

    //My methods:
    deleteNote(e){
        var thisNote = $(e.target).parents('li'); //here I will get the data-id value that I put in the <li> of page-my-notes.php
        $.ajax({
            beforeSend: (xhr) => {
                xhr.setRequestHeader('X-WP-Nonce', universityData.nonce); //makes WP validate the nonce code
            },
            url: universityData.root_url + '/index.php/wp-json/wp/v2/note/' + thisNote.data('id'), //this line send my request for WP REST API default
            type: 'DELETE',
            success: (response) => {
                thisNote.slideUp();
                console.log('deleted');
                console.log(response);
            },
            error: (response) => {
                console.log('not deleted');
                console.log(response);
            }
        });
    }

    editNote(e){
        var thisNote = $(e.target).parents('li'); //here I will get the data-id value that I put in the <li> of page-my-notes.php
        if(thisNote.data('state') == "editable"){
            this.makeNoteReadonly(thisNote); // makes the note uneditable
        }else{
            this.makeNoteEditable(thisNote); //makes the note editable
        }
    }

    makeNoteEditable(thisNote){
        thisNote.find('.edit-note').html('<i class="fa fa-times" area-hidden="true"></i>Cancel'); // shows the line to cancel edit
        thisNote.find(".note-title-field, .note-body-field").removeAttr('readonly').addClass('note-active-field'); //get only my fields with this classes and remove readonly attribute and add a new class
        thisNote.find('.update-note').addClass('update-note--visible'); //let my save button appears
        thisNote.data("state", "editable"); //defines state=editable to my if to be true when I want to edit
    }

    makeNoteReadonly(thisNote){
        thisNote.find('.edit-note').html('<i class="fa fa-pencil" area-hidden="true"></i>Edit'); // shows the line to edit
        thisNote.find(".note-title-field, .note-body-field").attr('readonly',"readonly").removeClass('note-active-field'); //get only my fields with this classes and add readonly attribute and remove a class 'note-active-field'
        thisNote.find('.update-note').removeClass('update-note--visible'); //hide my save button 
        thisNote.data("state", "cancel"); //defines state=cancel to my if to be false when I don't want to edit

    }
}


export default MyNotes;