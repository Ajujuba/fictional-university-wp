import $ from 'jquery';

class Like{
    constructor(){
        this.events();
    }

    events(){
        $('.like-box').on('click', this.ourClickDispatcher.bind(this));
    }


    //methods

    //This method will define if I want like or dislike a professor
    ourClickDispatcher(e){
        var currentLikeBox = $(e.target).closest('.like-box'); //identifies and stores the closest ancestral HTML element with the "like-box" class ensuring that it is always pointing to the right box

        if(currentLikeBox.attr('data-exists') == 'yes'){ //I'm going to use attr('data-exists') instead of data('exists') because this way I can listen to the element live with each interaction and not just when the page is loaded 
            this.deleteLike(currentLikeBox);
        }else{
            this.createLike(currentLikeBox);
        }
    }

    createLike(currentLikeBox){
        $.ajax({
            beforeSend: (xhr) => {
                xhr.setRequestHeader('X-WP-Nonce', universityData.nonce); //makes WP validate the nonce code
            },
            url: universityData.root_url + '/index.php/wp-json/university/v1/manageLike',
            type: 'POST',
            data: {
                'professorId': currentLikeBox.data('professor') //Will send my data-professor of single-professor.php
            } ,
            success: (response) => {
                currentLikeBox.attr('data-exists', 'yes'); //this will make my heart full
                var likeCount = parseInt(currentLikeBox.find('.like-count').html(), 10); //get my value of hearts count 
                likeCount++; //up 1
                currentLikeBox.find('.like-count').html(likeCount);
                currentLikeBox.attr('data-like', response); //when create a new post the response return my post id
                console.log(response)
            },
            error: (response) => {
                console.log(response)
            }
        });
    }

    deleteLike(currentLikeBox){
        $.ajax({
            beforeSend: (xhr) => {
                xhr.setRequestHeader('X-WP-Nonce', universityData.nonce); //makes WP validate the nonce code
            },
            url: universityData.root_url + '/index.php/wp-json/university/v1/manageLike',
            type: 'DELETE' ,
            data: {
                'like': currentLikeBox.attr('data-like') //Will send my data-like with the number of the post I want delete to single-professor.php
            } ,
            success: (response) => {
                currentLikeBox.attr('data-exists', 'no'); //this will make my heart empty
                var likeCount = parseInt(currentLikeBox.find('.like-count').html(), 10); //get my value of hearts count 
                likeCount--; //down 1
                currentLikeBox.find('.like-count').html(likeCount);
                currentLikeBox.attr('data-like', ''); //when delete a like I'll return ''
                console.log(response)
            },
            error: (response) => {
                console.log(response)
            }
        });
    }
}

export default Like