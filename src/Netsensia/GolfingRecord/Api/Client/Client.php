<?php
namespace Netsensia\GolfingRecord\Api\Client;

use GuzzleHttp\Message\Response;
use Netsensia\GolfingRecord\Api\Client\Traits\HttpClient;

class Client
{
    use HttpClient;
    
    /**
     * User
     * 
     * /user/{id}
     * 
     * @param $id The user id or email
     * 
     * @return boolean|mixed
     */
    public function getUserDetails($id = null)
    {
        if ($id) {
            $endpoint = '/v1/users/' . $id;
        } else {
            $endpoint = '/v1/my-details';
        }
        
        $response = $this->client()->request('GET', $this->apiBaseUri . $endpoint, $this->opts());
    
        if( $response->getStatusCode() != 200 ){
            return $this->log($response, false);
        }
    
        $jsonDecode = json_decode($response->getBody());
    
        $this->log($response, true);
    
        return $jsonDecode;
    }
    
    /**
     * User courses
     *
     * /user/{id}/courses/{courseId}
     *
     * @param $id The user id or email
     *
     * @return boolean|mixed
     */
    public function getUserCourses($id, $courseId = '')
    {
        $response = $this->client()->request('GET', $this->apiBaseUri . '/v1/users/' . $id . '/courses/' . $courseId, $this->opts());
    
        if( $response->getStatusCode() != 200 ){
            return $this->log($response, false);
        }
    
        $jsonDecode = json_decode($response->getBody());
    
        $this->log($response, true);
    
        return $jsonDecode;
    }
}
