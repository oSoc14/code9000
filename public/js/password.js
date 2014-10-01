$(document).ready(function() {

    // Load the password modal on load
    if ($("#email").val() == '') {
        $("#passwordResetModal").modal('show');
        $("#success").modal('hide');
    } else {
        $("#passwordResetModal").modal('hide');
        $("#success").modal('show');

        $('#success').on('hidden.bs.modal', function () {
            // Go to the landing page
            window.location.replace(window.location.origin);
        })
    }
});
