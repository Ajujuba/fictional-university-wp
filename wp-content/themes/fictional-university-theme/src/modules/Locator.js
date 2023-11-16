import $ from 'jquery';

class Locator{

    constructor(){
        this.test = null
        this.load()
        this.events()
    }

    events(){
        $("#map").on("click", '.test-ana',this.onMapClick);
        // this.map.on('zoomend', () => this.updateVisibleMarkers());
        // this.map.on('moveend', () => this.updateVisibleMarkers());
        let moveMapTimeout;

        this.map.on('moveend', () => {
            clearTimeout(moveMapTimeout);
            moveMapTimeout = setTimeout(() => {
                this.updateVisibleMarkers();
            }, 200); // Ajuste o tempo conforme necessário
        });
        this.map.on('zoomend', () => {
            clearTimeout(moveMapTimeout);
            moveMapTimeout = setTimeout(() => {
                this.updateVisibleMarkers();
            }, 200); // Ajuste o tempo conforme necessário
        });
    }

    load() {
        this.map = L.map('map').setView([41.862680437343776, 12.477422940752222], 4);
        
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
    
        let highlightedMarker = null;
    
        // Iterate over locations and create markers for each one
        locations.forEach(location => {
            const { lat, lon, title, id, link} = location;
    
            const marker = L.marker([lat, lon]).bindPopup(title);
                
            // Add a click event to the marker to highlight the card and focus on the point
            marker.on('click', () => {

                //Highlight the corresponding card
                // const listItem = document.querySelector(`[data-marker-id="${id}"]`);
                // if (listItem) {
                //     listItem.classList.add('highlighted');
                //     highlightedMarker = listItem;
                //     this.test = id
                // }

                const cardContainer = document.querySelector('.card-container');
                if (cardContainer) {
                    cardContainer.classList.add('highlighted');
                    this.test = id
                }

                // Scroll to the corresponding card
                listItem.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start',
                });
    
                this.map.setView([lat, lon], 13); // Focus on the marker point
            });

            markerCoordinates.push([lat, lon]); // Add the marker coordinates to the array

            markers.addLayer(marker);// Add markers to the cluster

            const listItem = this.createCard( id, lat, lon, title, link, this.test); // Add bookmarks to the list
    
            mapList.appendChild(listItem);
        });
    
        this.map.addLayer(markers); // Add the cluster to the map
    
        // Calculate the length (bounds) of markers
        //const markerBounds = L.latLngBounds(markerCoordinates);
        // Adjust the map view to include the extent of markers
        //map.fitBounds(markerBounds);   // By commenting on this line, the initial centering of the map is the way I want it to be shown to the landmarks  
        
    }

    //Updates my list to only show cards corresponding to my markers on the screen at the current time
    updateVisibleMarkers() {
        const mapBounds = this.map.getBounds();
        const visibleLocations = locations.filter(location =>
            mapBounds.contains(L.latLng(location.lat, location.lon))
        );
       
        const mapList = document.querySelector('#map-list');
        mapList.innerHTML = '';   // clean my list

        if (visibleLocations.length === 0) {
            // Add a message with local not found
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
            const listItem = this.createCard( id, lat, lon, title, link, this.test );
            mapList.appendChild(listItem);
        });
        
        //console.log('Locations visíveis:', visibleLocations);
    }
    
    onMapClick(e) {
        popup
            .setLatLng(e.latlng)
            .setContent("You clicked the map at " + e.latlng.toString())
            .openOn(this.map);
    }

    createCard(id, lat, lon, title, link, test) {
        console.log(test)

        const cardContainer = document.createElement('div');
        cardContainer.classList.add('card-container');
    
        const listItem = document.createElement('li');
        listItem.classList.add('map-list-item');

        //Highlight the corresponding card
        if (test == id) {
            listItem.classList.add('highlighted');
            this.test = 0 // this line makes my map lost the highlighted if use move or zoom
        }

        listItem.setAttribute('data-marker-id', id);
    
        const html = `
            <a class="card-map" href="${link}" title="${title}">
                <span class="stars">§</span>
                <span class="h5 title">${title}</span>
                <span class="text14">DESCRIPTION HERE</span>
            </a>
            <a href="https://www.google.com" class="btn-map">
                <span>Test</span>
            </a>
        `;
    
        listItem.innerHTML = html;
        cardContainer.appendChild(listItem);
    
        return cardContainer;
    }
}

export default Locator;