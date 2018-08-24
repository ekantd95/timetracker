
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
                // if both day and time were entered
                if (errors.length == 0) {
                    var ymd = day.value.split('-');
                    var hms = time.value.split(':');
                    var stamp = (new Date(ymd[0],ymd[1] - 1,ymd[2],hms[0],hms[1]).getTime())/1000;
                    console.log(stamp);
                    document.getElementById('stamp_' + i).value = stamp;
                }// if there were errors

                // if there are errors show them
                if (errors.length > 0) {
                    document.getElementById('error_' + i).innerHTML = errors;
                    errors_macro += 1;
                }
                macro_alterations++;
            }// end of if something was altered
        } // end of for loop

        if (macro_alterations == 0) {
            errors_macro++;
        }

        if (errors_macro > 0) {
            console.log('hi');
            if (macro_alterations == 0) {
                document.getElementById('macro').innerHTML = 'You have to enter something.';
            }
        } else {
            document.getElementById('macro').innerHTML = '';
            document.getElementById('add_marker_form').submit();
        }

} // end of validate_page()

function something_was_altered(i) {
    var name = document.getElementById('event_name_' + i);
    var category = document.getElementById('category_' + i);
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
