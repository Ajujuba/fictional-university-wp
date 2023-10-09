import $ from 'jquery';

class Search{
    // 1. describe and create/initiate our object
    constructor(){
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
    openOverlay(){
        this.searchOverlay.addClass("search-overlay--active");
        $('body').addClass('body-no-scroll');
        this.isOverlayOpen = true;
    }
    closeOverlay(){
        this.searchOverlay.removeClass("search-overlay--active");
        $('body').removeClass('body-no-scroll');
        this.isOverlayOpen = false;
    }
    keyPressDispatcher(event){
        if(event.keyCode == 83 && !this.isOverlayOpen && !$('input, textarea').is(':focus')){
            this.openOverlay();
        }
        if(event.keyCode == 27 && this.isOverlayOpen){
            this.closeOverlay();
        }
    }
    typingLogic(){
        if(this.searchField.val() != this.previousValue){
            clearTimeout(this.typingTimer);
            if(this.searchField.val() ){
                if(!this.isSpinnerVisible){
                    this.resultsDiv.html('<div class="spinner-loader"></div>');
                    this.isSpinnerVisible = true;
                }
                this.typingTimer = setTimeout(this.getResults.bind(this), 2000);
            }else{
                this.resultsDiv.html(' ');
                this.isSpinnerVisible = false;
            }
        }

        this.previousValue = this.searchField.val();
    }

    getResults(){
        this.resultsDiv.html('imagine real result');
        this.isSpinnerVisible = false;
    }

}

export default Search //with this line can me use this file for import in my index.js