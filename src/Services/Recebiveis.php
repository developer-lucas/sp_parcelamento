<?php

namespace Softpay\ Parcelamento\ Services;

use App\Http\Controllers\Controller;
use Softpay\Parcelamento\Contracts\CustomServiceInterface;

class Recebiveis {
	
	# Declaração das variáveis
	private $valorTotal;
	private $parcelas;
	private $parcelasSemJuros;    
    private $parcelaMinima;
    private $mdr;
	
	# Método construtor (Parâmetros necessários para utilizar a função)
	public function __construct($valorTotal, $parcelas, $parcelasSemJuros, $parcelaMinima, $mdr){
		
		$this->valorTotal        = number_format($valorTotal, 2, '.', '');
        $this->parcelas          = $parcelas;
        $this->parcelasSemJuros  = $parcelasSemJuros;
        $this->parcelaMinima     = number_format($parcelaMinima, 2, '.', '');
        $this->mdr               = $mdr;
		
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
		$object->put('netAmount', str_replace(".", "", $calculoParcelas->valorLiquido));			
		
		# Desconto aplicado no valor total
		$object->put('fee', str_replace(".", "", $calculoParcelas->taxaDesconto));	
		
		# Caso seja apenas uma parcela
		$parcelas[] = [
			'fee' 		  => $calculoParcelas->mdr['primeira'],
			'installment' => 1,
			'amount' 	  => $calculoParcelas->parcelas['primeira']
		];			
		
		# Calcula as demais parcelas
		for ( $i = 2; $i <= $this->parcelas; $i++ ) {
							
			# Adiciona as parcelas dentro do objeto installments
			$parcelas[] = [
				'fee'         => $calculoParcelas->mdr['demais'],
			    'installment' => $i,
			    'amount'      => $calculoParcelas->parcelas['demais']
			];	
				
			 	
		}
		
		# Inclui as parcelas na coleção
		$object->put('installments', $parcelas);
		
		# Retorna o objeto
		dd($object);
		return $object;
		
	}
	
	# Cálcula a taxa por parcela
	private function aplicaMDR() {
		
		# Inicializa o objeto a ser retornado
		$object 			    = new \stdClass();
		
		# Aplica o MDR para calcular o desconto a ser aplicado
		$taxaDesconto 		    =  number_format(floor(($this->valorTotal / 100 * $this->mdr) * 100) / 100, 2, '.', '');	
		
		# Adiciona as variáveis no objeto a ser retornado
   		$object->descontoMDR 	= $taxaDesconto;
		
		# Desconto que deve ser aplicado por parcela
		$object->taxaPorParcela = number_format(floor(($taxaDesconto / $this->parcelas) * 100) / 100, 2, '.', '');
		
		# Verifica se existe sobra na divisão da taxa de desconto / parcela
		$object->sobraTaxaPorParcela = number_format($taxaDesconto - floor(($object->taxaPorParcela * $this->parcelas) * 100) / 100, 2, '.', '');
						
		# Retorna o objeto
		return $object;
		
	}
	
	# Cálcula o valor por parcela de um valor
	private function parcelasSemJuros() {	
		
	}
	
	private function valorLiquido(){
		
		# Calcula o MDR
		$mdr = $this->aplicaMDR();
		
		# Inicializa o objeto a ser retornado
		$object 		             = new \stdClass();
		$object->valor               = number_format($this->valorTotal - $mdr->descontoMDR, 2, ".", "");
		$object->mdr['total']        = $mdr->descontoMDR;
		$object->mdr['primeiraParc'] = number_format($mdr->taxaPorParcela + $mdr->sobraTaxaPorParcela, 2, ".", "");
		$object->mdr['demaisParc']   = number_format($mdr->taxaPorParcela, 2, ".", "");
						
		# Retorna o calculo
		return $object;
		
	}
	
	# Cálculo de parcelas com juros
	private function parcelasComMDR(){		
		
		# Valor líquido da venda, já descontado o MDR
		$valorLiquido = $this->valorLiquido(); 
				
		# Inicializa o objeto a ser retornado
		$object = new \stdClass();
		
		# Calcula o valor por parcela
		$valorPorParcela 	    = number_format($valorLiquido->valor / $this->parcelas, 2, ".", "");
		
		# Calcula a sobra da divisão
		$sobraDivisaoParcela    = floor(($valorLiquido->valor - ($valorPorParcela * $this->parcelas)) * 100) / 100;
				
		# Adiciona o valor líquido da venda no objeto
		$object->valorLiquido    = $valorLiquido->valor;
		
		# Desconto do MDR
		$object->taxaDesconto   = $valorLiquido->mdr['total'];		
		
		# MDR aplicado por parcela
		$object->mdr['primeira'] = str_replace(".", "", $valorLiquido->mdr['primeiraParc']);
		
		# MDR aplicado por parcela
		$object->mdr['demais'] = str_replace(".", "", $valorLiquido->mdr['demaisParc']);
		
		# Adiciona as variáveis no objeto a ser retornado
   		$object->parcelas['primeira'] = str_replace(".", "", number_format($valorPorParcela + $sobraDivisaoParcela, 2, ".", ""));
		
		# Sobra da divisão das parcelas
		$object->parcelas['demais']  = str_replace(".", "", $valorPorParcela);
						
		# Retorna o objeto
		return $object;	
		
		
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