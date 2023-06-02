window.getDynamicWeekField = function(user, selector) {
    var week = jQuery(selector).val();
    var ok = true;

    if (week !== '' && jQuery('#week_inputs').attr('data-changed') == 1) {
        ok = confirm('Heb je de wijzigingen opgeslagen? Anders raak je die kwijt.');
    }

    if (ok === true) {
        jQuery.get( "/admin/user/" + user + "/editDynamicWeekField/" + week, function( data ) {
            jQuery( "#week_inputs" ).html( data.week_html );
            jQuery('#week_inputs').attr('data-changed', 0);

            jQuery('#week_inputs :input').on('change input', function() {
                jQuery('#week_inputs').attr('data-changed', 1);
            });
        });
    }
}
