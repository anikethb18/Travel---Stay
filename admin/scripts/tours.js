let add_tour_form = document.getElementById('add_tour_form');
let edit_tour_form = document.getElementById('edit_tour_form');
let add_tour_modal = new bootstrap.Modal(document.getElementById('add-tour'));
let edit_tour_modal = new bootstrap.Modal(document.getElementById('edit-tour'));

function get_all_tours() {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/tours.php", true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function() {
        document.getElementById('tours-data').innerHTML = this.responseText;
    };

    xhr.send('get_all_tours');
}

function add_tour() {
    let data = new FormData(add_tour_form);
    data.append('add_tour', '');

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/tours.php", true);

    xhr.onload = function() {
        add_tour_modal.hide();
        if (this.responseText == 1) {
            alert('success', 'New tour added!');
            add_tour_form.reset();
            get_all_tours();
        } else {
            alert('error', 'Server Down!');
        }
    };
    xhr.send(data);
}

function edit_details(id) {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/tours.php", true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function() {
        let data = JSON.parse(this.responseText);
        edit_tour_form.elements['tour_id'].value = data.id;
        edit_tour_form.elements['tour_name'].value = data.tour_name;
        edit_tour_form.elements['destination'].value = data.destination;
        edit_tour_form.elements['duration'].value = data.duration;
        edit_tour_form.elements['price'].value = data.price;
        edit_tour_form.elements['desc'].value = data.description;
        edit_tour_form.elements['travel_id'].value = data.travel_id;
        edit_tour_form.elements['travel_agency'].value = data.travel_agency;
        edit_tour_form.elements['status'].value = data.status;
        edit_tour_modal.show();
    };
    xhr.send('get_tour=' + id);
}

function submit_edit_tour() {
    let data = new FormData(edit_tour_form);
    data.append('edit_tour', '');

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/tours.php", true);

    xhr.onload = function() {
        edit_tour_modal.hide();
        if (this.responseText == 1) {
            alert('success', 'Tour data updated!');
            edit_tour_form.reset();
            get_all_tours();
        } else {
            alert('error', 'Server Down!');
        }
    };
    xhr.send(data);
}

function toggle_status(id, val) {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/tours.php", true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function() {
        if (this.responseText == 1) {
            alert('success', 'Status toggled!');
            get_all_tours();
        } else {
            alert('error', 'Server Down!');
        }
    };
    xhr.send('toggle_status=' + id + '&value=' + val);
}

function remove_tour(tour_id) {
    if (confirm("Are you sure you want to remove this tour?")) {
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "ajax/tours.php", true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        xhr.onload = function() {
            if (this.responseText == 1) {
                alert('success', 'Tour removed!');
                get_all_tours();
            } else {
                alert('error', 'Server Down!');
            }
        };
        xhr.send('remove_tour=' + tour_id);
    }
}

add_tour_form.addEventListener('submit', function(e) {
    e.preventDefault();
    add_tour();
});

edit_tour_form.addEventListener('submit', function(e) {
    e.preventDefault();
    submit_edit_tour();
});

window.onload = function() {
    get_all_tours();
};