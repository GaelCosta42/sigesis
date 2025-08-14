<?php

require __DIR__ . '/vendor/autoload.php'; 

use Plug4Market\ProdutoDTO;

$produtoDTO = new ProdutoDTO(
    brand: 'Nike',
    description: 'Tênis esportivo confortável',
    height: 12.5,
    lenght: 30.0,
    name: 'Air Zoom Pegasus',
    price: 499.90,
    productId: 'P12345',
    productname: 'Tênis Air Zoom Pegasus',
    sku: 'SKU12345',
    weight: 0.9,
    width: 20.0
);

$api = new ProdutosCanalVendaService();
$resultado = $api->postProduto($produtoDTO);

print_r($resultado);
