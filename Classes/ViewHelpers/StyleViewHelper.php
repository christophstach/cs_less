<?php

namespace Stach\CsLess\ViewHelpers;

/* * *************************************************************
 *  Copyright notice
 *
 *  (c) 2011 Christoph Stach
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
 * Style ViewHelper
 *
 * @author Christoph Stach
 * @version 1.1.0
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 * @package cs_less
 */
class StyleViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper {

    /**
     * Der Less-Compiler
     * 
     * @var \Stach\CsLess\Utility\LessCompiler
     * @inject
     */
    protected $lessCompiler;

    /**
     * Initialize arguments.
     *
     * @return void
     */
    public function initializeArguments() {
        parent::initializeArguments();

        $this->registerTagAttribute('href', 'string', 'Path to the LessFile', TRUE);
    }

    /**
     * Make Less.
     *
     * @return string
     */
    public function render() {
        $this->tag->setTagName('link');
        $this->tag->addAttribute('rel', 'stylesheet');
        $this->tag->addAttribute('href', $this->lessCompiler->compileFile($this->arguments['href']));

        return $this->tag->render();
    }

}

?>
