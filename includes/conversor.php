<?php
/**
 * Conversor de nomes de moedas para siglas ISO
 * Permite busca inteligente por nome ou código
 */

/**
 * Normaliza texto removendo acentos e convertendo para maiúsculas
 */
function normalizarTexto($texto) {
    $texto = mb_strtoupper($texto, 'UTF-8');
    $texto = str_replace(
        ['Á','À','Â','Ã','É','Ê','Í','Ó','Ô','Õ','Ú','Ç'],
        ['A','A','A','A','E','E','I','O','O','O','U','C'],
        $texto
    );
    return trim($texto);
}

/**
 * Converte nome da moeda (por extenso) em sigla ISO
 */
function converterNomeParaSigla($entrada) {
    $entradaNormalizada = normalizarTexto($entrada);
    
    $mapeamento = [
        'DOLAR' => 'USD',
        'DOLAR AMERICANO' => 'USD',
        'US DOLLAR' => 'USD',
        'DOLLAR' => 'USD',
        'EURO' => 'EUR',
        'LIBRA' => 'GBP',
        'LIBRA ESTERLINA' => 'GBP',
        'POUND' => 'GBP',
        'REAL' => 'BRL',
        'REAL BRASILEIRO' => 'BRL',
        'BITCOIN' => 'BTC',
        'PESO' => 'ARS',
        'PESO ARGENTINO' => 'ARS',
        'IENE' => 'JPY',
        'YEN' => 'JPY',
        'IENE JAPONES' => 'JPY',
        'FRANCO' => 'CHF',
        'FRANCO SUICO' => 'CHF',
        'DOLAR CANADENSE' => 'CAD',
        'DOLAR AUSTRALIANO' => 'AUD',
        'YUAN' => 'CNY',
        'YUAN CHINES' => 'CNY',
    ];
    
    // Se já é uma sigla (3 letras), retorna como está
    if (strlen($entradaNormalizada) === 3 && ctype_alpha($entradaNormalizada)) {
        return $entradaNormalizada;
    }
    
    return $mapeamento[$entradaNormalizada] ?? $entradaNormalizada;
}