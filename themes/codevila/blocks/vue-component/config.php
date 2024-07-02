<?php
return [
    'title' => 'Meu Bloco vue',
    'description' => 'Este é um bloco personalizado.',
    'category' => 'Vue Components',
    'icon' => 'fa fa-cube',
    'slug' => 'vuecomponent',
    'fields' => [
        'title' => [
            'label' => 'Título',
            'type' => 'text',
        ],
        'content' => [
            'label' => 'Conteúdo',
            'type' => 'textarea',
        ]
    ],
    'settings' => [
        "filter" => [
            "type" => "text",
            "label" => "filtro",
            "value" => "Example filtro",
        ],
        "gallery" => [
            "type" => "select",
            "label" => "gallery",
            "options" => [
                ['value' => 'select_layout', 'label' => 'Select Layout'],
                ['value' => 'select_layout2', 'label' => 'Select Layout2'],
            ]
        ]
    ]
];