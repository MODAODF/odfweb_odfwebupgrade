$(document).ready(function() {
    $('#odfwebupgrade').on('click', 'a.button', function() {
        $.ajax({
            url: OC.generateUrl("/apps/odfwebupgrade/credentials"),
        }).success(function(resp) {
            $('#updaterForm').attr('action', OC.getRootPath()+"/updater/")
            $('#updaterSecret').val(resp);
            $('#updaterForm').submit();
        })
    })
});