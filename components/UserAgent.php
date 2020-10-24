<?php

namespace app\components;

use Yii;
use yii\base\Component;

/**
 * Created by Yukal Alexander
 * Date: 29.01.17
 * Time: 11:35
 *
 *
 * User-Agent standards:
 *
 * RFC2616 (section 14.43, page 144)
 * http://www.ietf.org/rfc/rfc2616.txt
 *
 * RFC7231 (section 5.5.3, page 46)
 * https://tools.ietf.org/html/rfc7231#section-5.5.3
 *
 * User-Agent string template:
 * User-Agent: Mozilla/<version> (<system-information>) <platform> (<platform-details>) <extensions>
 *
 *
 * More about different User-Agent strings
 *
 * https://udger.com/resources/ua-list
 * https://developer.mozilla.org/ru/docs/Web/HTTP/Headers/User-Agent
 * https://deviceatlas.com/blog/user-agent-string-analysis
 * https://deviceatlas.com/blog/list-of-user-agent-strings
 * https://msdn.microsoft.com/en-us/library/ms537503(v=vs.85).aspx
 * https://developer.chrome.com/multidevice/user-agent
 * https://www.sitepoint.com/server-side-device-detection-with-browscap/
 *
 */

class UserAgent extends Component
{
    private $parsed = false;
    private $_usrAgent;
    private $_platform = 'unknown';
    private $_os = 'unknown';
    private $_browser = 'unknown';
    private $_browserVersion = 'unknown';
    public function init()
    {
        $this->getUsrAgent();
        $this->Parse();
        // Редирект по платформе
        // (from Desktop -> http://m.localhost/...)
        if ($this->isMobile) {
            // Another way...
            // $tpl = '/https?:\/\/m.'.Yii::$app->getRequest()->serverName.'/';
            // if (preg_match($tpl, Yii::$app->getRequest()->hostInfo) == 0) {
            if (! $this->isMobileHost) {
                Yii::$app->getResponse()->redirect($this->mobileLink);
                //$this->redirect($mobileUrl);
                Yii::$app->end();
            }
        }
        parent::init();
    }
    private function getUsrAgent()
    {
        if (empty($this->_userAgent)) {
            $this->_usrAgent = strtolower(Yii::$app->getRequest()->getUserAgent());
        }
        return $this->_usrAgent;
    }
    public function getPlatform()
    {
        return $this->_platform;
    }
    public function getOs()
    {
        return $this->_os;
    }
    public function getBrowser()
    {
        return $this->_browser;
    }
    public function getBrowserVersion()
    {
        return $this->_browserVersion;
    }
    public function getIsMobile()
    {
        return $this->getPlatform() == 'mobile';
    }
    public function getIsDesktop()
    {
        return $this->getPlatform() == 'desktop';
    }
    public function getIsMobileHost()
    {
        return (bool) strpos(
            Yii::$app->getRequest()->hostInfo,
            '://m.'.Yii::$app->getRequest()->serverName
        );
    }
    public function getMobileLink()
    {
        return str_replace(
            Yii::$app->getRequest()->serverName,
            'm.'.Yii::$app->getRequest()->serverName,
            Yii::$app->getRequest()->absoluteUrl
        );
    }
    public function getBrowserVersionMasks($aVersion = '', $bVersion = '')
    {
        if (empty($bVersion)) {
            if (empty($aVersion)) {
                return false;
            }
            $bVersion = $this->getBrowserVersion();
        }
        $getMin = function ($a, $b) {
            return $a < $b ? $a : $b;
        };
        $getMax = function ($a, $b) {
            return $a > $b ? $a : $b;
        };
        $aChunks = explode('.', $aVersion);
        $bChunks = explode('.', $bVersion);
        $aCount  = count($aChunks);
        $bCount  = count($bChunks);
        $minCount = $getMin($aCount, $bCount);
        if ($aCount > $bCount) {
            $aChunks = array_slice($aChunks, 0, $minCount);
        } else {
            $bChunks = array_slice($bChunks, 0, $minCount);
        }
        for ($i=0; $i<$minCount; $i+=1) {
            $aLen = strlen($aChunks[$i]);
            $bLen = strlen($bChunks[$i]);
            $maxLen = $getMax($aLen, $bLen);
            $minLen = $getMin($aLen, $bLen);
            if ($i+1<$minCount) {
                if ($aLen > $bLen) {
                    $aChunks[$i] = (int) substr($aChunks[$i], 0, $maxLen);
                } elseif ($aLen < $bLen) {
                    $bChunks[$i] = (int) substr($bChunks[$i], 0, $maxLen);
                }
            } else {
                if ($aLen > $bLen) {
                    $aChunks[$i] = (int) substr($aChunks[$i], 0, $minLen);
                } elseif ($aLen < $bLen) {
                    $bChunks[$i] = (int) substr($bChunks[$i], 0, $minLen);
                }
            }
            // printf("%s - %s %s\n", $minLen, $aChunks[$i], $bChunks[$i]);
        }
        $aVersion = implode('', $aChunks);
        $bVersion = implode('', $bChunks);
        return [ $aVersion, $bVersion ];
    }
    public function browserCompare($mixedBrowsers)
    {
        $tpl = '/([\d.]+)\s?([<=>])\s?([\d.]+)/';
        preg_match($tpl, $mixedBrowsers, $matches);
        if (count($matches) > 3) {
            list($firstCompare, $secondCompare) =
                $this->getBrowserVersionMasks($matches[1], $matches[3]);
            $firstCompare  = (int) $firstCompare;
            $secondCompare = (int) $secondCompare;
            if ($matches[2] == '>') {
                return ($firstCompare > $secondCompare);
            } elseif ($matches[2] == '<') {
                return ($firstCompare < $secondCompare);
            } elseif ($matches[2] == '=') {
                return ($firstCompare == $secondCompare);
            }
            // return [$firstCompare, $secondCompare];
        }
        return false;
    }
    /*public function browserLower($version) {
        $browserVersion = (int) preg_replace('/[^\d]+/', '', $this->getBrowserVersion());
        $neededVersion  = (int) preg_replace('/[^\d]+/', '', $version);
        return ($browserVersion < $neededVersion);
    }
    public function browserHigher($version) {
        $browserVersion = (int) preg_replace('/[^\d]+/', '', $this->getBrowserVersion());
        $neededVersion  = (int) preg_replace('/[^\d]+/', '', $version);
        return ($browserVersion > $neededVersion);
    }
    public function browserEqual($version) {
        $browserVersion = (int) preg_replace('/[^\d]+/', '', $this->getBrowserVersion());
        $neededVersion  = (int) preg_replace('/[^\d]+/', '', $version);
        return ($browserVersion == $neededVersion);
    }*/
    private function Parse()
    {
        if ($this->parsed) {
            return $this->parsed;
        }
        $data = $this->parseData($this->getUsrAgent());
        $this->_platform = $data['platform'];
        $this->_os = $data['os'];
        $this->_browser = $data['browser'];
        $this->_browserVersion = $data['browserVersion'];
        return ($this->parsed = true);
    }
    private function parseData($userAgent)
    {
        $platform = 'unknown';
        $os = 'unknown';
        $tplDesktop = '/windows|linux|mac(?=intosh|\s?os)/i';
        $tplMobile1 = '/mobi(?:le)?/i';
        $tplMobile2 = '/android|windows(?=\sphone)?|mac(?=intosh|\s?os)|symbos/i';
        $tplBrowser = '/(firefox|opr|opera|chrome|safari|edge|msie|iemobile)\/([\d.]+)/i';
        $tplBot     = '/([\w]+bot)\/([\d.]+)/i';
        //$tplMobile1 = '/mobi(?:le)?|ipad|iphone|android/i';
        //$tplBot = '/([\w]+bot|PlayStation|Nintendo)(?:\/([\d.]+))?/i';
        if (preg_match($tplMobile1, $userAgent, $matches) > 0) {
            // MOBILE
            preg_match($tplMobile2, $userAgent, $matches);
            // preg_match($tplMobile2, $userAgent, $matches, PREG_OFFSET_CAPTURE);
            $os = @$matches[0] ? $matches[0] : 'unknown';
            if ($os == 'windows') {
                $os .= 'phone';
            }
            if ($os == 'mac') {
                $os = 'ios';
            }
            $platform = 'mobile';
        } elseif (preg_match($tplDesktop, $userAgent, $matches)) {
            // DESKTOP
            $os = @$matches[0] ? $matches[0] : 'unknown';
            if ($os == 'mac') {
                $os .= 'os';
            }
            if ($os != 'unknown') {
                $platform = 'desktop';
            }
            //if ($platform == 'unknown')
            //    $platform = $userAgent;
        } elseif (preg_match($tplBot, $userAgent, $matches) > 0) {
            // BOT
            $platform = $matches[1];
        }
        // BROWSER
        preg_match($tplBrowser, $userAgent, $matches);
        $browser = @$matches[1] ? $matches[1] : 'unknown';
        $browserVersion = @$matches[2] ? $matches[2] : 'unknown';
        if ($browser == 'opr') {
            $browser = 'opera';
        }
        return [
            'platform' => $platform,
            'os' => $os,
            'browser' => $browser,
            'browserVersion' => $browserVersion
        ];
    }
    public function test()
    {
        $chunks = [
            'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:47.0) Gecko/20100101 Firefox/47.0',
            'Mozilla/5.0 (Intel Mac OS X x.y; rv:42.0) Gecko/20100101 Firefox/42.0',
            'Mozilla/5.0 (Macintosh; Intel Mac OS X x.y; rv:42.0) Gecko/20100101 Firefox/42.0',
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.8; rv:24.0) Gecko/20100101 Firefox/24.0',
            'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.36',
            'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.106 Safari/537.36 OPR/38.0.2220.41',
            'Mozilla/5.0 (iPad; CPU OS 6_0 like Mac OS X) AppleWebKit/536.26 (KHTML, like Gecko) Version/6.0 Mobile/10A5355d Safari/8536.25',
            'Mozilla/5.0 (Linux; U; Android 4.0.3; de-ch; HTC Sensation Build/IML74K) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 Mobile Safari/534.30',
            'Mozilla/5.0 (compatible; MSIE 9.0; Windows Phone OS 7.5; Trident/5.0; IEMobile/9.0)',
            'Opera/9.80 (J2ME/MIDP; Opera Mini/9.80 (S60; SymbOS; Opera Mobi/23.348; U; en) Presto/2.5.25 Version/10.54',
            'Mozilla/5.0 (Linux; U; Android 4.0.3; ko-kr; LG-L160L Build/IML74K) AppleWebkit/534.30 (KHTML, like Gecko) Version/4.0 Mobile Safari/534.30',
            'Mozilla/5.0 (Linux; U; Android 4.1.1; en-gb; Build/KLP) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 Safari/534.30',
            'Mozilla/5.0 (Linux; Android 4.4; Nexus 5 Build/_BuildID_) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/30.0.0.0 Mobile Safari/537.36',
            'Mozilla/5.0 (Linux; Android 5.1.1; Nexus 5 Build/LMY48B; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/43.0.2357.65 Mobile Safari/537.36',
            'Mozilla/5.0 (Linux; Android 6.0.1; SM-G920V Build/MMB29K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/52.0.2743.98 Mobile Safari/537.36',
            'Mozilla/5.0 (Linux; Android 5.1.1; SM-G928X Build/LMY47X) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.83 Mobile Safari/537.36',
            'Mozilla/5.0 (Windows Phone 10.0; Android 4.2.1; Microsoft; Lumia 950) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/46.0.2486.0 Mobile Safari/537.36 Edge/13.10586',
            'Mozilla/5.0 (Linux; Android 6.0.1; Nexus 6P Build/MMB29P) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.83 Mobile Safari/537.36',
            'Mozilla/5.0 (Linux; Android 6.0.1; E6653 Build/32.2.A.0.253) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/52.0.2743.98 Mobile Safari/537.36',
            'Mozilla/5.0 (Linux; Android 6.0; HTC One M9 Build/MRA58K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/52.0.2743.98 Mobile Safari/537.36',
            'Mozilla/5.0 (Linux; Android 7.0; Pixel C Build/NRD90M; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/52.0.2743.98 Safari/537.36',
            'Mozilla/5.0 (Linux; Android 6.0.1; SGP771 Build/32.2.A.0.253; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/52.0.2743.98 Safari/537.36',
            'Mozilla/5.0 (Linux; Android 5.1.1; SHIELD Tablet Build/LMY48C) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/52.0.2743.98 Safari/537.36',
            'Mozilla/5.0 (Linux; Android 5.0.2; SAMSUNG SM-T550 Build/LRX22G) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/3.3 Chrome/38.0.2125.102 Safari/537.36',
            'Mozilla/5.0 (Linux; Android 4.4.3; KFTHWI Build/KTU84M) AppleWebKit/537.36 (KHTML, like Gecko) Silk/47.1.79 like Chrome/47.0.2526.80 Safari/537.36',
            'Mozilla/5.0 (Linux; Android 5.0.2; LG-V410/V41020c Build/LRX22G) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/34.0.1847.118 Safari/537.36',
            'Mozilla/5.0 (CrKey armv7l 1.5.16041) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/31.0.1650.0 Safari/537.36',
            'Mozilla/5.0 (Linux; U; Android 4.2.2; he-il; NEO-X5-116A Build/JDQ39) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 Safari/534.30',
            'Mozilla/5.0 (Linux; Android 4.2.2; AFTB Build/JDQ39) AppleWebKit/537.22 (KHTML, like Gecko) Chrome/25.0.1364.173 Mobile Safari/537.22',
            'Dalvik/2.1.0 (Linux; U; Android 6.0.1; Nexus Player Build/MMB29T)',
            'AppleTV5,3/9.1.1',
            'Mozilla/5.0 (Nintendo WiiU) AppleWebKit/536.30 (KHTML, like Gecko) NX/3.0.4.2.12 NintendoBrowser/4.3.1.11264.US',
            'Mozilla/5.0 (Windows Phone 10.0; Android 4.2.1; Xbox; Xbox One) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/46.0.2486.0 Mobile Safari/537.36 Edge/13.10586',
            'Mozilla/5.0 (PlayStation 4 3.11) AppleWebKit/537.73 (KHTML, like Gecko)',
            'Mozilla/5.0 (PlayStation Vita 3.61) AppleWebKit/537.73 (KHTML, like Gecko) Silk/3.2',
            'Mozilla/5.0 (Nintendo 3DS; U; ; en) Version/1.7412.EU',
            'Mozilla/5.0 (X11; U; Linux armv7l like Android; en-us) AppleWebKit/531.2+ (KHTML, like Gecko) Version/5.0 Safari/533.2+ Kindle/3.0+',
            'Mozilla/5.0 (Linux; U; en-US) AppleWebKit/528.5+ (KHTML, like Gecko, Safari/528.5+) Version/4.0 Kindle/3.0 (screen 600x800; rotate)',
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/42.0.2311.135 Safari/537.36 Edge/12.246',
            'Mozilla/5.0 (X11; CrOS x86_64 8172.45.0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.64 Safari/537.36',
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_2) AppleWebKit/601.3.9 (KHTML, like Gecko) Version/9.0.2 Safari/601.3.9',
            'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.111 Safari/537.36',
            'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:15.0) Gecko/20100101 Firefox/15.0.1',
            'Googlebot/2.1 (+http://www.google.com/bot.html)',
            'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)',
            'Mozilla/5.0 (compatible; bingbot/2.0; +http://www.bing.com/bingbot.htm)',
            'Mozilla/5.0 (compatible; Yahoo! Slurp; http://help.yahoo.com/help/us/ysearch/slurp)'
        ];
        echo "<pre>";
        echo "Compare browser versions:\n";
        printf("55.0.2883.86 > 55.01.27 (%s)\n", $this->browserCompare('55.0.2883.86 > 55.01.27')?'true':'false');
        printf("55.0.2883.86 > 55.0.28 (%s)\n", $this->browserCompare('55.0.2883.86 > 55.0.28')?'true':'false');
        printf("55.0.2883.86 > 55.01.29 (%s)\n\n", $this->browserCompare('55.0.2883.86 > 55.01.29')?'true':'false');
        printf("55.0.2883.86 < 55.01.27 (%s)\n", $this->browserCompare('55.0.2883.86 < 55.01.27')?'true':'false');
        printf("55.0.2883.86 < 55.0.28 (%s)\n", $this->browserCompare('55.0.2883.86 < 55.0.28')?'true':'false');
        printf("55.0.2883.86 < 55.01.29 (%s)\n\n", $this->browserCompare('55.0.2883.86 < 55.01.29')?'true':'false');
        printf("55.0.2883.86 = 55.01.27 (%s)\n", $this->browserCompare('55.0.2883.86 = 55.01.27')?'true':'false');
        printf("55.0.2883.86 = 55.0.28 (%s)\n", $this->browserCompare('55.0.2883.86 = 55.0.28')?'true':'false');
        printf("55.0.2883.86 = 55.01.29 (%s)\n\n", $this->browserCompare('55.0.2883.86 = 55.01.29')?'true':'false');
        printf($this->getBrowserVersion()." = 55.0.29 (%s)\n\n", $this->browserCompare('55.0.2883.86 = 55.0.29')?'true':'false');
        // Self User-Agent info
        printf(
            "Self User-Agent info is:\n%s / <b style='color: #122b40'>%s</b> / <b style='color: darkgreen'>%s (%s)</b>\n\n\n",
            Yii::$app->userAgent->platform,
            Yii::$app->userAgent->os,
            Yii::$app->userAgent->browser,
            Yii::$app->userAgent->browserVersion
        );
        foreach ($chunks as &$userAgent) {
            $data = $this->parseData($userAgent);
            printf(
                "%s\n%s / <b style='color: #122b40'>%s</b> / <b style='color: darkgreen'>%s (%s)</b>\n\n",
                $userAgent,
                $data['platform'],
                $data['os'],
                $data['browser'],
                $data['browserVersion']
            );
        }
        echo "</pre>";
    }
}
