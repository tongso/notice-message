<?php


namespace tongso\noticeMessage;

/**
 * 统一发送结果
 * Class SMSResult
 * @package tongso\noticeMessage
 */
class SMSResult
{
    /**
     * @var string 0为成功,其它为失败
     */
    public $code = '';
    /**
     * @var string 失败消息
     */
    public $msg = '';
}