var eight_hours = 8 * 60 * 60 * 1000;
var total_in_a_day = 24 * 60 * 60 * 1000;
$('#warning').hide();
// Add event listeners to #9,#24 and #1
var scale_inputs = document.getElementsByClassName('scale_input');
for (var i = 0; i < scale_inputs.length; i++) {
    scale_inputs[i].addEventListener('click', retrieve_and_plot, false);
}

// Add event listener to #submit
document.getElementById('submit').addEventListener('click', function(){
    save();
    retrieve_and_plot();
}, false);


function retrieve_and_plot() {

    for (var i = 0; i < scale_inputs.length; i++) {
        if (document.getElementById(scale_inputs[i].id).checked == true) {
            var scale_number = parseInt(scale_inputs[i].id);
        }
    }

    // calculate timestamps for retrieve();
    var d = new Date();
    var now = d.getTime();
    var offset = d.getTimezoneOffset() * 60 * 1000;
    var since_midnight = (now - offset) % total_in_a_day;
    var midnight = now - since_midnight;
    var coming_midnight = midnight + (24 * 60 * 60 * 1000);

    var ajax_data = 'first_timestamp=' + Math.round(midnight/1000) + '&last_timestamp=' + Math.round(coming_midnight/1000);
    console.log(ajax_data);

    $.post('time_fetch.php', ajax_data, function(data) {

            if (data == 'no markers') {
                plot ('no markers', "main_canvas", scale_number);
                update_main_table();
            } else if ( (typeof data == 'string') && (data.substr(0,1) == '[') ) {
                parsed_data = JSON.parse(data);
                plot(parsed_data, "main_canvas", scale_number);
                update_main_table();
            } else {
                console.log('data for retrieve_and_plot was not as expected')
                console.log('data: ' + data);
            }


    }); // end of $.post()


} // end of plot()
retrieve_and_plot();


function save() {

    var start_bool;
    var event_name;
    var category;
    if (document.getElementById('start').checked) {
        start_bool = 'true';
        event_name = "";
        category = "";
    } else {
        start_bool = 'false';
        event_name = document.getElementById('event_name').value;
        category = document.getElementById('category').value;
    }

    var data = 'event_name=' + event_name +
    '&category=' + category +
    '&start_event=' + start_bool +
    '&time_saved=' + Math.round(new Date().getTime()/1000);

    $.post('time_save.php', data, function(data, status) {
        if (data == "success") {
            $('#warning').hide();
            update_main_table();

        } else if (data.substr(0,1) == 'S' || data.substr(0,1) == 'C') {
            // $("#warning").innerHTML = data;
            $("#warning").text(data);
            $('#warning').show();

        } else {
            $("#warning").text(data);
            $('#warning').show(1000);

        }
    });

} // end of save();

function update_main_table() {

    $.get('table_data_fetch.php', function(data,status) {
        if (data.substr(0,3) == "<ta") {
            document.getElementById('output').innerHTML = data;
        } else {
            console.log(data);
            console.log('table data retrieval messed up');
        }
    }) // end of $.post()


} // end of update_main_table()
update_main_table();
