<?php
 
/**
 * @copyright	Bright Cloud Studio
 * @author		Bright Cloud Studio
 * @package	Contao Pages
 * @license		LGPL-3.0+
 * @see			https://github.com/bright-cloud-studio/contao-pages
 **/
 
 
 
namespace Bcs\Module;

use Contao\BackendTemplate;
use Contao\Config;
use Contao\Environment;
use Contao\FrontendTemplate;
use Contao\ImageSizeModel;
use Contao\PageModel;
use Contao\FilesModel;
use Contao\StringUtil;
use Contao\System;
use Contao\FrontendUser;


class RelatedPages extends \Contao\Module
{
 
	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_related_pages';


	/**
	 * Do not display the module if there are no menu items
	 *
	 * @return string
	 */
	public function generate()
	{	
        $request = System::getContainer()->get('request_stack')->getCurrentRequest();
		if($request && System::getContainer()->get('contao.routing.scope_matcher')->isBackendRequest($request))
		{
			/** @var \BackendTemplate|object $objTemplate */
			$objTemplate = new BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### ' . mb_strtoupper($GLOBALS['TL_LANG']['FMD']['related_pages'][0]) . ' ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;
	
			return $objTemplate->parse();
		}	
		$strBuffer = parent::generate();
		return $strBuffer;
	}


	/**
	 * Generate the module
	 */
	protected function compile()
	{		
		/** @var \PageModel $objPage */
		global $objPage;
		$objContainer = System::getContainer();

		$arrRelated = StringUtil::deserialize($objPage->page_related, true);
	
		$arrPages = array();
		foreach ($arrRelated as $intPage) {
			$objRelatedPage = PageModel::findByPk($intPage);
			if ($objRelatedPage) {
				$row = $objRelatedPage->row();
				$row['url'] = $objRelatedPage->getFrontendUrl();

				if ($row['page_image']) {
					$strPhoto = '';
					$uuid = StringUtil::binToUuid($row['page_image']);
					$objFile = FilesModel::findByUuid($uuid);
					if ($objFile) {
						$strPhoto = $objFile->path;

						$arrMeta = StringUtil::deserialize($objFile->meta);

						$staticUrl = $objContainer->get('contao.assets.files_context')->getStaticUrl();
						$objPicture = $objContainer->get('contao.image.picture_factory')->create(
							$objContainer->getParameter('kernel.project_dir') . '/' . $objFile->path, 
							($this->preview_image_size ? $this->preview_image_size : null)
						);

						$arrPicture = array
						(
							'img' => $objPicture->getImg($objContainer->getParameter('kernel.project_dir'), $staticUrl),
							'sources' => $objPicture->getSources($objContainer->getParameter('kernel.project_dir'), $staticUrl)
						);

						$arrPicture['alt'] = $arrMeta['page_image_title'];
						$arrPicture['title'] = $arrMeta['page_image_title'];
						if ($row['page_image_overwrite_meta']) {
							if ($row['page_image_alt']) {
								$arrPicture['alt'] = $row['page_image_alt'];
							}
							if ($row['page_image_title']) {
								$arrPicture['title'] = $row['page_image_title'];
							}
						}
						
						$arrPicture['class'] = 'page_image';
						$objPictureTemplate = new FrontendTemplate('picture_default');
						$objPictureTemplate->setData($arrPicture);
						$row['page_image'] = $objPictureTemplate->parse();
					}
				}
				
				if ($row['page_images']) {
					$arrFormatted = array();
					$arrImages = StringUtil::deserialize($row['page_images']);
					foreach ($arrImages as $strImage) {
						$strPhoto = '';
						$uuid = StringUtil::binToUuid($strImage);
						$objFile = FilesModel::findByUuid($uuid);
						if ($objFile) {
							$strPhoto = $objFile->path;

							$arrMeta = StringUtil::deserialize($objFile->meta);

							$staticUrl = $objContainer->get('contao.assets.files_context')->getStaticUrl();
							$objPicture = $objContainer->get('contao.image.picture_factory')->create(
								$objContainer->getParameter('kernel.project_dir') . '/' . $objFile->path, 
								($this->preview_image_size ? $this->preview_image_size : null)
							);

							$arrPicture = array
							(
								'img' => $objPicture->getImg($objContainer->getParameter('kernel.project_dir'), $staticUrl),
								'sources' => $objPicture->getSources($objContainer->getParameter('kernel.project_dir'), $staticUrl)
							);

							$arrPicture['alt'] = $arrMeta['page_image_title'];
							$arrPicture['title'] = $arrMeta['page_image_title'];
							if ($row['page_image_overwrite_meta']) {
								if ($row['page_image_alt']) {
									$arrPicture['alt'] = $row['page_image_alt'];
								}
								if ($row['page_image_title']) {
									$arrPicture['title'] = $row['page_image_title'];
								}
							}
							
							$arrPicture['class'] = 'page_image';
							$objPictureTemplate = new FrontendTemplate('picture_default');
							$objPictureTemplate->setData($arrPicture);
							$arrFormatted[] = $objPictureTemplate->parse();
						}
					}
					$row['page_images'] = $arrFormatted;
				}
				
				$arrPages[] = $row;
			}
		}

		$this->Template->request = StringUtil::ampersand(Environment::get('indexFreeRequest'));
		$this->Template->pages = $arrPages;
	}

}
