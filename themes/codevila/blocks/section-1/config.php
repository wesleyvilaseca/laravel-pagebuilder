<?php

return [
    'hidden' => true,
    'title' => 'Section 1',
    'category' => 'widget',
    'icon' => 'fa fa-bars',
    "label" => [
        "type" => "text",
        "label" => "Label",
        "value" => "Example label",
    ],
    "options" => [
        "type" => "text",
        "label" => "Options (provide comma separated options)",
        "value" => "option 1, option 2",
    ],
    "required" => [
        "type" => "yes_no",
        "label" => "This selector is required",
        "value" => "0",
    ],
    "gallery" => [
        "type" => "select",
        "label" => "gallery",
        "options" => [
            ['value' => 'select_layout', 'label' => 'Select Layout'],
            ['value' => 'select_layout2', 'label' => 'Select Layout2'],
        ]
    ]
];