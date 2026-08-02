<?php
session_start();

if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header("Location: index.html");
    exit();
}

if (!isset($_SESSION['historico'])) {
    $_SESSION['historico'] = array();
}

$num = "";
$COOKIE_name1 = "num";
$COOKIE_name2 = "op";

if (isset($_POST['num']) && $_POST['num'] === "C") {
    $num = "";
    setcookie($COOKIE_name1, "", time() - 3600, "/");
    setcookie($COOKIE_name2, "", time() - 3600, "/");
    unset($_COOKIE['num'], $_COOKIE['op']);
} 

elseif (isset($_POST['num'])) {
    $num = $_POST['input'] . $_POST['num'];
}

if (isset($_POST['op'])) {
    setcookie($COOKIE_name1, $_POST['input'], time() + (86400 * 30), "/");
    setcookie($COOKIE_name2, $_POST['op'], time() + (86400 * 30), "/");
    $num = "";
}

if (isset($_POST['igual'])) {
    $num = $_POST['input'];
    $op = $_COOKIE['op'] ?? '';
    $num1 = $_COOKIE['num'] ?? 0;

    switch ($op) {
        case "+":
            $result = $num1 + $num;
            break;
        case "-":
            $result = $num1 - $num;
            break;
        case "/":
            $result = ($num != 0) ? ($num1 / $num) : "Erro (Div/0)";
            break;
        case "*":
            $result = $num1 * $num;
            break;
        default:
            $result = $num;
            break;
    }
    
    $operacao_str = "$num1 $op $num = $result";
    $_SESSION['historico'][] = $operacao_str;

    $num = $result; 
}

if (isset($_POST['limpar_historico'])) {
    $_SESSION['historico'] = array();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calculadora - Painel</title>
    <link rel="stylesheet" href="efeites.css">

    <style>
        
        .pdf-btn {
            background-color: #27ae60;
            color: white;
            padding: 8px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 10px;
        }

        .historico-box {
            margin-top: 20px;
            background: #f4f4f4;
            padding: 10px;
            border-radius: 5px;
            max-width: 300px;
        }


        @media print {
       
            .calculo, button, a, .no-print {
                display: none !important;
            }

            
            body {
                background: white;
                color: black;
                font-family: Arial, sans-serif;
            }

            .historico-box {
                max-width: 100%;
                background: none;
                border: none;
            }
        }
    </style>
</head>
<body>
    <h1>Bem-vindo à Máquina, <?php echo htmlspecialchars($_SESSION['usuario'] ?? 'Utilizador'); ?>!</h1>
    <p>Elimine dúvidas e erros mentais num instante.</p>
    <a href="logout.php" class="no-print">Sair</a>

    <hr class="no-print">

    <div class="calculo">
        <form action="" method="POST">
            <br>
            <input type="text" class="maininput" name="input" value="<?php echo htmlspecialchars($num); ?>" readonly> <br> <br>
            
            <input type="submit" class="numbtn" name="num" value="7">
            <input type="submit" class="numbtn" name="num" value="8">
            <input type="submit" class="numbtn" name="num" value="9">
            <input type="submit" class="calculobtn" name="op" value="+"><br>

            <input type="submit" class="numbtn" name="num" value="4">
            <input type="submit" class="numbtn" name="num" value="5">
            <input type="submit" class="numbtn" name="num" value="6">
            <input type="submit" class="calculobtn" name="op" value="-"><br>

            <input type="submit" class="numbtn" name="num" value="1">
            <input type="submit" class="numbtn" name="num" value="2">
            <input type="submit" class="numbtn" name="num" value="3">
            <input type="submit" class="calculobtn" name="op" value="*"><br><br>

            <input type="submit" class="C" name="num" value="C">
            <input type="submit" class="numbtn" name="num" value="0">
            <input type="submit" class="igual" name="igual" value="=">
            <input type="submit" class="calculobtn" name="op" value="/"><br><br>

            <button type="submit" name="limpar_historico" style="background:#e74c3c; color:white; border:none; padding:8px 10px; border-radius:4px; cursor:pointer;">Limpar Histórico</button>
        </form>
    </div>

    <div class="historico-box">
        <h3>Relatório / Histórico de Cálculos:</h3>
        <p><small>Data: <?php echo date('d/m/Y H:i'); ?></small></p>
        
        <?php if (!empty($_SESSION['historico'])): ?>
            <ul>
                <?php foreach ($_SESSION['historico'] as $calc): ?>
                    <li><?php echo htmlspecialchars($calc); ?></li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p><small>Nenhum cálculo efetuado ainda.</small></p>
        <?php endif; ?>
    </div>

    <br>
    <button onclick="window.print()" class="pdf-btn no-print">📄 Guardar como PDF</button>

</body>
</html>