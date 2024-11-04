document.addEventListener('DOMContentLoaded', function() {
    var filterCheckbox = document.getElementById('filterCheckbox');
    var resultsDiv = document.getElementById('events-results');
    var nextPage = 1;
    var prevPage = null;
    var maxPages = 1;
    var locationFilter = 'all';
    var monthFilter = 'all';

    console.log(customScriptData.admin_ajax_url);
    console.log(customScriptData.theme_path);

    if(filterCheckbox && resultsDiv){

        //Function to load events based on filter and page
        function loadEvents(filter, page, locationFilter, monthFilter) {
            var formData = new FormData();
            formData.append('filterCheck', filter);
            formData.append('page', page);
            formData.append('golf-location', locationFilter);
            formData.append('filter-month', monthFilter);

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
        loadEvents('venir', nextPage, locationFilter, monthFilter);

        var eventFilterForm = document.getElementById('filter-form');

        eventFilterForm.addEventListener('submit', function(event) {
            event.preventDefault();

            var isChecked = document.getElementById('filterCheckbox').checked;
            selectedFilter = isChecked ? 'passe' : 'venir';
            nextPage = 1;
            prevPage = null;
            locationFilter = document.getElementById('golf-location').value;
            monthFilter = document.getElementById('filter-month').value;    
            console.log(locationFilter)
            console.log(monthFilter)
            console.log(selectedFilter)
            loadEvents(selectedFilter, nextPage, locationFilter, monthFilter);
        });

        filterCheckbox.addEventListener('change', function() {
            // Determine filter based on switch state
            var selectedFilter = this.checked ? 'passe' : 'venir';
            nextPage = 1; // Restarts the page when changing the filter
            prevPage = null;
            locationFilter = 'all';
            monthFilter = 'all';
            document.getElementById('golf-location').value = 'all';
            document.getElementById('filter-month').value = 'all';

            loadEvents(selectedFilter, nextPage, locationFilter, monthFilter);
        });

        //Add a listener for the forward or back buttons on pages
        document.addEventListener('click', function(event) {
            if (event.target.classList.contains('load-more-button')) {
                prevPage = nextPage;
                nextPage++;
                var selectedFilter = filterCheckbox.checked ? 'passe' : 'venir';
                loadEvents(selectedFilter, nextPage, locationFilter, monthFilter);
            } else if (event.target.classList.contains('load-prev-button')) {
                nextPage = prevPage;
                prevPage = (nextPage > 1) ? nextPage - 1 : null;
                var selectedFilter = filterCheckbox.checked ? 'passe' : 'venir';
                loadEvents(selectedFilter, nextPage, locationFilter, monthFilter);
            } else if (event.target.classList.contains('pagination-buttons')) {
                // If you click on a numbered page, it updates the 'current' class
                nextPage = parseInt(event.target.dataset.page);
                prevPage = (nextPage > 1) ? nextPage - 1 : null;
                var selectedFilter = filterCheckbox.checked ? 'passe' : 'venir';
                loadEvents(selectedFilter, nextPage, locationFilter, monthFilter);
            }
        });

        // Function to update the visibility of pagination buttons
        function updatePaginationButtons() {
            var paginationContainer = document.querySelector('.pagination');
            var paginationButtons = paginationContainer.querySelectorAll('.pagination-buttons');
            var loadMoreButton = paginationContainer.querySelector('.load-more-button');
            var loadPrevButton = paginationContainer.querySelector('.load-prev-button');

            // Obtenha o número máximo de páginas do atributo de dados
            var maxPages = parseInt(paginationContainer.dataset.maxPages);

            if (loadMoreButton) {
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

            paginationButtons.forEach(function (button) {
                var pageNumber = parseInt(button.dataset.page);

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

        function cleanFilter() {
            document.getElementById('filterCheckbox').checked = false;
        }

        window.addEventListener('load', function() {
            if (document.getElementById('filterCheckbox').checked) {
                console.log('call cleanFilter')
                cleanFilter(); //Always clean when the page loads (add this to resolve this problem: When I select a past event and go back to the center page (with the browser arrows), I see the "upcoming" events with the toggle on the "past" side)
            }
        });
        
    }

    // locator.js
    if(document.getElementById('map')){
        class Locator {
            constructor() {
                this.dataMarkerId = null;
                this.clickPinToMarkCard = null;
                this.map = L.map('map').setView([41.862680437343776, 12.477422940752222], 4);
                this.load();
                this.events();
            }
    
            events() {
                    
                let moveMapTimeout;
    
                this.map.on('moveend', () => {
                    clearTimeout(moveMapTimeout);
                    moveMapTimeout = setTimeout(() => {
                        this.updateVisibleMarkers();
                    }, 100);
                });
    
                this.map.on('zoomend', () => {
                    clearTimeout(moveMapTimeout);
                    moveMapTimeout = setTimeout(() => {
                        this.updateVisibleMarkers();
                    }, 100);
                });
            }
    
            load() {
                // Add a tile layer to the map using OpenStreetMap tiles and provide attribution
                L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                }).addTo(this.map);
    
                const mapList = document.querySelector('#map-list'); // Get the HTML element with the ID 'map-list' to list map items
                const markerCoordinates = [];
                const markers = L.markerClusterGroup(); // Initialize a marker cluster group to manage multiple markers efficiently
                
                locations.forEach((location) => {
                    const { lat, lon, title, id, link } = location;

                    const marker = L.marker([lat, lon], { id: id }).bindPopup(title); // Create a map marker at the location's latitude and longitude, bind a popup with the title
                    
                    // Create a list item (card) for the location and append it to 'mapList'
                    const listItem = this.createCard(id, lat, lon, title, link, this.clickPinToMarkCard);
                    mapList.appendChild(listItem);

                    const cardContainer = document.querySelector('.card-container');
            
                    // Add an event listener to highlight the card when the marker is clicked
                    marker.on('click', () => {
                        if (cardContainer) {
                            cardContainer.classList.add('highlighted');
                            this.clickPinToMarkCard = id;
            
                            if (window.innerWidth > 1024) {
                                mapList.scrollTo({
                                    top: cardContainer.offsetTop - 12,
                                    behavior: 'smooth',
                                });
                            } else {
                                mapList.scrollTo({
                                    left: cardContainer.offsetLeft - 12,
                                    behavior: 'smooth',
                                });
                            }
                        }
                        // Center the map on the clicked marker and set the zoom level to 13
                        this.map.setView([lat, lon], 13);
                    });
        
                    markerCoordinates.push([lat, lon]); // Add the marker coordinates to the array
                    markers.addLayer(marker); // Add the marker to the marker cluster group
                });
    
                this.map.addLayer(markers); // Add the marker cluster group to the map
            }
    
            updateVisibleMarkers() {
                const mapBounds = this.map.getBounds();  // Get the current bounds (visible area) of the map

                // Filter locations to find only those within the visible map bounds
                const visibleLocations = locations.filter(location =>
                    mapBounds.contains(L.latLng(location.lat, location.lon))
                );
    
                const mapList = document.querySelector('#map-list');
                mapList.innerHTML = '';
    
                // If no locations are visible within the current map view
                if (visibleLocations.length === 0) {
                    const listItem = document.createElement('li');
                    listItem.classList.add('map-list-item');
                    const html = `
                        <div class="card-map">
                        <span class="stars">Sorry, I couldn't find any results for your search area.</span>
                        </div>
                    `;
                    listItem.innerHTML = html;
                    mapList.appendChild(listItem);
                    return;
                }
                
                // For each location visible within the map bounds
                visibleLocations.forEach(location => {
                    const { lat, lon, title, id, link } = location;
                    const listItem = this.createCard(id, lat, lon, title, link, this.clickPinToMarkCard);
                    mapList.appendChild(listItem);
                });
            }
    
            createCard(id, lat, lon, title, link, clickPinToMarkCard) {
                // Create a container for the card
                const cardContainer = document.createElement('div');
                cardContainer.classList.add('card-container');
    
                // Create a list item for the map list entry
                const listItem = document.createElement('li');
                listItem.classList.add('map-list-item');
    
                listItem.addEventListener('click', (e) => {
                    this.dataMarkerId = listItem.dataset.markerId; // Set the marker ID to the list item ID for tracking
        
                    const marker = this.findMarkerById(id);

                    // If the clicked item matches the marker ID, highlight the item
                    if (this.dataMarkerId == id) {
                        listItem.classList.add('highlighted');
                    }
        
                    if (marker) {
                        this.map.setView([lat, lon], 13);
                    }
        
                    e.stopPropagation();
                });
    
                // Check if the card should be highlighted, based on the provided ID or the stored marker ID
                if (clickPinToMarkCard == id || this.dataMarkerId == id) {
                    listItem.classList.add('highlighted');
                    this.clickPinToMarkCard = 0;
                    this.dataMarkerId = null;
                }
    
                listItem.dataset.markerId = id; // Assign the ID to the list item for tracking
    
                const html = `
                    <div class="card-map">
                        <span class="stars">§</span>
                        <span class="h5 title">${title}</span>
                        <span class="text14">DESCRIPTION HERE</span>
                    </div>
                    <a href="${link}" class="btn-map">
                        <span>Test button</span>
                    </a>
                `;
    
                listItem.innerHTML = html;
                cardContainer.appendChild(listItem);
    
                return cardContainer;
            }
    
            findMarkerById(id) {
                return document.querySelector(`[data-marker-id="${id}"]`);
            }
        }
        new Locator();
    }
});