<?php
namespace Netsensia\GolfingRecord\Api\Client;

use Netsensia\GolfingRecord\Api\Client\Traits\HttpClient;

class Client
{
    use HttpClient;
    
    public function getUserDetails($id)
    {
        return $this->simpleGet('/v1/users/' . $id);
    }
    
    public function getUserDetailsFromOAuthId($id, $provider)
    {
        return $this->simpleGet('/v1/oauth/users/' . $id . '/' . $provider);
    }
    
    public function getUserCourses($id)
    {
        return $this->simpleGet('/v1/users/' . $id . '/courses');
    }
    
    public function getUserFriends($id)
    {
        return $this->simpleGet('/v1/users/' . $id . '/friends');
    }

    public function getUserFriendCourses($id)
    {
        return $this->simpleGet('/v1/users/' . $id . '/friends/courses');
    }
    
    public function getDiagnostics()
    {
        return $this->simpleGet('/v1/diagnostics');
    }
    
    public function getTees()
    {
        return $this->simpleGet('/v1/tees');
    }
    
    public function courseSearch($q)
    {
        return $this->simpleGet('/v1/search/courses?q=' . $q);
    }

    public function createUserFriend($id, array $details)
    {
        return $this->simpleCreate('/v1/users/' . $id . '/friends', $details);
    }

    public function createUser(array $details)
    {
        return $this->simpleCreate('/v1/users', $details);
    }

    public function createCourse($id, array $details)
    {
        $ret = $this->simpleCreate('/v1/users/' . $id . '/courses', $details);
        return $ret;
    }
}
