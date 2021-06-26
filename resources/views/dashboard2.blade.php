<x-app-layout>
    <x-slot name="header">
        <div class="row">
            <div class="col-md-8">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Lista de agendamentos') }}
                </h2>
                <a href="{{ route('dashboard') }}">
                    <small>Atualizar página <i class="fas fa-redo"></i> </small>
                </a>
            </div>
            <div class="col-md-4" style="text-align: right;">
                <a href="{{route('solicitacao.candidato')}}">
                    <button class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                        Fazer agendamento
                    </button>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="container">

                @include('subviews.filtros', ['rota' => "dashboard"])

                <div class="row">
                    @if(session('mensagem'))
                    <div class="col-md-12">
                        <div class="alert alert-success" role="alert">
                            <p>{{session('mensagem')}}</p>
                        </div>
                    </div>
                    @endif
                </div>
                @include('subviews.list_agendamentos')
            </div>
        </div>
    </div>


@if(old('edit_agendamento_id') != null)
    <script>
        $(document).ready(function() {
            $('#visualizar_candidato_{{old('edit_agendamento_id')}}').modal('show');
            $('#btn_edit_{{old('edit_agendamento_id')}}').click();
        })
    </script>
@endif
<script>
    function mostrarFiltro(check, id) {
        if(check.checked) {
            document.getElementById(id).style.display = "block";
        } else {
            document.getElementById(id).style.display = "none";
        }
    }

    function selecionar_posto(posto_selecionado, id) {
        document.getElementById('seletor_data_'+id).innerHTML = "";
        document.getElementById('seletor_horario_'+id).innerHTML = "";
        $.ajax({
            url: "{{route('dias.posto.ajax')}}",
            method: 'GET',
            type: 'GET',
            data: {
                posto_id: posto_selecionado.value,
            },
            statusCode: {
                404: function() {
                    alert("Nenhum posto encontrado");
                }
            },
            success: function(data){
                console.log(data);
                var htmlDatas = "";
                var htmlHorarios ="";
                if (data != null) {
                    htmlDatas += `<label for="dia_vacinacao_${id}" class="style_titulo_input">DIA DA VACINAÇÃO<span class="style_titulo_campo">*</span><span class="style_subtitulo_input"> (obrigatório)</span></label>
                            <select id="dia_vacinacao_${id}" class="form-control style_input" name="dia_vacinacao_${id}" required onchange="selecionar_dia_vacinacao(this, ${id})"><option selected disabled>-- Selecione o dia --</option>`;
                    $.each(data, function(i, obj) {
                        htmlDatas += `<option value="${i}">${i}</option>`;
                    });
                    htmlDatas += `</select>`;

                    $.each(data, function(i, obj) {
                        htmlHorarios += `<div class="seletor_horario_dia_div_${id}"  id="seletor_horario_dia_${i}_${id}" style="display:none;">
                                    <div class="row horario_vacina_div">
                                        <div class="form-group col-md-12" style="width: 100%;">
                                            <label for="dia_vacinacao" class="style_titulo_input">HORÁRIO DA VACINAÇÃO<span class="style_titulo_campo">*</span><span class="style_subtitulo_input"> (obrigatório)</span></label>
                                            <select id="select_horario_input_${i}_${id}" name="hora_${id}" class="form-control style_input">
                                                <option selected disabled>-- Selecione o horário --</option>`;
                        $.each(obj, function(c, obj_include) {
                            var data_horario = (new Date(obj_include)).toString();
                            htmlHorarios += `<option value="${data_horario.substring(16,21).split(':').join(':')}">${data_horario.substring(16,21).split(':').join(':')}</option>`;
                        });

                        htmlHorarios += `</select>
                                        </div>
                                    </div>
                                </div>`;
                    });
                }
                $('#seletor_data_'+id).append(htmlDatas);
                $('#seletor_horario_'+id).append(htmlHorarios);
            },
            error:function(data){
                alert('Houve algum erro, entre em contato com a administração do site.');
            },
        })
    }

    function selecionar_dia_vacinacao(select_dia, id) {
        var divHorarios = document.getElementById('seletor_horario_'+id);
        var divHoras = document.getElementById("seletor_horario_dia_"+select_dia.value+"_"+id);

        for (var i = 0; i < divHorarios.children.length; i++) {
            var inputHoras = divHorarios.children[i].children[0].children[0].children[1];
            if (divHoras == divHorarios.children[i]) {
                divHorarios.children[i].style.display = "";
                inputHoras.setAttribute('name', "horario_vacinacao_"+id);
                inputHoras.required = true;
            } else {
                divHorarios.children[i].style.display ="none";
                inputHoras.selectedIndex = 0;
                inputHoras.setAttribute('name', "");
                inputHoras.required = false;
            }
        }

    }

    function reagendar(id, bool) {
        if (bool) {
            document.getElementById("editar_agendado_para_"+id).style.display = "block";
            document.getElementById("agendado_para_"+id).style.display = "none";
        } else {
            document.getElementById("editar_agendado_para_"+id).style.display = "none";
            document.getElementById("agendado_para_"+id).style.display = "block";
        }
    }

    function desabilitar(btn, idForm) {
        btn.disabled = true;
        var form = document.getElementById(idForm);
        form.submit();
    }


    /*
    function filtrar() {
        $.ajax({
            url: "{{route('agendamentos.filtro.ajax')}}",
            method: 'GET',
            type: 'GET',
            data: {
                nome_check: document.getElementById('nome_check_input').checked,
                cpf_check: document.getElementById('cpf_check_input').checked,
                data_check: document.getElementById('data_check_input').checked,
                dose_check: document.getElementById('dose_check_input').checked,
                outro: document.getElementById('outro').checked,
                aprovado: document.getElementById('aprovado').checked,
                reprovado: document.getElementById('reprovado').checked,
                nome: document.getElementById('nome').value,
                cpf: document.getElementById('cpf').value,
                data: document.getElementById('data').value,
                dose: document.getElementById('dose').value,
                field: document.getElementById('field').value,
                order: document.getElementById('order').value,
            },
            statusCode: {
                404: function() {
                    alert("Nenhum posto encontrado");
                }
            },
            success: function(data){
                console.log(data);
                 var html = "";
                if (data != null) {
                    if (data.length > 0) {
                        $.each(data, function(i, obj) {
                            html += ``
                        })
                    }
                }
                document.getElementById('agendamentos').innerHTML = "";
                $('#agendamentos').append(html);
            },
            error:function(data){
                console.log('erro');
                alert('Erro'.data);
            },
        })
    }*/
</script>
</x-app-layout>
