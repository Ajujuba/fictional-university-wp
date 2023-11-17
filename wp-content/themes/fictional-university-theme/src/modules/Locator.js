import $ from 'jquery';

class Locator{

    constructor(){
        this.dataMarkerId = null
        this.clickPinToMarkCard = null
        this.map = L.map('map').setView([41.862680437343776, 12.477422940752222], 4); //defines where my map open 
        this.load()
        this.events()
    }

    events(){
        $("#map").on("click", '.test-ana',this.onMapClick);
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
    
        //After creating the map, create the zoom control and adjust its position
        var zoomControl = L.control.zoom({
            position: 'topright'
        });
    
        zoomControl.addTo(this.map); // Add zoom control to map
    
        const mapList = document.querySelector('#map-list');

        const markerCoordinates = []; // array to store marker coordinates
    
        var markers = L.markerClusterGroup(); // Create the marker cluster
        
        // Iterate over locations and create markers for each one
        locations.forEach(location => {
            const { lat, lon, title, id, link} = location;
    
            const marker = L.marker([lat, lon], { id: id }).bindPopup(title);
            
            const cardContainer = document.querySelector('.card-container');

            // Add a click event to the marker to highlight the card and focus on the point
            marker.on('click', () => {

                if (cardContainer) {
                    cardContainer.classList.add('highlighted');
                    this.clickPinToMarkCard = id
                }

                // Scroll to the corresponding card
                if (window.innerWidth > 1024) {
                    mapList.scrollTo({
                        top: cardContainer.offsetTop - 12,
                        behavior: 'smooth',
                    })
                } else {
                    mapList.scrollTo({
                        left: cardContainer.offsetLeft - 12,
                        behavior: 'smooth',
                    })
                }
    
                this.map.setView([lat, lon], 13); // Focus on the marker point
            });

            markerCoordinates.push([lat, lon]); // Add the marker coordinates to the array

            markers.addLayer(marker);// Add markers to the cluster

            const listItem = this.createCard( id, lat, lon, title, link, this.clickPinToMarkCard); // Add bookmarks to the list
    
            mapList.appendChild(listItem);
        });
    
        this.map.addLayer(markers); // Add the cluster to the map
    }

    //Updates my list to only show cards corresponding to my markers on the screen at the current time
    updateVisibleMarkers() {
        const mapBounds = this.map.getBounds();
        const visibleLocations = locations.filter(location =>
            mapBounds.contains(L.latLng(location.lat, location.lon))
        );

        const mapList = document.querySelector('#map-list');
        mapList.innerHTML = '';   // clean my list

        // Add a message with local not found
        if (visibleLocations.length === 0) {
            const listItem = document.createElement('li');
            listItem.classList.add('map-list-item');
            const html = `
                <div class="card-map">
                    <span class="stars">No result for your search</span>
                </div>
            `;
            listItem.innerHTML = html;
            mapList.appendChild(listItem);
            return;
        }

        //  Add cards only about my visible locations
        visibleLocations.forEach(location => {
            const { lat, lon, title, id, link} = location;
            const listItem = this.createCard( id, lat, lon, title, link, this.clickPinToMarkCard );
            mapList.appendChild(listItem);
        });
    }
    
    onMapClick(e) {
        popup
            .setLatLng(e.latlng)
            .setContent("You clicked the map at " + e.latlng.toString())
            .openOn(this.map);
    }

    createCard(id, lat, lon, title, link, clickPinToMarkCard) {

        const cardContainer = document.createElement('div');
        cardContainer.classList.add('card-container');
    
        const listItem = document.createElement('li');
        listItem.classList.add('map-list-item');

        // Add an event when click in the card
        listItem.addEventListener('click', (e) => {

            this.dataMarkerId = $(listItem).data('marker-id');
            
            // Find the card and add highlight
            const marker = this.findMarkerById(id);
            if (this.dataMarkerId == id) {
                listItem.classList.add('highlighted');
            }

            if (marker) {
                this.map.setView([lat, lon], 13); // Zoom in the marker corresponding
            }

            e.stopPropagation(); // Prevent click propagation to avoid conflicts with the map
        });

        //Highlight the corresponding card
        if (clickPinToMarkCard == id || this.dataMarkerId == id) {
            listItem.classList.add('highlighted');
            this.clickPinToMarkCard = 0 // this line makes my map lost the highlighted if use move or zoom
            this.dataMarkerId = null
        }

        listItem.setAttribute('data-marker-id', id);
    
        const html = `
            <div class="card-map">
                <span class="stars">§</span>
                <span class="h5 title">${title}</span>
                <span class="text14">DESCRIPTION HERE</span>
            </div>
            <a href="https://www.google.com" class="btn-map">
                <span>Test button</span>
            </a>
        `;
    
        listItem.innerHTML = html;
        cardContainer.appendChild(listItem);
    
        return cardContainer;
    }

    findMarkerById(id) {
        let foundMarker = null;
        foundMarker = document.querySelector(`[data-marker-id="${id}"]`);
        return foundMarker;
    }
}

export default Locator;