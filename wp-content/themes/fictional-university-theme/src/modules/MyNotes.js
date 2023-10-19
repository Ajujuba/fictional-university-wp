import $ from 'jquery';

class MyNotes{
    constructor(){
        this.events()
    }

    events(){
        $("#my-notes").on("click", '.delete-note',this.deleteNote);
        $("#my-notes").on("click", '.edit-note',this.editNote.bind(this)); //control the status of my note - bind(this) is important here, otherwise js will modify the value of 'this' and set the equal whatever object that has been clicked on
        $("#my-notes").on("click", '.update-note',this.updateNote.bind(this)); //makes my update
        $(".submit-note").on("click", this.createNote.bind(this)); //makes my insert

    }

    //My methods:
    deleteNote(e){
        var thisNote = $(e.target).parents('li'); //here I will get the data-id value that I put in the <li> of page-my-notes.php
        $.ajax({
            beforeSend: (xhr) => {
                xhr.setRequestHeader('X-WP-Nonce', universityData.nonce); //makes WP validate the nonce code
            },
            url: universityData.root_url + '/index.php/wp-json/wp/v2/note/' + thisNote.data('id'), //this line send my request for WP REST API default to delete my note
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

    updateNote(e){
        var thisNote = $(e.target).parents('li'); //here I will get the data-id value that I put in the <li> of page-my-notes.php
        
        var ourUpdatedPost = {
            // The WP REST API is looking for specific property names, so if you can change the title, give 'title', if you want edit de body, give 'content'
            'title': thisNote.find('.note-title-field').val(),
            'content': thisNote.find('.note-body-field').val(),
        }

        $.ajax({
            beforeSend: (xhr) => {
                xhr.setRequestHeader('X-WP-Nonce', universityData.nonce); //makes WP validate the nonce code
            },
            url: universityData.root_url + '/index.php/wp-json/wp/v2/note/' + thisNote.data('id'), //this line send my request for WP REST API default to edit my note
            type: 'POST',
            data: ourUpdatedPost,
            success: (response) => {
                this.makeNoteReadonly(thisNote);
                console.log('updated');
                console.log(response);
            },
            error: (response) => {
                console.log('not updated');
                console.log(response);
            }
        });
    }

    createNote(e){
        var ourNewPost = {
            // The WP REST API is looking for specific property names, so if you can change the title, give 'title', if you want edit de body, give 'content'
            'title': $(".new-note-title").val(), // defines my title according what I wrote
            'content': $(".new-note-body").val(), // defines my content according what I wrote
            'status': 'publish' //This line define my post like publish, so will appears in real time. If I changed it to private so that only the post owner sees this post, but the front is not confiable, so did this change in my backend
        }

        $.ajax({
            beforeSend: (xhr) => {
                xhr.setRequestHeader('X-WP-Nonce', universityData.nonce); //makes WP validate the nonce code
            },
            url: universityData.root_url + '/index.php/wp-json/wp/v2/note/', //this line send my request for WP REST API default to create a new note
            type: 'POST',
            data: ourNewPost,
            success: (response) => {
                $('.new-note-title, .new-note-body').val('');
                $(`
                    <li data-id="${response.id}">
                        <input readonly class="note-title-field" value="${response.title.raw}">
                        <span class="edit-note"><i class="fa fa-pencil" area-hidden="true"></i>Edit</span>
                        <span class="delete-note"><i class="fa fa-trash-o" area-hidden="true"></i>Delete</span>
                        <textarea readonly class="note-body-field">${response.content.raw}</textarea>
                        <span class="update-note btn btn--blue btn--small"><i class="fa fa-arrow-right" area-hidden="true"></i>Save</span>
                    </li>
                `).prependTo('#my-notes').hide().slideDown();
                console.log('created');
                console.log(response);
            },
            error: (response) => {
                console.log('not created');
                console.log(response);
            }
        });
    }
}


export default MyNotes;