<div class="container">
    <div class="content-wrapper">
        <div class="text-center mb-5 pb-4">
            <h1 class="display-2 fw-bold mb-4 gradient-text" style="font-size: 3rem;">
                <i class="bi bi-people me-3"></i> Jogadores e Personagens
            </h1>
            <p class="lead fs-5">Explore os jogadores da campanha e seus respectivos heróis</p>
        </div>

        <?php
        require_once "admin/config.inc.php";

        // 1. Otimização: Buscar todos os personagens e dados dos jogadores em uma única consulta
        $sql = "SELECT p.*, j.numero as contato 
                FROM personagens p 
                LEFT JOIN jogadores j ON p.jogador = j.jogador 
                ORDER BY p.jogador ASC, p.personagem ASC";
        $resultado = mysqli_query($conexao, $sql);

        $jogadores = [];
        if ($resultado && mysqli_num_rows($resultado) > 0) {
            while ($personagem = mysqli_fetch_assoc($resultado)) {
                // Agrupa os personagens por jogador
                $jogadores[$personagem['jogador']]['personagens'][] = $personagem;
                // Armazena o contato do jogador (apenas uma vez)
                if (!isset($jogadores[$personagem['jogador']]['contato'])) {
                    $jogadores[$personagem['jogador']]['contato'] = $personagem['contato'];
                }
            }
        }

        if(!empty($jogadores)){
        ?>
        <div class="accordion" id="accordionJogadores">
            <?php
            // 2. Itera sobre o array de jogadores já agrupado
            foreach($jogadores as $jogador_nome => $dados_jogador){
                // ID único para o collapse do Bootstrap
                $collapseId = 'collapse' . preg_replace('/[^a-zA-Z0-9]/', '', $jogador_nome);
            ?>
            <div class="accordion-item glass-card mb-3">
                <h2 class="accordion-header" id="heading-<?= $collapseId ?>">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#<?= $collapseId ?>" aria-expanded="false" aria-controls="<?= $collapseId ?>" title="Personagens: <?= count($dados_jogador['personagens']) ?>">
                        <i class="bi bi-person-circle me-3 fs-4"></i>
                        <div class="d-flex justify-content-between w-100 align-items-center pe-3">
                            <span class="fw-bold fs-5"><?= htmlspecialchars($jogador_nome) ?></span>
                            <?php if (!empty($dados_jogador['contato'])): ?>
                                <span class="badge bg-secondary-subtle text-secondary-emphasis rounded-pill">
                                    <i class="bi bi-telephone-fill me-1"></i> <?= htmlspecialchars($dados_jogador['contato']) ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    </button>
                </h2>
                <div id="<?= $collapseId ?>" class="accordion-collapse collapse" aria-labelledby="heading-<?= $collapseId ?>" data-bs-parent="#accordionJogadores">
                    <div class="accordion-body">
                        <div class="row g-4">
                            <?php foreach($dados_jogador['personagens'] as $dados){ ?>
                            <div class="col-md-6 col-lg-4">
                                <div class="glass-card p-4 h-100" style="position: relative; overflow: hidden; border: 1px solid rgba(255,255,255,0.1)">
                                    <div style="position: absolute; top: -50px; right: -50px; width: 150px; height: 150px; background: var(--purple-gradient); opacity: 0.1; border-radius: 50%;"></div>
                                    
                                    <div class="mb-4" style="position: relative; z-index: 1;">
                                        <div class="p-3 mb-3" style="background: var(--purple-gradient); border-radius: 16px; text-align: center;">
                                            <h3 class="mb-1 fw-bold text-white"><?= htmlspecialchars($dados['personagem']) ?></h3>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <h5 class="mb-3" style="color: var(--primary-cyan);"><i class="bi bi-info-circle me-2"></i> Informações</h5>
                                            <div class="row g-2">
                                                <div class="col-6">
                                                    <div class="p-2" style="background: rgba(99, 102, 241, 0.1); border-radius: 8px;">
                                                        <p class="mb-0 small text-secondary">Espécie</p>
                                                        <p class="mb-0 fw-semibold" style="color: var(--primary-cyan); font-size: 0.9rem;"><?= htmlspecialchars($dados['especie']) ?></p>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="p-2" style="background: rgba(99, 102, 241, 0.1); border-radius: 8px;">
                                                        <p class="mb-0 small text-secondary">Classe</p>
                                                        <p class="mb-0 fw-semibold" style="color: var(--primary-cyan); font-size: 0.9rem;"><?= htmlspecialchars($dados['classe']) ?></p>
                                                    </div>
                                                </div>
                                                <?php if(!empty($dados['subclasse'])) { ?>
                                                <div class="col-12 mt-2">
                                                    <div class="p-2" style="background: rgba(99, 102, 241, 0.1); border-radius: 8px;">
                                                        <p class="mb-0 small text-secondary">Subclasse</p>
                                                        <p class="mb-0 fw-semibold" style="color: var(--primary-cyan); font-size: 0.9rem;"><?= htmlspecialchars($dados['subclasse']) ?></p>
                                                    </div>
                                                </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                        
                                        <div>
                                            <h5 class="mb-3" style="color: var(--primary-cyan);"><i class="bi bi-bar-chart me-2"></i> Atributos</h5>
                                            <div class="row g-2">
                                                <?php
                                                    $atributos = ['forca' => 'FOR', 'destreza' => 'DES', 'constituicao' => 'CON', 'inteligencia' => 'INT', 'sabedoria' => 'SAB', 'carisma' => 'CAR'];
                                                    foreach ($atributos as $key => $label) {
                                                ?>
                                                <div class="col-4">
                                                    <div class="p-2 text-center" style="background: rgba(99, 102, 241, 0.15); border-radius: 10px; border: 1px solid rgba(99, 102, 241, 0.3);">
                                                        <p class="mb-0 small text-secondary"><?= $label ?></p>
                                                        <p class="mb-0 fw-bold" style="color: var(--primary-cyan); font-size: 1.1rem;"><?= $dados[$key] ?></p>
                                                    </div>
                                                </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            }
            ?>
        </div>
        <?php
        } else {
        ?>
        <div class="text-center py-5">
            <div style="width: 120px; height: 120px; background: rgba(99, 102, 241, 0.1); border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 2rem;">
                <i class="bi bi-inbox fs-1" style="color: var(--primary-cyan);"></i>
            </div>
            <h2 class="mb-3 gradient-text">Nenhum jogador ou personagem encontrado</h2>
            <p class="lead text-secondary mb-4">Cadastre um personagem no painel de admin para que ele e seu jogador apareçam aqui.</p>
            <a href="admin/login.php" class="btn btn-modern btn-lg">
                <i class="bi bi-shield-lock me-2"></i> Acessar Admin para Cadastrar
            </a>
        </div>
        <?php
        }
        mysqli_close($conexao);
        ?>
    </div>
</div>
