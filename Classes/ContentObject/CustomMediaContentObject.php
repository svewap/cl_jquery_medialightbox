<?php

class CustomMediaContentObject extends \TYPO3\CMS\Frontend\ContentObject\MediaContentObject {

    public function render($conf = array()) {



    	$flexParams = isset($conf['flexParams.'])
    	? $this->cObj->stdWrap($conf['flexParams'], $conf['flexParams.'])
    	: $conf['flexParams'];

    	if (substr($flexParams, 0, 1) === '<') {
    		// It is a content element rather a TS object
    		$flexParams = t3lib_div::xml2array($flexParams, 'T3');
    		foreach($flexParams['data'] as $sheetData) {
    			$this->cObj->readFlexformIntoConf($sheetData['lDEF'], $conf['parameter.'], TRUE);
    		}
    	}

        $renderType = $this->doFlexFormOverlay($conf, 'renderType');


        if ($renderType != "medialightbox") return parent::render($conf);



        /*
        $conf[$conf['type'] . '.'] = array_merge($conf['mimeConf.']['swfobject.'][$conf['type'] . '.'], $typeConf);

        $conf = array_merge($conf['mimeConf.']['swfobject.'], $conf);
        unset($conf['mimeConf.']);
        $conf['flashvars.'] = array_merge((array) $conf['flashvars.'], $conf['predefined']);
*/

        // flexforms

        $tx_cljquerymedialightbox_previewimage = t3lib_div::xml2array($this->cObj->data['tx_cljquerymedialightbox_previewimage']);



        $filename = $tx_cljquerymedialightbox_previewimage['data']['sDEF']['lDEF']['previewimageFile']['vDEF'];
        $maxwidth = $tx_cljquerymedialightbox_previewimage['data']['sDEF']['lDEF']['previewimageMaxWidth']['vDEF'];
        $maxheight = $tx_cljquerymedialightbox_previewimage['data']['sDEF']['lDEF']['previewimageMaxHeight']['vDEF'];



        if ($filename) $conf['mimeConf.']['swfobject.']['video.']['previewimage.']['filename'] = $tx_cljquerymedialightbox_previewimage['data']['sDEF']['lDEF']['previewimageFile']['vDEF'];
        if ($maxwidth) $conf['mimeConf.']['swfobject.']['video.']['previewimage.']['maxwidth'] = $tx_cljquerymedialightbox_previewimage['data']['sDEF']['lDEF']['previewimageMaxWidth']['vDEF'];
        if ($maxheight) $conf['mimeConf.']['swfobject.']['video.']['previewimage.']['maxheight'] = $tx_cljquerymedialightbox_previewimage['data']['sDEF']['lDEF']['previewimageMaxHeight']['vDEF'];


        if ($conf['mimeConf.']['swfobject.']['video.']['previewimage.']['use']) {
            $conf['mimeConf.']['swfobject.']['flashvars.']['autoPlay'] = true;
        }

        $content = '';
        $flashvars = $params = $attributes = '';
        $prefix = '';
        if ($GLOBALS['TSFE']->baseUrl) {
            $prefix = $GLOBALS['TSFE']->baseUrl;
        }
        if ($GLOBALS['TSFE']->absRefPrefix) {
            $prefix = $GLOBALS['TSFE']->absRefPrefix;
        };

        $typeConf = $conf[$conf['type'] . '.'];



        //add SWFobject js-file
        $GLOBALS['TSFE']->getPageRenderer()->addJsFile(TYPO3_mainDir . 'contrib/flashmedia/swfobject/swfobject.js');

        $player = $this->cObj->stdWrap($conf[$conf['type'] . '.']['player'], $conf[$conf['type'] . '.']['player.']);
        $installUrl = $conf['installUrl'] ? $conf['installUrl'] : $prefix . TYPO3_mainDir . 'contrib/flashmedia/swfobject/expressInstall.swf';
        $filename = $this->cObj->stdWrap($conf['file'], $conf['file.']);
        if ($filename && $conf['forcePlayer']) {
            if (strpos($filename, '://') !== FALSE) {
                $conf['flashvars.']['file'] = $filename;
            } else {
                if ($prefix) {
                    $conf['flashvars.']['file'] = $prefix . $filename;
                } else {
                    $conf['flashvars.']['file'] = str_repeat('../', substr_count($player, '/')) . $filename;
                }

            }
        } else {
            $player = $filename;
        }
        // Write calculated values in conf for the hook
        $conf['player'] = $player;
        $conf['installUrl'] = $installUrl;
        $conf['filename'] = $filename;
        $conf['prefix'] = $prefix;

        // merge with default parameters
        $conf['flashvars.'] = array_merge((array) $typeConf['default.']['flashvars.'], (array) $conf['flashvars.']);
        $conf['params.'] = array_merge((array) $typeConf['default.']['params.'], (array) $conf['params.']);
        $conf['attributes.'] = array_merge((array) $typeConf['default.']['attributes.'], (array) $conf['attributes.']);
        $conf['embedParams'] = 'flashvars, params, attributes';



        // Hook for manipulating the conf array, it's needed for some players like flowplayer
        if (is_array($GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/hooks/class.tx_cms_mediaitems.php']['swfParamTransform'])) {
            foreach($GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/hooks/class.tx_cms_mediaitems.php']['swfParamTransform'] as $classRef) {
                t3lib_div::callUserFunction($classRef, $conf, $this);
            }
        }
        if (is_array($conf['flashvars.'])) {
            t3lib_div::remapArrayKeys($conf['flashvars.'], $typeConf['mapping.']['flashvars.']);
        }
        $flashvars = 'var flashvars = ' . (count($conf['flashvars.']) ? json_encode($conf['flashvars.']) : '{}') . ';';

        if (is_array($conf['params.'])) {
            t3lib_div::remapArrayKeys($conf['params.'], $typeConf['mapping.']['params.']);
        }
        $params = 'var params = ' . (count($conf['params.']) ? json_encode($conf['params.']) : '{}') . ';';

        if (is_array($conf['attributes.'])) {
            t3lib_div::remapArrayKeys($conf['attributes.'], $typeConf['attributes.']['params.']);
        }
        $attributes = 'var attributes = ' . (count($conf['attributes.']) ? json_encode($conf['attributes.']) : '{}') . ';';


        $conf['width'] = $flexParams['data']['sGeneral']['lDEF']['mmWidth']['vDEF'];
        $conf['height'] = $flexParams['data']['sGeneral']['lDEF']['mmHeight']['vDEF'];


        $splashImgUrl = $this->getSplashImageUrl($conf['mimeConf.']['swfobject.']);

        $filePath = $conf['parameter.']['mmFile'];
        if (strpos($filePath, "http://") === FALSE) $filePath = $prefix.$filePath;

        $content = '<div class="videolightbox"><a href="'.$GLOBALS['TSFE']->absRefPrefix.'typo3/contrib/flashmedia/flvplayer.swf?flashvars=autoPlay=true&width='.$conf['width'].'&height='.$conf['height'].'&file='.$filePath.'" class="lightbox"><img src="'.$splashImgUrl.'" /></a></div>';

        return $content;
    }



    function getSplashImageUrl($conf) {

        $local_cObj = t3lib_div::makeInstance('tslib_cObj'); // Local cObj.

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
        if ($conf['video.']['previewimage.']['filename']) $file = 'uploads/tx_cljquerymedialightbox/'.$conf['video.']['previewimage.']['filename'];


        $img_overlay_src = $GLOBALS['TSFE']->tmpl->getFileName($conf['video.']['previewimage.']['buttonsrc']);
        $img_overlay_info = @getImageSize($img_overlay_src);

        $lConf = array(
    		'file' => $file,
			  'file.' => array(
				'width' => $conf['video.']['previewimage.']['maxwidth'].$suf,
				'height' => $conf['video.']['previewimage.']['maxheight']
        )
        );

        $videoimage = $local_cObj->IMG_RESOURCE($lConf);
        $imginfo = @getImageSize($videoimage);


        $gifBuilder = array(
            'XY' => $imginfo[0].','.$imginfo[1],
        	  'format' => 'jpg',
            '2' => 'IMAGE',
    		    '2.' => array(
       			    'file' => $videoimage,
        		    'transparentBackground' => 1,
                'format' => 'jpg',
            ),
        );
        if ($img_overlay_src) {
            $gifBuilder['10'] = 'IMAGE';
            $gifBuilder['10.'] = array(
    			      'file' => $img_overlay_src,
                'offset' => ($imginfo[0]/2-$img_overlay_info[0]/2).','.($imginfo[1]/2-$img_overlay_info[1]/2),
                'transparentBackground' => 1,
                'format' => 'png',
            );
        }
        $imgArr = $local_cObj->getImgResource('GIFBUILDER', $gifBuilder);

        return $imgArr[3];
    }


}