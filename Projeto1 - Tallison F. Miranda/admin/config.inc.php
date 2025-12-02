<?php
    $conexao = mysqli_connect("127.0.0.1","root","","projetofinal_ded",3306);

    if(!$conexao){
        echo "<h2>Erro ao conectar o banco de dados: " . mysqli_connect_error() . "</h2>";

    }
