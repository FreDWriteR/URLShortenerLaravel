var longURL = $('#formToShort').find('input[name="longURL"]').val();
$(document).ready(function () {
    $("#formToShort").on('submit', function (e){
        e.preventDefault();
        var form = $(this);
        $.ajax({
            url: '/long-to-short-and-save-redirect',
            headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
            type: 'post',
            data: form.serialize(),
            success: function(response) {
                $('#result_form').html('Твой новый короткий URL: '+response);
            }
        });
        e.preventDefault();
    });
});


