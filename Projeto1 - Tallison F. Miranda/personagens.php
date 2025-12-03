<div class="container">
    <div class="content-wrapper">
        <div class="text-center mb-5 pb-4">
            <h1 class="display-2 fw-bold mb-4 gradient-text" style="font-size: 3rem;">
                <i class="bi bi-people me-3"></i> Jogadores e Personagens
            </h1>
            <p class="lead fs-5">Explore os jogadores da campanha e seus respectivos heróis</p>
        </div>

        <?php
        /**
         * PASSO 1: PREPARAÇÃO E BUSCA DE DADOS
         */

        // Inclui o arquivo de configuração do banco de dados.
        // 'require_once' garante que o arquivo seja incluído apenas uma vez, evitando erros de re-declaração.
        // Este arquivo deve conter a variável de conexão '$conexao'.
        require_once "admin/config.inc.php";

        // Consulta SQL otimizada para buscar todos os dados necessários em uma única chamada ao banco.
        // - SELECT p.*, j.numero as contato: Seleciona todas as colunas da tabela 'personagens' (usando o alias 'p')
        //   e a coluna 'numero' da tabela 'jogadores' (alias 'j'), renomeando-a para 'contato' para clareza.
        // - FROM personagens p: Define a tabela principal da consulta.
        // - LEFT JOIN jogadores j ON p.jogador = j.jogador: Une a tabela 'personagens' com 'jogadores'.
        //   O 'LEFT JOIN' é usado para garantir que todos os personagens sejam listados, mesmo que, por algum motivo,
        //   o jogador correspondente não tenha um registro na tabela 'jogadores'.
        // - ORDER BY p.jogador ASC, p.personagem ASC: Ordena os resultados. A ordenação por jogador é CRUCIAL
        //   para que o agrupamento em PHP (próximo passo) funcione corretamente, pois todos os personagens
        //   de um mesmo jogador virão em sequência.
        $sql = "SELECT p.*, j.numero as contato 
                FROM personagens p 
                LEFT JOIN jogadores j ON p.jogador = j.jogador 
                ORDER BY p.jogador ASC, p.personagem ASC";
        
        // Executa a consulta SQL no banco de dados.
        $resultado = mysqli_query($conexao, $sql);

        // Inicializa um array vazio que será usado para agrupar os personagens por jogador.
        // A estrutura final será: ['NomeDoJogador' => ['contato' => '...', 'personagens' => [ ... ]]]
        $jogadores = [];
        // Verifica se a consulta foi bem-sucedida e se retornou pelo menos uma linha.
        if ($resultado && mysqli_num_rows($resultado) > 0) {
            // Itera sobre cada linha de resultado. 'mysqli_fetch_assoc' retorna cada linha como um array associativo.
            while ($personagem = mysqli_fetch_assoc($resultado)) {
                // Lógica de agrupamento: Usa o nome do jogador como a chave principal do array '$jogadores'.
                // Adiciona o array completo do personagem atual dentro da chave 'personagens' do jogador correspondente.
                $jogadores[$personagem['jogador']]['personagens'][] = $personagem;
                // Para evitar sobreescrever o contato a cada personagem do mesmo jogador,
                // verificamos se o contato já foi definido. Se não, o definimos.
                if (!isset($jogadores[$personagem['jogador']]['contato'])) {
                    $jogadores[$personagem['jogador']]['contato'] = $personagem['contato'];
                }
            }
        }

        if(!empty($jogadores)){
        /**
         * PASSO 2: RENDERIZAÇÃO DOS DADOS NA PÁGINA
         * Se o array $jogadores não estiver vazio, significa que encontramos dados para exibir.
         */
        ?>
        <div class="accordion" id="accordionJogadores">
            <?php
            // Itera sobre o array '$jogadores' que foi montado no passo anterior.
            // A cada iteração, '$jogador_nome' receberá a chave (ex: 'Tallison') e
            // '$dados_jogador' receberá o valor (o array com 'contato' e 'personagens').
            foreach($jogadores as $jogador_nome => $dados_jogador){
                // Cria um ID único e válido para os elementos HTML do acordeão.
                // Nomes de jogadores podem conter espaços ou caracteres especiais que não são permitidos em IDs.
                // 'preg_replace' remove tudo que não for letra ou número, garantindo um ID seguro.
                $collapseId = 'collapse' . preg_replace('/[^a-zA-Z0-9]/', '', $jogador_nome);
            ?>
            <!-- Início do item do acordeão para um jogador -->
            <div class="accordion-item glass-card mb-3">
                <h2 class="accordion-header" id="heading-<?= $collapseId ?>">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#<?= $collapseId ?>" aria-expanded="false" aria-controls="<?= $collapseId ?>" title="Personagens: <?= count($dados_jogador['personagens']) ?>">
                        <i class="bi bi-person-circle me-3 fs-4"></i>
                        <div class="d-flex justify-content-between w-100 align-items-center pe-3">
                            <span class="fw-bold fs-5"><?= htmlspecialchars($jogador_nome) // Exibe o nome do jogador. 'htmlspecialchars' é essencial para prevenir ataques de Cross-Site Scripting (XSS). ?></span>
                            <?php if (!empty($dados_jogador['contato'])): ?>
                                <span class="badge bg-secondary-subtle text-secondary-emphasis rounded-pill">
                                    <i class="bi bi-telephone-fill me-1"></i> <?= htmlspecialchars($dados_jogador['contato']) ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    </button>
                </h2>
                <!-- Conteúdo colapsável do acordeão, que contém os cards dos personagens -->
                <div id="<?= $collapseId ?>" class="accordion-collapse collapse" aria-labelledby="heading-<?= $collapseId ?>" data-bs-parent="#accordionJogadores">
                    <div class="accordion-body">
                        <div class="row g-4">
                            <?php // Agora, itera sobre a lista de personagens ESPECÍFICA deste jogador. ?>
                            <?php foreach($dados_jogador['personagens'] as $dados){ ?>
                            <div class="col-md-6 col-lg-4">
                                <!-- Card individual para cada personagem -->
                                <div class="glass-card p-4 h-100" style="position: relative; overflow: hidden; border: 1px solid rgba(255,255,255,0.1)">
                                    <div style="position: absolute; top: -50px; right: -50px; width: 150px; height: 150px; background: var(--purple-gradient); opacity: 0.1; border-radius: 50%;"></div>
                                    
                                    <div class="mb-4" style="position: relative; z-index: 1;">
                                        <!-- Cabeçalho do card com o nome do personagem -->
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
                                                    // Para evitar repetição de código, criamos um array que mapeia as colunas do banco ('forca') para as labels ('FOR').
                                                    // Em seguida, um loop 'foreach' gera o HTML para cada um dos 6 atributos dinamicamente.
                                                ?>
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
        /**
         * PASSO 3: MENSAGEM DE "NENHUM RESULTADO"
         * Se o array $jogadores estiver vazio após a consulta, exibe uma mensagem amigável para o usuário.
         */
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
        // É uma boa prática fechar a conexão com o banco de dados ao final do script para liberar recursos no servidor.
        mysqli_close($conexao);
        ?>
    </div>
</div>
