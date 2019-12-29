<?php

namespace Softpay\Parcelamento;

use Illuminate\Support\ServiceProvider;
use Softpay\Parcelamento\Services\Parcelamento;


/*********************************************************************************************************
 * Os provedores de serviços são o local central de toda a inicialização do aplicativo Laravel.          *
 * Seu próprio aplicativo, bem como todos os serviços principais do Laravel, são inicializados por       *
 * meio de provedores de serviços.                                                                       *
 ********************************************************************************************************/

class ParcelamentoServiceProvider extends ServiceProvider
{
    # Comandos que serão utilizados
    protected $commands = [
        'Softpay\Parcelamento\Commands\NewPackage',
        'Softpay\Parcelamento\Commands\RemovePackage',
        'Softpay\Parcelamento\Commands\GetPackage',
        'Softpay\Parcelamento\Commands\GitPackage',
        'Softpay\Parcelamento\Commands\ListPackages',
        'Softpay\Parcelamento\Commands\MoveTests',
        'Softpay\Parcelamento\Commands\CheckPackage',
        'Softpay\Parcelamento\Commands\PublishPackage',
        'Softpay\Parcelamento\Commands\EnablePackage',
        'Softpay\Parcelamento\Commands\DisablePackage',
    ];
    
    public function boot()
    {
		if ($this->app->runningInConsole()) {
			
			$this->publishes([
            	__DIR__.'/../config/Parcelamento.php' => config_path('Parcelamento.php'),
			]);
			
		};
		
    }
    
    public function register()
    {
				
		# Registrando o arquivo de configuração do nosso pacote
        $this->mergeConfigFrom(__DIR__.'/../config/Parcelamento.php', 'Config');
        $this->commands($this->commands);
		
		# Registrando os serviços disponíveis no pacote
		$this->app->singleton('parcelamento', function ($app) {
        	return new Parcelamento($app);
    	});
		
    }
	
}