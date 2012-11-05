<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');
/**
 * Add palette to tl_content
 */
foreach($GLOBALS['TL_DCA']['tl_content']['palettes'] as $pallete_name => $pallete_value) {
    if ($pallete_name != '__selector__') $GLOBALS['TL_DCA']['tl_content']['palettes'][$pallete_name].=';{el_references_legend},el_references_list';
}

/**
 * Add fields to tl_content
 */
$GLOBALS['TL_DCA']['tl_content']['fields']['el_references_list'] = array
(
    'label' => '',
    'value' => '',
    'inputType' => 'elementReferences'
);