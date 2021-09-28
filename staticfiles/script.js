$(document).ready(function () {
    $('.page-link').on('click', function(e){
        e.preventDefault();
        pesq = $('#pesquisa').val()
        filter_table = $('select[id=filter_table] option').filter(':selected').val()
        pagination = $(this).attr('href')
        $.ajax({
            url: 'inc/filter.php',
            type: "POST",
            data: {'pagination':pagination, 'filterObj':filter_table, 'search':pesq},
            datType:'JSON',
            success: function(res){
                $('.filter_data').html(res)
            }
        })

    })
    $('.delete_time').on('click', function() {
        pesq = $('#pesquisa').val()
        var id = $(this).attr("id");
        var time = $(this).attr("name")
        Swal.fire({
            icon: 'warning',
            title: '<strong>Exclusão de time</strong>',
            
            html:
                'Tem certeza que deseja excluir o time: <b>'+time+'</b> ?',
            showCloseButton: true,
            showCancelButton: true,
            focusConfirm: false,
            confirmButtonText:
                'Excluir',
            cancelButtonText:
                'Cancelar',
        }).then((value) => {
            if(value.isConfirmed){

                $.ajax({
                    url: 'inc/filter.php',
                    type: "POST",
                    data: {'delete_time':id, 'search':pesq},
                    datType:'JSON',
                    success: function(res){
                        $('.filter_data').html(res)
                        Swal.fire({
                            icon: 'success',
                            title:'O time '+time+' foi apagado',
                            showConfirmButton: false,
                            timer: 2000
                        });
                    }
                })

            }
            
        }) 
        
    })
    
})
