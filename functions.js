var global_utility;
var colors = document.getElementById('colorkey').value;
var pairs = colors.split(';');
categories = [];
colors = [];

function get_fillstyle(category) {
    pairs.forEach(function(pair, index) {
        var keyval = pair.split(',');
        categories.push(keyval[0]);
        colors.push(keyval[1]);
    })// end of foreach

    // #colorkey must be present in the dom for this function to work
    for (var i = 0; i < categories.length; i++) {
        if (category == categories[i]) {
            return colors[i];
        }
    }
    // default light blue if the events category doesn't have a color value defined in the categories
    return '#42d7f4';
}

function is_label_appropriate(label_width, square_width) {

    if (label_width < square_width - 8) {
        return true;
    } else {
        return false;
    }
}

function plot(parsed_data, canvas, scale_number) {

    function labeller(event_name, label_x_location, square_width) {
        // var label_width =  c.measureText(event_name).width;
        // // label_height = c.measureText(event_name).height;
        var label_height = 10;
        // c.rect(label_x_location + square_width/2 - label_width/2 - 3, graph_height/2 - label_height/2 - 3, label_width + 6, label_height + 6);
        // c.fillStyle = 'white';
        // c.lineW = 'none';
        // c.fill();
        c.font = "12px helvetica neue";
        c.fillStyle = 'black';
        c.fillText(event_name, label_x_location + square_width/2, graph_height/2 + label_height/2);

    }

    // unpackage data
    var markers = parsed_data[0];
    topoff = parsed_data[1];
    // set initial labels if it's 9 or 24
    if (scale_number == 9) {
        var label = 9;
    } else {
        var label = 1;
    }

    // setup canvas
    var day_canvas = document.getElementById(canvas);
    var width = day_canvas.clientWidth;
    var height = day_canvas.clientHeight;
    day_canvas.width = width * 2;
    day_canvas.height = height * 2;
    var graph_height = .90 * height;
    var x_lab_height = .10 * height;
    var total = scale_number * 60 * 60;
    var total_in_8 = 8 * 60 * 60;
    var total_in_24 = 24 * 60 * 60;
    var c = day_canvas.getContext('2d');
    c.scale(2,2);
    c.textAlign = 'center';
    var line_x_loc = 1;

    // clear the canvas
    c.clearRect(0,0,width,height);


    if (markers.length !== 0) {

        // if scale_number == 9 cut ends of data so only appropriate markers remain;
        if (scale_number !== 24) {
            var d = new Date();
            var now = d.getTime();
            var offset = d.getTimezoneOffset() * 60 * 1000;
            // now -= offset;

            // get timestamp boundaries
            if (scale_number == 9) {
               var first_stamp = markers[0].tim * 1000 - offset;
               var in_a_day = first_stamp % total_in_a_day;
               var midnight = first_stamp - in_a_day;
               var first_cut = midnight + (8 * 60 * 60 * 1000);
               var second_cut = midnight + (17 * 60 * 60 * 1000);
           } else if (scale_number == 1) {
               var second_cut = Date.now() - offset;
               var first_cut = second_cut - (60 * 60 * 1000) - offset;

           }
           markers_filtered = [];
           for (var i = 0; i < markers.length; i++) {
               var fs = markers[i].tim * 1000 - offset;
               if ( (fs > first_cut) && (fs <= second_cut) ) {
                   markers_filtered.push(markers[i]);
               }
           } // end of for

           // topoff for 9 plot
           if (scale_number == 9) {
               for (var i = 0; i < markers.length; i++) {
                   var time = markers[i].tim * 1000 - offset;
                   // first marker after the second cut
                   if (time > second_cut) {
                       if (markers[i].start_event == null) {
                           topoff = markers[i];
                       }
                       break;
                   }
                }
            }

           markers = markers_filtered;

       } // scale number not 24

        // loop through all events and plot squares and labels
        label_x_location = 0;

        for (var i = 0; i < markers.length; i++) {

            // if it's a start
            if (markers[i].start_event == "1") {
                // don't plot but move label_x_location the appropriate amount
                var offset = (new Date().getTimezoneOffset()) * 60;
                var new_date = markers[i].tim - offset;
                seconds_today = new_date % total_in_24;
                if (scale_number == 9) { seconds_today -= total_in_8; };
                if (scale_number == 1) {
                    var time_since_midnight = (Math.round(Date.now()/1000) - offset) % total_in_24;
                    var time_since_last_event = time_since_midnight - seconds_today;
                    var seconds_in_the_hour = 3600 - time_since_last_event;
                    seconds_today = seconds_in_the_hour;
                }

                label_x_location = (seconds_today/total) * width;

            // else if it's an end
            } else {

                // if first data point is an end
                if (i == 0) {
                    // square
                    var d = new Date();
                    var offset = (d.getTimezoneOffset()) * 60;

                    // need to program in automatic calculation of weather or not it's DST where the client is and then the appropriate offset
                    var seconds_today = (markers[i].tim - offset) % total_in_24;
                    if (scale_number == 9) { seconds_today -= total_in_8; }
                    if (scale_number == 1) {
                        var stamp = Math.round(Date.now() / 1000) - offset;
                        var time_since_midnight = stamp % total_in_24;
                        var time_since_last_event = time_since_midnight - seconds_today;
                        var seconds_in_the_hour = 3600 - time_since_last_event;

                        seconds_today = seconds_in_the_hour;

                    }
                    var partial_end_width = (seconds_today/total) * width;
                    c.fillStyle = get_fillstyle(markers[i].category);
                    c.fillRect(0,0, partial_end_width, graph_height);

                    c.moveTo(partial_end_width, 0);
                    c.lineTo(partial_end_width, graph_height);
                    c.stroke();

                    //label if event's longer than 10 min
                    if (is_label_appropriate(c.measureText(markers[i].event_name).width, partial_end_width)) {
                        labeller(markers[i].event_name, label_x_location, partial_end_width);
                    }
                    label_x_location = label_x_location + partial_end_width;

                // not first end data points
                } else {
                    // square
                        var length = markers[i].tim - markers[i-1].tim;
                        var square_width = length/total * width;
                        c.fillStyle = get_fillstyle(markers[i].category);
                        c.fillRect(label_x_location, 0, square_width, graph_height);
                        c.moveTo(label_x_location + square_width, 0);
                        c.lineTo(label_x_location + square_width, graph_height);
                        c.stroke();

                        // labels if event's longer than 10 min
                        if (is_label_appropriate(c.measureText(markers[i].event_name).width, square_width)) {
                            labeller(markers[i].event_name, label_x_location, square_width);
                        }
                        label_x_location = label_x_location + square_width;



                } // if it's an end but not the first one

            } // if it's an end

        } // end of for loop over each row

        // if there is topoff complete it
        if (typeof topoff == 'object') {
            // square
            var last_square_width = width - label_x_location;
            c.fillStyle = get_fillstyle(topoff.category);
            c.fillRect(label_x_location, 0, width, graph_height);

            //label if event's longer than 10 min
            if (is_label_appropriate(c.measureText(topoff.event_name).width,last_square_width)) {
                labeller(topoff.event_name, label_x_location, last_square_width);
            }

            // topoff if scale_number == 9
            if (scale_number == 9) {

            }
        }

    } else if (markers.length == 0 && typeof topoff == 'object') {
        c.fillStyle = get_fillstyle(topoff.category);
        c.fillRect(0,0,width, graph_height);
        labeller(topoff.event_name, 0, width);
    }

    // draw hour lines and hour labels
    if (scale_number == 9 || scale_number == 24) {
        for (var i = 1; i < scale_number; i++) {
            // draw (dashed) line
            c.beginPath();
            c.strokeStyle = 'black';
            c.setLineDash([3,10]);
            c.moveTo(width/scale_number * line_x_loc, 0);
            c.lineTo(width/scale_number * line_x_loc, graph_height);
            c.stroke();

            // draw hour x axis labels
            c.fillStyle = 'black';
            c.font = '12px Helvetica neue';
            c.textBaseline = 'middle';
            c.fillText(label, width/scale_number * line_x_loc, graph_height + x_lab_height/2 + 1);
            label++;
            line_x_loc++;
        }
    } else { // scale_number == 1
        var spacer;
        x_loc_1 = width;

        label_in_milliseconds = Date.now();


        for (var i = 0; i < 5; i++) {

            var label_object = new Date(label_in_milliseconds);

            var minute_label = ( label_object.getMinutes() < 10 ? '0' : '' ) + label_object.getMinutes()
            var label_formatted = '' + label_object.getHours() + ':' + minute_label;

            if (i == 1 || i == 2 || i == 3 ) {
                // draw (dashed) line
                c.beginPath();
                c.strokeStyle = 'black';
                c.setLineDash([3,10]);
                c.moveTo(x_loc_1, 0);
                c.lineTo(x_loc_1, graph_height);
                c.stroke();
            }

            c.font = '12px Helvetica neue';
            c.fillStyle = 'black';

            // draw x axis labels
            if (i == 0) {
                c.textAlign = 'right';
                spacer = -4;
            } else if (i == 4) {
                c.textAlign = 'left';
                spacer = 4;
            } else {
                c.textAlign = 'center';
            }

            c.fillText(label_formatted, x_loc_1 + spacer, graph_height + 12);

            x_loc_1 -= width/4;
            label_in_milliseconds -= (15 * 60 * 1000);
            spacer = 0;

        } // end of for loop
    }


    // HR above the labels

    c.beginPath();
    c.moveTo(0,graph_height);
    c.lineTo(width, graph_height);
    c.setLineDash([]);
    c.stroke();



} // end of plot()
