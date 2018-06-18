
function selectAll(el) {
    var checkboxes = el.closest('ul').querySelectorAll('input[type="checkbox"]');

    for (var i = 0; i < checkboxes.length; i++) {
        if (checkboxes[i] != el)
            checkboxes[i].checked = el.checked;
    }

}

var parents = p =document.querySelectorAll('input[data-parent="0"]');
//console.log(c);
//document.querySelectorAll('input[data-parent="0"]').onclick = selectCities;

for(i = 0; i < parents.length;i++) {
    parents[i].addEventListener('click', function(){
        t=this;
        //this.dataset.parent;
        // find all elements that have data-parent = parentID and check them

        var children = c = document.querySelectorAll('input[data-parent="' + this.dataset.parent + '"]');
        //console.log("I love js");
    });
}

// If the user clicks in the window, set the background color of <body> to yellow
function selectCities(el) {
    e=el;
    console.log(el);
    document.getElementsByTagName("BODY")[0].style.backgroundColor = "yellow";
}