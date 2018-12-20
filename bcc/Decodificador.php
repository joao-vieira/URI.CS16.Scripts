<html>
    <h3>Decodificador de BCC!</h3>
    <form action="Decodificador.php" method="get">
        <input type="text" name="transmissao">
        <input type="submit" value="Decodificar">
    </form>
</html>

<?php
    echo "<br/> Valor recebido: " . $_GET["transmissao"] . "<br/>";

    $TRANSMISSAO_RECEBIDA = $_GET["transmissao"];
    $ERRO_ENCONTRADO = false;
    $BITS_TOTAIS = array();

    $bitAux = -1;
    $letraBin = "";
    $contador = 1;
    for ($i=0; $i < strlen($TRANSMISSAO_RECEBIDA); $i++) { 
        $bit = $TRANSMISSAO_RECEBIDA[$i];
        $letraBin .= $bit;
        if($contador == 8) {
            $BITS_TOTAIS[] = $letraBin;
            $letraBin = "";
            $contador = 0;
        }
        $contador++;
    }


    $contador2 = 0;
    foreach ($BITS_TOTAIS as $value) {
        $contador2 = 0;
        for ($i=0; $i < strlen($value); $i++) { 
            if($i == strlen($value) - 1) {
                $bitBCC = $value[$i];
                $ERRO_ENCONTRADO = ((int)$bitBCC != ($contador2 % 2));
                if(!$ERRO_ENCONTRADO  &&  count($BITS_TOTAIS) - 1 == array_search($value, $BITS_TOTAIS)) {
                    $bitAux = (int)$bitBCC;
                }
            } else {
                if($value[$i] == "1") $contador2++;
            }
        }
        if($ERRO_ENCONTRADO) break;
    }

    if(!$ERRO_ENCONTRADO) {
        $contador3 = 0;
        for ($i=0; $i < 8; $i++) { 
            $valor = "";
            foreach ($BITS_TOTAIS as $value2) {
                $valor .= $value2[$i];
            }

            $contador3 = 0;
            for ($k=0; $k < strlen($valor); $k++) { 
                if($k == strlen($valor) - 1) {
                    $bitBCC = $valor[$k];
                    $ERRO_ENCONTRADO = ((int)$bitBCC != ($contador3 % 2));
                    if(!$ERRO_ENCONTRADO  &&  $k == 7) $ERRO_ENCONTRADO = ($contador3 % 2) != $bitAux;
                } else {
                    if($valor[$k] == "1") $contador3++;
                }
            }
            if($ERRO_ENCONTRADO) break;
        }

        if(!$ERRO_ENCONTRADO) {
            $novoValor = "";
            for ($x=0; $x < count($BITS_TOTAIS) - 1; $x++) { 
                $dec = bindec( substr($BITS_TOTAIS[$x], 0, 7) );
                $letra = chr( $dec );                
                $novoValor .= $letra;
            }

            echo "<br/> Palavra proveniente da transmissao: $novoValor <br/><br/>";

            for ($i=0; $i < count($BITS_TOTAIS); $i++) { 
                $byte = $BITS_TOTAIS[$i];
                $linha = "";
                if($i != count($BITS_TOTAIS) - 1) {
                    $linha .= ord($novoValor[$i]) . " ($novoValor[$i])" . " = ";
                } else {
                    $linha .= "-------------------------------<br/>";
                    $linha .= "BCC ~> ";
                }

                for ($j=0; $j < strlen($byte); $j++) { 
                    if($j == strlen($byte) - 1) $linha .= " &nbsp;|&nbsp;&nbsp;";
                    $linha .= " " + $byte[$j];
                }
                echo $linha . "<br/>";
            }

        } else {
            echo "<br/> Houve erro ao transmitir a mensagem (BCC_2)! <br/>";
        }
    } else {
        echo "<br/> Houve erro ao transmitir a mensagem (BCC)! <br/>";
    }

?>