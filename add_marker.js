
// document.getElementById('submit').disabled = 'true';
console.log('flashed');

var entries = document.getElementsByClassName('entry');
for (var i = 0; i < entries.length; i++) {
    entries[i].addEventListener('click', function() {
        console.log('clicked');
    }, false)
};
console.log('flashed');
