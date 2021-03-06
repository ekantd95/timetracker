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
    ajax_data = 'first_timestamp=' + first_timestamp + '&last_timestamp=' + last_timestamp;

    $.post('time_fetch.php', ajax_data, function(data) {

        var canvases = document.getElementsByClassName('sevenD_canvas');

        // if there were no markers in the past week
        if (data == 'no markers') {

            // get scale number
            for (var j = 0; j < scale_inputs.length; j++) {
                if (scale_inputs[j].checked == true) {
                    var scale_number = parseInt(scale_inputs[j].id);
                }
            }

            for (var i = 0; i < 7; i++) {
                plot('no markers', canvases[i].id , scale_number)
            }

            return;

        } // if there were no markers

        // parcel data and continue
        parsed_data = JSON.parse(data);
        markers_7 = parsed_data[0];
        topoff = parsed_data[1];


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
            var current_day = new Date();
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

        //go through marker_tree and add topoffs
         markers_tree_new = [
            [
                markers_tree[0],
                0
            ],
            [
                markers_tree[1],
                0
            ],
            [
                markers_tree[2],
                0
            ],
            [
                markers_tree[3],
                0
            ],
            [
                markers_tree[4],
                0
            ],
            [
                markers_tree[5],
                0
            ],
            [
                markers_tree[6],
                0
            ]
        ];

        for (var i = 6; i > -1; i--) {
            if (markers_tree[i].length > 0 && markers_tree[i][0].start_event == null){
            // first event is end and therefore topoff must be applied to every prior day without markers and only the first prior day with markers
                for (var j = (i-1); j > -1; j--) {
                    if (markers_tree_new[j][0].length == 0) {
                        markers_tree_new[j][1] = markers_tree[i][0];
                    } else {
                        markers_tree_new[j][1] = markers_tree[i][0];
                        break;
                    }
                }
            }
        }

        // plot once for every canvas
        canvases_order = [6,5,4,3,2,1,0];

        for (var i = 0; i < 7; i++) {

            // get scale number
            for (var j = 0; j < scale_inputs.length; j++) {
                if (scale_inputs[j].checked == true) {
                    var scale_number = parseInt(scale_inputs[j].id);
                }
            }

            // plot
            plot(markers_tree_new[i], canvases[canvases_order[i]].id , scale_number);



        } // end of for loop
    }); // end of ajax call

}; // end of plot_7
plot_7();
