# 💰 Consulta de Cotações em Tempo Real

[![PHP Version](https://img.shields.io/badge/PHP-7.4%2B-blue.svg)](https://php.net)
[![License: MIT](https://img.shields.io/badge/License-MIT-green.svg)](https://opensource.org/licenses/MIT)

🌐 **Demo Online:** [https://ativos.site.je/](https://ativos.site.je/)  
📄 **Meu Currículo:** [https://curriculo.free.nf/](https://curriculo.free.nf/)

---

## 📝 Sobre o Projeto (Explicação Simples)

Este foi meu primeiro projeto prático focado em **consumo de APIs REST**. A ideia era criar uma ferramenta onde qualquer pessoa pudesse digitar o nome de uma moeda (como "dolar", "euro" ou "bitcoin") e ver o valor atualizado em Reais (R$), sem precisar saber os códigos técnicos (como USD ou EUR).

Como estou em fase de aprendizado, foquei em aplicar conceitos reais do mercado de trabalho:
1. **Conectar o PHP ao mundo externo:** Aprendi a usar a biblioteca `cURL` para buscar dados de servidores externos.
2. **Tratar dados:** Aprendi a ler respostas em formato JSON e transformá-las em informações úteis para o usuário.
3. **Garantir que o sistema não quebre:** Implementei um sistema de "fallback" (plano B). Se a primeira API falhar, o sistema tenta automaticamente uma segunda e uma terceira opção.
4. **Organização do código:** Separei o código em arquivos diferentes (lógica, estilo e estrutura) para facilitar a leitura e manutenção, fugindo do "código espaguete".

## ✨ Funcionalidades

- 🔍 **Busca Inteligente:** Aceita nomes por extenso ("dolar", "libra") ou siglas ("USD", "GBP").
- 🛡️ **Sistema de Fallback:** Se a API principal cair, o sistema usa automaticamente uma API de backup, garantindo que o usuário sempre veja um resultado.
- 📱 **Interface Responsiva:** Funciona bem em celulares e computadores.
- ⚠️ **Tratamento de Erros:** Mensagens amigáveis caso o usuário digite algo inválido ou a internet falhe.

## 🛠️ Tecnologias Utilizadas

| Tecnologia | Para que serve no projeto |
|------------|---------------------------|
| **PHP** | Linguagem principal para processar os dados no servidor. |
| **cURL** | Ferramenta do PHP para fazer requisições HTTP (buscar dados na internet). |
| **JSON** | Formato de dados que as APIs usam para enviar as cotações. |
| **HTML5 + CSS3** | Para criar a estrutura e o visual bonito da página. |
| **Git/GitHub** | Para salvar o histórico de alterações e mostrar meu código. |

## ⚙️ Como Funciona (Passo a Passo)

1. O usuário digita "dolar" no formulário e envia.
2. O PHP recebe esse texto, remove acentos e converte para maiúsculas.
3. O sistema verifica em uma lista interna e descobre que "DOLAR" = "USD".
4. O PHP usa o `cURL` para perguntar à **Brasil API**: "Qual o valor do USD?".
5. Se a Brasil API responder, o PHP lê o JSON, pega o valor e mostra na tela.
6. Se a Brasil API der erro, o PHP automaticamente pergunta para a **AwesomeAPI**, e assim por diante.

## 📁 Estrutura do Projeto

```text
consulta-cotacoes-php/
├── index.php              # Página principal (HTML + lógica de exibição)
├── includes/
│   ├── api.php            # Funções que buscam os dados nas APIs
│   └── conversor.php      # Função que traduz "dolar" para "USD"
├── assets/
│   └── style.css          # Estilos visuais (cores, fontes, layout)
├── README.md              # Este arquivo de documentação
└── .gitignore             # Arquivos que não devem ir para o GitHub

🚀 Como Rodar Localmente
Se você quiser testar no seu computador:
Tenha o PHP instalado (ou use o XAMPP).
Clone este repositório ou baixe os arquivos.
Abra o terminal na pasta do projeto e digite:

   php -S localhost:8000

   Acesse no navegador: http://localhost:8000

   👨‍💻 Autor

Desenvolvido com foco em aprendizado e boas práticas por:
Paulo Gomes de Oliveira

