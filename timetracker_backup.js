function plot() {

    var data;

    // find and set the total number of hours to display (and set initial x axis label value)
    if (document.getElementById('9').checked == true) {
        var scale_number = 9;
        var scale_9 = true;
        var label = 9;
    } else {
        var scale_number = 24;
        var label = 1;
    }

    // setup canvas
    var day_canvas = document.getElementById('canvas');
    var width = day_canvas.clientWidth;
    var height = day_canvas.clientHeight;
    day_canvas.width = width * 2;
    day_canvas.height = height * 2;
    var graph_height = .90 * height;
    var x_lab_height = .10 * height;
    var total = scale_number * 60 * 60;
    var total_in_9 = 9 * 60 * 60;
    var total_in_24 = 24 * 60 * 60;
    var c = day_canvas.getContext('2d');
    c.scale(2,2);
    var x_loc = 1;

    // clear the canvas
    c.clearRect(0,0,width,height);

    // retrieve data
    var ajax = getXMLHttpRequestObject();

    ajax.open('POST', 'timetracker_fetch.php', true);
    ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    var ajax_data = 'scale_number=' + scale_number;
    ajax.send(ajax_data);

    // once the ready state  of the ajax object changes
    ajax.onreadystatechange = function() {
      if (ajax.readyState == 4) {
        if ((ajax.status >= 200 && ajax.status < 300) || (ajax.status == 304)) {
            if ((typeof ajax.responseText == 'string') && (ajax.responseText.substr(0,1) == '[')) {

                data = JSON.parse(ajax.responseText);
                fish = data;
                console.log(fish);
                plot(data, "main_canvas");




            } else {
                console.log(ajax.responseText);
            }
        }
      } // ready state not equal 4
    }; // end of onreadystatechange function


    // loop through all events and plot squares and labels
    var label_x_location = 0;

    for (var i = 0; i < fish.length; i++) {

        // if it's a start
        if (data[i].start_event == "1") {

            // don't plot
            var new_date = fish[i].tim - offset + 3600;
            var seconds_today = new_date % total_in_24;
            if (scale_9) { seconds_today -= total_in_9; }
            label_x_location = (seconds_today/total) * width;
            console.log('start event');

        // else if it's an end
        } else {

            // if first data point is an end
            if (i == 0) {
                // square
                console.log(offset);
                // need to program in automatic calculation of weather or not it's DST where the client is and then the appropriate offset
                var seconds_today = (fish[i].tim - offset + 3600) % total_in_24;
                console.log(fish);
                if (scale_9) { seconds_today -= total_in_9; }
                var partial_end_width = (seconds_today/total) * width;
                c.fillStyle = get_fillstyle(fish[i].category);
                c.fillRect(0,0, partial_end_width, graph_height);
                //label if event's longer than 10 min
                if (fish[i].length > (10 * 60)) {
                    c.font = "12px helvetica neue";
                    c.fillStyle = 'white';
                    c.textAlign = 'center';
                    c.fillText(fish[i].event_name, partial_end_width/2, graph_height/2);
                }
                label_x_location = label_x_location + partial_end_width;
                console.log('first end even');

            // not first end data point
            } else {
                // square
                    var square_width = fish[i].length/total * width;
                    c.fillStyle = get_fillstyle(fish[i].category);
                    c.fillRect(label_x_location, 0, square_width, graph_height);
                    // c.strokeStyle = "black";
                    // c.lineWidth = 1;
                    // c.rect(label_x_location, 0, square_width, graph_height);
                    // c.moveTo(label_x_location, 0);
                    // c.lineTo(label_x_location, graph_height);
                    // c.stroke();
                    c.moveTo(label_x_location + square_width, 0);
                    c.lineTo(label_x_location + square_width, graph_height);
                    c.stroke();
                    // labels if event's longer than 10 min
                    if (fish[i].length > (10 * 60)) {
                        c.font = "12px helvetica neue";
                        c.fillStyle = 'black';
                        c.textAlign = 'center';
                        c.fillText(fish[i].event_name, label_x_location + square_width/2, graph_height/2);
                    }
                    label_x_location = label_x_location + square_width;
            } // if it's an end but not the first one

        } // if it's an end

    } // end of for over each row


    // draw hour lines and hour labels
    for (var i = 1; i < scale_number; i++) {
        // draw (dashed) line
        c.beginPath();
        c.strokeStyle = 'black';
        c.setLineDash([3,10]);
        c.moveTo(width/scale_number * x_loc, 0);
        c.lineTo(width/scale_number * x_loc, graph_height);
        c.stroke();

        // draw hour x axis labels
        c.fillStyle = 'black';
        c.textAlign = 'center';
        c.fillText(label, width/scale_number * x_loc, graph_height + 13);
        label++;
        x_loc++;
    }

    // HR above the labels

    c.beginPath();
    c.moveTo(0,graph_height);
    c.lineTo(width, graph_height);
    c.setLineDash([]);
    c.stroke();



} // end of plot()
