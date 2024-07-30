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
    'Asc\Backend\ZyppyPage' 	=> 'system/modules/zyppy_page/library/Asc/Backend/ZyppyPage.php',
    'Asc\Module\PagePreview' 	=> 'system/modules/zyppy_page/library/Asc/Module/PagePreview.php',
    'Asc\Module\RelatedPages' 	=> 'system/modules/zyppy_page/library/Asc/Module/RelatedPages.php',
	'Asc\PagePreview' 			=> 'system/modules/zyppy_page/library/Asc/PagePreview.php'
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
