<?php
$settings['display'] = 'vertical';
$settings['fields'] = array(
    'fact' => array(
        'caption' => 'Факт',
        'type' => 'textarea'
    )
);
$settings['templates'] = array(
    'outerTpl' => '[+wrapper+]',
    'rowTpl' => '<li>[+text+], [+image+], [+thumb+], [+textarea+], [+date+], [+dropdown+], [+listbox+], [+listbox-multiple+], [+checkbox+], [+option+]</li>'
);  
$settings['configuration'] = array(
    'enablePaste' => true,
    'enableClear' => true,
    'csvseparator' => ','
);
