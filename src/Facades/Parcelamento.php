<?php

namespace Softpay\Parcelamento\Facades;

use Illuminate\Support\Facades\Facade;

class Parcelamento extends Facade {
	
    protected static function getFacadeAccessor() {
		
        return 'parcelamento';
		
    }
	
}