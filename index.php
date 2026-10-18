<?php
/**
 * Consulta de Cotações - Projeto de Demonstração
 * 
 * Aplicação PHP que consome múltiplas APIs REST para buscar cotações
 * de moedas com sistema de fallback automático.
 * 
 * @author [Seu Nome]
 * @version 1.0.0
 */

require_once 'includes/conversor.php';
require_once 'includes/api.php';

// Processa o formulário
$resultado = null;
$erro = null;
$moedaBuscada = '';
$siglaEncontrada = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['moeda'])) {
    $moedaBuscada = $_POST['moeda'];
    $siglaEncontrada = converterNomeParaSigla($moedaBuscada);
    $resultado = consultarCotacao($siglaEncontrada);
    
    if (!$resultado['sucesso']) {
        $erro = $resultado['erro'];
        $resultado = null;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta de Cotações - PHP REST API</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="container">
        <h1>💰 Consulta de Cotações</h1>
        <p class="subtitle">Consumindo múltiplas APIs REST com fallback automático</p>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="moeda">Nome ou Sigla da Moeda:</label>
                <input 
                    type="text" 
                    id="moeda" 
                    name="moeda" 
                    placeholder="Ex: dolar, euro, USD, bitcoin"
                    value="<?php echo htmlspecialchars($moedaBuscada); ?>"
                    required
                    maxlength="30"
                >
            </div>
            <button type="submit">Consultar Cotação</button>
        </form>
        
        <?php if ($resultado): ?>
            <div class="resultado sucesso">
                <h3>✅ Cotação Encontrada</h3>
                <?php if ($siglaEncontrada && strtoupper($moedaBuscada) !== $siglaEncontrada): ?>
                    <div class="sigla-info">
                        🔍 Você buscou por "<strong><?php echo htmlspecialchars($moedaBuscada); ?></strong>" 
                        → Sigla: <strong><?php echo htmlspecialchars($siglaEncontrada); ?></strong>
                    </div>
                <?php endif; ?>
                <div class="fonte">🔗 Fonte: <?php echo htmlspecialchars($resultado['fonte']); ?></div>
                <div class="info-row">
                    <span class="info-label">Moeda:</span>
                    <span class="info-value"><?php echo htmlspecialchars($resultado['moeda']); ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Compra:</span>
                    <span class="info-value">R$ <?php echo number_format($resultado['compra'], 4, ',', '.'); ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Venda:</span>
                    <span class="info-value">R$ <?php echo number_format($resultado['venda'], 4, ',', '.'); ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Data:</span>
                    <span class="info-value"><?php echo htmlspecialchars($resultado['data']); ?></span>
                </div>
            </div>
        <?php endif; ?>
        
        <?php if ($erro): ?>
            <div class="resultado erro">
                <h3>❌ Erro</h3>
                <p><?php echo htmlspecialchars($erro); ?></p>
            </div>
        <?php endif; ?>
        
        <div class="exemplos">
            <strong>💡 Você pode digitar:</strong>
            <ul>
                <li><code>dolar</code> ou <code>USD</code> → Dólar americano</li>
                <li><code>euro</code> ou <code>EUR</code> → Euro</li>
                <li><code>bitcoin</code> ou <code>BTC</code> → Bitcoin</li>
                <li><code>libra</code> ou <code>GBP</code> → Libra esterlina</li>
            </ul>
        </div>
        
        <div class="status-apis">
            <strong>🔄 APIs Configuradas:</strong><br>
            1. Brasil API → 2. AwesomeAPI → 3. ExchangeRate-API
        </div>
    </div>
</body>
</html>