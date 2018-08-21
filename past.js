var eight_hours = 8 * 60 * 60 * 1000;
var total_in_a_day = 24 * 60 * 60 * 1000;
var months_with_30 = [3,4,8,10];
var months_with_31 = [0,2,4,6,7,9,11];

var scale_inputs = document.getElementsByClassName('scale_input');
for (var i = 0; i < scale_inputs.length; i++) {
    scale_inputs[i].addEventListener('click', plot_7, false);
}

function plot_7() {

    // calculate midnights array
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
        markers_7 = parsed_data[0];
        var topoff = parsed_data[1];
        var canvases = document.getElementsByClassName('sevenD_canvas');


        first_midnight = midnight - (6 * 24 * 60 * 60 * 1000);
        first_midnight_inc = first_midnight;

        // loop to create midnights array
        midnights_7 = [];
        for (var i = 0; i < 7; i++) {
            midnights_7.push(first_midnight_inc);
            first_midnight_inc += 24 * 60 * 60 * 1000;
        }

        //loop to create days array
        days_7 = [];
        first_day = new Date(first_midnight).getDate();
        days_7.push(first_day);
        for (var i = 1; i < 7; i++) {
            var current_day = new Date(markers_7[i].tim * 1000 - offset);
            // get array of all days to match with the markers.
            // NEED TO PROGRAM CASES OF FEBRUARY AND LEAP YEAR

            // make next day 0 if needed
            if (days_7[days_7.length - 1] == 30) {
                months_with_30.foreach(function(month,index){
                    if (month == current_day.getMonth()) {
                        // if it's a month with 30 days next day should be 0
                        days_7.push(0);
                    }
                });
            } else if (first_day == 31) {
                days_7.push(0);
            } else {
                days_7.push(days_7[days_7.length - 1] + 1);
            }

        } // end of for loop to create days array

        markers_tree = new Array(7);
        for (var i = 0; i < markers_tree.length; i++) {
            markers_tree[i] = [];
        }
        // filter markers into apropriate array
        for (var i = 0; i < markers_7.length; i++) {
            var marker_date = new Date(markers_7[i].tim * 1000 - offset).getDate();
            days_7.forEach(function(day,index) {
                if (day == marker_date) {
                  markers_tree[index].push(markers_7[i]);
                }
            }); // end of foreach

        }

        // // first midnight
        // first_stamp = markers_7[0].tim * 1000;
        // var first_stamp_day = (first_stamp - offset) % total_in_a_day;
        // first_midnight = first_stamp - first_stamp_day;
        // midnights = [];
        // for (var i = 0; i < 7; i++) {
        //     midnights.push(first_midnight);
        //     first_midnight += total_in_a_day;
        // }
        // midnights.shift();
        // markers_tree = [];
        // for (var i = 0; i < 7; i++) {
        //     markers_tree.push([]);
        // }
        //
        // var token = 0;
        // for (var i = 0; i < markers_7.length; i++) {
        //     if ((markers_7[i].tim * 1000) > midnights[token]) {
        //         token++;
        //     }
        //     markers_tree[token].push(markers_7[i]);
        // }

        // plot once for every canvas
        canvases_order = [6,5,4,3,2,1,0];

        for (var i = 0; i < 7; i++) {

            // get scale number
            for (var j = 0; j < scale_inputs.length; j++) {
                if (scale_inputs[j].checked == true) {
                    var scale_number = parseInt(scale_inputs[j].id);
                }
            }

            if (markers_tree[i].length == 0) {
                plot('no markers', canvases[canvases_order[i]].id, scale_number);
            } else {

                if (i == 6) {
                    if (topoff == 1) {
                        var day_topoff = 1;
                    } else {
                        var day_topoff = 0;
                    }
                }

                //put together
                single_day_data = [markers_tree[i],day_topoff];

                // plot
                plot(single_day_data, canvases[canvases_order[i]].id , scale_number);

            } // if data actually showed up

        } // end of for loop
    });

}; // end of plot_7
plot_7();
