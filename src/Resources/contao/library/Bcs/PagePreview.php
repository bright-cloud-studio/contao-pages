<?php
 
/**
 * @copyright	Bright Cloud Studio
 * @author		Bright Cloud Studio
 * @package	Contao Pages
 * @license		LGPL-3.0+
 * @see			https://github.com/bright-cloud-studio/contao-pages
 **/
 
namespace Bcs;

use Contao\FrontendTemplate;
use Contao\PageModel;
use Contao\System;
use Contao\StringUtil;

class PagePreview 
{

    public function replaceInsertTags($insertTag)
    {
        $tokens = trimsplit('::', $insertTag);

		
        if ($tokens[0] == 'page_preview' || $tokens[0] == 'contao_pages')
		{
			
			if (stristr($tokens[1], ":") !== FALSE) {
				list($tokens[1], $strLookup) = explode(":", $tokens[1]);
			}
			
			$objContainer = System::getContainer();

			switch($tokens[1])
			{
				case "page_teaser":
				case "teaser":
					if (!isset($tokens[2])) {
						global $objPage;
					} else {
						$objPage = PageModel::findPublishedByIdOrAlias($tokens[2]);
					}
					return $objPage->page_teaser;
				break;

				case "page_image":
				case "image":
					if (!isset($tokens[2]) || !$tokens[2]) {
						global $objPage;
					} else {
						$objPage = PageModel::findPublishedByIdOrAlias($tokens[2]);
					}
					if ($objPage->page_image) {
						$strPhoto = '';
						$uuid = StringUtil::binToUuid($objPage->page_image);
						$objFile = FilesModel::findByUuid($uuid);
						if ($objFile) {
							$strPhoto = $objFile->path;

							$arrMeta = StringUtil::deserialize($objFile->meta);

							$staticUrl = $objContainer->get('contao.assets.files_context')->getStaticUrl();
							$objPicture = $objContainer->get('contao.image.picture_factory')->create(
								$objContainer->getParameter('kernel.project_dir') . '/' . $objFile->path, 
								($tokens[3] ? $tokens[3] : null)
							);

							$arrPicture = array
							(
								'img' => $objPicture->getImg($objContainer->getParameter('kernel.project_dir'), $staticUrl),
								'sources' => $objPicture->getSources($objContainer->getParameter('kernel.project_dir'), $staticUrl)
							);

							$arrPicture['alt'] = $arrMeta['page_image_title'];
							$arrPicture['title'] = $arrMeta['page_image_title'];
							if ($objPage->page_image_overwrite_meta) {
								if ($objPage->page_image_alt) {
									$arrPicture['alt'] = $objPage->page_image_alt;
								}
								if ($objPage->page_image_title) {
									$arrPicture['title'] = $objPage->page_image_title;
								}
							}
							
							$arrPicture['class'] = 'page_image';
							$objPictureTemplate = new FrontendTemplate('picture_default');
							$objPictureTemplate->setData($arrPicture);
							return $objPictureTemplate->parse();
						}
					}
					return FALSE;
			
				break;


				case "page_images":
				case "images":
					if (!isset($tokens[2])) {
						global $objPage;
					} else {
						$objPage = PageModel::findPublishedByIdOrAlias($tokens[2]);
					}
					$arrImages = StringUtil::deserialize($objPage->page_images);
					if ($arrImages[$strLookup]) {
						$strPhoto = '';
						$uuid = StringUtil::binToUuid($arrImages[$strLookup]);
						$objFile = FilesModel::findByUuid($uuid);
						if ($objFile) {
							$strPhoto = $objFile->path;

							$arrMeta = StringUtil::deserialize($objFile->meta);

							$staticUrl = $objContainer->get('contao.assets.files_context')->getStaticUrl();
							$objPicture = $objContainer->get('contao.image.picture_factory')->create(
								$objContainer->getParameter('kernel.project_dir') . '/' . $objFile->path, 
								($tokens[3] ? $tokens[3] : null)
							);

							$arrPicture = array
							(
								'img' => $objPicture->getImg($objContainer->getParameter('kernel.project_dir'), $staticUrl),
								'sources' => $objPicture->getSources($objContainer->getParameter('kernel.project_dir'), $staticUrl)
							);

							$arrPicture['alt'] = $arrMeta['page_image_title'];
							$arrPicture['title'] = $arrMeta['page_image_title'];
							if ($objPage->page_image_overwrite_meta) {
								if ($objPage->page_image_alt) {
									$arrPicture['alt'] = $objPage->page_image_alt;
								}
								if ($objPage->page_image_title) {
									$arrPicture['title'] = $objPage->page_image_title;
								}
							}
							
							$arrPicture['class'] = 'page_image';
							$objPictureTemplate = new FrontendTemplate('picture_default');
							$objPictureTemplate->setData($arrPicture);
							return $objPictureTemplate->parse();
						}
					}
					return FALSE;
				break;

				case "page_image_url":
				case "image_url":
					if (!isset($tokens[2])) {
						global $objPage;
					} else {
						$objPage = PageModel::findPublishedByIdOrAlias($tokens[2]);
					}
					if ($objPage->page_image_url) {
						return $objPage->page_image_url;
					} else {
						return FALSE;
					}
				break;

				case "rich_text":
				case "rich_text_1":
					if (!isset($tokens[2])) {
						global $objPage;
					} else {
						$objPage = PageModel::findPublishedByIdOrAlias($tokens[2]);
					}
					if ($objPage->rich_text_1) {
						return $objPage->rich_text_1;
					} else {
						return FALSE;
					}
				break;
				
				case "rich_text_2":
					if (!isset($tokens[2])) {
						global $objPage;
					} else {
						$objPage = PageModel::findPublishedByIdOrAlias($tokens[2]);
					}
					if ($objPage->rich_text_2) {
						return $objPage->rich_text_2;
					} else {
						return FALSE;
					}
				break;
				
				case "rich_text_3":
					if (!isset($tokens[2])) {
						global $objPage;
					} else {
						$objPage = PageModel::findPublishedByIdOrAlias($tokens[2]);
					}
					if ($objPage->rich_text_3) {
						return $objPage->rich_text_3;
					} else {
						return FALSE;
					}
				break;
				
				case "rich_text_4":
					if (!isset($tokens[2])) {
						global $objPage;
					} else {
						$objPage = PageModel::findPublishedByIdOrAlias($tokens[2]);
					}
					if ($objPage->rich_text_4) {
						return $objPage->rich_text_4;
					} else {
						return FALSE;
					}
				break;
				
			}
        }

        return false;
    }	

}
