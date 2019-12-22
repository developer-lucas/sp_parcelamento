[![Build Status](http://img.shields.io/travis/badges/badgerbadgerbadger.svg?style=flat-square)](https://travis-ci.org/badges/badgerbadgerbadger) [![Dependency Status](http://img.shields.io/gemnasium/badges/badgerbadgerbadger.svg?style=flat-square)](https://gemnasium.com/badges/badgerbadgerbadger) [![Coverage Status](http://img.shields.io/coveralls/badges/badgerbadgerbadger.svg?style=flat-square)](https://coveralls.io/r/badges/badgerbadgerbadger) [![Code Climate](http://img.shields.io/codeclimate/github/badges/badgerbadgerbadger.svg?style=flat-square)](https://codeclimate.com/github/badges/badgerbadgerbadger) [![Github Issues](http://githubbadges.herokuapp.com/badges/badgerbadgerbadger/issues.svg?style=flat-square)](https://github.com/badges/badgerbadgerbadger/issues) [![Pending Pull-Requests](http://githubbadges.herokuapp.com/badges/badgerbadgerbadger/pulls.svg?style=flat-square)](https://github.com/badges/badgerbadgerbadger/pulls)[![License](http://img.shields.io/:license-mit-blue.svg?style=flat-square)](http://badges.mit-license.org) 

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
