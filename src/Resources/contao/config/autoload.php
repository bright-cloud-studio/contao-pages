<?php
 
/**
 * Zyppy Page
 *
 * Copyright (C) 2018-2022 Andrew Stevens Consulting
 *
 * @package    asconsulting/zyppy_page
 * @link       https://andrewstevens.consulting
 */


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
    'Bcs\Backend\ZyppyPage' 	=> 'system/modules/zyppy_page/library/Bcs/Backend/ContaoPages.php',
    'Bcs\Module\PagePreview' 	=> 'system/modules/zyppy_page/library/Bcs/Module/PagePreview.php',
    'Bcs\Module\RelatedPages' 	=> 'system/modules/zyppy_page/library/Bcs/Module/RelatedPages.php',
	'Bce\PagePreview' 			=> 'system/modules/zyppy_page/library/Bcs/PagePreview.php'
));

/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
    'mod_pagepreview' 			=> 'system/modules/zyppy_page/templates',
	'nav_pagepreview_header' 	=> 'system/modules/zyppy_page/templates',
	'nav_pagepreview_body' 		=> 'system/modules/zyppy_page/templates',
));
