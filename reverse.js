function reverse(x) {
    var array = [];
    for (var i = x.length - 1; i > 0; i--) {
        array.push(x.charAt(i));
    }
    return array.join();
}
