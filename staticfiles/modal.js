$(document).ready(function () {
    var time = $('#recipient-name')
    var estado =  $('#estado')
    var bool_button = false
    /* Vai receber o evento submit e usa ro e.preventDefault para nao ocorrer o evento */
    $('#form1').on('submit',function(e) {
        e.preventDefault();
        /* Pega o valor atual da quantidade por pagina, isso porque quando for renderizar o html com o ajax
            e nao ignorar a quantidade selecionada anteriomente 
        */
        filter_table = $('select[id=filter_table] option').filter(':selected').val()
        /*  desc_or_asc =$('#invert').val()
            pesq = $('#pesquisa').val()
            Mesma coisa que acontece com o filter table    
        */
        desc_or_asc =$('#invert').val()
        pesq = $('#pesquisa').val()

        /* O ajax, evia tudo que pegamos anteriomente via post, vai vai enviar os dados pra fazer as query */
        $.ajax({
            url: 'inc/filter.php',
            type: "POST",
            data: {'search':pesq, 'desc_or_asc': desc_or_asc,'filterObj':filter_table},
            datType:'JSON',
            success: function(res){
                $('.filter_data').html(res)
            }
        })
    });
    filter_table = $('select[id=filter_table] option').filter(':selected').val()
    $('#filter_table').on('change', function () {
        desc_or_asc =$('#invert').val()
        pesq = $('#pesquisa').val()
        _filterObj = this.value
        $.ajax({
            url: 'inc/filter.php',
            type: "POST",
            data: {'filterObj':_filterObj, 'search':pesq, 'desc_or_asc': desc_or_asc},
            datType:'JSON',
            success: function(res){
                $('.filter_data').html(res)
            }
        })
    });

    $('button[name=success]').addClass('btn-times btn-light')
    
    time.bind('keyup',function(e) {
        /* Evento keyup para capturar as teclas digitadas, e convefere se tem o valor
            se não, vai dar para a variavel false, se tiver, True e dar uma classe que nao vai deixar
            o button clicavel
        */
        if(time.val()){
            bool_button = true
        }else{
            bool_button=false
            $('button[name=success]').addClass('btn-times btn-light')
        }
        /* Mesmo esquema, se tiver selecionado o primeiro (que é o option selected) vai retornar false */
        if(estado.val() == $("#estado option:first").val()){
            bool_button = false
            $('button[name=success]').addClass('btn-times btn-light')
        }
        /* se for true retorna uam classe que deixa clicavel e tira as classes que impossibilita */
        if(bool_button){
            $('button[name=success]').removeClass('btn-times btn-light')
            $('button[name=success]').addClass('btn-success')
        }
    });

    /* Mesmo esquema do de cima */
    
    estado.on('change', function () {
        if(estado.val() == $("#estado option:first").val()){
            bool_button = false
            $('button[name=success]').addClass('btn-times btn-light')
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


    /*
    
    O submit cancelado do modal, vai enviar todos os dados necessarios para nao serem ignorado e quebrarem o layout
    
    Quando for enviado o modal vai ser fechado com o beforeSend do ajax, e quando success, vai renderizar a responta com .html()

    Vai resetar os campos do modal, o select optione o input

    e o sucesso() vai mostrar um sweetalert para mostrar sucesso

    e colocando as classes para deixar inclicavel

    */
    $('#add_fut').on('submit',function(e) {
        e.preventDefault();
        filter_table = $('select[id=filter_table] option').filter(':selected').val()
        desc_or_asc =$('#invert').val()
        pesq = $('#pesquisa').val()
        if(bool_button){
            $.ajax({
                url: 'inc/filter.php',
                type: "POST",
                data: {'time':time.val(),'estado':estado.val(), 'search':pesq, 'desc_or_asc': desc_or_asc, 'filterObj':filter_table},
                datType:'JSON',
                beforeSend: function(){
                    $('#exampleModal').modal({hide:true});
                },
                success: function(res){
                    $('.filter_data').html(res)
                    $('#exampleModal').modal('hide')
                    $('#add_fut').trigger('reset')
                    sucesso()
                    $('button[name=success]').addClass('btn-times btn-light')
                    
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
