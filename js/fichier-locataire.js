$(function(){
    $('.handle-default-action').on('click', function(e) {
        e.preventDefault();
        $('#TelechargerModele').modal('show');
        let url = $(this).closest('ul.dropdown-menu').attr('data-url');
        var type = `<div class="card d-none type" data-file-type="${$(this).attr('data-file-type')}"></div>`
        $.get(url, function(form){
            $('#modal-body-content').append(type + form);
            $('#file-spinner').addClass('d-none');
        });
    });

    $('#TelechargerModele').on('hidden.bs.modal', event => {
        $('#file-spinner').removeClass('d-none');
        $('#modal-body-content .card').remove()
    })
})
