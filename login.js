// window.addEventListener('load', function() {

  var inputs = document.getElementsByTagName('input');
  var submit = document.getElementById('register');
  var ups = document.getElementsByClassName('up');

  function everything_is_complete() {
    var empty = [];
    // check all inputs if values
    for (var i = 0; i < inputs.length; i++) {
      if (inputs[i].value == "") { // input is empty
        empty.push('1');
      }
    }
    if (empty.length > 0) {
      return 0;
    } else {
      return 1;
    }
  } // end of is_everything_empty

  function check(id) {
    document.getElementById(id + '_check').style.display = 'inline';
    document.getElementById(id + '_cross').style.display = 'none';
  }

  function cross(id) {
    document.getElementById(id + '_check').style.display = 'none';
    document.getElementById(id + '_cross').style.display = 'inline';
  }

  function are_all_crosses_shown() {
      var errors = 0;
      for (var i = 0; i < ups.length; i++) {
          if (ups[i].style.display == '' || ups[i].style.display == 'none') {
              errors++;
          }
      }
      if (errors > 0) {
          submit.disabled = true;
      } else {
          submit.disabled = false;
      }
  }

  function validate_registration(e) {
    switch (this.name) {
      case 'first_name':
        if (this.value == '') { cross(this.id);
        } else { // there is a value
          check(this.id);
        }
        are_all_crosses_shown();
        break;
      case 'last_name':
        if (this.value == '') {
          cross(this.id);
        } else { // there is a value
          check(this.id);
        }
        are_all_crosses_shown();
        break;
      case 'username':
      if (this.value == '') {
        cross(this.id);
      } else { // there is a value
        check(this.id);
      }
        break;
        are_all_crosses_shown();
      case 'pass1':
        if (this.value == '') {
          cross(this.id);
        } else { // there is a value
          check(this.id);
        }
        are_all_crosses_shown();
        break;
      case 'pass2':
        if (this.value == document.getElementById('pass1').value) { // accurate
          check(this.id);
        } else {
          cross(this.id);
        }
        are_all_crosses_shown();
        break;
    } // end of switch
  } // end of validate_registration

  for (var i = 0; i < inputs.length; i++) {
    inputs[i].addEventListener('blur', validate_registration, false);
  }

// }, false); // document.onlad
