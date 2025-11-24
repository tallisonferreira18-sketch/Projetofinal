<?php

    require_once "config.inc.php";

    echo "<h1>Lista de Personagens</h1>";
    echo "<p><a href='?pg=personagem-form'>Cadastrar Personagem</a></p>";

    $sql = "SELECT * FROM Personagens";
    $resultado = mysqli_query($conexao, $sql);

    if(mysqli_num_rows($resultado) > 0){
?>
<table border="1" cellpadding="5" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Jogador</th>
            <th>Espécie/Classe</th>
            <th>Atributos (FOR/DES/CON/INT/SAB/CAR)</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
    <?php
        while($dados = mysqli_fetch_array($resultado)){
            $atributos = "{$dados['forca']}/{$dados['destreza']}/{$dados['constituicao']}/{$dados['inteligencia']}/{$dados['sabedoria']}/{$dados['carisma']}";
    ?>
        <tr>
            <td><?=$dados['id']?></td>
            <td><?=$dados['personagem']?></td>
            <td><?=$dados['jogador']?></td>
            <td><?=$dados['especie']?> / <?=$dados['classe']?> (<?=$dados['subclasse']?>)</td>
            <td><?=$atributos?></td>
            <td>
                <a href='?pg=personagem-form-alterar&id=<?=$dados['id']?>'>Editar</a> |
                <a href='?pg=personagem-excluir&id=<?=$dados['id']?>'>Excluir</a>
            </td>
        </tr>
    <?php
        }
    ?>
    </tbody>
</table>
<?php
    }else{
        echo "<h3>Nenhum personagem cadastrado!</h3>";
    }
?>