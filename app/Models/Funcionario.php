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
        // $diasDeFeriasDisponiveis = $anosTrabalhados * 12;

        // Inicializa a variável para armazenar os dias de férias disponíveis
        $diasDeFeriasDisponiveis = 0;

        // Acumula os dias de férias disponíveis com base nos intervalos
        if ($anosTrabalhados > 10) {
            // Se trabalhou mais de 10 anos, somar:
            // 5 anos * 12 dias + 5 anos * 18 dias + anos restantes * 30 dias
            $diasDeFeriasDisponiveis = (5 * 12) + (5 * 18) + (($anosTrabalhados - 10) * 30);
        } elseif ($anosTrabalhados > 5) {
            // Se trabalhou entre 5 e 10 anos, somar:
            // 5 anos * 12 dias + anos restantes * 18 dias
            $diasDeFeriasDisponiveis = (5 * 12) + (($anosTrabalhados - 5) * 18);
        } else {
            // Se trabalhou até 5 anos, somar anos * 12 dias
            $diasDeFeriasDisponiveis = $anosTrabalhados * 12;
        }

        // Calcular quantos dias de férias o funcionário já tirou
        $diasDeFeriasUsados = 0;

        foreach ($this->ferias as $ferias) {
            // Diferença em dias entre a data de início e a data de fim das férias
            $diasFerias = Carbon::parse($ferias->data_inicio)->diffInWeekdays(Carbon::parse($ferias->data_fim)) + 1;
            $diasDeFeriasUsados += $diasFerias;
        }

        // Dias de férias pendentes
        $diasFeriasPendentes = $diasDeFeriasDisponiveis - $diasDeFeriasUsados;

        // Caso o cálculo resulte em um número negativo, retorna 0
        return max($diasFeriasPendentes, 0);
    }
}
