<?php

namespace Softpay\ Parcelamento\ Services;

use App\Http\Controllers\Controller;
use Softpay\Parcelamento\Contracts\CustomServiceInterface;

class Parcelamento {
	
	# Declaração das variáveis
	private $valorTotal;
	private $parcelas;
	private $parcelasSemJuros;    
    private $parcelaMinima;
    private $mdr;
	
	# Método construtor (Parâmetros necessários para utilizar a função)
	public function __construct($valorTotal = 0, $parcelas = 1, $parcelasSemJuros = 0, $parcelaMinima = 0, $mdr = 0){
		
		$this->valorTotal        = ( isset($valorTotal) ? number_format($valorTotal, 2, '.', '') : 0 );
        $this->parcelas          = $parcelas;
        $this->parcelasSemJuros  = $parcelasSemJuros;
        $this->parcelaMinima     = number_format($parcelaMinima, 2, '.', '');
        $this->mdr               = $mdr;
		
    }
	
	# Função para simular um parcelamento
	public function simularVenda(){
		
		
	}
	
	# Função para gerar as parcelas
	public function gerarRecebiveis(){
		
		# Calcula o valor por parcela
		$calculoParcelas = $this->parcelasComMDR();
		
		# Cria um array das parcelas
		$parcelas = array();
		
		# Cria a coleção que será retornada
		$object = collect();	
		
		# Valor total da transação
		$object->put('grossAmount', str_replace(".", "", $this->valorTotal));	
		
		# Valor líquido da transação
		$object->put('netAmount', ltrim(str_replace(".", "", $calculoParcelas->valorLiquido), "0"));			
		
		# Desconto aplicado no valor total
		$object->put('fee', ltrim(str_replace(".", "", $calculoParcelas->descontoMDR), "0"));	
		
		# Caso seja apenas uma parcela
		$parcelas[1] = [
			'fee' 		  => ltrim($calculoParcelas->taxaPrimeiraParcela, "0"),
			'installment' => 1,
			'amount' 	  => ltrim($calculoParcelas->parcelas['primeira'], "0")
		];			
		
		# Calcula as demais parcelas
		for ( $i = 2; $i <= $this->parcelas; $i++ ) {
							
			# Adiciona as parcelas dentro do objeto installments
			$parcelas[$i] = [
				'fee'         => ltrim($calculoParcelas->taxaPorParcela, "0"),
			    'installment' => $i,
			    'amount'      => ltrim($calculoParcelas->parcelas['demais'], "0")
			];	
				
			 	
		}
		
		# Inclui as parcelas na coleção
		$object->put('installments', $parcelas);
		
		# Retorna o objeto
		return $object;
		
	}
	
	# Cálcula a taxa por parcela
	private function aplicaMDR() {
		
		# Inicializa o objeto a ser retornado
		$object 			         = new \stdClass();
		
		# Aplica o MDR para calcular o desconto a ser aplicado
		$taxaDesconto 		         =  number_format(floor(($this->valorTotal / 100 * $this->mdr) * 100) / 100, 2, '.', '');	
		
		# Valor líquido da transação
		$object->valorLiquido        = number_format($this->valorTotal - $taxaDesconto, 2, ".", "");
		
		# Adiciona as variáveis no objeto a ser retornado
   		$object->descontoMDR 	     = $taxaDesconto;
		
		# Desconto que deve ser aplicado por parcela
		$object->taxaPorParcela      = number_format(floor(($taxaDesconto / $this->parcelas) * 100) / 100, 2, ".", "");
		
		# Verifica se existe sobra na divisão da taxa de desconto / parcela
		$object->taxaPrimeiraParcela = number_format($taxaDesconto - floor(($object->taxaPorParcela * ($this->parcelas - 1)) * 100) / 100, 2, ".", "");
						
		# Retorna o objeto
		return $object;
		
	}
	
	# Cálculo de parcelas com juros
	private function parcelasComMDR(){		
		
		# Valor líquido da venda, já descontado o MDR
		$object = $this->aplicaMDR();
		
		# Calcula o valor por parcela
		$valorPorParcela 	    = number_format($object->valorLiquido / $this->parcelas, 2, ".", "");				
				
		# Adiciona as variáveis no objeto a ser retornado
   		$object->parcelas['primeira'] = number_format($object->valorLiquido - floor(( ($valorPorParcela * ($this->parcelas - 1))) * 100) / 100, 2, ".", "");
		
		# Sobra da divisão das parcelas
		$object->parcelas['demais']  = $valorPorParcela;
						
		# Retorna o objeto
		return $object;	
		
		
	}
	
	########## Métodos SET ##############
	public function setValorTotal($valorTotal){
		$this->valorTotal = number_format($valorTotal, 2, '.', '');
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
	
	public function setMDR($mdr) {
        $this->mdr = $mdr;
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
        return $this->mdr;
    }
    
	
}