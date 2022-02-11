<?php
namespace tongso\noticeMessage;

class NoticeMessage
{
    //支持的接口服务商别名
    private $SDKS = ['Aliyun', 'Juhe'];

    private function getVendorSender($apiVendor) {
        $smsSender = null;
        if (empty($apiVendor)) {
            $apiVendor = config("noticeMessage.shortMessage.defaultVendor");
        }
        $vendorName = ucfirst(strtolower($apiVendor));
        //判断vendor合法性并实例化服务器sdk类
        if (in_array($vendorName, $this->SDKS)) {
            $class = '\\tongso\\noticeMessage\\sdks\\' . $vendorName;
            return new $class();
        } else {
            throw new \Exception('暂时还不支持该' . $apiVendor . '的扩展');
        }
    }

    /**
     * 短信发送方法
     * @param string $phoneNumbers 要发送的手机号码,多个用,号分隔
     * @param $templateCode 短信模板编号
     * @param $templateParam 模板中使用的参数值 数组["code" => 1234]
     * @param $apiVendor 使用指定供应商的短信接口，为空则获取配置文件中defaultVendor值
     * @throws \Exception
     */
    public function sendSms(string $phoneNumbers, $templateCode, $templateParam = array(), $apiVendor = null) : SMSResult {
        $smsSender = $this->getVendorSender($apiVendor);
        return $smsSender->startSendSms($phoneNumbers, $templateCode, $templateParam);
    }

}
