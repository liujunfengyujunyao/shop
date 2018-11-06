
var html = document.getElementsByTagName("html")[0];
var hWidth = html.getBoundingClientRect().width;
html.style.fontSize = hWidth * (100 / 360) + 'px';