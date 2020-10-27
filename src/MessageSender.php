<?php
/**
 * 短消息发送功能
 */
namespace tongso\noticeMessage;

abstract class MessageSender
{
    /**
     * 加载不同服务器的配置信息，由服务商SDK实现
     * @return mixed
     */
    abstract protected function loadVendorConfig();

    /**
     * 需要服务商SDK实现，把用户短信信息内容转换成SDK需要的格式后发送
     * @param string $phoneNumbers 短信接收手机号码多个用,号分隔
     * @param $templateCode 短信模板编码
     * @param array $templateParam 短信参数值，为数组键值对
     * @return mixed
     */
    abstract protected function startSendSms(string $phoneNumbers, $templateCode, array $templateParam = array()) : SMSResult;

}