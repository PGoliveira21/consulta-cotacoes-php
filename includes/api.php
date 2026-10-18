<?php
/**
 * Módulo de consumo de APIs de cotação
 * Implementa sistema de fallback automático entre múltiplas APIs
 */

/**
 * Realiza requisição HTTP usando cURL
 */
function fazerRequisicao($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    return ['response' => $response, 'httpCode' => $httpCode];
}

/**
 * Busca cotação na Brasil API
 * @link https://brasilapi.com.br/
 */
function buscarBrasilAPI($moeda) {
    $url = "https://brasilapi.com.br/api/cotacao/v1/cotacao/{$moeda}";
    $resultado = fazerRequisicao($url);
    
    if ($resultado['httpCode'] === 200) {
        $dados = json_decode($resultado['response'], true);
        if (!empty($dados)) {
            $cotacao = $dados[0];
            return [
                'sucesso' => true,
                'fonte' => 'Brasil API',
                'moeda' => $cotacao['moeda'] ?? $moeda,
                'compra' => $cotacao['cotacao_compra'] ?? 0,
                'venda' => $cotacao['cotacao_venda'] ?? 0,
                'data' => date('d/m/Y', strtotime($cotacao['data_cotacao'] ?? 'now'))
            ];
        }
    }
    return ['sucesso' => false];
}

/**
 * Busca cotação na AwesomeAPI
 * @link https://economia.awesomeapi.com.br/
 */
function buscarAwesomeAPI($moeda) {
    $mapeamento = [
        'USD' => 'USD-BRL', 'EUR' => 'EUR-BRL', 'GBP' => 'GBP-BRL',
        'ARS' => 'ARS-BRL', 'BTC' => 'BTC-BRL', 'JPY' => 'JPY-BRL',
        'CAD' => 'CAD-BRL', 'AUD' => 'AUD-BRL', 'CHF' => 'CHF-BRL',
        'CNY' => 'CNY-BRL'
    ];
    
    $codigo = $mapeamento[$moeda] ?? "{$moeda}-BRL";
    $url = "https://economia.awesomeapi.com.br/last/{$codigo}";
    $resultado = fazerRequisicao($url);
    
    if ($resultado['httpCode'] === 200) {
        $dados = json_decode($resultado['response'], true);
        $chave = str_replace('-', '', $codigo);
        
        if (isset($dados[$chave])) {
            $cotacao = $dados[$chave];
            return [
                'sucesso' => true,
                'fonte' => 'AwesomeAPI',
                'moeda' => $cotacao['name'] ?? $moeda,
                'compra' => floatval($cotacao['bid'] ?? 0),
                'venda' => floatval($cotacao['ask'] ?? 0),
                'data' => date('d/m/Y', strtotime($cotacao['create_date'] ?? 'now'))
            ];
        }
    }
    return ['sucesso' => false];
}

/**
 * Busca cotação na ExchangeRate-API
 * @link https://www.exchangerate-api.com/
 */
function buscarExchangeRateAPI($moeda) {
    $url = "https://api.exchangerate-api.com/v4/latest/{$moeda}";
    $resultado = fazerRequisicao($url);
    
    if ($resultado['httpCode'] === 200) {
        $dados = json_decode($resultado['response'], true);
        
        if (isset($dados['rates']['BRL'])) {
            $taxa = $dados['rates']['BRL'];
            return [
                'sucesso' => true,
                'fonte' => 'ExchangeRate-API',
                'moeda' => $moeda . '/BRL',
                'compra' => $taxa,
                'venda' => $taxa * 1.01,
                'data' => date('d/m/Y')
            ];
        }
    }
    return ['sucesso' => false];
}

/**
 * Consulta cotação com fallback automático entre APIs
 * Tenta cada API em ordem até obter sucesso
 */
function consultarCotacao($moeda) {
    $moeda = strtoupper(trim($moeda));
    
    $apis = [
        'brasilapi' => 'buscarBrasilAPI',
        'awesomeapi' => 'buscarAwesomeAPI',
        'exchangerate' => 'buscarExchangeRateAPI'
    ];
    
    foreach ($apis as $nome => $funcao) {
        $resultado = $funcao($moeda);
        if ($resultado['sucesso']) {
            return $resultado;
        }
    }
    
    return ['sucesso' => false, 'erro' => 'Não foi possível obter a cotação em nenhuma das APIs disponíveis.'];
}