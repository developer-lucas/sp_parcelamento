<?php

namespace Softpay\Parcelamento\Facades;

use Illuminate\Support\Facades\Facade;

class ParcelamentoFacade extends Facade {
	
    protected static function getFacadeAccessor() {
		
        return 'parcelamento';
		
    }
	
}