<?php
$settings['display'] = 'vertical';
$settings['fields'] = array(
    'type' => array(
        'caption' => 'Type',
        'type' => 'text'
    ),
    'rank' => array(
        'caption' => 'Rank',
        'type' => 'text'
    ),
    'author' => array(
        'caption' => 'Author',
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
