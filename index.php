<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conversor de Moedas</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php 
        $reais = $_GET["reais"] ?? 0;
    ?>
    
    <header>
    <h1>Conversor de Real (R$) <br> em Dólar (US$)</h1>
    </header>
    
    <main>
        <!-- Formulário para entrada do valor em Reais -->

        <form action="<?= $_SERVER["PHP_SELF"]?>" method="get">
            <label for="reais">Quantos R$ você quer converter?</label>
            <input type="number" name="reais" id="idreais" min="1" step="0.01">
            
            <?= '<p class="cotacao"><br>*Cotação em tempo real do <strong><a href="https://www.bcb.gov.br/" target="_blank">Banco Central</a></strong></p>';?>

            <input type="submit" value="Converter">
        </form>
    </main>

    <section id="resultado">

        <h2>Resultado da Conversão<br><br></h2>

        <p><?php 
            $inicio = date("m-d-Y", strtotime("-7 days"));
            $final = date("m-d-Y");

            //Cotação vinda da API do Banco do Brasil
            
            $url = 'https://olinda.bcb.gov.br/olinda/servico/PTAX/versao/v1/odata/CotacaoDolarPeriodo(dataInicial=@dataInicial,dataFinalCotacao=@dataFinalCotacao)?@dataInicial=\''.$inicio.'\'&@dataFinalCotacao=\''.$final.'\'&$top=1&$orderby=dataHoraCotacao%20desc&$format=json&$select=cotacaoCompra,dataHoraCotacao';

            $dados = json_decode(file_get_contents($url), true);
            
            // Verifica se a resposta é válida e se a cotação não é zero

            if ($dados && isset($dados["value"][0]["cotacaoCompra"]) && $dados["value"][0]["cotacaoCompra"] != 0) {
                $cotacao = $dados["value"][0]["cotacaoCompra"];
                $dolar = $reais / $cotacao;
                echo "Seus <strong>R\$" . number_format($reais, 2, ",", ".") . "</strong> equivalem a <strong>US\$" . number_format($dolar, 2, ",", ".") . "</strong>";
            } else {
                echo "Não foi possível obter a taxa de câmbio atual.";
            }
        ?></p>
    </section>
</body>
</html>
