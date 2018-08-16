var eight_hours = 8 * 60 * 60 * 1000;
var total_in_a_day = 24 * 60 * 60 * 1000;

var scale_inputs = document.getElementsByClassName('scale_input');
for (var i = 0; i < scale_inputs.length; i++) {
    scale_inputs[i].addEventListener('click', plot_7, false);
}

function plot_7() {
    // calculate the timestamp of midnight for reference
    var d = new Date();
    var offset = d.getTimezoneOffset() * 60 * 1000;
    var now = d.getTime();
    var since_midnight = (now - offset) % total_in_a_day;
    midnight = now - since_midnight;

    var first_timestamp = Math.round((midnight - total_in_a_day * 6)/1000);
    var last_timestamp = Math.round(now / 1000);
    var ajax_data = 'first_timestamp=' + first_timestamp + '&last_timestamp=' + last_timestamp;

    $.post('time_fetch.php', ajax_data, function(data) {
        // split into data and topoff and get canvases
        parsed_data = JSON.parse(data);
        var markers_7 = parsed_data[0];
        var topoff = parsed_data[1];
        var canvases = document.getElementsByClassName('sevenD_canvas');


        // first midnight
        first_stamp = markers_7[0].tim * 1000;
        var first_stamp_day = (first_stamp - offset) % total_in_a_day;
        var first_midnight = first_stamp - first_stamp_day;
        midnights = [];
        for (var i = 0; i < 7; i++) {
            midnights.push(first_midnight);
            first_midnight += total_in_a_day;
        }
        midnights.shift();
        markers_tree = [];
        for (var i = 0; i < 7; i++) {
            markers_tree.push([]);
        }

        var token = 0;
        for (var i = 0; i < markers_7.length; i++) {
            if ((markers_7[i].tim * 1000) > midnights[token]) {
                token++;
            }
            markers_tree[token].push(markers_7[i]);
        }

        // plot once for every canvas
        canvases_order = [6,5,4,3,2,1,0];
        for (var i = 6; i > -1; i--) {
            if (markers_tree[i].length == 0) {
                plot('no markers', canvases[i].id, scale_number);
            } else {

                if (i == 1) {
                    if (topoff == 1) {
                        var day_topoff = 1;
                    } else if (markers_tree[i + 1][0].start_event == 0) {
                        var day_topoff = 1;
                    } else {
                        var day_topoff = 0;
                    }
                }

                //put together
                var data_to_send = markers_tree[i];
                var single_day_data = [data_to_send,day_topoff];

                // get scale number
                for (var j = 0; j < scale_inputs.length; j++) {
                    if (document.getElementById(scale_inputs[j].id).checked == true) {
                        var scale_number = parseInt(scale_inputs[j].id);
                    }
                }
                // plot
                plot(single_day_data, canvases[canvases_order[i]].id , scale_number);

            } // if data actually showed up

        } // end of for loop
    });

}; // end of plot_7
plot_7();
