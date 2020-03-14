<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js"></script>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

<div class="container">
    <div class="col-md-12">
        <br><br>
        <canvas id="myChart" width="800" height="400"></canvas>
    </div>
    <div class="col-md-12" style="overflow: auto; height: 500px; margin-top: 5%; margin-bottom: 10%; border: solid 2px green">
        <?php
            $json_file = file_get_contents("cotacao.json");
            $jsonPHP = json_decode($json_file);
            $total = count($jsonPHP);

            $cont = 0;
            $aux = 0;
            $media = 0;
            $saldoRealInicial = number_format($_POST['saldo'], 2, '.', '');
            $saldoReal = number_format($_POST['saldo'], 2, '.', '');
            $saldoDolar = 0;
            $dolar = array();

            if(strlen($saldoReal) <= 7)
                $venda = 0.50;
            elseif(strlen($saldoReal) == 8)
                $venda = 0.80;
            foreach ($jsonPHP as $obj) {
                echo "<div class='alert alert-success text-center' style='margin-top: 2%'>";
                    if($cont > 1) {
                        $dolar[0] = $dolar[1];
                        $dolar[1] = $obj->cotacaoCompra;
                    }
                    else
                        $dolar[$cont] = $obj->cotacaoCompra;
                    if($aux == 0)
                        $media = $obj->cotacaoCompra;
                    else
                        $media = array_sum($dolar) / 2;
                    echo "Média: ".$media."  ||  ";
                    if($aux < 6) {
                        echo "Percepção: ".$dolar[0]."  ||  ";
                        if($media >= $dolar[0]) {
                            echo "Aguardar ||  ";
                        }
                        elseif($media < $dolar[0] or $media == $dolar[0])
                            echo "Vender Dólar  ||  ";
                        echo "Saldo (R$): ".number_format($saldoReal, 2, '.', '')." || ";
                        echo "Saldo (US$): ".number_format($saldoDolar, 2, '.', '')."<br>";
                    }
                    if($aux > 5) {
                        echo "Percepção: ".$dolar[1]."  ||  ";
                        if($media > $dolar[1] and $saldoReal > 100) {
                            echo "Comprar Dólar  || ";
                            $saldoDolar = $saldoDolar + (($saldoReal * $venda) / $dolar[1]);
                            $saldoReal = $saldoReal - ($saldoReal * $venda);
                        } elseif ($media < $dolar[1] and $saldoDolar > 0 or $saldoDolar >= $saldoReal * 0.050 and $saldoDolar > 0) {
                            echo "Vender Dolar ||  ";
                            $saldoReal = $saldoReal + ($saldoDolar * $dolar[1]);
                            $saldoDolar = 0;
                        } elseif($media < $dolar[1 and $saldoDolar == 0]) {
                            echo "Comprar Dólar  || ";
                            $saldoDolar = $saldoDolar + (($saldoReal * $venda) / $dolar[1]);
                            $saldoReal = $saldoReal - ($saldoReal * $venda);
                        }

                        echo "Saldo (R$): ".number_format($saldoReal, 2, '.', '')." || ";
                        echo "Saldo (US$): ".number_format($saldoDolar, 2, '.', '')."<br>";
                    }
                    $cont++;
                    $aux++;
                echo "</div>";
            }
            $lucro = $saldoReal - $saldoRealInicial;
        ?>
    </div>
    <?php
        echo "<h1 class='text-center' style='margin-bottom: 5%'>Lucro R$: ".number_format($lucro, 2, '.', '')."</h1>";
    ?>
</div>

<script>
    var ctx = document.getElementById('myChart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: [
                <?php
                    for($i = 0; $i < $total; $i++){
                        echo $i.",";
                    }
                ?>
            ],
            datasets: [
                {
                    label: '# Cotação',
                    data: [
                        <?php
                            foreach ($jsonPHP as $obj) {
                                echo $obj->cotacaoCompra.",";
                            }
                        ?>
                    ],
                    backgroundColor: 'transparent',
                    borderWidth: 1,
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                },
                {
                    label: '# Média',
                    data: [
                        <?php
                            $cont = 0;
                            $aux = 0;
                            $media = 0;
                            $dolar = array();

                            foreach ($jsonPHP as $obj) {
                                if($cont > 1) {
                                    $dolar[0] = $dolar[1];
                                    $dolar[1] = $obj->cotacaoCompra;
                                }
                                else
                                    $dolar[$cont] = $obj->cotacaoCompra;
                                if($aux == 0)
                                    $media = $obj->cotacaoCompra;
                                else
                                    $media = array_sum($dolar) / 2;
                                echo $media.",";
                                $cont++;
                                $aux++;
                            }
                        ?>
                    ],
                    backgroundColor: 'transparent',
                    borderWidth: 1,
                    borderColor: [
                        'rgba(0, 0, 132, 1)',
                    ],
                }
            ]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: false
                    }
                }]
            }
        }
    });
</script>

<?php
    /*Média para periodo de 5 dias
    if($cont > 4) {
        $dolar[0] = $dolar[1];
        $dolar[1] = $dolar[2];
        $dolar[2] = $dolar[3];
        $dolar[3] = $dolar[4];
        $dolar[4] = $obj->cotacaoCompra;
    }
    else
        $dolar[$cont] = $obj->cotacaoCompra;
    if($aux == 0)
        $media = $obj->cotacaoCompra;
    elseif($aux == 1)
        $media = array_sum($dolar) / 2;
    elseif($aux == 2)
        $media = array_sum($dolar) / 3;
    elseif($aux == 3)
        $media = array_sum($dolar) / 4;
    else
        $media = array_sum($dolar) / 5;*/
?>
