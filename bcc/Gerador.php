<html>
    <h3>Gerador de BCC!</h3>
    <form action="Gerador.php" method="get">
        <input type="text" name="palavra">
        <input type="submit" value="Gerar">
    </form>
</html>

<?php

    $PALAVRA = strtoupper($_GET["palavra"]);
    $TRANSMISSAO_GERADA = ""; 
    $SAIDA = "";

    $colunas = array();
    $contador = 0;
    for ($i=0; $i < strlen($PALAVRA); $i++) { 
        $letra = $PALAVRA[$i];
        $valorASCII = ord($letra);
        $binario = decbin($valorASCII);
        $SAIDA .= $valorASCII . " ($letra)" . " => &nbsp;&nbsp;&nbsp;";

        $contador = 0;
        for ($j=0; $j < strlen($binario); $j++) { 
            $bit = $binario[$j];
            $colunas[$j] .= $bit;
            
            $SAIDA .= " &nbsp;" . $bit;
            if($bit == "1") $contador++;
        }

        $TRANSMISSAO_GERADA .= $binario;

        if($contador % 2 == 0) {
            $SAIDA .= " &nbsp;&nbsp;&nbsp;| Bit de Paridade: 0";
            $TRANSMISSAO_GERADA .= "0";
        } else {
            $SAIDA .= " &nbsp;&nbsp;&nbsp;| Bit de Paridade: 1";
            $TRANSMISSAO_GERADA .= "1";
        }
        $SAIDA .= "<br/>";
    }
    $SAIDA .= "-----------------------------------------<br/>";
    $SAIDA .= "BCC ~~> &nbsp;&nbsp;&nbsp;&nbsp;";

    $novaLinha = "";
    $contador2 = 0;
    foreach ($colunas as $coluna) {
        $contador2 = 0;
        for ($i=0; $i < strlen($coluna); $i++) { 
            if($coluna[$i] == "1") $contador2++;
        }
        
        if($contador2 % 2 == 0) {
            $SAIDA .= " &nbsp;0";
            $novaLinha .= "0";
        } else {
            $SAIDA .= " &nbsp;1";
            $novaLinha .= "1";
        }
    }
    $TRANSMISSAO_GERADA .= $novaLinha;

    $contador3 = 0;
    for ($i=0; $i < strlen($novaLinha); $i++) { 
        if ($novaLinha[$i] == "1") $contador3++;
    }
    if($contador3 % 2 == 0) {
        $SAIDA .= " &nbsp;&nbsp;&nbsp;| Bit de Paridade: 0";
        $TRANSMISSAO_GERADA .= "0";
    } else {
        $SAIDA .= " &nbsp;&nbsp;&nbsp;| Bit de Paridade: 1";
        $TRANSMISSAO_GERADA .= "1";
    }

    echo "<br/>$SAIDA<br/>";
    echo "Transmissao Final: $TRANSMISSAO_GERADA<br/>";
?>