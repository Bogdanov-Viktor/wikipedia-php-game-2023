<?php
//
//      /api/WikipediaApi.php
//

    // // функция кодирует строку для URL (urlencode)
    // function my_url_encode($s){
    //     $s= strtr ($s, array (" "=> "%20", "а"=>"%D0%B0", "А"=>"%D0%90","б"=>"%D0%B1", "Б"=>"%D0%91", "в"=>"%D0%B2", "В"=>"%D0%92", "г"=>"%D0%B3", "Г"=>"%D0%93", "д"=>"%D0%B4", "Д"=>"%D0%94", "е"=>"%D0%B5", "Е"=>"%D0%95", "ё"=>"%D1%91", "Ё"=>"%D0%81", "ж"=>"%D0%B6", "Ж"=>"%D0%96", "з"=>"%D0%B7", "З"=>"%D0%97", "и"=>"%D0%B8", "И"=>"%D0%98", "й"=>"%D0%B9", "Й"=>"%D0%99", "к"=>"%D0%BA", "К"=>"%D0%9A", "л"=>"%D0%BB", "Л"=>"%D0%9B", "м"=>"%D0%BC", "М"=>"%D0%9C", "н"=>"%D0%BD", "Н"=>"%D0%9D", "о"=>"%D0%BE", "О"=>"%D0%9E", "п"=>"%D0%BF", "П"=>"%D0%9F", "р"=>"%D1%80", "Р"=>"%D0%A0", "с"=>"%D1%81", "С"=>"%D0%A1", "т"=>"%D1%82", "Т"=>"%D0%A2", "у"=>"%D1%83", "У"=>"%D0%A3", "ф"=>"%D1%84", "Ф"=>"%D0%A4", "х"=>"%D1%85", "Х"=>"%D0%A5", "ц"=>"%D1%86", "Ц"=>"%D0%A6", "ч"=>"%D1%87", "Ч"=>"%D0%A7", "ш"=>"%D1%88", "Ш"=>"%D0%A8", "щ"=>"%D1%89", "Щ"=>"%D0%A9", "ъ"=>"%D1%8A", "Ъ"=>"%D0%AA", "ы"=>"%D1%8B", "Ы"=>"%D0%AB", "ь"=>"%D1%8C", "Ь"=>"%D0%AC", "э"=>"%D1%8D", "Э"=>"%D0%AD", "ю"=>"%D1%8E", "Ю"=>"%D0%AE", "я"=>"%D1%8F", "Я"=>"%D0%AF"));
    //     return $s;
    //     }
    // // функция раскодирует строку из URL (urldecode)
    // function my_url_decode($s){
    //     $s= strtr ($s, array ("%20"=>" ", "%D0%B0"=>"а", "%D0%90"=>"А", "%D0%B1"=>"б", "%D0%91"=>"Б", "%D0%B2"=>"в", "%D0%92"=>"В", "%D0%B3"=>"г", "%D0%93"=>"Г", "%D0%B4"=>"д", "%D0%94"=>"Д", "%D0%B5"=>"е", "%D0%95"=>"Е", "%D1%91"=>"ё", "%D0%81"=>"Ё", "%D0%B6"=>"ж", "%D0%96"=>"Ж", "%D0%B7"=>"з", "%D0%97"=>"З", "%D0%B8"=>"и", "%D0%98"=>"И", "%D0%B9"=>"й", "%D0%99"=>"Й", "%D0%BA"=>"к", "%D0%9A"=>"К", "%D0%BB"=>"л", "%D0%9B"=>"Л", "%D0%BC"=>"м", "%D0%9C"=>"М", "%D0%BD"=>"н", "%D0%9D"=>"Н", "%D0%BE"=>"о", "%D0%9E"=>"О", "%D0%BF"=>"п", "%D0%9F"=>"П", "%D1%80"=>"р", "%D0%A0"=>"Р", "%D1%81"=>"с", "%D0%A1"=>"С", "%D1%82"=>"т", "%D0%A2"=>"Т", "%D1%83"=>"у", "%D0%A3"=>"У", "%D1%84"=>"ф", "%D0%A4"=>"Ф", "%D1%85"=>"х", "%D0%A5"=>"Х", "%D1%86"=>"ц", "%D0%A6"=>"Ц", "%D1%87"=>"ч", "%D0%A7"=>"Ч", "%D1%88"=>"ш", "%D0%A8"=>"Ш", "%D1%89"=>"щ", "%D0%A9"=>"Щ", "%D1%8A"=>"ъ", "%D0%AA"=>"Ъ", "%D1%8B"=>"ы", "%D0%AB"=>"Ы", "%D1%8C"=>"ь", "%D0%AC"=>"Ь", "%D1%8D"=>"э", "%D0%AD"=>"Э", "%D1%8E"=>"ю", "%D0%AE"=>"Ю", "%D1%8F"=>"я", "%D0%AF"=>"Я"));
    //     return $s;
    // }


    class WikipediaApi {
        // $data = array('key1' => 'value1', 'key2' => 'value2');
        public const WIKI_HOST = 'https://ru.wikipedia.org';
        protected static $headersGet = array(
             'http' => array(
                'header'  => 
                    "User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64; rv:49.0) Gecko/20100101 Firefox/49.0\r\n" .
                    "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8\r\n" .
                    "Accept-Language: ru-RU,ru;q=0.8\r\n" .
                    // 'Accept-Language': 'en-US,en;q=0.5'
                    "Accept-Encoding: gzip, deflate\r\n" .
                    "DNT: 1\r\n" .
                    "Connection: keep-alive\r\n" .
                    "Upgrade-Insecure-Requests: 1\r\n",
                 'method'  => 'GET'//,
                 // 'content' => http_build_query($data)
             )
        );

        protected static function contextOfGetReq(){
            return stream_context_create(self::$headersGet);
        }

        protected static function rmSubstrInStart(string $str, string $substr){
            return substr($str, strlen($substr),
                     strlen($str) - strlen($substr));
        }

        protected static function startsWith(string $str, string $substr){
            return (strcmp(
                        substr($str, 0, strlen($substr)),
                        $substr
                    ) === 0);
        }

        protected static function rmSubstrInStartIfExists(string $str, string $substr){
            $result = $str;
            if(self::startsWith($str, $substr))
                $result = self::rmSubstrInStart($str, $substr);
            return $result;
        }

        public static function getRandWikiPage(){
            $context  = self::contextOfGetReq();
            // недокументированная функция для возврата случайной страницы википедии:
            // "https://ru.wikipedia.org/wiki/%D0%A1%D0%BB%D1%83%D0%B6%D0%B5%D0%B1%D0%BD%D0%B0%D1%8F:%D0%A1%D0%BB%D1%83%D1%87%D0%B0%D0%B9%D0%BD%D0%B0%D1%8F_%D1%81%D1%82%D1%80%D0%B0%D0%BD%D0%B8%D1%86%D0%B0"
            // "https://ru.wikipedia.org/wiki/Служебная:Случайная_страница"

            // документированные функции:
            // https://ru.wikipedia.org/wiki/Википедия:API/Новичкам

            $url = self::WIKI_HOST . '/wiki/Служебная:Случайная_страница';
            $headers = get_headers($url, false, $context);
            $location = "";
            for($i = 0; $i < count($headers); ++$i) {
                if (strpos($headers[$i], "location: ") === 0)
                {
                    $location = self::rmSubstrInStart($headers[$i], "location: ");
                    // $location = substr($headers[$i], strlen("location: "),
                    //  strlen($headers[$i]) - strlen("location: "));
                    $location = urldecode($location);
                }
            }
            $location = self::rmSubstrInStartIfExists($location, self::WIKI_HOST);
            return $location;
        }
        public static function getStartUrlPath(){
            return self::getRandWikiPage();
        }
        public static function getFinishUrlPath(){
            return self::getRandWikiPage();
        }
        public static function getLinksFromPage(string $page){
            // $page = '/wiki/Сафроновская_(Виноградовский_район)';
            $page = self::WIKI_HOST . $page;
            $htmlText = file_get_contents($page);

            $dom = new DOMDocument;
            libxml_use_internal_errors(true);
            $dom->loadHTML($htmlText);
            libxml_use_internal_errors(false);

            $aElements = $dom->getElementsByTagName('a');
            $hrefs = array();
            foreach ($aElements as $aElement) {
                $href = urldecode($aElement->getAttribute('href'));
                $href = self::rmSubstrInStartIfExists($href, self::WIKI_HOST); //убрать путь к хосту Wikipedia
                if(
                    self::startsWith($href, 'https:') ||
                    self::startsWith($href, 'http:') ||
                    self::startsWith($href, '//') ||
                    self::startsWith($href, 'ftp:') ||
                    self::startsWith($href, 'ssh:')
                    )
                    $href = ""; //убрать кросдоменные ссылки
                if(strpos($href, "#") !== false)
                    $href  = substr($href, 0, strpos($href, "#")); //убрать якоря из ссылок
                if($href !== "") //убрать ссылки со сраницы саму на себя
                    array_push($hrefs, $href);
            }
            
            return $hrefs;
        }
        public static function getFastPathFromToLength(string $from, string $to){
            //доп. TODO
            //поиск в ширину
            return 3;
        }
    }
?>