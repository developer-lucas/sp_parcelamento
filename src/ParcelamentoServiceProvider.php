<?php

namespace Softpay\Parcelamento;

use Illuminate\Support\ServiceProvider;
use Softpay\Parcelamento\Services\Parcelamento;
use Softpay\Parcelamento\Files\Filesystem;
use Softpay\Parcelamento\Files\TemporaryFileFactory;
use Softpay\Parcelamento\Validation;

/*********************************************************************************************************
 * Os provedores de serviços são o local central de toda a inicialização do aplicativo Laravel.          *
 * Seu próprio aplicativo, bem como todos os serviços principais do Laravel, são inicializados por       *
 * meio de provedores de serviços.                                                                       *
 ********************************************************************************************************/

class ParcelamentoServiceProvider extends ServiceProvider
{
    
    public function boot() {
		
    }
    
    public function register() {
		
		$this->app->bind(TemporaryFileFactory::class, function () {
            return new TemporaryFileFactory(
                config('temporary_files.local_path', config('exports.temp_path', storage_path('framework/softpay-logs'))),
                config('temporary_files.remote_disk')
            );
        });
		
		# Registra o sistema de arquivos
		$this->app->bind(Filesystem::class, function () {
            return new Filesystem($this->app->make('filesystem'));
        });
		
		# Registrando os serviços disponíveis no pacote
		$this->app->singleton('parcelamento', function ($app) {
        	return new Parcelamento($app);
    	});
		
		
    }
	
}