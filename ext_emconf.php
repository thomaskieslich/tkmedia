<?php
$EM_CONF[$_EXTKEY] = array(
    'title' => 'Extend Textmedia',
    'category' => 'fe',
    'state' => 'stable',
    'author' => 'ThomasK',
    'author_email' => 'post@thomaskieslich.de',
    'version' => '8.5.0',
    'constraints' => array(
        'depends' => array(
            'typo3' => '8.5.1-8.7.99'
        ),
        'conflicts' => array(
            'css_styled_content' => ''
        ),
        'suggests' => array(
            'fluid_styled_content' => ''
        ),
    )
);
