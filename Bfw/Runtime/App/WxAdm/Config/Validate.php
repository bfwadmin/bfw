<?php
use App\[[DOM]]\Client\Client_Member;
$_config_arr['Validate']['pass'] = [
    "Member_Login_[[DOM]]",
    "Member_Register_[[DOM]]",
    "Member_FindPwd_[[DOM]]",
    "Member_Logout_[[DOM]]"
];
$_config_arr['Validate']['group'] = [
    "Person_ListData_Pm" => [
        Client_Member::KIND_NORMAL,
        Client_Member::KIND_COMP
    ],
    "Order_MyList_Pm" => [
        Client_Member::KIND_NORMAL,
        Client_Member::KIND_COMP
    ]
];//根据groupid来进行权限选择