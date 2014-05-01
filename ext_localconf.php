<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

if (!is_array($TYPO3_CONF_VARS['SYS']['caching']['cacheConfigurations']['cs_less_cache'])) {
    $TYPO3_CONF_VARS['SYS']['caching']['cacheConfigurations']['cs_less_cache'] = array();
}