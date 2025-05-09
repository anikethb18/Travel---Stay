let add_flight_form = document.getElementById('add_flight_form');
let edit_flight_form = document.getElementById('edit_flight_form');
let add_flight_modal = new bootstrap.Modal(document.getElementById('add-flight'));
let edit_flight_modal = new bootstrap.Modal(document.getElementById('edit-flight'));

function get_all_flights() {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/flights.php", true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function() {
        document.getElementById('flights-data').innerHTML = this.responseText;
    };

    xhr.send('get_all_flights');
}

function add_flight() {
    let data = new FormData(add_flight_form);
    data.append('add_flight', '');

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/flights.php", true);

    xhr.onload = function() {
        add_flight_modal.hide();
        if (this.responseText == 1) {
            alert('success', 'New flight added!');
            add_flight_form.reset();
            get_all_flights();
        } else {
            alert('error', 'Server Down!');
        }
    };
    xhr.send(data);
}

function edit_details(id) {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/flights.php", true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function() {
        let data = JSON.parse(this.responseText);
        edit_flight_form.elements['flight_id'].value = data.id;
        edit_flight_form.elements['flight_name'].value = data.flight_name;
        edit_flight_form.elements['departure_city'].value = data.departure_city;
        edit_flight_form.elements['arrival_city'].value = data.arrival_city;
        edit_flight_form.elements['departure_time'].value = data.departure_time;
        edit_flight_form.elements['arrival_time'].value = data.arrival_time;
        edit_flight_form.elements['travel_id'].value = data.travel_id;
        edit_flight_form.elements['travel_agency'].value = data.travel_agency;
        edit_flight_form.elements['status'].value = data.status;
        edit_flight_modal.show();
    };
    xhr.send('get_flight=' + id);
}

function submit_edit_flight() {
    let data = new FormData(edit_flight_form);
    data.append('edit_flight', '');

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/flights.php", true);

    xhr.onload = function() {
        edit_flight_modal.hide();
        if (this.responseText == 1) {
            alert('success', 'Flight data updated!');
            edit_flight_form.reset();
            get_all_flights();
        } else {
            alert('error', 'Server Down!');
        }
    };
    xhr.send(data);
}

function toggle_status(id, val) {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/flights.php", true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function() {
        if (this.responseText == 1) {
            alert('success', 'Status toggled!');
            get_all_flights();
        } else {
            alert('error', 'Server Down!');
        }
    };
    xhr.send('toggle_status=' + id + '&value=' + val);
}

function remove_flight(flight_id) {
    if (confirm("Are you sure you want to remove this flight?")) {
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "ajax/flights.php", true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        xhr.onload = function() {
            if (this.responseText == 1) {
                alert('success', 'Flight removed!');
                get_all_flights();
            } else {
                alert('error', 'Server Down!');
            }
        };
        xhr.send('remove_flight=' + flight_id);
    }
}

add_flight_form.addEventListener('submit', function(e) {
    e.preventDefault();
    add_flight();
});

edit_flight_form.addEventListener('submit', function(e) {
    e.preventDefault();
    submit_edit_flight();
});

window.onload = function() {
    get_all_flights();
};