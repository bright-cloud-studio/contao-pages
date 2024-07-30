<?php
 
/**
 * @copyright	Bright Cloud Studio
 * @author		Bright Cloud Studio
 * @package	Contao Pages
 * @license		LGPL-3.0+
 * @see			https://github.com/bright-cloud-studio/contao-pages
 **/

use Contao\Controller;


/** Palettes */
$GLOBALS['TL_DCA']['tl_module']['palettes']['pagepreview']	= '{title_legend},name,headline,type;{image_size_legend},preview_image_size;{nav_legend},levelOffset,showLevel,hardLimit,showProtected,showHidden;{reference_legend:hide},defineRoot;{template_legend:hide},customTpl,navHeaderTpl,navBodyTpl,navSubitemTpl,navSubitemLimit,bodyNumberOfActive;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space';
$GLOBALS['TL_DCA']['tl_module']['palettes']['related_pages']	= '{title_legend},name,headline,type;{image_size_legend},preview_image_size;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space';
	
	
/** Fields */
$GLOBALS['TL_DCA']['tl_module']['fields']['preview_image_size'] = array
(
	'inputType'               => 'select',
	'foreignKey'              => 'tl_image_size.name',
	'options_callback'        => array('Bcs\Backend\ContaoPages', 'getPreviewImageSizes'),
	'eval'                    => array('mandatory'=>true, 'tl_class'=>'w50'),
	'sql'                     => "int(10) unsigned NOT NULL default 0",
	'relation'                => array('type'=>'hasOne', 'load'=>'lazy')
);

$GLOBALS['TL_DCA']['tl_module']['fields']['navHeaderTpl'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['navHeaderTpl'],
	'exclude'                 => true,
	'inputType'               => 'select',
	'options_callback' => static function ()
	{
		return Controller::getTemplateGroup('nav_');
	},
	'eval'                    => array('tl_class'=>'w50'),
	'sql'                     => "varchar(64) NOT NULL default ''",
	'default'                 => 'nav_pagepreview_header'
);

$GLOBALS['TL_DCA']['tl_module']['fields']['navBodyTpl'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['navBodyTpl'],
	'exclude'                 => true,
	'inputType'               => 'select',
	'options_callback' => static function ()
	{
		return Controller::getTemplateGroup('nav_');
	},
	'eval'                    => array('tl_class'=>'w50'),
	'sql'                     => "varchar(64) NOT NULL default ''",
	'default'                 => 'nav_pagepreview_body'
);

$GLOBALS['TL_DCA']['tl_module']['fields']['navSubitemTpl'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['navSubitemTpl'],
	'exclude'                 => true,
	'inputType'               => 'select',
	'options_callback' => static function ()
	{
		return Controller::getTemplateGroup('nav_');
	},
	'eval'                    => array('tl_class'=>'w50'),
	'sql'                     => "varchar(64) NOT NULL default ''",
	'default'                 => 'nav_default'
);

$GLOBALS['TL_DCA']['tl_module']['fields']['navSubitemLimit'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['navSubitemLimit'],
	'default'                 => 3,
	'exclude'                 => true,
	'inputType'               => 'text',
	'eval'                    => array('mandatory'=>true, 'rgxp'=>'natural', 'tl_class'=>'w50'),
	'sql'                     => "smallint(5) unsigned NOT NULL default '0'"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['bodyNumberOfActive'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['bodyNumberOfActive'],
	'default'                 => 4,
	'exclude'                 => true,
	'inputType'               => 'text',
	'eval'                    => array('mandatory'=>true, 'rgxp'=>'natural', 'tl_class'=>'w50'),
	'sql'                     => "smallint(5) unsigned NOT NULL default '0'"
);
