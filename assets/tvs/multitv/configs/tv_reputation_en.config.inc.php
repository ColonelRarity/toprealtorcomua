<?php
$settings['display'] = 'vertical';
$settings['fields'] = array(
    'name' => array(
        'caption' => 'Название',
        'type' => 'text'
    ),
    'info' => array(
        'caption' => 'Мероприятиe',
        'type' => 'text'
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
