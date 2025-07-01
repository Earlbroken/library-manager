<?php
$host = "localhost";
$usuario = "root";
$senha = "";
$banco = "jogoteca";

$conexao = new mysqli($host, $usuario, $senha, $banco);
if ($conexao->connect_error) {
    die("Erro de conexão: " . $conexao->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['salvar'])) {
  $titulo = $_POST['titulo'];
  $genero = $_POST['genero'];
  $plataforma = $_POST['plataforma'];
  $ano = $_POST['ano_lancamento'];
  $descricao = $_POST['descricao'];

  $sql = "INSERT INTO jogos (titulo, genero, plataforma, ano_lancamento, descricao)
          VALUES ('$titulo', '$genero', '$plataforma', '$ano', '$descricao')";
  $conexao->query($sql);
  header("Location: index.php?msg=adicionado");
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['atualizar'])) {
  $id = $_POST['id'];
  $titulo = $_POST['titulo'];
  $genero = $_POST['genero'];
  $plataforma = $_POST['plataforma'];
  $ano = $_POST['ano_lancamento'];
  $descricao = $_POST['descricao'];

  $sql = "UPDATE jogos SET 
          titulo='$titulo', genero='$genero', plataforma='$plataforma',
          ano_lancamento='$ano', descricao='$descricao'
          WHERE id=$id";
  $conexao->query($sql);
  header("Location: index.php?msg=editado");
  exit;
}

if (isset($_GET['excluir'])) {
  $id = $_GET['excluir'];
  $conexao->query("DELETE FROM jogos WHERE id=$id");
  header("Location: index.php?msg=excluido");
  exit;
}

$jogoEdicao = null;
if (isset($_GET['editar'])) {
  $id = $_GET['editar'];
  $jogoEdicao = $conexao->query("SELECT * FROM jogos WHERE id=$id")->fetch_assoc();
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Jogoteca</title>
  <style>
    body { font-family: Arial; background: #f4f4f4; padding: 20px; }
    h1 { color: #333; }
    input, textarea { margin: 5px 0; width: 300px; padding: 5px; }
    button { padding: 6px 12px; cursor: pointer; }
    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    th, td { border: 1px solid #ccc; padding: 8px; text-align: center; }
    a { text-decoration: none; margin: 0 5px; }
    .msg { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; padding: 10px; margin-bottom: 15px; width: fit-content; }
  </style>
</head>
<body>

<?php if (isset($_GET['msg'])): ?>
  <div class="msg">
    <?php
      if ($_GET['msg'] === 'adicionado') echo "🎉 Jogo adicionado com sucesso!";
      elseif ($_GET['msg'] === 'editado') echo "✏️ Jogo editado com sucesso!";
      elseif ($_GET['msg'] === 'excluido') echo "🗑️ Jogo excluído com sucesso!";
    ?>
  </div>
<?php endif; ?>

<h1>🎮 Jogoteca</h1>

<?php if ($jogoEdicao): ?>
  <h2>✏️ Editar Jogo</h2>
  <form method="POST">
    <input type="hidden" name="id" value="<?= $jogoEdicao['id'] ?>">
    <input type="text" name="titulo" value="<?= $jogoEdicao['titulo'] ?>" required><br>
    <input type="text" name="genero" value="<?= $jogoEdicao['genero'] ?>"><br>
    <input type="text" name="plataforma" value="<?= $jogoEdicao['plataforma'] ?>"><br>
    <input type="number" name="ano_lancamento" value="<?= $jogoEdicao['ano_lancamento'] ?>"><br>
    <textarea name="descricao"><?= $jogoEdicao['descricao'] ?></textarea><br>
    <button type="submit" name="atualizar">Atualizar</button>
  </form>
<?php else: ?>
  <h2>➕ Adicionar Novo Jogo</h2>
  <form method="POST">
    <input type="text" name="titulo" placeholder="Título" required><br>
    <input type="text" name="genero" placeholder="Gênero"><br>
    <input type="text" name="plataforma" placeholder="Plataforma"><br>
    <input type="number" name="ano_lancamento" placeholder="Ano"><br>
    <textarea name="descricao" placeholder="Descrição"></textarea><br>
    <button type="submit" name="salvar">Salvar</button>
  </form>
<?php endif; ?>

<h2>📋 Lista de Jogos</h2>
<table>
  <tr>
    <th>Título</th><th>Gênero</th><th>Plataforma</th><th>Ano</th><th>Ações</th>
  </tr>
  <?php
    $resultado = $conexao->query("SELECT * FROM jogos");
    while ($jogo = $resultado->fetch_assoc()):
  ?>
  <tr>
    <td><?= $jogo['titulo'] ?></td>
    <td><?= $jogo['genero'] ?></td>
    <td><?= $jogo['plataforma'] ?></td>
    <td><?= $jogo['ano_lancamento'] ?></td>
    <td>
      <a href="?editar=<?= $jogo['id'] ?>">✏️</a>
      <a href="?excluir=<?= $jogo['id'] ?>" onclick="return confirm('Deseja excluir este jogo?')">❌</a>
    </td>
  </tr>
  <?php endwhile; ?>
</table>

</body>
</html>