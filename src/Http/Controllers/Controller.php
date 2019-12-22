<?php

namespace Softpay\ Parcelamento\ Http\ Controllers;

use App\ Http\ Controllers\ Controller;
use Illuminate\ Http\ Request;

use Softpay\Parcelamento\Models\Parcelamento;

class Controller extends Controller {
	
	# Função para gerar os recebíveis de uma transação
	public function gerarRecebiveis() {
		
		return Parcelamento::gerarRecebiveis();
		
	}	
	
	# Função para simular o parcelamento de uma venda
	public function simularParcelamento(){
		
		return Parcelamento::simularParcelamento();
		
	}
	
	
}