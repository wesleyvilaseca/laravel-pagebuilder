<?php
$filePath = 'vendor/hansschouten/phpagebuilder/src/ThemeBlock.php';
$insertLines = "use App\Supports\ExtensionsTraitClass\ThemeBlockExtensionTrait;\n";

$fileContents = file_get_contents($filePath);

// Verifica se o use já não está presente no arquivo
if (strpos($fileContents, 'use App\Supports\ExtensionsTraitClass\ThemeBlockExtensionTrait;') === false) {
    // Encontrar o fim do namespace
    $namespaceEndPos = strpos($fileContents, ';', strpos($fileContents, 'namespace')) + 1;

    // Insere o use após o namespace
    $updatedContents = substr_replace($fileContents, "\n\n$insertLines", $namespaceEndPos, 0);

    // Insere após a declaração da classe ThemeBlock
    $updatedContents = preg_replace('/(class ThemeBlock\s*\{)/', "$1\n\nuse ThemeBlockExtensionTrait;\n\n", $updatedContents, 1);

    // Salva o arquivo atualizado
    file_put_contents($filePath, $updatedContents);

    echo "O use ThemeBlockExtension foi adicionado com sucesso após o namespace em $filePath.\n";
} else {
    echo "O arquivo já contém o use App\Supports\ExtensionsTraitClass\ThemeBlockExtensionTrait.\n";
}

$filePath = 'vendor/hansschouten/phpagebuilder/src/Modules/GrapesJS/Block/BlockAdapter.php';
$fileContents = file_get_contents($filePath);

if (strpos($fileContents, '$this->block->getThumbPathTrait()') === false) {
    // Realiza as substituições necessárias
    $updatedContents = str_replace('$this->block->getThumbPath()', '$this->block->getThumbPathTrait()', $fileContents);
    $updatedContents = str_replace('phpb_full_url($this->block->getThumbUrl())', '$this->block->getThumbUrlTrait()', $updatedContents);

    // Salva o arquivo atualizado
    file_put_contents($filePath, $updatedContents);

    echo "O blockadapter foi resolvido foi adicionado com sucesso após o namespace em $filePath.\n";
} else {
    echo "O arquivo já contém o this->block->getThumbPathTrait().\n";
}