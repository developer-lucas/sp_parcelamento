# Softpay - Módulo parcelamento
Módulo para gerar o parcelamento, incluindo MDR e antecipação, em até 18x.

## Criando um novo parcelamento

```php
<?php
use Softpay\Parcelamento;

$parcelamento = new spParcelamento(12, 6, 10.00, 2.75);
        
$parcelamento->gerarParcelas('5587.56');

```
>OU
```php
<?php
use Parcel\Parcelamento;

$parcelamento = new Parcelamento();

$parcelamento->setMaxParcelas(10);
$parcelamento->setParcelasSemJuros(5);
$parcelamento->setValorMinimoParcelar(50.00);
$parcelamento->setPorcentagemJuros(2.50);

$parcelamento->gerarParcelas('777.77');

```
Ainda faltam alguns ajustes no arredondamento, pois o php não trabalha muito bem com casas decimais.
