<?php

namespace Softpay\ Parcelamento\ Models;

use Illuminate\ Database\ Eloquent\ Model;

class Parcelamento extends Model {
	
	# Declaração das variáveis
	private $valorTotal;
	private $parcelas;
	private $parcelasSemJuros;    
    private $parcelaMinima;
    private $MDR;
	
	# Método construtor
	public function __construct($valorTotal, $parcelas = 1, $parcelasSemJuros = 0, $parcelaMinima = 0, $MDR = 0){
		
		$this->valorTotal        = str_replace(".", "", floor($valorTotal * 100) / 100);
        $this->parcelas          = $parcelas;
        $this->parcelasSemJuros  = $parcelasSemJuros;
        $this->parcelaMinima     = $parcelaMinima;
        $this->MDR               = $MDR;
		
    }
	
	# Função para gerar as parcelas
	public function gerarRecebiveis(){
		
		# Prepara o objeto a ser retornado
		$object 			  = new \stdClass();
		$object->grossAmount  = $this->valorTotal;
		
		# Taxa por parcela
		$taxaPorParcela       = floor($this->MDR / $this->parcelas * 100) / 100;
		
		return $object;
		
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
	
	########## Métodos SET ##############
	public function setValorTotal($valorTotal){
		$this->valorTotal = $valorTotal;
	}
	
	public function setParcelas($parcelas) {
        $this->parcelas = $parcelas;
    }
	
	public function setParcelasSemJuros($parcelasSemJuros) {
        $this->parcelasSemJuros = $parcelasSemJuros;
    }
	
	public function setParcelaMinima($parcelaMinima) {
        $this->parcelaMinima = $parcelaMinima;
    }
	
	public function setMDR($MDR) {
        $this->MDR = $MDR;
    }
	
	########## Métodos GET ##############
	public function getValorTotal(){
		return $this->valorTotal;
	}
	
	public function getParcelas() {
        return $this->parcelas;
    }
	
	public function getParcelasSemJuros() {
        return $this->parcelasSemJuros;
    }
	
	public function getParcelaMinima() {
        return $this->parcelaMinima;
    }
	
	public function getMDR() {
        return $this->MDR;
    }
    
	
}