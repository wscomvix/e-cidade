<?php
$cnpj = $_GET['cnpj'];
$ano = $_GET['ano'];
$orgao = $_GET['orgao'];
$reduzido = $_GET['reduzido'];
$nomeArquivo = "arquivo-{$reduzido}";
$erro = false;
$confirmacao = "";

if (strtolower(end(explode('.', $_FILES["$nomeArquivo"]['name']))) != "pdf") {
    echo "<div style=\"color: red;\">Envie arquivos somente com extens�o .pdf</div>";
    $erro = true;
} else {
    $diretorio = "extratobancariosicom/{$cnpj}/{$ano}";
    $novoNome = "{$diretorio}/CTB_{$orgao}_{$reduzido}.pdf";

    if (!is_dir($diretorio)) {
        mkdir($diretorio, 0777, true);
    }

    if (file_exists($novoNome)) {
        $confirmacao = "[Substitu�do]";
    }

    if (!move_uploaded_file($_FILES["$nomeArquivo"]['tmp_name'], $novoNome)) {
        echo "<div style=\"color: red;\">N�o foi poss�vel salvar arquivo</div>";
        $erro = true;
    }
}

if ($erro == false)
    echo "<span class='enviado'>Enviado {$confirmacao}</span>";
?>
