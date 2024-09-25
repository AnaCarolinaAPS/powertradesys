<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Funcionario extends Model
{
    use HasFactory;

    protected $fillable = [
        'ci',
        'nome',
        'email',
        'contato',
        'cargo',
        'data_contratacao'
    ];

    public function servicos()
    {
        return $this->hasMany(ServicosFuncionario::class);
    }

    public function folhas_pagamento()
    {
        return $this->hasMany(FolhaPagamento::class);
    }

    public function ferias()
    {
        return $this->hasMany(Ferias::class);
    }

    public function ferias_pendente()
    {
        // Data de contratação do funcionário
        $dataContratacao = Carbon::parse($this->data_contratacao);

        // Data atual
        $hoje = Carbon::now();

        // Calcular anos trabalhados (a partir da data de contratação)
        $anosTrabalhados = $dataContratacao->diffInYears($hoje);

        // Calcular dias de férias disponíveis (12 dias por ano trabalhado)
        $diasDeFeriasDisponiveis = $anosTrabalhados * 12;

        // Calcular quantos dias de férias o funcionário já tirou
        $diasDeFeriasUsados = 0;

        foreach ($this->ferias as $ferias) {
            // Diferença em dias entre a data de início e a data de fim das férias
            $diasFerias = Carbon::parse($ferias->data_inicio)->diffInDays(Carbon::parse($ferias->data_fim)) + 1;
            $diasDeFeriasUsados += $diasFerias;
        }

        // Dias de férias pendentes
        $diasFeriasPendentes = $diasDeFeriasDisponiveis - $diasDeFeriasUsados;

        // Caso o cálculo resulte em um número negativo, retorna 0
        return max($diasFeriasPendentes, 0);
    }
}
