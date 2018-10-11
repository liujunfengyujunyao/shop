// var pdoc = window.parent.document;
// var html = document.getElementById("rootHTML") ? document.getElementById("rootHTML") : pdoc.getElementById("rootHTML");
if(document.getElementById("rootHTML"))
{
	var html = document.getElementById("rootHTML")
	var hWidth = html.getBoundingClientRect().width;
	html.style.fontSize = hWidth * (100 / 360) + 'px';
}
else
{
	var html = document.getElementsByTagName("html")[0];
	var roothtml = window.parent.document.getElementById("rootHTML");
	console.log(roothtml.style.fontSize);
	html.style.fontSize = roothtml.style.fontSize;
}


// console.log(getParentSize());
// var hWidth = html.getBoundingClientRect().width;
// html.style.fontSize = hWidth * (100 / 360) + 'px';
