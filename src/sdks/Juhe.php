<?php
namespace tongso\noticeMessage\sdks;
use think\facade\Config;
use tongso\noticeMessage\MessageSender;
use tongso\noticeMessage\SMSResult;

/**
 *
 * Class Juhe
 * @package Tongso\NoticeMessage
 * @see api doc https://www.juhe.cn/docs/api/id/54
 */
class Juhe extends MessageSender
{


    private $apiKey;
    private static $API_URL = "http://v.juhe.cn/sms/send";

    /**
     * Juhe constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->loadVendorConfig();
    }

    /**
     * @param $mobile 手机号码
     * @param $tpl_id 模板ID
     * @param $templateParam 模板值 无特殊字符的代码示例:tpl_value=urlencode("#code#=1234&#company#=聚合数据")<br/>
     * 带特殊字符的代码示例:tpl_value=urlencode("#code#=1234&#company#=urlencode('聚#合#数#据')")
     * @return mixed
     */
    protected function callSendSms($mobile, $tpl_id, $templateParam) {

        $params = array(
            'key'   => $this->apiKey,
            'mobile'    => $mobile,
            'tpl_id'    => $tpl_id,
            'tpl_value' => $templateParam
        );

        $paramstring = http_build_query($params);
        $content = $this->juheCurl(self::$API_URL, $paramstring);
        $result = json_decode($content, true);
        return $result;
    }

    /**
     * 请求接口返回内容
     * @param  string $url [请求的URL地址]
     * @param  string $params [请求的参数]
     * @param  int $ipost [是否采用POST形式]
     * @return  string
     */
    function juheCurl($url, $params = false, $ispost = 0)
    {
        $httpInfo = array();
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_USERAGENT, 'JuheData');
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        if ($ispost) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
            curl_setopt($ch, CURLOPT_URL, $url);
        } else {
            if ($params) {
                curl_setopt($ch, CURLOPT_URL, $url.'?'.$params);
            } else {
                curl_setopt($ch, CURLOPT_URL, $url);
            }
        }
        $response = curl_exec($ch);
        if ($response === FALSE) {
            //echo "cURL Error: " . curl_error($ch);
            return false;
        }
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $httpInfo = array_merge($httpInfo, curl_getinfo($ch));
        curl_close($ch);
        return $response;
    }

    protected function loadVendorConfig()
    {
        //加载配置
        $configs = Config::get("noticeMessage.shortMessage.apiConfigs.juhe");
        if (!empty($configs['apiKey']))
            $this->apiKey = $configs['apiKey'];
        else
            throw new \Exception('请配置apiKey');

    }

    public function startSendSms(string $phoneNumbers, $templateCode, array $templateParam = null) : SMSResult
    {
        $smsResult = new SMSResult();
        $smsResult->code = 500;
        $parsedTemplateValue = null;
        if (!empty($templateParam)) {
            $newTemplateParam = array();
            foreach ($templateParam as $filed => $value) {
               $newField = '#' . $filed . '#';
               $newValue = urlencode($value);
               $newTemplateParam[] = $newField . '=' . $newValue;
            }
            $parsedTemplateValue = implode('&', $newTemplateParam);
        }
        $sendRet = $this->callSendSms($phoneNumbers, $templateCode, $parsedTemplateValue);
        if ($sendRet['error_code'] == 0) {
            $smsResult->code = 0;
        } else {
            $smsResult->msg = $sendRet['reason'];
        }
        return $smsResult;
    }
}
