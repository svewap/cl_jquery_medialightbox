<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

t3lib_extMgm::addStaticFile($_EXTKEY,'Configuration/TypoScript/', 'jQuery MediaLightbox');


// Video


$tempColumns = array (
    'tx_cljquerymedialightbox_previewimage' => array (
        'exclude' => 0,
        'label' => 'LLL:EXT:cl_jquery_medialightbox/locallang_db.xml:tt_content.tx_cljquerymedialightbox_previewimage',
        'config' => array (
            'type' => 'flex',
            'ds' => array (
                'default' => 'FILE:EXT:cl_jquery_medialightbox/flexform_tt_content_tx_cljquerymedialightbox_previewimage.xml',
            ),
        )
    ),
);


t3lib_extMgm::addTCAcolumns('tt_content',$tempColumns,1);
t3lib_extMgm::addToAllTCAtypes('tt_content','tx_cljquerymedialightbox_previewimage;;;;1-1-1', 'media', 'after:bodytext');



?>