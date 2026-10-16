<?php
function mm_images($moduleid, $title='', $templates='') {
	global $modx, $content, $mm_fields;
	$e = &$modx->Event;
	$templates = explode(",", $templates);
	if (in_array($content['template'], $templates)) {
		$title = empty($title) ? "Управление изображениями" : $title;
		if (isset($content['id']))
			$iframecontent = '<iframe id="mm_images" src="'.$modx->config['site_url'].'manager/index.php?a=112&id='.$moduleid.'&category='.$content['id'].'&get=category" style="width:100%;height:800px;" scrolling="auto" frameborder="0"></iframe>';
		else
			$iframecontent = '<p class="warning">Прежде чем добавлять изображения сохраните </p>';
		mm_createTab($title, 'wgt_images', '', '', $iframecontent);
		$e->output($output . "\n");	
	}
}