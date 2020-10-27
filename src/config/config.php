<?php
return [
    //手机短信
    "shortMessage" => [
        //当前使用的短信接口服务商
        "defaultVendor" => "aliyun",
        //各服务商接口参数配置，每个服务商接口验证参数可能不一样
        "apiConfigs" => [
            "aliyun" => [
                "accessKeyId" => '',
                "accessSecret" => '',
                "signName" => 'MrByte.cn',
                "regionId" => 'cn-hangzhou',//阿里默认值
                "host" => 'dysmsapi.aliyuncs.com'//阿里默认值
            ],
            "juhe" => [
                "apiKey" => ''
            ]
        ]
    ]
];