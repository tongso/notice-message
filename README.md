# notice-message
适用于Thinkphp6的短消息发送扩展

目前已支持：Aliyun、聚合数据短信发送
欢迎提交PR和提建议

如果需要支持更多服务商接口或者有问题可以联系6306643@qq.com

## 安装扩展
```php
composer require tongso/notice-message
```

// 安装之后会在config目录里自动生成noticeMessage.php配置文件

```php
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
```

## 使用方法
```php
NoticeMessage::sendSms("手机号码", "短信模板ID", "短信模板中的变量值", "短信服务商名称");
```

## 调用参数说明

    1、短信模板中的变量值：是一个key为变量名值为变量值的数组，例：array('code' => '3420')
    
    2、短信服务商名称：目前只支持aliyun和juhe

## 想要完善的功能
   
   1、支持队列发送
   
   2、支持邮件发送
   
   3、支持日志功能
   
   4、支持随机验证码的生成和验证 