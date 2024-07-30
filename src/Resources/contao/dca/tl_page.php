<?php

/**
 * Bright Cloud Studio's Contao Pages
 *
 * Copyright (C) 2024-2025 Bright Cloud Studio
 *
 * @package	 bright-cloud-studio/contao-pages
 * @link	 https://brightcloudstudio.com
 */


	
/**
 * Palettes
 */
$GLOBALS['TL_DCA']['tl_page']['palettes']['__selector__'][] = 'page_image_overwrite_meta';

foreach($GLOBALS['TL_DCA']['tl_page']['palettes'] as $name => $palette) {
	if (is_string($palette) && $name != '__selector__') {
		if ($strSub = stristr($palette, '{meta_legend}')) {
			$strSub2 = stristr($strSub, ';', TRUE);
			if ($strSub2 !== FALSE) {
				$GLOBALS['TL_DCA']['tl_page']['palettes'][$name] = str_replace($strSub2, $strSub2 .',page_image,page_image_overwrite_meta,page_images,page_image_url,page_teaser,page_related,rich_text_1,rich_text_2,rich_text_3,rich_text_4', $GLOBALS['TL_DCA']['tl_page']['palettes'][$name]);
			} else {
				$GLOBALS['TL_DCA']['tl_page']['palettes'][$name] = str_replace($strSub, $strSub .',page_image,page_image_overwrite_meta,page_images,page_image_url,page_teaser,page_related,rich_text_1,rich_text_2,rich_text_3,rich_text_4', $GLOBALS['TL_DCA']['tl_page']['palettes'][$name]);
			}
		}
	}
}


/**
 * Subpalettes
 */
$GLOBALS['TL_DCA']['tl_page']['subpalettes']['page_image_overwrite_meta'] = 'page_image_alt,page_image_title';
	
	
/**
 * Fields
 */
$GLOBALS['TL_DCA']['tl_page']['fields']['page_image'] = array
(
	'label'					 => &$GLOBALS['TL_LANG']['tl_page']['page_image'],
	'exclude'				 => true,
	'inputType'				 => 'fileTree',
	'eval'					 => array('filesOnly'=>true, 'extensions'=>Config::get('validImageTypes'), 'fieldType'=>'radio', 'tl_class'=>'clr w50'),
	'sql'					 => "binary(16) NULL"
);

$GLOBALS['TL_DCA']['tl_page']['fields']['page_image_overwrite_meta'] = array
(
	'label'					 => &$GLOBALS['TL_LANG']['tl_page']['page_image_overwrite_meta'],
	'inputType'				=> 'checkbox',
	'eval'					=> array('submitOnChange'=>true, 'tl_class'=>'w50 clr'),
	'sql'					=> "char(1) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_page']['fields']['page_image_alt'] = array
(
	'label'					 => &$GLOBALS['TL_LANG']['tl_page']['page_image_alt'],
	'search'				=> true,
	'inputType'				=> 'text',
	'eval'					=> array('maxlength'=>255, 'tl_class'=>'w50'),
	'sql'					=> "varchar(255) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_page']['fields']['page_image_title'] = array
(
	'label'					 => &$GLOBALS['TL_LANG']['tl_page']['page_image_title'],
	'search'				=> true,
	'inputType'				=> 'text',
	'eval'					=> array('maxlength'=>255, 'tl_class'=>'w50'),
	'sql'					=> "varchar(255) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_page']['fields']['page_images'] = array
(
	'label'					 => &$GLOBALS['TL_LANG']['tl_page']['page_images'],
	'exclude'				 => true,
	'inputType'				 => 'fileTree',
	'eval'					 => array('filesOnly'=>true, 'extensions'=>Config::get('validImageTypes'), 'multiple'=>true, 'fieldType'=>'checkbox', 'tl_class'=>'clr w50'),
	'sql'					 => "blob NULL",
);

$GLOBALS['TL_DCA']['tl_page']['fields']['page_image_url'] = array
(
	'label'					=> &$GLOBALS['TL_LANG']['tl_page']['page_image_url'],
	'search'				=> true,
	'inputType'				=> 'text',
	'eval'					=> array('rgxp'=>'url', 'maxlength'=>255, 'tl_class'=>'clr w50'),
	'sql'					=> "varchar(255) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_page']['fields']['page_teaser'] = array
(
	'label'					 => &$GLOBALS['TL_LANG']['tl_page']['page_teaser'],
	'exclude'				 => true,
	'inputType'				 => 'textarea',
	'search'				 => true,
	'eval'					 => array('style'=>'height:60px', 'decodeEntities'=>true, 'tl_class'=>'clr w50'),
	'sql'					 => "text NULL"
);

$GLOBALS['TL_DCA']['tl_page']['fields']['page_related'] = array
(
	'label'					=> &$GLOBALS['TL_LANG']['tl_page']['page_related'],
	'inputType'				=> 'pageTree',
	'foreignKey'			=> 'tl_page.title',
	'eval'					=> array('multiple'=>true, 'fieldType'=>'checkbox', 'isSortable'=>true, 'tl_class'=>'clr'),
	'sql'					=> "text NULL",
	'relation'				=> array('type'=>'hasMany', 'load'=>'lazy')
);

$GLOBALS['TL_DCA']['tl_page']['fields']['rich_text_1'] = array
(
	'label'					 => &$GLOBALS['TL_LANG']['tl_page']['rich_text_1'],
	'exclude'				 => true,
	'inputType'				 => 'textarea',
	'search'				 => true,
	'eval'					 => array('style'=>'height:60px', 'rte'=>'tinyMCE', 'tl_class'=>'clr long'),
	'explanation'  			 => 'insertTags',
	'sql'					 => "text NULL"
);

$GLOBALS['TL_DCA']['tl_page']['fields']['rich_text_2'] = array
(
	'label'					 => &$GLOBALS['TL_LANG']['tl_page']['rich_text_2'],
	'exclude'				 => true,
	'inputType'				 => 'textarea',
	'search'				 => true,
	'eval'					 => array('style'=>'height:60px', 'rte'=>'tinyMCE', 'tl_class'=>'clr long'),
	'explanation'  			 => 'insertTags',
	'sql'					 => "text NULL"
);

$GLOBALS['TL_DCA']['tl_page']['fields']['rich_text_3'] = array
(
	'label'					 => &$GLOBALS['TL_LANG']['tl_page']['rich_text_3'],
	'exclude'				 => true,
	'inputType'				 => 'textarea',
	'search'				 => true,
	'eval'					 => array('style'=>'height:60px', 'rte'=>'tinyMCE', 'tl_class'=>'clr long'),
	'explanation'  			 => 'insertTags',
	'sql'					 => "text NULL"
);

$GLOBALS['TL_DCA']['tl_page']['fields']['rich_text_4'] = array
(
	'label'					 => &$GLOBALS['TL_LANG']['tl_page']['rich_text_4'],
	'exclude'				 => true,
	'inputType'				 => 'textarea',
	'search'				 => true,
	'eval'					 => array('style'=>'height:60px', 'rte'=>'tinyMCE', 'tl_class'=>'clr long'),
	'explanation'  			 => 'insertTags',
	'sql'					 => "text NULL"
);
