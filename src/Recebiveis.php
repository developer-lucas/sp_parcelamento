<?php

namespace Softpay\ Parcelamento;

use App\Http\Controllers\Controller;
use Softpay\Parcelamento\Contracts\CustomServiceInterface;
use Softpay\Parcelamento\Files\Filesystem;
use Softpay\Parcelamento\Files\TemporaryFile;

class Recebiveis {
	
	# Declaração das variáveis
	private $valorTotal;
	private $parcelas;
	private $parcelasSemJuros;    
    private $parcelaMinima;
    private $mdr;
	protected $filesystem;
	
	# Método construtor (Parâmetros necessários para utilizar a função)
	public function __construct($valorTotal = 0, $parcelas = 1, $parcelasSemJuros = 0, $parcelaMinima = 0, $mdr = 0){
				
		$this->valorTotal        = str_replace(".", "", $valorTotal);
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
		$calculoParcelas = $this->aplicaMDR();
		
		# Cria um array das parcelas
		$parcelas = array();
		
		# Cria a coleção que será retornada
		$object = new \stdClass();	
		
		# Valor total da transação
		$object->grossAmount = str_replace(".", "", $this->valorTotal);	
		
		# Valor líquido da transação
		$object->netAmount = str_replace(".", "", ltrim($calculoParcelas->valorLiquido, "0"));			
		
		# Desconto aplicado no valor total
		$object->fee = str_replace(".", "", ltrim($calculoParcelas->descontoMDR, "0"));	
		
		# Caso seja apenas uma parcela
		$parcelas[1] = [
			'fee' 	 => str_replace(".", "", ltrim($calculoParcelas->taxaPrimeiraParcela, "0")),
			'amount' => str_replace(".", "", ltrim($calculoParcelas->valorPrimeiraParcela, "0"))
		];			
		
		# Calcula as demais parcelas
		for ( $i = 2; $i <= $this->parcelas; $i++ ) {
							
			# Adiciona as parcelas dentro do objeto installments
			$parcelas[$i] = [
				'fee'    => str_replace(".", "", ltrim($calculoParcelas->taxaPorParcela, "0")),
			    'amount' => str_replace(".", "", ltrim($calculoParcelas->valorPorParcela, "0"))
			];	
				
			 	
		}
		
		# Inclui as parcelas na coleção
		$object->installments = $parcelas;
		
		# Retorna o objeto
		return $object;
		
	}
	
	# Verifica a soma das taxas
	private function validaTaxas($object) {
		
		# Soma das taxas
		$somaTaxas = $object->taxaPrimeiraParcela + ($object->taxaPorParcela * ($this->parcelas - 1));
		
		# Se a soma das parcelas ultrapassar o valor líquido
		if ($somaTaxas > $object->descontoMDR) {
			$object->taxaPrimeiraParcela = $object->taxaPrimeiraParcela - ($somaTaxas - $object->valorLiquido);
		};
		
		# Se a soma das parcelas for menor que o valor líquido
		if ($somaTaxas < $object->descontoMDR){
			$object->taxaPrimeiraParcela = $object->descontoMDR - ($object->descontoMDR - $somaTaxas);
		}
		
	}
	
	# Verifica se a soma das parcelas corresponde com o netAmount
	private function validaParcelas($object) {
				
		# Soma das parcelas	
		$somaParcelas = $object->valorPrimeiraParcela + ($object->valorPorParcela * ($this->parcelas - 1));
						
		# Se a soma das parcelas ultrapassar o valor líquido
		if ($somaParcelas > $object->valorLiquido) {
			$object->valorPrimeiraParcela = $object->valorPrimeiraParcela - ($somaParcelas - $object->valorLiquido);
		};
		
		# Se a soma das parcelas for menor que o valor líquido
		if ($somaParcelas < $object->valorLiquido){
			$object->valorPrimeiraParcela = $object->valorLiquido - ($object->valorLiquido - $somaParcelas);
		}
		
	}
	
	# Cálcula a taxa por parcela
	private function aplicaMDR() {
		
		# Inicializa o objeto a ser retornado
		$object 			          = new \stdClass();
		
		# Adiciona as variáveis no objeto a ser retornado
   		$object->descontoMDR 	      = number_format(($this->valorTotal / 100) / 100 * $this->mdr, 2);	
		
		# Valor líquido da transação
		$object->valorLiquido         = number_format(($this->valorTotal / 100) - $object->descontoMDR, 2, ".", "");
					
		# Desconto que deve ser aplicado por parcela
		$object->taxaPorParcela       = number_format($object->descontoMDR / $this->parcelas, 2, ".", "");
				
		# Verifica se existe sobra na divisão da taxa de desconto / parcela
		$object->taxaPrimeiraParcela  = number_format($object->descontoMDR - ($object->taxaPorParcela * ($this->parcelas - 1)), 2, ".", "");
				
		# Calcula o valor por parcela
		$object->valorPorParcela      = number_format($object->valorLiquido / $this->parcelas, 2, ".", "");
				
		# Adiciona as variáveis no objeto a ser retornado
   		$object->valorPrimeiraParcela = number_format($object->valorLiquido - floor(( ($object->valorPorParcela * ($this->parcelas - 1))) * 100) / 100, 2, ".", "");
					
		# Verifica os cálculos das parcelas
		$this->validaParcelas($object);
		
		# Verifica os cálculos das taxas
		$this->validaTaxas($object);
		
		# Retorna o objeto
		return $object;
		
	}
	
	########## Métodos SET ##############
	public function setValorTotal($valorTotal){
		$this->valorTotal = str_replace(".", "", $valorTotal);
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