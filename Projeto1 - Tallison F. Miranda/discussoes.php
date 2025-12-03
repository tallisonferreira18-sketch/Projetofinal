<?php
// --- Processamento de postagem ---

require_once "admin/config.inc.php";

// Recebe o comando pra salvar os dados do formulário
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = trim($_POST["nome"]);
    $email = trim($_POST["email"]);
    $texto = trim($_POST["texto"]);

    // Validações básicas
    if (empty($nome) || empty($email) || empty($texto)) {
        $erro = "Todos os campos são obrigatórios!";
    } elseif (strlen($texto) > 450) {
        $erro = "O texto não pode ter mais que 450 caracteres.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = "Email inválido.";
    } else {

        // Prevenir SQL injecton
        $nome = mysqli_real_escape_string($conexao, $nome);
        $email = mysqli_real_escape_string($conexao, $email);
        $texto = mysqli_real_escape_string($conexao, $texto);

        // consulta pra verifcar se jogador existe
        $query_jogador = "
            SELECT id 
            FROM jogadores 
            WHERE jogador = '$nome' AND email = '$email'
            LIMIT 1
        ";

        $resultado = mysqli_query($conexao, $query_jogador);

        // Se não existe, cria jogador
        if (mysqli_num_rows($resultado) === 0) {
            mysqli_query($conexao, "
                INSERT INTO jogadores (jogador, email, personagem, numero)
                VALUES ('$nome', '$email', 'Despojado', '0')
            ");

            $id_jogador = mysqli_insert_id($conexao);
        } else {
            $jogador = mysqli_fetch_assoc($resultado);
            $id_jogador = $jogador["id"];
        }

        // Insere postagem vinculada ao jogador
        $sql = "
            INSERT INTO postagens (id_usuario, texto)
            VALUES ($id_jogador, '$texto')
        ";

        if (mysqli_query($conexao, $sql)) {
            $sucesso = "Postagem enviada com sucesso!";
        } else {
            $erro = "Erro ao salvar postagem: " . mysqli_error($conexao);
        }
    }
}

// Consutla no banco em busca de postagens

$query_postagens = "
    SELECT 
        postagens.id, 
        postagens.texto, 
        postagens.data_criacao,
        jogadores.jogador AS nome,
        jogadores.email
    FROM postagens
    JOIN jogadores ON postagens.id_usuario = jogadores.id
    ORDER BY postagens.data_criacao DESC;
";

$postagens = mysqli_query($conexao, $query_postagens);
?>

<div class="container">
    <div class="content-wrapper">

        <!-- Título da página -->
        <div class="text-center mb-5 pb-4">
            <h1 class="display-2 fw-bold mb-4 gradient-text" style="font-size: 3rem;">
                <i class="bi bi-chat-dots-fill me-3"></i> Sistema de Postagens
            </h1>
        </div>

        <div class="row">
            <div class="col-md-10 mx-auto">

                <!-- Formulário de nova postagem -->
                <div class="glass-card p-5 mb-5">
                    <div class="d-flex align-items-center mb-4">
                        <div class="me-3"
                            style="width: 60px; height: 60px; background: var(--cyan-gradient);
                                   border-radius: 16px; display: flex; align-items: center; justify-content: center;">
                            <i class="bi bi-pencil-square fs-2 text-white"></i>
                        </div>
                        <h3 class="mb-0 gradient-text">Nova Postagem</h3>
                    </div>

                    <?php if (!empty($erro)): ?>
                        <div class="alert alert-danger"><?= $erro ?></div>
                    <?php endif; ?>

                    <?php if (!empty($sucesso)): ?>
                        <div class="alert alert-success"><?= $sucesso ?></div>
                    <?php endif; ?>

                    <form method="POST" class="mt-3">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nome</label>
                            <input type="text" name="nome" class="form-control glass-input" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Email</label>
                            <input type="email" name="email" class="form-control glass-input" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Mensagem</label>
                            <textarea name="texto" rows="4" maxlength="450"
                                      class="form-control glass-input"
                                      placeholder="Escreva sua mensagem (máx. 450 caracteres)" required></textarea>
                        </div>

                        <button type="submit"
                                class="btn btn-primary px-4 py-2"
                                style="background: var(--cyan-gradient); border: 0; border-radius: 12px;">
                            <i class="bi bi-send-fill me-2"></i> Enviar
                        </button>
                    </form>
                </div>

                <!-- Lista de postagens -->
                <div class="glass-card p-5">
                    <div class="d-flex align-items-center mb-4">
                        <div class="me-3"
                            style="width: 60px; height: 60px; background: var(--cyan-gradient);
                                   border-radius: 16px; display: flex; align-items: center; justify-content: center;">
                            <i class="bi bi-journal-text fs-2 text-white"></i>
                        </div>
                        <h3 class="mb-0 gradient-text">Postagens Recentes</h3>
                    </div>

                    <?php while ($postagem = mysqli_fetch_assoc($postagens)): ?>
                        <div class="p-4 mb-4"
                             style="background: rgba(99, 102, 241, 0.07);
                                    border-radius: 12px;
                                    border-left: 3px solid var(--primary-cyan);">
                            <h5 class="mb-1" style="color: var(--primary-cyan);">
                                <i class="bi bi-person-circle me-2"></i>
                                <?= htmlspecialchars($postagem["nome"]) ?>
                            </h5>

                            <p class="text-secondary mb-2" style="font-size: 0.9rem;">
                                <i class="bi bi-envelope-fill me-1"></i>
                                <?= htmlspecialchars($postagem["email"]) ?>
                            </p>

                            <p class="mt-3 mb-3 text-dark" style="line-height: 1.6;">
                                <?= nl2br(htmlspecialchars($postagem["texto"])) ?>
                            </p>

                            <small class="text-secondary">
                                <i class="bi bi-clock me-1"></i>
                                <?= date("d/m/Y H:i", strtotime($postagem["data_criacao"])) ?>
                            </small>
                        </div>
                    <?php endwhile; ?>

                </div>
            </div>
        </div>
    </div>
</div>
