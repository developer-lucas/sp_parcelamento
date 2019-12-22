<?php

namespace Softpay\Parcelamento;

use Illuminate\Support\ServiceProvider;


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
		# Inicializando o arquivo de parcelamento junto com o pacote
        $this->publishes([
            __DIR__.'/../config/Parcelamento.php' => config_path('Parcelamento.php'),
        ]);
		
    }
    
    public function register()
    {
		# Registrando o arquivo de configuração do nosso pacote
        $this->mergeConfigFrom(__DIR__.'/../config/Parcelamento.php', 'Parcelamento');
        $this->commands($this->commands);
		
		# Registrando nosso controller
		$this->app->make('Softpay\Parcelamento\Http\Controllers\CalculatorController');
		
    }
	
}