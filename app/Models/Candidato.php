<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Candidato extends Model
{
    use HasFactory,  Notifiable;

    public const SEXO_ENUM = ["Masculino", "Feminino"];
    public const APROVACAO_ENUM = ["Não Analisado", "Aprovado", "Reprovado", "Vacinado"];
    public const DOSE_ENUM = ["1ª Dose", '2ª Dose', "Dose única"];
    public const PROFISSAO_ENUM = ["Acadêmico em saúde e estudante da área técnica em saúde em estágio hospitalar, atenção básica, clínica e laboratório", 
                                   "Agente comunitário de saúde", "Agente de combate às endemias", "Assistente sociail", "Biólogo(a)", "Biomédico(a)",
                                   "Biomédico(a)", "Cuidador(a) de idoso", "Doulas/parteiras", "Enfermeiro(a)", "Farmacêutico(a)", "Fisioterapeuta", 
                                   "Fonoaudiólogo(a)", "Funcionário do sistema funerário", "Funcionário do Instituto Médico Legal (IML)", "Médico(a)",
                                   "Médico(a) veterinário(a)", "Nutricionista", "Odontólogo(a)", "Profissional de educação física", "Profissional da vigilância em saúde",
                                   "Profissional que atua em programas ou serviços de atendimento domiciliar", "Psicólogo(a)", "Serviço de Verificação de Óbito (SVO)",
                                   "Técnicos e auxiliares em geral", "Terapeuta ocupacional", "Trabalhador de apoio geral"];

    protected $fillable = [
        "nome_completo",
        "data_de_nascimento",
        "cpf",
        "numero_cartao_sus",
        "sexo",
        "nome_da_mae",
        "telefone",
        "whatsapp",
        "email",
        "cep",
        "cidade",
        "bairro",
        "logradouro",
        "numero_residencia",
        "complemento_endereco",
        "chegada",
        "saida",
        "lote_id",
        "posto_vacinacao_id",
        "etapa_id",
        "dose",
        "profissional_da_saude",
        "pessoa_idosa",
    ];

    protected $casts = [
        'chegada' => 'datetime',
    ];

    public function etapa() {
        return $this->belongsTo(Etapa::class, 'etapa_id');
    }

    public function getWhatsapp()
    {
        $array =  array("(", ")", "-", " ");
        return str_replace($array, "", $this->whatsapp);
    }

    public function posto() {
        return $this->belongsTo(PostoVacinacao::class, 'posto_vacinacao_id');
    }

    public function lote() {
        return $this->belongsTo(Lote::class, 'lote_id');
    }


    public function data_de_nascimento_dmY() {
        return (new Carbon($this->data_de_nascimento))->format("d/m/Y");
    }

}
