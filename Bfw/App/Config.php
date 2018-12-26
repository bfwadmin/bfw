<?php
$_config_arr['Globle'] = [
    "routetype" => 0,
    "instance_name" => "001",
    "lang" => "Zh",
    "page_suffix" => ".html",
    "defaultdom" => "Wbhome",
    "defaultact" => "",
    "defaultcont" => "",
    "runmode" => "C",
    "service" => [
        "WxAdm_Order_Down" => [
            "type" => "queue",
            "para" => 0,
            "limit" => 3
        ],
        "WxAdm1_Power_GetPowerDetailByGroupId" => [
            "type" => "queue",
            "para" => 0,
            "limit" => 10
        ]
    ],
    "host_runmode" => [
        "c.exm.com" => [
            "mode" => "C",
            "dom" => "WxAdm",
            "cont" => "Power",
            "act" => "ListData",
            "routetype" => 2,
            "route" => [
                "/\/artwork\/([0-9]+)\\.html$/" => [
                    "url" => "/Article/DetailData/id/[1]",
                    "method" => 'GET'
                ],
                "/\/artwork\/$/" => [
                    "url" => "/Article/ListData",
                    "method" => 'GET'
                ]
            ]
        ],
        "m.exm.com" => [
            "mode" => "M",
            "dom" => "",
            "cont" => "",
            "act" => ""
        ],
        "s.exm.com" => [
            "mode" => "S",
            "dom" => "",
            "cont" => "",
            "act" => ""
        ],
        "a.exm.com" => [
            "mode" => "C",
            "dom" => "Center",
            "cont" => "Hello",
            "act" => "ListData",
            "routetype" => 0
        ]
    ]
];
?>