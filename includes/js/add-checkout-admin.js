jQuery(document).ready(function(){

    if(document.getElementById('check_nt_note_1').checked) {
    jQuery(".nt_note_1").show(1000);
} else {
    jQuery(".nt_note_1").hide(1000);
}

jQuery('#check_nt_note_1').click(function() {
    jQuery(".nt_note_1").toggle(1000);
});


if(document.getElementById('check_nt_note_2').checked) {
    jQuery(".nt_note_2").show(1000);
} else {
    jQuery(".nt_note_2").hide(1000);
}

jQuery('#check_nt_note_2').click(function() {
    jQuery(".nt_note_2").toggle(1000);
});


})