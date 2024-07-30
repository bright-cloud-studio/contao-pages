<?php
 
/**
 * @copyright	Bright Cloud Studio
 * @author		Bright Cloud Studio
 * @package	Contao Pages
 * @license		LGPL-3.0+
 * @see			https://github.com/bright-cloud-studio/contao-pages
 **/

/** Register the classes */
ClassLoader::addClasses(array
(
    //'Bcs\Backend\ContaoPages' 	=> 'system/modules/zyppy_page/library/Bcs/Backend/ContaoPages.php',
    //'Bcs\Module\PagePreview' 	=> 'system/modules/zyppy_page/library/Bcs/Module/PagePreview.php',
    //'Bcs\Module\RelatedPages' 	=> 'system/modules/zyppy_page/library/Bcs/Module/RelatedPages.php',
	//'Bcs\PagePreview' 			=> 'system/modules/zyppy_page/library/Bcs/PagePreview.php'
));

/** Register the templates */
TemplateLoader::addFiles(array
(
    //'mod_page_preview' 			=> 'system/modules/zyppy_page/templates',
	//'nav_pagepreview_header' 	=> 'system/modules/zyppy_page/templates',
	//'nav_pagepreview_body' 		=> 'system/modules/zyppy_page/templates',
));
