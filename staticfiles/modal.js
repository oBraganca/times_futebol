$(document).ready(function () {
    var time = $('#recipient-name')
    var estado =  $('#estado')
    var bool_button = false

    $('#form1').on('submit',function(e) {
        e.preventDefault();
        pesq = $('#pesquisa').val()
        $.ajax({
            url: 'inc/filter.php',
            type: "POST",
            data: {'search':pesq},
            datType:'JSON',
            beforeSend: function(){
            },
            success: function(res){
                $('.filter_data').html(res)
            }
        })
    });
    filter_table = $('select[id=filter_table] option').filter(':selected').val()
    $('#filter_table').on('change', function () {
        console.log('a')
        pesq = $('#pesquisa').val()
        _filterObj = this.value
        $.ajax({
            url: 'inc/filter.php',
            type: "POST",
            data: {'filterObj':_filterObj, 'search':pesq},
            datType:'JSON',
            success: function(res){
                $('.filter_data').html(res)
            }
        })
    });

    $('button[name=success]').addClass('btn-times btn-light')
    
    time.bind('keyup',function(e) {
        if(time.val()){
            bool_button = true
        }else{
            bool_button=false
            $('button[name=success]').addClass('btn-times btn-light')
        }
        if(estado.val() == $("#estado option:first").val()){
            bool_button = false
            $('button[name=success]').addClass('btn-times btn-light')
        }
        if(bool_button){
            $('button[name=success]').removeClass('btn-times btn-light')
            $('button[name=success]').addClass('btn-success')
        }
    });
    
    estado.on('change', function () {
        if(estado.val() == $("#estado option:first").val()){
            bool_button = false
        }else{
            if(time.val()){
                bool_button = true
            }else{
                bool_button = false
                $('button[name=success]').addClass('btn-times btn-light')
            }
        }
        if(bool_button){
            $('button[name=success]').removeClass('btn-times btn-light')
            $('button[name=success]').addClass('btn-success')
        }
    });


    $('#add_fut').on('submit',function(e) {
        e.preventDefault();
        pesq = $('#pesquisa').val()
        if(bool_button){
            $.ajax({
                url: 'inc/filter.php',
                type: "POST",
                data: {'time':time.val(),'estado':estado.val(), 'search':pesq},
                datType:'JSON',
                beforeSend: function(){
                    $('#exampleModal').modal({hide:true});
                },
                success: function(res){
                    $('.filter_data').html(res)
                    $('#exampleModal').modal('hide')
                    $('#add_fut').trigger('reset')
                    sucesso()
                    
                }
            })
        }  
    })

    
    $('#exampleModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget) 
        var recipient = button.data('whatever')
        
        var modal = $(this)
    })

    function sucesso() {
        Swal.fire({
            position: 'top',
            icon: 'success',
            title: 'Time criado e disponivel com sucesso.',
            showConfirmButton: false,
            timer: 1500
        })
    }
    
})
