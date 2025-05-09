function get_flight_bookings(search='',page=1)
{
    let xhr = new XMLHttpRequest();
    xhr.open("POST","ajax/flight_booking_records.php",true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function(){
        let data = JSON.parse(this.responseText);
        document.getElementById('flight-table-data').innerHTML = data.table_data;
        document.getElementById('flight-table-pagination').innerHTML = data.pagination;
    }

    xhr.send('get_flight_bookings&search='+search+'&page='+page);
}

function change_flight_page(page){
    get_flight_bookings(document.getElementById('flight_search_input').value,page);
}

function download_flight_invoice(id){
    window.location.href = 'generate_flight_pdf.php?gen_pdf&id='+id;
}


window.onload = function(){
    get_flight_bookings();
}