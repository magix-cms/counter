<?php
function smarty_function_counter_data($params, $smarty){
	$modelTemplate = $smarty->tpl_vars['modelTemplate']->value instanceof frontend_model_template ? $smarty->tpl_vars['modelTemplate']->value : new frontend_model_template();
    $collection = new plugins_counter_public($modelTemplate);
	$smarty->assign('counters',$collection->getCounters());;
}