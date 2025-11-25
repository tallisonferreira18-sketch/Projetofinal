<?php
session_start();

$usuario = "admin";
$senha = "123";

$username = $_POST['username'];
$password = $_POST['password'];

if ($username === $usuario && $password === $senha) {
    $_SESSION['logado'] = true;
    header("Location: admin.php");
    exit;

} else {
    echo "Login inválido! <a href='login.php'>Tentar novamente</a>";
}