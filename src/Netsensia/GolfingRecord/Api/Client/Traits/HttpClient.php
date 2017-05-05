<?php
namespace Netsensia\GolfingRecord\Api\Client\Traits;

use GuzzleHttp\Client as GuzzleClient;

trait HttpClient
{
    /**
     * The base URI for the API
     */
    private $apiBaseUri;
    
    /**
     * 
     * @var GuzzleClient
     */
    private $guzzleClient;
    
    /**
     * 
     * @var string
     */
    private $apiKey;
    
    /**
     * The status code from the last API call
     * 
     * @var number
     */
    private $lastStatusCode;
    
    /**
     * The content body from the last API call
     * 
     * @var string
     */
    private $lastContent;
    
    /**
     * Did the last API call return with an error?
     * 
     * @var boolean
     */
    private $isError;

    /**
     * Create an API client for the given uri endpoint.
     * 
     * @param string $apiKey  The API key
     */
    public function __construct(
        $apiBaseUri = null,
        $apiKey = null
    )
    {
        $this->apiBaseUri = $apiBaseUri;
        $this->apiKey = $apiKey;
    }

    /**
     * Returns the GuzzleClient.
     *
     * @return GuzzleClient
     */
    private function client()
    {

        if( !isset($this->guzzleClient) ){
            $this->guzzleClient = new GuzzleClient(['http_errors' => false]);
        }

        return $this->guzzleClient;

    }
    
    protected function opts($options = []) {
        if (!empty($this->apiKey)) {
            return array_merge(
                $options,
                ['headers' => [
                        'Authorization' => 'Bearer ' . $this->apiKey,
                        'Content-Type' => 'application/json',
                        'Accept' => 'application/json',
                    ]
                ]
            );
        } else {
            return $options;
        }
    }
    
    /**
     * @return the $isError
     */
    public function isError()
    {
        return $this->isError;
    }

    /**
     * @param boolean $isError
     */
    public function setIsError($isError)
    {
        $this->isError = $isError;
    }

    /**
     * @return the $apiKey
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * @param string $apiKey
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * @return the $lastStatusCode
     */
    public function getLastStatusCode()
    {
        return $this->lastStatusCode;
    }

    /**
     * @param number $lastStatusCode
     */
    public function setLastStatusCode($lastStatusCode)
    {
        $this->lastStatusCode = $lastStatusCode;
    }

    /**
     * @return the $lastContent
     */
    public function getLastContent()
    {
        return $this->lastContent;
    }

    /**
     * @param string $lastContent
     */
    public function setLastContent($lastContent)
    {
        $this->lastContent = $lastContent;
    }
    
    public function simpleGet($endpoint)
    {
        $response = $this->client()->request('GET', $this->apiBaseUri . $endpoint, $this->opts());
        
        return $this->checkResponse($response, 200);
    }

    public function simpleCreate($endpoint, $details)
    {
        $response = $this->client()->request('POST', $this->apiBaseUri . $endpoint, $this->opts(['json' => $details]));
        
        return $this->checkResponse($response, 201);
    }
    
    private function checkResponse($response, $expectedCode)
    {
        if ($response->getStatusCode() != $expectedCode) {
            return $this->log($response, false);
        }
        
        $jsonDecode = json_decode($response->getBody());
        
        $this->log($response, true);
        
        return $jsonDecode;
    }
    
    /**
     * Log the response of the API call and set some internal member vars
     * If content body is JSON, convert it to an array
     *
     * @param \GuzzleHttp\Psr7\Response $response
     * @param bool $isSuccess
     * @return boolean
     *
     * @todo - External logging
     */
    public function log(\GuzzleHttp\Psr7\Response $response, $isSuccess=true)
    {
        $this->lastStatusCode = $response->getStatusCode();
    
        $responseBody = (string)$response->getBody();
        $jsonDecoded = json_decode($responseBody, true);
    
        if (json_last_error() == JSON_ERROR_NONE) {
            $this->lastContent = $jsonDecoded;
        } else {
            $this->lastContent = ['error' => 'An unknown error has occurred'];
        }
    
        // @todo - Log properly
        if (!$isSuccess) {
        }
    
        $this->setIsError(!$isSuccess);
    
        return $isSuccess;
    }
    
}
