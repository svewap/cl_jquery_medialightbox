<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2010 Sven Wappler <typo3(at)wapplersystems.de>
 *  All rights reserved
 *
 *  This script is part of the Typo3 project. The Typo3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/
/**
 *
 *
 * @author Sven Wappler
 */
class JqueryMedialightbox extends \TYPO3\CMS\Frontend\ContentObject\FlowPlayerContentObject {

	var $cObj;

	var $extKey = "cl_jquery_medialightbox";

	var $conf;

	public function __construct() {

	}

	function customMediaRender($renderType, $conf, &$parent) {

		$this -> cObj = $parent -> cObj;
		$this -> conf = $conf;
		#print_r($conf);

		$pageRenderer = $GLOBALS['TSFE'] -> getPageRenderer();
		$prefix = '';
		if ($GLOBALS['TSFE'] -> baseUrl) {
			$prefix = $GLOBALS['TSFE'] -> baseUrl;
		}
		if ($GLOBALS['TSFE'] -> absRefPrefix) {
			$prefix = $GLOBALS['TSFE'] -> absRefPrefix;
		}

		$typeConf = array();



		// flexforms

		$tx_cljquerymedialightbox_previewimage = \TYPO3\CMS\Core\Utility\GeneralUtility::xml2array($parent -> data['tx_cljquerymedialightbox_previewimage']);

		/*
		 $filename = $this->pi_getFFvalue($tx_cljquerymedialightbox_previewimage, 'previewimageFile', 'sDEF');
		 $maxwidth = $this->pi_getFFvalue($tx_cljquerymedialightbox_previewimage, 'previewimageMaxWidth', 'sDEF');
		 $maxheight = $this->pi_getFFvalue($tx_cljquerymedialightbox_previewimage, 'previewimageMaxHeight', 'sDEF');

		 if ($filename)
		 $conf['video.']['previewimage.']['filename'] = $this -> pi_getFFvalue($tx_cljquerymedialightbox_previewimage, 'previewimageFile', 'sDEF');
		 if ($maxwidth)
		 $conf['video.']['previewimage.']['maxwidth'] = $this -> pi_getFFvalue($tx_cljquerymedialightbox_previewimage, 'previewimageMaxWidth', 'sDEF');
		 if ($maxheight)
		 $conf['video.']['previewimage.']['maxheight'] = $this -> pi_getFFvalue($tx_cljquerymedialightbox_previewimage, 'previewimageMaxHeight', 'sDEF');

		 if ($conf['video.']['previewimage.']['use']) {
		 $conf['flashvars.']['autoPlay'] = true;
		 }*/

		$width = isset($conf['width.']) ? $this -> cObj -> stdWrap($conf['width'], $conf['width.']) : $conf['width'];
		if (!$width) {
			$width = $conf[$type . '.']['defaultWidth'];
		}
		$height = isset($conf['height.']) ? $this -> cObj -> stdWrap($conf['height'], $conf['height.']) : $conf['height'];
		if (!$height) {
			$height = $conf[$type . '.']['defaultHeight'];
		}

		$replaceElementIdString = uniqid('mmswf');
		$GLOBALS['TSFE'] -> register['MMSWFID'] = $replaceElementIdString;

		$videoSources = '';
		if (is_array($conf['sources'])) {
			foreach ($conf['sources'] as $source) {
				$fileinfo = \TYPO3\CMS\Core\Utility\GeneralUtility::split_fileref($source);
				$mimeType = $this -> mimeTypes[$fileinfo['fileext']]['video'];
				$videoSources .= '<source src="' . $source . '"' . ($mimeType ? ' type="' . $mimeType . '"' : '') . ' />' . LF;
			}
		}

		if (is_array($conf['predefined'])) {
			foreach ($conf['predefined'] as $source) {

			}
		}


		$splashImgUrl = $this->getSplashImageUrl($conf['mimeConf.']['swfobject.']);
		$poster = "Video";
		if ($splashImgUrl) $poster = '<img src="'.$splashImgUrl.'" />';

		$previewImage = LF . '<a class="medialightbox" href="#' . $replaceElementIdString . '_video_js">'.$poster.'</a>';

		$contentVid = LF . '<video id="' . $replaceElementIdString . '_video_js" controls preload="auto" class="video-js vjs-default-skin" controls data-setup=\'{"height":"auto", "width":"auto"}\'>' . LF . $videoSources . LF . '</video>' . LF;

		$content = $previewImage . LF . '<div class="videocontainer">' . $contentVid . '</div>';

		return $content;
	}

	function customMediaParams(&$params, $conf) {

		$params['items'][] = array("Vorschaubild", "previewimage", "");
		$params['items'][] = array("Breite des Vorschaubildes", "previewimage_mw", "");
		$params['items'][] = array("Höhe des Vorschaubildes", "previewimage_mh", "");

	}

	function customMediaRenderTypes(&$params, $conf) {
		$params['items'][] = array("Medialightbox", "medialightbox", "");
		$params['config']['items'][] = array("Medialightbox", "medialightbox", "");
	}

	function getSplashImageUrl($conf) {

		//print_r($conf);
		$conf['splashImageMode'] = 'resize2max';

		if ($conf['splashImageMode']) {
			switch ($conf['splashImageMode']) {
				case 'resize2max' :
					$suf = 'm';
					break;
				case 'crop' :
					$suf = 'c';
					break;
				case 'resize' :
					$suf = '';
					break;
			}
		}

		$file = $conf['video.']['previewimage.']['defaultsrc'];
		$width =  $conf['video.']['previewimage.']['maxwidth'];
		$height = $conf['video.']['previewimage.']['maxheight'];


		if (isset($this->conf['predefined']['previewimage'])) $file = $this->conf['predefined']['previewimage'];
		if (isset($this->conf['predefined']['previewimage_mw'])) $width = $this->conf['predefined']['previewimage_mw'];
		if (isset($this->conf['predefined']['previewimage_mh'])) $height = $this->conf['predefined']['previewimage_mh'];


		$img_overlay_src = $GLOBALS['TSFE'] -> tmpl -> getFileName($conf['video.']['previewimage.']['buttonsrc']);
		$img_overlay_info = @getImageSize($img_overlay_src);

		$lConf = array('file' => $file, 'file.' => array('width' => $width . $suf, 'height' => $height));

		$videoimage = $this->cObj -> IMG_RESOURCE($lConf);
		$imginfo = @getImageSize($videoimage);

		$gifBuilder = array('XY' => $imginfo[0] . ',' . $imginfo[1], 'format' => 'jpg', '2' => 'IMAGE', '2.' => array('file' => $videoimage, 'transparentBackground' => 1, 'format' => 'jpg', ), );
		if ($img_overlay_src) {
			$gifBuilder['10'] = 'IMAGE';
			$gifBuilder['10.'] = array('file' => $img_overlay_src, 'offset' => ($imginfo[0] / 2 - $img_overlay_info[0] / 2) . ',' . ($imginfo[1] / 2 - $img_overlay_info[1] / 2), 'transparentBackground' => 1, 'format' => 'png', );
		}
		$imgArr = $this->cObj -> getImgResource('GIFBUILDER', $gifBuilder);

		return $imgArr[3];
	}

}
