<?php
if (!defined ('TYPO3_MODE')) die ('Access denied.');


$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/hooks/class.tx_cms_mediaitems.php']['customMediaRender'][] = 'EXT:cl_jquery_medialightbox/Classes/JqueryMedialightbox.php:JqueryMedialightbox';
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/hooks/class.tx_cms_mediaitems.php']['customMediaRenderTypes'][] = 'EXT:cl_jquery_medialightbox/Classes/JqueryMedialightbox.php:JqueryMedialightbox';
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/hooks/class.tx_cms_mediaitems.php']['customMediaParams'][] = 'EXT:cl_jquery_medialightbox/Classes/JqueryMedialightbox.php:JqueryMedialightbox';


#$TYPO3_CONF_VARS['FE']['XCLASS']['tslib/content/class.tslib_content_media.php'] = t3lib_extMgm::extPath($_EXTKEY).'class.ux_tslib_content_media.php';

?>