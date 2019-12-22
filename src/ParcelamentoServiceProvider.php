<?php

namespace Softpay\Parcelamento;

use Illuminate\Support\ServiceProvider;

class ParcelamentoServiceProvider extends ServiceProvider
{
    
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
        $this->publishes([
            __DIR__.'/../config/Parcelamento.php' => config_path('Parcelamento.php'),
        ]);
    }
    
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/Parcelamento.php', 'Parcelamento');
        $this->commands($this->commands);
    }
	
}