<?php
namespace Api\App\Http;


class Response 
{   

    /**
     * send send the results with status code with the appropriate headers
     *
     * @param mixed $data data that is sent
     * @param string $type type of response
     * @param integer $code response code
     * @return void
     */
    public static function send($data, $type = 'html' , $code = 200){
        if(!$data){
            $code = 400;
            $data = [];
            $data['status'] =$code;
        } 
        switch ($type) {
            case 'json':
                echo static::json($data);
                break;
            case 'html':
                echo static::html($data);
                break;
                
            default:
                echo static::default($data);
                break;
        }
        static::http_response_code($code);
    }

    /**
     * json appropriate response for json data
     *
     * @param object $data
     * @return object json encoded
     */
    public static function json($data){
        header('Content-Type: application/json');
        header('Accept: application/json');
        return  json_encode($data);
    }

    /**
     * html appropriate response for html data
     * @param string $htmlFile relative path from the public folder of the html file to read
     * @return string content of file
     */
    public static function html($htmlFile){
        global $PUBLIC_PATH;
        header('Content-Type: text/html; charset=utf-8');
        header('Accept: text/html');
        return file_get_contents($PUBLIC_PATH . $htmlFile. '.html' , 'html');
    }

    /**
     * default response headers plain text
     *
     * @param mixed $data
     * @return void
     */
    public static function default($data){
        header('Content-Type: text/plain');
        return $data;
    }

    /**
     * Change the headers from a response code
     * @param [int] $code Response code
     * @return void
     */
    public static function http_response_code($code = null){
        if ($code !== null) {
            switch ($code) {
                case 100:
                    $text = 'Continue';
                    break;
                case 101:
                    $text = 'Switching Protocols';
                    break;
                case 200:
                    $text = 'OK';
                    break;
                case 201:
                    $text = 'Created';
                    break;
                case 202:
                    $text = 'Accepted';
                    break;
                case 203:
                    $text = 'Non-Authoritative Information';
                    break;
                case 204:
                    $text = 'No Content';
                    break;
                case 205:
                    $text = 'Reset Content';
                    break;
                case 206:
                    $text = 'Partial Content';
                    break;
                case 300:
                    $text = 'Multiple Choices';
                    break;
                case 301:
                    $text = 'Moved Permanently';
                    break;
                case 302:
                    $text = 'Moved Temporarily';
                    break;
                case 303:
                    $text = 'See Other';
                    break;
                case 304:
                    $text = 'Not Modified';
                    break;
                case 305:
                    $text = 'Use Proxy';
                    break;
                case 400:
                    $text = 'Bad Request';
                    break;
                case 401:
                    $text = 'Unauthorized';
                    break;
                case 402:
                    $text = 'Payment Required';
                    break;
                case 403:
                    $text = 'Forbidden';
                    break;
                case 404:
                    $text = 'Not Found';
                    break;
                case 405:
                    $text = 'Method Not Allowed';
                    break;
                case 406:
                    $text = 'Not Acceptable';
                    break;
                case 407:
                    $text = 'Proxy Authentication Required';
                    break;
                case 408:
                    $text = 'Request Time-out';
                    break;
                case 409:
                    $text = 'Conflict';
                    break;
                case 410:
                    $text = 'Gone';
                    break;
                case 411:
                    $text = 'Length Required';
                    break;
                case 412:
                    $text = 'Precondition Failed';
                    break;
                case 413:
                    $text = 'Request Entity Too Large';
                    break;
                case 414:
                    $text = 'Request-URI Too Large';
                    break;
                case 415:
                    $text = 'Unsupported Media Type';
                    break;
                case 500:
                    $text = 'Internal Server Error';
                    break;
                case 501:
                    $text = 'Not Implemented';
                    break;
                case 502:
                    $text = 'Bad Gateway';
                    break;
                case 503:
                    $text = 'Service Unavailable';
                    break;
                case 504:
                    $text = 'Gateway Time-out';
                    break;
                case 505:
                    $text = 'HTTP Version not supported';
                    break;
                default:
                    exit('Unknown http status code "' . htmlentities($code) . '"');
                    break;
            }
            $protocol = (isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0');
            header($protocol . ' ' . $code . ' ' . $text);
            $GLOBALS['http_response_code'] = $code;
        } else {
            $code = (isset($GLOBALS['http_response_code']) ? $GLOBALS['http_response_code'] : 200);
        }
        return $code;
    }

}
