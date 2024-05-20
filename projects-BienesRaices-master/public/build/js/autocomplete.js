$(document).ready(function() {
    $('#buscarLocalidad').autocomplete({
        source: function(request, response) {
            $.ajax({
                url: '/propiedades/buscarLocalidades',
                dataType: 'json',
                data: {
                    term: request.term
                },
                success: function(data) {
                    response(data.map(function(item) {
                        return item.localidad;
                    }));
                }
            });
        },
        minLength: 1,
    });
});
