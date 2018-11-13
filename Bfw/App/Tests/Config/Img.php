<?php
//  图片压缩
/*裁剪类型 	 
const IMAGE_THUMB_SCALE = 1; // 常量，标识缩略图等比例缩放类型
const IMAGE_THUMB_FILLED = 2; // 常量，标识缩略图缩放后填充类型
const IMAGE_THUMB_CENTER = 3; // 常量，标识缩略图居中裁剪类型
const IMAGE_THUMB_NORTHWEST = 4; // 常量，标识缩略图左上角裁剪类型
const IMAGE_THUMB_SOUTHEAST = 5; // 常量，标识缩略图右下角裁剪类型
const IMAGE_THUMB_FIXED = 6; // 常量，标识缩略图固定尺寸缩放类型
*/
$_config_arr['Img']['size'] = [
   "200_200_1" => [
        200,
        200,
        1
    ],
    "400_200_1" => [
        400,
        200,
        1
    ],
    "800_400_1" => [
        800,
        400,
        1
    ],
    "750_500_3" => [
        750,
        500,
        3
    ],
	"2000_1600_1" => [
        2000,
        1600,
        1
    ]
   
];