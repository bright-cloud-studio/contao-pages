<?php
 
/**
 * @copyright	Bright Cloud Studio
 * @author		Bright Cloud Studio
 * @package	Contao Pages
 * @license		LGPL-3.0+
 * @see			https://github.com/bright-cloud-studio/contao-pages
 **/

namespace Bcs\Backend;

use Contao\Backend as ContaoBackend;


class ContaoPages extends ContaoBackend
{
	
	public function getPreviewImageSizes($dc)
	{
		
		$objImageSize = $this->Database->prepare("SELECT id, name, width, height, resizeMode FROM tl_image_size WHERE pid=? ORDER BY name")->execute($dc->activeRecord->pid);

		if ($objImageSize->numRows < 1)
		{
			return array();
		}

		$arrOptions = array();

		while ($objImageSize->next())
		{
			$arrOptions[$objImageSize->id] = $objImageSize->name ." [" .$objImageSize->width ."x" .$objImageSize->height ." " .$objImageSize->resizeMode ."]";
		}

		return $arrOptions;
	}	

}
