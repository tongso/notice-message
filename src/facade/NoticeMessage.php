<?php

namespace tongso\noticeMessage\facade;
use think\Facade;

/**
 *
 * Class NoticeMessageFacade
 * @method static Array sendSms(string $phoneNumbers, $templateCode, array $templateParam, $apiVendor)
 *
 * @see \Tongso\NoticeMessage\MessageSender
 */
class NoticeMessage extends Facade
{
    protected static function getFacadeClass()
    {
        return \tongso\noticeMessage\NoticeMessage::class;
    }
}