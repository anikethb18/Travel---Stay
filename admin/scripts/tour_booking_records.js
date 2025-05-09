function get_tour_bookings(search='',page=1)
{
    let xhr = new XMLHttpRequest();
    xhr.open("POST","ajax/tour_booking_records.php",true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function(){
        let data = JSON.parse(this.responseText);
        document.getElementById('tour-table-data').innerHTML = data.table_data;
        document.getElementById('tour-table-pagination').innerHTML = data.pagination;
    }

    xhr.send('get_tour_bookings&search='+search+'&page='+page);
}

function change_tour_page(page){
    get_tour_bookings(document.getElementById('tour_search_input').value,page);
}

function download_tour_invoice(id){
    window.location.href = 'generate_tour_pdf.php?gen_pdf&id='+id;
}


window.onload = function(){
    get_tour_bookings();
}