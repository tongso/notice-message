<?php
/**
 * 阿里云短信SDK
 */

namespace tongso\noticeMessage\Sdks;


use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Client\Exception\ClientException;
use AlibabaCloud\Client\Exception\ServerException;
use think\Exception;
use think\facade\Config;
use tongso\noticeMessage\MessageSender;
use tongso\noticeMessage\SMSResult;

class Aliyun extends MessageSender
{

    private $accessKeyId;
    private $accessSecret;
    private $signName;
    private $regionId = 'cn-hangzhou';
    private $host = 'dysmsapi.aliyuncs.com';

    /**
     * Aliyun constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->loadVendorConfig();
    }

    /**
     * 调用接口发送短信
     * @param $phoneNumbers 手机号
     * @param $templateCode 模板编码
     * @param $templateParam 模板参数值，json对象格式
     * @return array
     * @throws ClientException
     * @throws ServerException
     */
    protected function callSendSms(string $phoneNumbers, string $templateCode, string $templateParam = null) {
        AlibabaCloud::accessKeyClient($this->accessKeyId, $this->accessSecret)
            ->regionId($this->regionId)
            ->asDefaultClient();
            $result = AlibabaCloud::rpc()
                ->product('Dysmsapi')
                ->version('2017-05-25')
                ->action('SendSms')
                ->method('POST')
                ->host($this->host)
                ->options([
                    'query' => [
                        'RegionId' => $this->regionId,
                        'PhoneNumbers' => $phoneNumbers,
                        'SignName' => $this->signName,
                        'TemplateCode' => $templateCode,
                        'TemplateParam' => $templateParam,
                    ],
                ])
                ->request();
            return $result->toArray();
    }

    protected function loadVendorConfig()
    {
        //加载配置
        $configs = Config::get("noticeMessage.shortMessage.apiConfigs.aliyun");
        if (!empty($configs['accessKeyId']))
            $this->accessKeyId = $configs['accessKeyId'];
        else
            throw new \Exception('请配置accessKeyId');

        if (!empty($configs['accessSecret']))
            $this->accessSecret = $configs['accessSecret'];
        else
            throw new \Exception('accessSecret');

        if (!empty($configs['signName']))
            $this->signName = $configs['signName'];
        else
            throw new \Exception('signName');

        if (!empty($configs['regionId']))
            $this->regionId = $configs['regionId'];

        if (!empty($configs['host']))
            $this->host = $configs['host'];
    }

    public function startSendSms(string $phoneNumbers, $templateCode, array $templateParam = null) :SMSResult
    {
        $smsResult = new SMSResult();
        $smsResult->code = 500;
        $parsedTemplateParam = null;
        if (!empty($templateParam))
            $parsedTemplateParam = json_encode($templateParam);
        try {
            $sendRet = $this->callSendSms($phoneNumbers, $templateCode, $parsedTemplateParam);
            if ($sendRet['Code'] == 'Code') {
                $smsResult->code = 0;
            } else {
                $smsResult->msg = $sendRet['Message'];
            }
        } catch (ClientException $e) {
            $smsResult->msg = $e->getMessage();
        } catch (ServerException $e) {
            $smsResult->msg = $e->getMessage();
        } catch (\Exception $e) {
            $smsResult->msg = $e->getMessage();
        } finally {
            return $smsResult;
        }
    }
}