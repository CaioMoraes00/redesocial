
<?php

require_once "../back/config.php";

// Usuário temporário para teste
$id_usuario = 1;

// NOMES DEMONSTRATIVOS
$nomes = [
    "Lucas Oliveira",
    "Gabriel Santos",
    "Pedro Henrique",
    "Matheus Costa",
    "Rafael Almeida",
    "João Miguel"
];

// TEXTOS DAS PUBLICAÇÕES
$textos = [
    "Que campeonato disputado! Cada rodada está trazendo novas emoções. Quem será o grande campeão desta temporada? ⚽",

    "A Champions League está chegando em sua fase decisiva! Qual time você acredita que vai levantar a taça? 🏆",

    "O mercado da bola promete movimentar bastante os clubes. Qual contratação seria perfeita para o seu time? 🔥",

    "O futebol brasileiro continua revelando grandes talentos. Qual jovem jogador merece mais oportunidades? 🇧🇷",

    "Na sua opinião, quem é o melhor jogador do mundo atualmente? Deixe sua opinião nos comentários! 💬",

    "Futebol é paixão, história e emoção. Não importa o tamanho do clube, sempre existe um motivo para torcer! 📢"
];

// IMAGENS DAS PUBLICAÇÕES
$imagens = [
    "https://images.unsplash.com/photo-1522778119026-d647f0596c20?w=900",
    "https://images.unsplash.com/photo-1574629810360-7efbbe195018?w=900",
    "https://images.unsplash.com/photo-1431324155629-1a6deb1dec8d?w=900",
    "https://images.unsplash.com/photo-1517466787929-bc90951d0974?w=900",
    "https://images.unsplash.com/photo-1521412644187-c49fa049e84d?w=900",
    "https://images.unsplash.com/photo-1508098682722-e99c643e7f0b?w=900"
];

// SISTEMA DE CURTIDAS
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["curtir"])) {

    $id_publicacao = (int) $_POST["id_publicacao"];

    $sql = "SELECT id_curtida FROM curtidas
            WHERE id_usuario = ? AND id_publicacao = ?";

    $stmt = $con->prepare($sql);
    $stmt->execute([$id_usuario, $id_publicacao]);

    if ($stmt->fetch()) {

        $sql = "DELETE FROM curtidas
                WHERE id_usuario = ? AND id_publicacao = ?";

        $stmt = $con->prepare($sql);
        $stmt->execute([$id_usuario, $id_publicacao]);

    } else {

        $sql = "INSERT INTO curtidas (id_usuario, id_publicacao)
                VALUES (?, ?)";

        $stmt = $con->prepare($sql);
        $stmt->execute([$id_usuario, $id_publicacao]);

    }

    header("Location: index.php");
    exit;
}

// BUSCAR PUBLICAÇÕES
$sql = "SELECT * FROM publicacoes ORDER BY id_publicacao DESC";
$stmt = $con->prepare($sql);
$stmt->execute();

$publicacoes = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>FutSocial - Rede Social de Futebol</title>

    <link rel="stylesheet" href="style.css">
</head>

<body>

    <header class="topo">

        <h1>Fut<span>Social</span></h1>

        <input
            type="text"
            placeholder="Pesquisar notícias, times e jogadores...">

        <div class="perfil">
            <a href="login.html" class="btn-entrar">Entrar</a>
        </div>

    </header>

    <main class="container">

        <aside class="menu">

            <h3>Menu</h3>

            <a href="index.php">🏠 Início</a>
            <a href="#">🔥 Em alta</a>
            <a href="#">📰 Notícias</a>
            <a href="#">⚽ Futebol</a>
            <a href="#">🏆 Campeonatos</a>
            <a href="login.html">👤 Meu perfil</a>

        </aside>

        <section class="feed">

            <div class="criar-publicacao">

                <h2>O que está acontecendo no futebol?</h2>

                <textarea placeholder="Compartilhe uma notícia ou opinião..."></textarea>

                <div class="acoes-publicacao">

                    <button class="foto">📷 Foto</button>

                    <button class="publicar">Publicar</button>

                </div>

            </div>

            <div class="titulo-feed">
                <h2>⚽ FUTEBOL EM DESTAQUE</h2>
                <p>As principais publicações da comunidade FutSocial.</p>
            </div>

            <?php $contador = 0; ?>

            <?php foreach ($publicacoes as $publicacao): ?>

                <?php

                $id_pub = $publicacao["id_publicacao"];

                $sqlCurtidas = "SELECT COUNT(*) FROM curtidas
                                WHERE id_publicacao = ?";

                $stmtCurtidas = $con->prepare($sqlCurtidas);
                $stmtCurtidas->execute([$id_pub]);

                $totalCurtidas = $stmtCurtidas->fetchColumn();

                $sqlMinhaCurtida = "SELECT id_curtida FROM curtidas
                                    WHERE id_usuario = ? AND id_publicacao = ?";

                $stmtMinhaCurtida = $con->prepare($sqlMinhaCurtida);
                $stmtMinhaCurtida->execute([$id_usuario, $id_pub]);

                $curtiu = $stmtMinhaCurtida->fetch();

                $nomeUsuario = $publicacao["nome"] ?? "";

                if (empty($nomeUsuario)) {
                    $nomeUsuario = $nomes[$contador % count($nomes)];
                }

                $texto = $textos[$contador % count($textos)];

                $imagem = $imagens[$contador % count($imagens)];

                ?>

                <article class="publicacao">

                    <div class="usuario">

                        <div class="avatar">
                            <?= strtoupper(substr($nomeUsuario, 0, 1)) ?>
                        </div>

                        <div>

                            <h3>
                                <?= htmlspecialchars($nomeUsuario) ?>
                            </h3>

                            <small>Torcedor • FutSocial</small>

                        </div>

                    </div>

                    <p>
                        <?= htmlspecialchars($texto) ?>
                    </p>

                    <img
                        src="<?= htmlspecialchars($imagem) ?>"
                        alt="Imagem de futebol"
                        class="imagem-publicacao">

                    <div class="interacoes">

                        <form method="POST">

                            <input
                                type="hidden"
                                name="id_publicacao"
                                value="<?= $id_pub ?>">

                            <button type="submit" name="curtir">

                                <?= $curtiu ? "❤️ Curtido" : "🤍 Curtir" ?>

                                (<?= $totalCurtidas ?>)

                            </button>

                        </form>

                        <button type="button">💬 Comentar</button>

                        <button type="button">🔗 Compartilhar</button>

                    </div>

                </article>

                <?php $contador++; ?>

            <?php endforeach; ?>

            <?php if (empty($publicacoes)): ?>

                <p>Nenhuma publicação encontrada.</p>

            <?php endif; ?>

        </section>

        <aside class="lateral">

            <h3>Em alta 🔥</h3>

            <div class="assunto">
                <small>Campeonato</small>
                <h4>Brasileirão</h4>
            </div>

            <div class="assunto">
                <small>Competição</small>
                <h4>Champions League</h4>
            </div>

            <div class="assunto">
                <small>Assunto</small>
                <h4>Mercado da bola</h4>
            </div>

        </aside>

    </main>

</body>
</html>
