<?php
namespace spec\Netsensia\GolfingRecord\Api\Client;

include "spec/SpecHelper.php";

use PhpSpec\ObjectBehavior;

class ClientSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Netsensia\GolfingRecord\Api\Client\Client');
    }

    function it_can_get_the_list_of_tees()
    {
        $this->beConstructedWith(config('API_URI'), config('API_ADMIN_KEY'));
        
        $this->getTees()->shouldBeAnArrayWithItemCount(9);
    }

    function it_can_create_and_retrieve_user_courses()
    {
        $pagination = PHP_INT_MAX;

        $this->beConstructedWith(config('API_URI'), config('API_USER_KEY'));

        $name = time();
        
        $courseCount = count($this->getUserCourses(config('USER_ID'))->getWrappedObject()->data);
        
        $this->createCourse(config('USER_ID'), getCourseData($name))->shouldBeAnObjectContainingKeyAndValue('name', $name);
        $this->getUserCourses(config('USER_ID'))->shouldBeAResultSetWithItemCount($courseCount + 1 < $pagination ? $courseCount + 1 : $pagination);
        $this->createCourse(config('USER_ID'), getCourseData(++$name))->shouldBeAnObjectContainingKeyAndValue('name', $name);
        $this->createCourse(config('USER_ID'), getCourseData(++$name))->shouldBeAnObjectContainingKeyAndValue('name', $name);
        $this->getUserCourses(config('USER_ID'))->shouldBeAResultSetWithItemCount($courseCount + 3 < $pagination ? $courseCount + 3 : $pagination);
    }
    
    function it_can_search_for_courses()
    {
        $this->beConstructedWith(config('API_URI'), config('API_USER_KEY'));
        
        $name = uniqid('course_search', true);

        $this->courseSearch($name)->shouldBeAnArrayWithItemCount(0);
        $this->createCourse(config('USER_ID'), getCourseData($name))->shouldBeAnObjectContainingKeyAndValue('name', $name);
        
        // give a chance for the course to be indexed by Elasticsearch
        sleep(2);
        $this->courseSearch($name)->shouldBeAnArrayWithItemCount(1);
        
    }
    
    function it_can_create_a_user_friend_relationship()
    {
        $this->beConstructedWith(config('API_URI'), config('API_ADMIN_KEY'));
        
        $name = time();
        $user1Details = $this->createUser(['realname' => $name, 'oauth_id' => md5($name), 'oauth_provider' => 'test'])->getWrappedObject();
        $user2Details = $this->createUser(['realname' => $name, 'oauth_id' => md5($name), 'oauth_provider' => 'test'])->getWrappedObject();
        
        $this->createUserFriend($user1Details->id, ['friend_id' => $user2Details->id, 'access_level' => 2])->shouldBeAnObjectContainingKeyAndValue('status', 'created');
        $this->createUserFriend($user1Details->id, ['friend_id' => $user2Details->id, 'access_level' => 1])->shouldBe(false);
    }
    
    function it_can_create_and_retrieve_user_friends()
    {
        $pagination = PHP_INT_MAX;

        $this->beConstructedWith(config('API_URI'), config('API_USER_KEY'));

        $name = time();

        $courseCount = count($this->getUserCourses(config('USER_ID'))->getWrappedObject()->data);

        $this->createCourse(config('USER_ID'), getCourseData($name))->shouldBeAnObjectContainingKeyAndValue('name', $name);
        $this->getUserCourses(config('USER_ID'))->shouldBeAResultSetWithItemCount($courseCount + 1 < $pagination ? $courseCount + 1 : $pagination);
        $this->createCourse(config('USER_ID'), getCourseData(++$name))->shouldBeAnObjectContainingKeyAndValue('name', $name);
        $this->createCourse(config('USER_ID'), getCourseData(++$name))->shouldBeAnObjectContainingKeyAndValue('name', $name);
        $this->getUserCourses(config('USER_ID'))->shouldBeAResultSetWithItemCount($courseCount + 3 < $pagination ? $courseCount + 3 : $pagination);
    }

    function it_will_return_diagnostics_and_they_will_be_good()
    {
        $this->beConstructedWith(config('API_URI'), config('API_ADMIN_KEY'));

        $this->getDiagnostics()->shouldBeAnObjectContainingKeyAndValue('ok', true);
    }

    function it_can_get_user_details_from_an_id_using_a_user_token()
    {
        $this->beConstructedWith(config('API_URI'), config('API_USER_KEY'));
        $this->getUserDetails(config('USER_ID'))->shouldBeAnObjectContainingKeyAndValue('id', config('USER_ID'));
    }
    
    function it_can_get_user_details_from_an_oauth_id_and_provider_using_an_admin_token()
    {
        $this->beConstructedWith(config('API_URI'), config('API_ADMIN_KEY'));
        $this->getUserDetailsFromOAuthId(config('OAUTH_ID'), 'golfingrecord')->shouldBeAnObjectContainingKeyAndValue('id', config('USER_ID'));
    }

    function it_can_get_user_details_from_an_id_using_an_admin_token()
    {
        $this->beConstructedWith(config('API_URI'), config('API_ADMIN_KEY'));
        $this->getUserDetails(config('USER_ID'))->shouldBeAnObjectContainingKeyAndValue('id', config('USER_ID'));
    }

    function it_can_create_a_user()
    {
        $this->beConstructedWith(config('API_URI'), config('API_ADMIN_KEY'));
        $name = time();
        $this->createUser(['realname' => $name, 'oauth_id' => md5($name), 'oauth_provider' => 'test'])->shouldBeAnObjectContainingKeyAndValue('realname', $name);
    }
    
    function it_can_get_a_list_of_friend_courses()
    {
        $this->beConstructedWith(config('API_URI'), config('API_ADMIN_KEY'));
        
        $name = time();
        $user1Details = $this->createUser(['realname' => $name, 'oauth_id' => md5($name), 'oauth_provider' => 'test'])->getWrappedObject();
        $user2Details = $this->createUser(['realname' => $name, 'oauth_id' => md5($name), 'oauth_provider' => 'test'])->getWrappedObject();
        $user3Details = $this->createUser(['realname' => $name, 'oauth_id' => md5($name), 'oauth_provider' => 'test'])->getWrappedObject();
        $user4Details = $this->createUser(['realname' => $name, 'oauth_id' => md5($name), 'oauth_provider' => 'test'])->getWrappedObject();
        
        $this->createUserFriend($user1Details->id, ['friend_id' => $user2Details->id, 'access_level' => 2])->shouldBeAnObjectContainingKeyAndValue('status', 'created');
        $this->createUserFriend($user1Details->id, ['friend_id' => $user3Details->id, 'access_level' => 2])->shouldBeAnObjectContainingKeyAndValue('status', 'created');
        
        $this->createCourse($user2Details->id, getCourseData($name))->shouldBeAnObjectContainingKeyAndValue('name', $name);
        $this->createCourse($user2Details->id, getCourseData($name+1))->shouldBeAnObjectContainingKeyAndValue('name', $name+1);
        $this->createCourse($user3Details->id, getCourseData($name+2))->shouldBeAnObjectContainingKeyAndValue('name', $name+2);
        $this->createCourse($user4Details->id, getCourseData($name+3))->shouldBeAnObjectContainingKeyAndValue('name', $name+3);
        
        $this->getUserFriendCourses($user1Details->id)->shouldBeAnArrayWithItemCount(3);
        $this->getUserFriendCourses($user1Details->id)->shouldBeAnArrayWithValues('name', [$name, $name+1, $name+2]);
    }

    public function getMatchers()
    {
        return [
            'beAnObjectContainingKeyAndValue' => function ($subject, $key, $value) {
                return !empty($subject) && property_exists($subject, $key) && $subject->$key == $value;
            },
            'beAResultSetWithItemCount' => function ($subject, $count) {
                return !empty($subject) && property_exists($subject, 'data') && count($subject->data) == $count;
            },
            'beAnArrayWithItemCount' => function ($subject, $count) {
                if (empty($subject) && $count == 0) {
                    return true;
                }
                return !empty($subject) && is_array($subject) && count($subject) == $count;
            },
            'beAnArrayWithValues' => function ($subject, $key, $values) {
                if (!empty($subject) && is_array($subject) && count($subject) == count($values)) {
                    foreach ($subject as $item) {
                        if (!in_array($item->$key, $values)) {
                            return false;
                        }
                    }
                    return true;
                }
                
                return false;
            }
        ];
    }
    
    
}
