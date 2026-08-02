<?php
session_start();

$usuario_correto = "Ilustre";
$senha_correta = "654321";

$usuario_digitado = $_POST['usuario'] ?? '';
$senha_digitada = $_POST['senha'] ?? '';

if ($usuario_digitado === $usuario_correto && $senha_digitada === $senha_correta) {
   
    $_SESSION['logado'] = true;
    $_SESSION['usuario'] = $usuario_digitado;

    header("Location: Calculadora.php");
    exit();
} else {
    
    echo "<script>alert('Usuário ou senha incorretos!'); window.location.href='index.html';</script>";
}
?>