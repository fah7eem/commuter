window.addEventListener('beforeinstallprompt', (e) => {
  // Prevent Chrome 67 and earlier from automatically showing the prompt
  e.preventDefault();
  // Stash the event so it can be triggered later.
  deferredPrompt = e;
  // Update UI to notify the user they can add to home screen
  addBtn.style.display = 'block';

  addBtn.addEventListener('click', (e) => {
    // hide our user interface that shows our A2HS button
    addBtn.style.display = 'none';
    // Show the prompt
    deferredPrompt.prompt();
    // Wait for the user to respond to the prompt
    deferredPrompt.userChoice.then((choiceResult) => {
        if (choiceResult.outcome === 'accepted') {
          console.log('User accepted the A2HS prompt');
        } else {
          console.log('User dismissed the A2HS prompt');
        }
        deferredPrompt = null;
      });
  });
});


function search(){
  var start = $('#start').val();
  var destination = $('#destination').val();
  $.get( "/journey/"+start+'/'+destination, function( data ) {

    if(data.itineraries.length > 0){
      $('#results').removeClass('d-none');
    }else{
      $('#results').addClass('d-none');
    }
    data.itineraries.forEach(function(value, index) {
        $('#depart-'+index).html(value.departureTime);
        $('#arrival-'+index).html(value.arrivalTime);
        $('#totalDistance-'+index).html(value.distance.value + value.distance.unit);
        $('#totalDuration-'+index).html(value.duration);
        value.legs.forEach(function(value2) {
          cost = 0.00;
          if('stop' in value2.waypoints[value2.waypoints.length - 1]){
            pointName = value2.waypoints[value2.waypoints.length - 1].stop.name;
          }else if('location' in value2.waypoints[value2.waypoints.length - 1]){
            pointName = value2.waypoints[value2.waypoints.length - 1].location.address;
          }
          type = value2.type;
          distance = value2.distance.value + value2.distance.unit;
          duration = value2.duration;
          vehicle = false;
          fareName = false;
          fare = false;
          if('fare' in value2){
            fareName = value2.fare.fareProduct.name;
            fare = value2.fare.cost.amount + ' ' + value2.fare.cost.currencyCode;
            cost = cost + parseFloat(value2.fare.cost.amount);
          }
          if('vehicle' in value2){
            vehicle = value2.vehicle.headsign;
          }
          $('#itinerary-'+index).append(itinerary_html(pointName, type, distance, duration, vehicle, fareName, fare));
        });
    });
  });
}

function itinerary_html(pointName, type, distance, duration, vehicle, fareName, fare){
  var html = '<hr><span class="text-primary">'+pointName+'</span><span class="float-right">'+type+'</span><br><br><span>Distance: <span class="text-info">'+distance+'</span></span><span class="float-right">Duration: <span  class="text-info">'+duration+'</span></span><br>';
  if(vehicle !== false){
    html = html + '<span>Vehicle: <span class="text-info">'+vehicle+'</span></span>';
  }
  if(fareName !== false && fare !== false){
    html = html + '<br><br><span>'+fareName+'</span><span  class=" float-right text-info">'+fare+'</span></span>';
  }
  return html;
}