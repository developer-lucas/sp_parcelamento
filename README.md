<p align="center">
 <a href="https://www.softpay.com.br"><img src="https://console.europag.com.br/assets/images/softpay.png" title="Softpay" alt="Softpay"></a>
</p>

A Softpay Tecnologia em meios de pagamentos é uma empresa especializada no fornecimento de tecnologias para Adquirentes e Subadquirentes. Atualmente são mais de 40 milhões de vendas processadas todos os anos.

# Módulo Recebíveis

Esse módulo foi desenvolvimento para calcular e gerar recebíveis de uma transação de cartão de crédito, parcelada em até 18x. 

# Funcionalidades disponíveis

 - Simulação de parcelamento (Com ou sem Juros) em até 18x, com ou sem antecipação;
 - Geração dos recebíveis de uma transação;
 - Máximo de parcelas de acordo com o valor mínimo;
 - Cálculo de antecipação;
 - Suporte a Framework Laravel;
 
## Instalando o pacote via Composer

```
composer require Softpay/Parcelamento
```

## Retorno das informações

```
grossAmount  : Valor bruto da venda.
netAmount    : Valor líquido, descontado o MDR.
fee          : MDR aplicado sobre o valor total da venda.
installments : Array de parcelas (fee, installment e amount).

* Todos os valores são retornados sem pontos ou vírgula, incluindo os centavos (Ex: R$160,00 => 16000).

```

## Gerando recebíveis de uma transação

```php
<?php
use Softpay\Parcelamento\Recebiveis;

$parcelamento = new Recebiveis($valorTotal, $parcelas, $parcelasSemJuros, $valorTotal, $MDR);
        
$parcelamento->gerarRecebiveis();

```
Ou se preferir:
```php
<?php
use Parcel\Parcelamento\Recebiveis;

$parcelamento = new Recebiveis();

$parcelamento->setValorTotal(1000.00);
$parcelamento->setParcelas(10);
$parcelamento->setParcelasSemJuros(5);
$parcelamento->setParcelaMinima(50.00);
$parcelamento->setMDR(2.50);

$parcelamento->gerarRecebiveis();

```

