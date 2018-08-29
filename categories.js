document.getElementById('turnin').addEventListener('click', validate_page, false);
var parsed_data;

function validate_page() {

                var category = document.getElementById('category');
                var color = document.getElementById('color');
                var errors = '';

                if (category.value.trim() == 'none') {
                    errors += 'category can\'t be \'none\'';
                }

                if (category.value.trim() == '') {
                    errors += 'category value not set ';
                }

                if (category.value.trim() == '') {
                    errors += 'color value not set';
                }

                // get names of all users active categories
                console.log('before ajax');
                $.get('max_cat.php',function(data) {
                    console.log('ajax in motion');
                    parsed_data = JSON.parse(data);
                    if (parsed_data.length == 19) {
                        errors += 'you\'ve reached the maximum number of allowable categories';
                    } else {
                        for (var i = 0; i < parsed_data.length; i++) {
                            console.log('i:' + i);
                            if (category.value == parsed_data[i]) {
                                errors += 'can\'t have two categories with the same name';
                                break;
                            }
                        }
                    }

                    if (errors.length > 0) {
                        document.getElementById('error').innerHTML = errors;
                    } else {
                        document.getElementById('categories_form').submit();
                    }

                })// end of ajax


        } // end of validate_page();
