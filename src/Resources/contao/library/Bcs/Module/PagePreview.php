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

class PagePreview extends \Contao\Module
{

	/** Template @var string */
	protected $strTemplate = 'mod_page_preview';
    
	/** @return string */
	public function generate()
	{
        $request = System::getContainer()->get('request_stack')->getCurrentRequest();
		if($request && System::getContainer()->get('contao.routing.scope_matcher')->isBackendRequest($request))
		{
			/** @var \BackendTemplate|object $objTemplate */
			$objTemplate = new BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### ' . mb_strtoupper($GLOBALS['TL_LANG']['FMD']['page_preview'][0]) . ' ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}
		$strBuffer = parent::generate();
		return ($this->Template->items != '') ? $strBuffer : '';
	}


	/** Generate the module */
	protected function compile()
	{
		/** @var \PageModel $objPage */
		global $objPage;

		// Set the trail and level
		if ($this->defineRoot && $this->rootPage > 0)
		{
			$trail = array($this->rootPage);
			$level = 0;
		}
		else
		{
			$trail = $objPage->trail;
			$level = ($this->levelOffset > 0) ? $this->levelOffset : 0;
		}

		$lang = null;
		$host = null;

		// Overwrite the domain and language if the reference page belongs to a differnt root page (see #3765)
		if ($this->defineRoot && $this->rootPage > 0)
		{
			$objRootPage = PageModel::findWithDetails($this->rootPage);

			// Set the language
			if (Config::get('addLanguageToUrl') && $objRootPage->rootLanguage != $objPage->rootLanguage)
			{
				$lang = $objRootPage->rootLanguage;
			}

			// Set the domain
			if ($objRootPage->rootId != $objPage->rootId && $objRootPage->domain != '' && $objRootPage->domain != $objPage->domain)
			{
				$host = $objRootPage->domain;
			}
		}

		$this->Template->request = StringUtil::ampersand(Environment::get('indexFreeRequest'));
		$this->Template->skipId = 'skipNavigation' . $this->id;
		$this->Template->skipNavigation = StringUtil::specialchars($GLOBALS['TL_LANG']['MSC']['skipNavigation']);

		$this->navigationTpl = ($this->navHeaderTpl ? $this->navHeaderTpl : 'nav_pagepreview_header');
		$this->Template->headerItems = $this->renderNavigation($trail[$level], 1, $host, $lang);

		$this->navigationTpl = ($this->navBodyTpl ? $this->navBodyTpl : 'nav_pagepreview_body');
		$this->Template->bodyItems = $this->renderNavigation($trail[$level], 1, $host, $lang);

		$this->Template->items = $this->Template->bodyItems;

	}


	/**
	 * Recursively compile the navigation menu and return it as HTML string
	 *
	 * @param integer $pid
	 * @param integer $level
	 * @param string  $host
	 * @param string  $language
	 *
	 * @return string
	 */
	protected function renderNavigation($pid, $level=1, $host=null, $language=null)
	{
		// Get all active subpages
		
		//$objSubpages = PageModel::findPublishedSubpagesWithoutGuestsByPid($pid, $this->showHidden, $this instanceof ModuleSitemap);
		$objSubpages = self::getPublishedSubpagesByPid($pid, $this->showHidden, $this instanceof ModuleSitemap);

		if ($objSubpages === null)
		{
			return '';
		}

		$items = array();
		$groups = array();

		// Get all groups of the current front end user
		if (System::getContainer()->get('contao.security.token_checker')->hasFrontendUser())
		{
			$this->import(FrontendUser::class, 'User');
			$groups = $this->User->groups;
		}

		// Layout template fallback
		if ($this->navigationTpl == '')
		{
			$this->navigationTpl = 'nav_default';
		}

		if ($level > 1) {
			$this->navigationTpl = $this->navSubitemTpl;
		}

		/** @var \FrontendTemplate|object $objTemplate */
		$objTemplate = new FrontendTemplate($this->navigationTpl);

		$objTemplate->pid = $pid;
		$objTemplate->type = static::class;
		$objTemplate->cssID = $this->cssID; // see #4897
		$objTemplate->level = 'level_' . $level++;
		$objTemplate->module = $this; // see #155

		/** @var PageModel $objPage */
		global $objPage;
		$objContainer = System::getContainer();

		$subCounter = 0;

		// Browse subpages
		foreach ($objSubpages as $objSubpage)
		{
			// Skip hidden sitemap pages
			if ($this instanceof ModuleSitemap && $objSubpage->sitemap == 'map_never')
			{
				continue;
			}

			$subitems = '';
			$_groups = StringUtil::deserialize($objSubpage->groups);

			// Override the domain (see #3765)
			if ($host !== null)
			{
				$objSubpage->domain = $host;
			}

			// Do not show protected pages unless a front end user is logged in
			if (!$objSubpage->protected || $this->showProtected || ($this instanceof ModuleSitemap && $objSubpage->sitemap == 'map_always') || (\is_array($_groups) && \is_array($groups) && \count(array_intersect($_groups, $groups))))
			{

				$subCounter++;
				if ($this->navSubitemLimit > 0 && $subCounter > $this->navSubitemLimit && $level > 2) {
					break;
				}

				// Check whether there will be subpages
				if ($objSubpage->subpages > 0 && (!$this->showLevel || $this->showLevel >= $level || (!$this->hardLimit && ($objPage->id == $objSubpage->id || \in_array($objPage->id, $this->Database->getChildRecords($objSubpage->id, 'tl_page'))))))
				{
					$subitems = $this->renderNavigation($objSubpage->id, $level, $host, $language);
				}

				$href = null;

				// Get href
				switch ($objSubpage->type)
				{
					case 'redirect':
						$href = $objSubpage->url;

						if (strncasecmp($href, 'mailto:', 7) === 0)
						{
							$href = StringUtil::encodeEmail($href);
						}
						break;

					case 'forward':
						if ($objSubpage->jumpTo)
						{
							$objNext = PageModel::findPublishedById($objSubpage->jumpTo);
						}
						else
						{
							$objNext = PageModel::findFirstPublishedRegularByPid($objSubpage->id);
						}

						// Hide the link if the target page is invisible
						if (!$objNext instanceof PageModel || (!$objNext->loadDetails()->isPublic && !BE_USER_LOGGED_IN))
						{
							continue 2;
						}

						try
						{
							$href = $objNext->getFrontendUrl();
						}
						catch (ExceptionInterface $exception)
						{
							System::log('Unable to generate URL for page ID ' . $objSubpage->id . ': ' . $exception->getMessage(), __METHOD__, TL_ERROR);

							continue 2;
						}
						break;

					default:
						try
						{
							$href = $objSubpage->getFrontendUrl();
						}
						catch (ExceptionInterface $exception)
						{
							System::log('Unable to generate URL for page ID ' . $objSubpage->id . ': ' . $exception->getMessage(), __METHOD__, TL_ERROR);

							continue 2;
						}
						break;
				}

				$row = $objSubpage->row();
				$trail = in_array($objSubpage->id, $objPage->trail);

				// Active page
				if (($objPage->id == $objSubpage->id || $objSubpage->type == 'forward' && $objPage->id == $objSubpage->jumpTo) && !$this instanceof \ModuleSitemap && $href == \Environment::get('request'))
				{
					// Mark active forward pages (see #4822)
					$strClass = (($objSubpage->type == 'forward' && $objPage->id == $objSubpage->jumpTo) ? 'forward' . ($trail ? ' trail' : '') : 'active') . (($subitems != '') ? ' submenu' : '') . ($objSubpage->protected ? ' protected' : '') . (($objSubpage->cssClass != '') ? ' ' . $objSubpage->cssClass : '');

					$row['isActive'] = true;
					$row['isTrail'] = false;
				}

				// Regular page
				else
				{
					$strClass = (($subitems != '') ? 'submenu' : '') . ($objSubpage->protected ? ' protected' : '') . ($trail ? ' trail' : '') . (($objSubpage->cssClass != '') ? ' ' . $objSubpage->cssClass : '');

					// Mark pages on the same level (see #2419)
					if ($objSubpage->pid == $objPage->pid)
					{
						$strClass .= ' sibling';
					}

					$row['isActive'] = false;
					$row['isTrail'] = $trail;
				}

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

						$arrPicture['alt'] = ($arrMeta['page_image_title'] ?? '');
						$arrPicture['title'] = ($arrMeta['page_image_title'] ?? '');
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

				$row['subitems'] = $subitems;
				$row['class'] = trim($strClass);
				$row['title'] = specialchars($objSubpage->title, true);
				$row['pageTitle'] = specialchars($objSubpage->pageTitle, true);
				$row['link'] = $objSubpage->title;
				$row['href'] = $href;
				$row['nofollow'] = (strncmp($objSubpage->robots, 'noindex,nofollow', 16) === 0);
				$row['target'] = '';
				$row['description'] = str_replace(array("\n", "\r"), array(' ' , ''), $objSubpage->description);

				// Override the link target
				if ($objSubpage->type == 'redirect' && $objSubpage->target)
				{
					$row['target'] = ($objPage->outputFormat == 'xhtml') ? ' onclick="return !window.open(this.href)"' : ' target="_blank"';
				}
				
				$items[] = $row;
			}
		}

		// Add classes first and last
		if (!empty($items))
		{
			$last = \count($items) - 1;

			$items[0]['class'] = trim($items[0]['class'] . ' first');
			$items[$last]['class'] = trim($items[$last]['class'] . ' last');
		}

		$objTemplate->items = $items;

		return !empty($items) ? $objTemplate->parse() : '';
	}

}
