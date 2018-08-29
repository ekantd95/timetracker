for (var i = 0; i < document.getElementsByClassName('cross').length; i++) {
    $('#cross_' + i).hide();
}
// hide all checks
for (var i = 0; i < document.getElementsByClassName('check').length; i++) {
    $('#check_' + i).hide();
};
// $('#add_marker_form').submit(function(event) {
//     event.preventDefault();
// })
document.getElementById('turnin').addEventListener('click', validate_page,false);

function day_of_the_month(d) {
  return (d.getDate() < 10 ? '0' : '') + d.getDate();
}
function month(d) {
    return ((d.getMonth() + 1) < 10 ? '0' : '') + (d.getMonth() + 1);
}

var da = new Date();
var today = da.getFullYear() + '-' + month(da) + '-' + day_of_the_month(da);
var now_ms = da.getTime();

function validate_page() {
        var errors_macro = 0;
        var macro_alterations = 0;
        for (var i = 0; i < 10; i++) {
            if (something_was_altered(i)) {
                // must validate it all
                var name = document.getElementById('event_name_' + i);
                var category = document.getElementById('category_' + i);
                var start = document.getElementById('start_' + i);
                var day = document.getElementById('day_' + i);
                var time = document.getElementById('time_' + i);

                var errors = '';

                if (day.value == '') {
                    errors += 'day value not set ';
                }

                if (time.value == '') {
                    errors += 'time value not set';
                }

                if (category.value == 'none') {
                    errors += 'category value not set';
                }

                // if both day and time were entered
                if (errors.length == 0) {
                    var ymd = day.value.split('-');
                    var hms = time.value.split(':');
                    var stamp = (new Date(ymd[0],ymd[1] - 1,ymd[2],hms[0],hms[1]).getTime())/1000;
                    console.log(stamp);
                    document.getElementById('stamp_' + i).value = stamp;

                    if (day.value == today && (stamp * 1000) > now_ms) {
                        errors += 'can\'t enter markers in the future';
                    }

                }// if there were errors

                // if there are errors show them
                if (errors.length > 0) {
                    document.getElementById('error_' + i).innerHTML = errors;
                    errors_macro += 1;
                }
                macro_alterations++;
            }// end of if something was altered
        } // end of for loop

        // check for duplicate time stamps in the macro set
        var stamps = document.getElementsByClassName('stamp');
        stamps_array = [];
        for (var i = 0; i < stamps.length; i++) {
            if (stamps[i].value !== '') {
                stamps_array.push(stamps[i].value);
            }
        } // end of for loop to populate stamps_array
        var counts = [];
        var errors_dupe = 0;
        for (var i = 0; i < stamps_array.length; i++) {
            if (counts[stamps_array[i]] === undefined) {
                counts[stamps_array[i]] = 1;
            } else {
                errors_macro++;
                errors_dupe++;
            }
        };

        if (macro_alterations == 0) {
            errors_macro++;
        }

        if (errors_macro > 0) {
            if (macro_alterations == 0) {
                document.getElementById('macro').innerHTML = 'You have to enter something.';
            } else if (errors_dupe > 0) {
                document.getElementById('macro').innerHTML = 'You can\'t enter multiple markers with the same day/time';
            }
        } else {
            document.getElementById('macro').innerHTML = '';
            document.getElementById('add_marker_form').submit();
        }

} // end of validate_page()

function something_was_altered(i) {
    var name = document.getElementById('event_name_' + i);
    // var category = document.getElementById('category_' + i);
    var start = document.getElementById('start_' + i);
    var day = document.getElementById('day_' + i);
    var time = document.getElementById('time_' + i);

    var alterations = '';

    if (name.value !== '') {
        alterations += 1;
    }

    if (start.checked == 'true') {
        alterations += 1;
    }

    if (time.value !== "") {
        alterations += 1;
    }

    if (day.value !== "") {
        alterations += 1;
    }

    if (alterations.length == 0) {
        return false;
    } else {
        return true;
    }

}
