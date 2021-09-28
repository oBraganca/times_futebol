$(document).ready(function () {
    var desc_or_asc =$('#invert').val()
    $('#invert_seq').on('click', function(){
        if(desc_or_asc == 'DESC'){
            desc_or_asc = 'ASC'
        }else{
            desc_or_asc = 'DESC'
        }
        pesq = $('#pesquisa').val()
        filter_table = $('select[id=filter_table] option').filter(':selected').val()
        pagination = $(this).attr('href')
        $.ajax({
            url: 'inc/filter.php',
            type: "POST",
            data: {'pagination':pagination, 'filterObj':filter_table, 'search':pesq, 'desc_or_asc': desc_or_asc},
            datType:'JSON',
            success: function(res){
                $('.filter_data').html(res)
            }
        })
    })
    $('.page-link').on('click', function(e){
        e.preventDefault();
        desc_or_asc =$('#invert').val()
        pesq = $('#pesquisa').val()
        filter_table = $('select[id=filter_table] option').filter(':selected').val()
        pagination = $(this).attr('href')
        $.ajax({
            url: 'inc/filter.php',
            type: "POST",
            data: {'pagination':pagination, 'filterObj':filter_table, 'search':pesq, 'desc_or_asc': desc_or_asc},
            datType:'JSON',
            success: function(res){
                $('.filter_data').html(res)
            }
        })

    })
    $('.delete_time').on('click', function() {
        pesq = $('#pesquisa').val()
        desc_or_asc =$('#invert').val()
        filter_table = $('select[id=filter_table] option').filter(':selected').val()
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
                    data: {'delete_time':id, 'search':pesq, 'desc_or_asc': desc_or_asc, 'filterObj':filter_table},
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
