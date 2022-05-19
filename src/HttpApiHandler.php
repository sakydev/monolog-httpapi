<?php
namespace sakydev\HttpApiHandler;

use Exception;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;
use WpOrg\Requests\Requests;
use Monolog\LogRecord;

/**
 * HTTP API Handler For Monolog
 *
 * This class allows you to send your logs to an external API 
 *
 * @author Saqib Razzaq <saqibrzzaq@gmail.com>
 */

class HttpApiHandler extends AbstractProcessingHandler
{
    public function __construct($apiUrl, $channel, $headers = ['Content-Type: application/json']) {
        // Requests will be sent to apiUrl/{loglevel}/{channel}
        $this->apiUrl = $apiUrl;
        $this->channel = $channel;
        $this->headers = $headers;
    }

    /**
     * format the log to send
     * @param $record[] log data
     * @return void
     */
    public function write(array $record): void
    {
        // If your api wants output data as a string, uncomment below lines

        // $format = new LineFormatter;
        // $context = $record['context'] ? $format->stringify($record['context']) : '';

        // If you are sending to Kibana or something similar, it is better to send data as
        // object so it is decodeable and makes more sense
        $message = ['message' => $record['message'], 'extra' => $record['extra']];
        $this->send($record['level_name'], array_merge($message, $record['context']), $this->channel);
    }

    /**
    * Send error messages to Logstash
    * @author Saqib Razzaq
    * @return void
    */
    public function send($level, $message, $channel = 'default-channel') : void {
        try {
            $url = sprintf("%s/%s/%s", $this->apiUrl, strtoupper($level), $channel);

            $response = Requests::post($url, $this->headers, $message);
            $response->throw_for_status();

            if (empty($response->body)) {
                throw new Exception("Failed to send logs to {$url}");
            }
        } catch (Exception $e) {
            // do what you will with error
        }
    }
}
