<p align="center">
 <a href="https://www.softpay.com.br"><img src="https://console.europag.com.br/assets/images/softpay.png" title="Softpay" alt="Softpay"></a>
</p>

# Softpay - Módulo parcelamento
Módulo para gerar os recebíveis da transação. Possibilidade de:

 - Simulação de parcelamento (Com ou sem Juros) em até 18x, com ou sem antecipação;
 - Geração dos recebíveis de uma transação;
 - Máximo de parcelas de acordo com o valor mínimo;
 - Cálculo de antecipação;
 
## Instalando o pacote via Composer

```
composer require Softpay/Parcelamento
```


## Gerando recebíveis de uma transação

```php
<?php
use Softpay\Parcelamento;

$parcelamento = new Parcelamento($valorTotal, $parcelas, $parcelasSemJuros, $valorTotal, $MDR);
        
$parcelamento->gerarRecebiveis();

```
Método alternativo
```php
<?php
use Parcel\Parcelamento;

$parcelamento = new Parcelamento();

$parcelamento->setValorTotal(1000.00);
$parcelamento->setParcelas(10);
$parcelamento->setParcelasSemJuros(5);
$parcelamento->setParcelaMinima(50.00);
$parcelamento->setMDR(2.50);

$parcelamento->gerarRecebiveis('777.77');

```
Módulo em fase beta! Não recomendado para utilização em produção.
