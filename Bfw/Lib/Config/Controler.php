<?php
// 允许访问的控制器
$_config_arr['Controler']['allow'] = [
    'Cms_Member',
    'Cms_Page',
    'Cms_Order',
    'Cms_Pay',
    'Cms_Attach',
    'Cms_Artwork',
    'Cms_Artist',
    "Cms_Refund",
    "Cms_Wallet",
    "Cms_Helper",
    "Cms_BankCard",
    "Cms_Address",
    "Cms_Cart",
    "Cms_Express",
    'Cms_Background',
    "Cms_CataAttr",
    "Cms_Favorite",
    "Cms_Love",
    "Cms_Focus",
    'Cms_News',
    "Cms_Article"
];
// 路由重转向
$_config_arr['Controler']['url'] = [
    "/\/art\/([0-9]+)\/([0-9]+)$/" => [
        "url" => "/Cms/Member/Login/[1]/[2]/",
        "method" => 'GET',
        "expire" => 6000,
        "device" => "mobile"
    ],
    "/\/([0-9_a-z]+)\/[a-z]+\/[0-9]+\/[0-9]+\/[0-9]+\/Art_[0-9]+.[a-zA-Z]+$/" => [
        "url" => "/Cms/Attach/Resize/tag/[1]/",
        "method" => 'GET',
        "expire" => 6000
    ],
    "/loginband\//" => [
        "url" => "/Cms/Member/Login/thirdname/3",
        "method" => 'GET',
        "expire" => 6000
    ],
    "/\/artwork\/([0-9]+)\\.html$/" => [
        "url" => "/Cms/Page/ArtworkDetail/id/[1]",
        "method" => 'GET',
        "expire" => 6000
    ],
    "/\/artwork\/([0-9]+)$/" => [
        "url" => "/Cms/Page/ArtworkDetail/id/[1]",
        "method" => 'GET',
        "expire" => 6000
    ],
    "//" => [
        "url" => "http://wei.88art.com",
        "method" => 'GET',
        "expire" => 6000,
        "device" => "mobile"
    ]
];