document.addEventListener("DOMContentLoaded", function() {
    var lat =  document.querySelector('[data-name="latitude"] input');
    var alertShown = false;

    if(lat){
        lat.addEventListener('focus', () => {
            if (alertShown) return; //ensures that the alert only appears once

            const endereco = `${document.querySelector('[data-name="address"] input').value}, ${document.querySelector('[data-name="city"] input').value}, ${document.querySelector('[data-name="country"] input').value}, ${document.querySelector('[data-name="postal_code"] input').value}`;
            console.log(endereco)

            // Send information to geocode API
            fetch(`https://nominatim.openstreetmap.org/search?q=${endereco}&format=json`)
            .then(response => response.json())
            .then(data => {
                // Check the response
                if (data.length > 0) {
                    const latitude = data[0].lat;
                    const longitude = data[0].lon;

                    // Fill in the field latitude and longitude
                    document.querySelector('[data-name="latitude"] input').value = latitude;
                    document.querySelector('[data-name="longitude"] input').value = longitude;

                    console.log('Generated coordinates:', latitude, longitude);
                } else {
                    console.error('No coordinates found for the address provided.');
                    alert('Sorry, no coordinates found for the address provided. Please check the address or paste the coordinates directly into the fields.');
                    alertShown = true;
                }
            })
            .catch(error => {
                console.error('Error when searching for coordinates:', error);
            });
        });
    }
});

