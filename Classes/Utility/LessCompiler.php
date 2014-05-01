<?php

namespace Stach\CsLess\Utility;

/* * *************************************************************
 *  Copyright notice
 *
 *  (c) 2011 Christoph Stach
 *  (c) 2014 Benedict Burckhart <benedict.burckhart@flagbit.de>
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 * ************************************************************* */

/**
 * @author Christoph Stach, Benedict Burckhart
 * @version 1.0.2
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 * @package cs_less
 */
class LessCompiler implements \TYPO3\CMS\Core\SingletonInterface {

    /**
     * @var string $outputFolder
     */
    private $outputFolder = 'typo3temp';

    /**
     * @var \TYPO3\CMS\Core\Cache\Frontend\AbstractFrontend
     */
    private $cacheInstance = null;

    /**
     * @param string $filePath The filepath to the less-file
     * @return string The filepath to the compiled css-file
     */
    public function compileFile($filePath) {
        $folder = dirname($filePath);
        $file = basename($filePath);
				
        $cssFilePath = $this->outputFolder . DIRECTORY_SEPARATOR . substr($file, 0, -5) . '-' . md5_file(($folder . DIRECTORY_SEPARATOR . $file)) . '.css';
        $this->cachedCompile($folder . DIRECTORY_SEPARATOR . $file, $cssFilePath);

        return sprintf('%s?%s',$cssFilePath, $this->getCachedTimestamp());
    }

    /**
     * @param string $in The .LESS Inputfile to compile
     * @param string $out The .CSS Ouputfile
     * @return boolean TRUE If the file was compiled, FALSE if the file was cached
     */
    private function cachedCompile($in, $out) {
        $folder = '../' . dirname($in) . DIRECTORY_SEPARATOR;
        $cachedFile = $this->outputFolder . DIRECTORY_SEPARATOR . basename($in) . '.cache';

        if (file_exists($cachedFile)) {
            $cache = unserialize(file_get_contents($cachedFile));
        } else {
            $cache = $in;
        }

        $newCache = \lessc::cexecute($cache, TRUE);
        
        

        if (!is_array($cache) || $newCache['updated'] > $cache['updated']) {
            $newCache['compiled'] = preg_replace('#url\(\'([a-z0-9/_\-.]+[\?\#a-z0-9]*)\'\)#i', 'url(\'' . $folder . '$1\')', $newCache['compiled']);
            $newCache['compiled'] = preg_replace('#url\("([a-z0-9/_\-.]+[\?\#a-z0-9]*)"\)#i', 'url(\'' . $folder . '$1\')', $newCache['compiled']);
            $newCache['compiled'] = preg_replace('#url\(([a-z0-9/_\-.]+[\?\#a-z0-9]*)\)#i', 'url(\'' . $folder . '$1\')', $newCache['compiled']);

            file_put_contents($cachedFile, serialize($newCache));
            file_put_contents($out, $newCache['compiled']);

            return true;
        }

        return false;
    }

    /**
     * Get cached timestamp for cachebreaker
     *
     * @return integer
     */
    private function getCachedTimestamp() {
        $this->initializeCache();
        if (($timestamp = $this->getCache('timestamp')) === false) {
            $timestamp = time();
            $this->setCache('timestamp', (string)$timestamp);
        }
				
        return $timestamp;
    }

    /**
     * Initialize cache instance to be ready to use
     *
     * @return void
     */
    private function initializeCache() {
        \TYPO3\CMS\Core\Cache\Cache::initializeCachingFramework();
				
				try {
					$this->cacheInstance = $GLOBALS['typo3CacheManager']->getCache('cs_less_cache');	
				} catch (\TYPO3\CMS\Core\Cache\Exception\NoSuchCacheException $exception) {
					$this->cacheInstance = $GLOBALS['typo3CacheFactory']->create(
						'cs_less_cache',
						$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['cs_less_cache']['frontend'],
						$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['cs_less_cache']['backend'],
						$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['cs_less_cache']['options']
					);
				}
    }

    /**
     * Cache set
     *
     * @param $key
     * @param $value
     * @return void | boolean false if cache ist not initialized
     */
    private function setCache($key, $value, $expire=0) {
        if ($this->cacheInstance === null) {
            return false;
        }
				
        $this->cacheInstance->set($key, $value, array('lesscache'), $expire);
    }

    /**
     * Cache get
     *
     * @param $key
     * @return mixed | false if cache ist not initialized
     */
    private function getCache($key) {
        if ($this->cacheInstance === null) {
            return false;
        }
				
        return $this->cacheInstance->get($key);
    }

}