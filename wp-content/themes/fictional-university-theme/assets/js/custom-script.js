document.addEventListener('DOMContentLoaded', function() {
    var filterCheckbox = document.getElementById('filterCheckbox');
    var resultsDiv = document.getElementById('events-results');
    var nextPage = 1;
    var prevPage = null;
    var maxPages = 1;

    console.log(customScriptData.admin_ajax_url);

    if(filterCheckbox && resultsDiv){

        //Function to load events based on filter and page
        function loadEvents(filter, page) {
            var formData = new FormData();
            formData.append('filterCheck', filter);
            formData.append('page', page);

            fetch(customScriptData.admin_ajax_url + '?action=custom_event_filter', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                resultsDiv.innerHTML = data;
                updatePaginationButtons();
            });
        }

        // Add an initial AJAX call when the page loadsa
        loadEvents('venir', nextPage);

        filterCheckbox.addEventListener('change', function() {
            // Determine filter based on switch state
            var selectedFilter = this.checked ? 'passe' : 'venir';
            nextPage = 1; // Restarts the page when changing the filter
            prevPage = null; //Reset the previous page
            loadEvents(selectedFilter, nextPage);
        });

        //Add a listener for the forward or back buttons on pages
        document.addEventListener('click', function(event) {
            if (event.target.classList.contains('load-more-button')) {
                prevPage = nextPage;
                nextPage++;
                var selectedFilter = filterCheckbox.checked ? 'passe' : 'venir';
                loadEvents(selectedFilter, nextPage);
            } else if (event.target.classList.contains('load-prev-button')) {
                nextPage = prevPage;
                prevPage = (nextPage > 1) ? nextPage - 1 : null;
                var selectedFilter = filterCheckbox.checked ? 'passe' : 'venir';
                loadEvents(selectedFilter, nextPage);
            } else if (event.target.classList.contains('pagination-buttons')) {
                // If you click on a numbered page, it updates the 'current' class
                nextPage = parseInt(event.target.dataset.page);
                prevPage = (nextPage > 1) ? nextPage - 1 : null;
                var selectedFilter = filterCheckbox.checked ? 'passe' : 'venir';
                loadEvents(selectedFilter, nextPage);
            }
        });

        // Function to update the visibility of pagination buttons
        function updatePaginationButtons() {
            var paginationButtons = document.querySelectorAll('.pagination-buttons');
            var loadMoreButton = document.querySelector('.load-more-button');
            var loadPrevButton = document.querySelector('.load-prev-button');

            maxPages = paginationButtons.length;
            if(loadMoreButton){
                if (nextPage === 1) {
                    loadPrevButton.style.visibility = 'hidden';
                } else {
                    loadPrevButton.style.visibility = 'visible';
                }
    
                if (nextPage >= maxPages) {
                    loadMoreButton.style.visibility = 'hidden';
                } else {
                    loadMoreButton.style.visibility = 'visible';
                }
            }

            paginationButtons.forEach(function(button) {
                var pageNumber = parseInt(button.dataset.page);
                var selectedFilter = filterCheckbox.checked ? 'passe' : 'venir';

                if (pageNumber === nextPage) {
                    button.classList.add('current');
                } else {
                    button.classList.remove('current');
                }
            });
        }

        // Add class 'current' to page 1 initially
        var page1Button = document.querySelector('.pagination-buttons[data-page="1"]');
        if (page1Button) {
            page1Button.classList.add('current');
        }
    }

});