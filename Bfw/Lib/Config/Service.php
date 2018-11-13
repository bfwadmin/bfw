<?php

// service运用模式
$_config_arr['Service']['config'] = [
    "Cms_Order_Close" => ["type"=>"queue","para"=>1,"limit"=>3],
    "Cms_Sms_Send" => ["type"=>"queue","para"=>1,"limit"=>3],
    "Cms_Member_Auth" => ["type"=>"session","para"=>0,"limit"=>3],
    "Cms_Focus_Add" => ["type"=>"session","limit"=>1],
    "Cms_Focus_Cancel" => ["type"=>"session","limit"=>1],
    "Cms_Favorite_Cancel" => ["type"=>"session","limit"=>1],
    "Cms_Favorite_Add" => ["type"=>"session","limit"=>1],
    "Cms_Address_Update" => ["type"=>"session","limit"=>3],
    "Cms_Address_Insert" => ["type"=>"session","limit"=>5],
    "Cms_Artwork_Insert" => ["type"=>"session","limit"=>5],
    "Cms_Order_Down" => ["type"=>"queue","para"=>0,"limit"=>3]
];
 