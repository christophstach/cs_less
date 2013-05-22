<?php

$extensionPath = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('cs_less');

$autoload = array(
    'lessc' => $extensionPath . 'Resources/Private/PHP/lessphp/lessc.inc.php'
);

return $autoload;
?>