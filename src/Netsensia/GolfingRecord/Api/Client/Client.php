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
    
    public function getCourse($id)
    {
        return $this->simpleGet('/v1/courses/' . $id);
    }
    
    public function getUserFriends($id)
    {
        return $this->simpleGet('/v1/users/' . $id . '/friends');
    }
    
    public function getUserFriend($id, $friendId)
    {
        return $this->simpleGet('/v1/users/' . $id . '/friends/' . $friendId);
    }
    
    public function deleteUserFriend($id, $friendId)
    {
        return $this->simpleDelete('/v1/users/' . $id . '/friends/' . $friendId);
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
    
    public function getStats($id, $courseId = null, $startDate = null, $endDate = null)
    {
        $endpoint = '/v1/users/' . $id . '/statistics';
        if (!empty($courseId)) {
            $endpoint .= '/' . $courseId;
        }
        if (!empty($startDate)) {
            $endpoint .= '?start_date=' . $startDate;
        }
        if (!empty($endDate)) {
            if (strpos($endpoint, '?') !== false) {
                $endpoint .= '&';
            } else {
                $endpoint .= '?';
            }
            $endpoint .= 'end_date=' . $endDate;
        }

        return $this->simpleGet($endpoint);
    }
    
    public function getHandicap($id, $courseId = null)
    {
        $endpoint = '/v1/users/' . $id . '/handicap';
        if (!empty($courseId)) {
            $endpoint .= '/' . $courseId;
        }
        return $this->simpleGet($endpoint);
    }
    
    public function getPayments($id)
    {
        return $this->simpleGet('/v1/users/' . $id . '/payments');
    }
    
    public function createRound($id, $courseId, array $details)
    {
        return $this->simpleCreate('/v1/users/' . $id . '/courses/' . $courseId . '/rounds', $details);
    }
    
    public function getRounds($id, $courseId)
    {
        return $this->simpleGet('/v1/users/' . $id . '/courses/' . $courseId . '/rounds');
    }

    public function getRound($id, $courseId, $roundId)
    {
        return $this->simpleGet('/v1/users/' . $id . '/courses/' . $courseId . '/rounds/' . $roundId);
    }

    public function compareRoundWithFriends($id, $courseId, $roundId)
    {
        return $this->simpleGet('/v1/users/' . $id . '/courses/' . $courseId . '/rounds/' . $roundId . '/compare');
    }
    
    public function updateRound($userId, $courseId, $roundId, array $details)
    {
        return $this->simpleUpdate('/v1/users/' . $userId . '/courses/' . $courseId . '/rounds/' . $roundId, $details);
    }
    
    public function deleteRound($userId, $courseId, $roundId)
    {
        return $this->simpleDelete('/v1/users/' . $userId . '/courses/' . $courseId . '/rounds/' . $roundId);
    }

    public function getHandicapSystems()
    {
        return $this->simpleGet('/v1/handicap-systems');
    }
    
    public function getAccessLevels()
    {
        return $this->simpleGet('/v1/access-levels');
    }
    
    public function courseSearch($q)
    {
        return $this->simpleGet('/v1/search/courses?q=' . $q);
    }

    public function createUserFriend($id, array $details)
    {
        return $this->simpleCreate('/v1/users/' . $id . '/friends', $details);
    }

    public function updateUserFriendAccessLevel($id, $friendId, $accessLevel)
    {
        return $this->simpleUpdate('/v1/users/' . $id . '/friends/' . $friendId, [
            'access_level' => $accessLevel
        ]);
    }
    
    public function createUser(array $details)
    {
        return $this->simpleCreate('/v1/users', $details);
    }

    public function createCourse($id, array $details)
    {
        return $this->simpleCreate('/v1/users/' . $id . '/courses', $details);
    }
    
    public function updateCourse($userId, $courseId, array $details)
    {
        return $this->simpleUpdate('/v1/users/' . $userId . '/courses/' . $courseId, $details);
    }
    
    public function deleteCourse($userId, $courseId)
    {
        return $this->simpleDelete('/v1/users/' . $userId . '/courses/' . $courseId);
    }
}
