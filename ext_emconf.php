<?php
$EM_CONF[$_EXTKEY] = array(
    'title'        => 'Extend Textmedia',
    'category'     => 'fe',
    'state'        => 'stable',
    'author'       => 'ThomasK',
    'author_email' => 'post@thomaskieslich.de',
    'version'      => '7.6.0',
    'constraints'  => array(
        'depends'   => array(
            'typo3' => '7.6.11-8.9.99'
        ),
        'conflicts' => array(
            'css_styled_content' => ''
        ),
        'suggests'  => array(
            'fluid_styled_content' => ''
        ),
    ),
    'autoload' =>
        array(
            'psr-4' =>
                array(
                    "ThomasK\\Tkmedia\\" => "Classes"
                )
        )
);
