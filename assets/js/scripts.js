
function selectAll(el) {
    var checkboxes = el.closest('ul').querySelectorAll('input[name="locations[]"]');

    for (var i = 0; i < checkboxes.length; i++) {
        if (checkboxes[i] != el)
            checkboxes[i].checked = el.checked;
    }

}

