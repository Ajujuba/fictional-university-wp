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
                L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                }).addTo(this.map);
    
                const mapList = document.querySelector('#map-list');
                const markerCoordinates = [];
                const markers = L.markerClusterGroup();
                
                locations.forEach((location) => {
                    const { lat, lon, title, id, link } = location;

                    const marker = L.marker([lat, lon], { id: id }).bindPopup(title);
                    const listItem = this.createCard(id, lat, lon, title, link, this.clickPinToMarkCard);
                    mapList.appendChild(listItem);

                    const cardContainer = document.querySelector('.card-container');
            
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
                        this.map.setView([lat, lon], 13);
                    });
        
                    markerCoordinates.push([lat, lon]);
                    markers.addLayer(marker);
                });
    
                this.map.addLayer(markers);
            }
    
            updateVisibleMarkers() {
                const mapBounds = this.map.getBounds();
                const visibleLocations = locations.filter(location =>
                    mapBounds.contains(L.latLng(location.lat, location.lon))
                );
    
                const mapList = document.querySelector('#map-list');
                mapList.innerHTML = '';
    
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
    
                visibleLocations.forEach(location => {
                    const { lat, lon, title, id, link } = location;
                    const listItem = this.createCard(id, lat, lon, title, link, this.clickPinToMarkCard);
                    mapList.appendChild(listItem);
                });
            }
    
            createCard(id, lat, lon, title, link, clickPinToMarkCard) {
                const cardContainer = document.createElement('div');
                cardContainer.classList.add('card-container');
    
                const listItem = document.createElement('li');
                listItem.classList.add('map-list-item');
    
                listItem.addEventListener('click', (e) => {
                this.dataMarkerId = listItem.dataset.markerId;
    
                const marker = this.findMarkerById(id);
                if (this.dataMarkerId == id) {
                    listItem.classList.add('highlighted');
                }
    
                if (marker) {
                    this.map.setView([lat, lon], 13);
                }
    
                e.stopPropagation();
                });
    
                if (clickPinToMarkCard == id || this.dataMarkerId == id) {
                    listItem.classList.add('highlighted');
                    this.clickPinToMarkCard = 0;
                    this.dataMarkerId = null;
                }
    
                listItem.dataset.markerId = id;
    
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