<?php

namespace Softpay\ Parcelamento\ Models;

use Illuminate\ Database\ Eloquent\ Model;

class Parcelamento extends Model {
	
	# Declaração das variáveis
	private $parcelasSemJuros;
    private $maxParcelas;
    private $valorMinimoParcelar;
    private $porcentagemJuros = 0;
	
	# Método construtor
	public function __construct($parcelas = 0, $parcelasSemJuros = 0, $parcelaMinima = 0, $porcentagemMDR = 0){
		
        $this->parcelas          = $parcelas;
        $this->parcelasSemJuros  = $parcelasSemJuros;
        $this->parcelaMinima     = $parcelaMinima;
        $this->porcentagemMDR    = $porcentagemMDR;
    }
	
	# Função para gerar as parcelas
	public function gerarParcelas(){
		
	}
	
	# Cálculo de parcelas sem juros
	private function parcelasSemJuros(){
		
	}
	
	# Cálculo de parcelas com juros
	private function parcelasComJuros(){
		
	}
	
	# Cálculo do máximo de parcelas, dependendo do valor
	private function maximoParcelas(){
		
	}
	
}