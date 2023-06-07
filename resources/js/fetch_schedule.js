window.fetchSchedule = function(user, selector) {
    var selectedWeek = jQuery(selector).val();
    var confirmAction = true;

    if (selectedWeek !== '' && jQuery('#fetched_week').attr('data-changed') == 1) {
        confirmAction = confirm('Onopgeslagen wijzigingen weggooien en doorgaan?');
    }

    if (confirmAction === true) {
        jQuery.get( "/admin/user/" + user + "/getSchedule/" + selectedWeek, function( data ) {
            jQuery( "#fetched_week" ).html( data.schedule );
            jQuery('#fetched_week').attr('data-changed', 0);

            jQuery('#fetched_week :input').on('change input', function() {
                jQuery('#fetched_week').attr('data-changed', 1);
            });
        });
    }
}
