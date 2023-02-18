<?php
namespace Flynn314\WebClient;

class WebClient
{
    const USER_AGENT_FIREFOX = 'Mozilla/5.0 (Windows NT 5.1; rv:31.0) Gecko/20100101 Firefox/31.0';
    const USER_AGENT_CHROME = 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.2 (KHTML, like Gecko) Chrome/22.0.1216.0 Safari/537.2';
    const USER_AGENT_SAFARI = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_3) AppleWebKit/537.75.14 (KHTML, like Gecko) Version/7.0.3 Safari/7046A194A';
    const USER_AGENT_TWITTERBOT = 'Twitterbot/1.0';
    const USER_AGENT_FACEBOOK = 'facebookexternalhit/1.1 (+https://www.facebook.com/externalhit_uatext.php)';

    public function fileGetContentsAsSafari(string $url, array $headers = []): string
    {
        return $this->fileGetContents($url, static::USER_AGENT_SAFARI, $headers);
    }

    public function fileGetContentsAsChrome(string $url, array $headers = []): string
    {
        return $this->fileGetContents($url, static::USER_AGENT_CHROME, $headers);
    }

    /**
     * @param string $url
     * @param string $userAgent
     * @param array  $headers
     * @return string
     * @throws \Exception
     */
    public function fileGetContents(string $url, string $userAgent, array $headers = []): string
    {
//        $cookie = tmpfile();
        $ch = curl_init($url);
        $options = [
            CURLOPT_CONNECTTIMEOUT => 20,
            CURLOPT_USERAGENT => $userAgent,
            CURLOPT_AUTOREFERER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_RETURNTRANSFER => true,
//            CURLOPT_COOKIEFILE => $cookie,
//            CURLOPT_COOKIEJAR => $cookie,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_SSL_VERIFYHOST => 0
        ];
        if ($headers) {
            foreach ($headers as $key => $value) {
                $headers[$key] = sprintf('%s: %s', $key, $value);
            }
            $options[CURLOPT_HTTPHEADER] = $headers;
        }
        curl_setopt_array($ch, $options);
        $data = curl_exec($ch);
        curl_close($ch);
        if (!$data) {
            throw new \Exception(sprintf('No content for "%s"', $url));
        }

        return $data;
    }
}
