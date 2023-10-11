import $ from 'jquery';

class Search{
    // 1. describe and create/initiate our object
    constructor(){
        this.addSearchHTML();
        this.openButton = $('.js-search-trigger');
        this.closeButton = $('.search-overlay__close');
        this.searchOverlay = $('.search-overlay');
        this.isOverlayOpen = false;
        this.searchField = $('#js-search-term');
        this.typingTimer;
        this.resultsDiv = $('#search-overlay__results');
        this.isSpinnerVisible = false;
        this.previousValue;
        this.events();
    }

    // 2. events
    events(){
        this.openButton.on('click', this.openOverlay.bind(this));
        this.closeButton.on('click', this.closeOverlay.bind(this));
        $(document).on('keydown', this.keyPressDispatcher.bind(this)); // using keydown because if the user press for a long time my search will open fast, in the first moment
        this.searchField.on('keydown', this.typingLogic.bind(this));
    }

    // 3. methods

    //open my search screen
    openOverlay(){
        this.searchOverlay.addClass("search-overlay--active");
        $('body').addClass('body-no-scroll');
        this.searchField.val(''); //open my input = ''
        this.resultsDiv.html(''); //open my div = ''
        setTimeout(() => this.searchField.focus(), 301); //making the cursor focused on the field after 301miliseg which is the time my animation loads the search screen
        this.isOverlayOpen = true;
    }

    //close my search screen
    closeOverlay(){
        this.searchOverlay.removeClass("search-overlay--active");
        $('body').removeClass('body-no-scroll');
        this.isOverlayOpen = false;
    }

    //open and close my search screen with ESC and S
    keyPressDispatcher(event){
        if(event.keyCode == 83 && !this.isOverlayOpen && !$('input, textarea').is(':focus')){
            this.openOverlay();
        }
        if(event.keyCode == 27 && this.isOverlayOpen){
            this.closeOverlay();
        }
    }

    //this code make my icon loader appear and hidden. And get what my user write
    typingLogic(){
        if(this.searchField.val() != this.previousValue){
            clearTimeout(this.typingTimer);
            if(this.searchField.val() ){
                if(!this.isSpinnerVisible){
                    this.resultsDiv.html('<div class="spinner-loader"></div>');
                    this.isSpinnerVisible = true;
                }
                this.typingTimer = setTimeout(this.getResults.bind(this), 1000);
            }else{
                this.resultsDiv.html(' ');
                this.isSpinnerVisible = false;
            }
        }

        this.previousValue = this.searchField.val();
    }

    //get data from WP json according to what the user searched for and returns in my screen
    getResults(){

        //asynchronous search
        $.when(
            $.getJSON(universityData.root_url + '/index.php/wp-json/wp/v2/posts?search=' + this.searchField.val()),
            $.getJSON(universityData.root_url + '/index.php/wp-json/wp/v2/pages?search=' + this.searchField.val()),
            $.getJSON(universityData.root_url + '/index.php/wp-json/wp/v2/event?search=' + this.searchField.val()),
            $.getJSON(universityData.root_url + '/index.php/wp-json/wp/v2/program?search=' + this.searchField.val()),
            $.getJSON(universityData.root_url + '/index.php/wp-json/wp/v2/campus?search=' + this.searchField.val())

        ).then((posts, pages, events, programs, campuses) => {
            var combineResults = posts[0].concat(pages[0], events[0], programs[0], campuses[0]);
            this.resultsDiv.html(`
                <h2 class="search-overlay__section-title">General Information</h2>
                ${ combineResults.length ? '<ul class="link-list min-list">' : '<p>No general informations matches that search. </p>'}
                ${
                    combineResults.map(
                        //.join('') concatenate the elements of an array into a single string, in this case The elements will simply be concatenated next to each other without any additional space between them.
                        item => ` <li><a href="${item.link}">${item.title.rendered}</a> ${item.type == 'post' ? `by ${item.authorName}` : '' }</li>`
                    ).join('')
                }
                ${combineResults.length ? '</ul>' : ''}
            `);
        }, () => {
            this.resultsDiv.html('<p>Unexpected error; please try again.</p>');
        });

        // synchronous search
        // $.getJSON(universityData.root_url + '/index.php/wp-json/wp/v2/posts?search=' + this.searchField.val(), posts => { 
        //     $.getJSON(universityData.root_url + '/index.php/wp-json/wp/v2/pages?search=' + this.searchField.val(), pages => {
        //         var combineResults = posts.concat(pages);
        //         this.resultsDiv.html(`
        //             <h2 class="search-overlay__section-title">General Information</h2>
        //             ${ combineResults.length ? '<ul class="link-list min-list">' : '<p>No general informations matches that search. </p>'}
        //             ${
        //                 combineResults.map(
        //                     //.join('') concatenate the elements of an array into a single string, in this case The elements will simply be concatenated next to each other without any additional space between them.
        //                     item => ` <li><a href="${item.link}">${item.title.rendered}</a></li>`
        //                 ).join('')
        //             }
        //             ${combineResults.length ? '</ul>' : ''}
        //         `);
        //     });
        // });
        this.isSpinnerVisible = false;
    }

    addSearchHTML(){
        $('body').append(`
            <div class="search-overlay">
                <div class="search-overlay__top">
                    <div class="container">
                        <i class="fa fa-search search-overlay__icon" aria-hidden="true"></i>
                        <input type="text" class="search-term" placeholder="What are you looking for?" id="js-search-term">
                        <i class="fa fa-window-close search-overlay__close" aria-hidden="true"></i>
                    </div>
                </div>
                <div class="container">
                    <div id="search-overlay__results">

                    </div>
                </div>
            </div>
        `);
    }

}

export default Search //with this line can me use this file for import in my index.js