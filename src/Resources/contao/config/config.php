<?php
 
/**
 * @copyright	Bright Cloud Studio
 * @author		Bright Cloud Studio
 * @package	Contao Pages
 * @license		LGPL-3.0+
 * @see			https://github.com/bright-cloud-studio/contao-pages
 **/

/** Front end modules */ 
$GLOBALS['FE_MOD']['zyppy_page']['pagepreview'] 	= 'Bcs\Module\PagePreview'; 
$GLOBALS['FE_MOD']['zyppy_page']['related_pages'] 	= 'Bcs\Module\RelatedPages'; 


/**
 \\\\e* Hooks */
$GLOBALS['TL_HOOKS']['replaceInsertTags'][]				= array('Bcs\PagePreview', 'replaceInsertTags');
