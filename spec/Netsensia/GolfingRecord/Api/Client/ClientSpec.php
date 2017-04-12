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
    
    function it_can_create_a_user_friend_relationship()
    {
        $this->beConstructedWith(config('API_URI'), config('API_ADMIN_KEY'));
        
        $name = time();
        $userDetails = $this->createUser(['realname' => $name, 'oauth_id' => md5($name), 'oauth_provider' => 'test'])->getWrappedObject();
        
        $this->createUserFriend($userDetails->id, ['friend_id' => config('USER_ID'), 'access_level' => 2])->shouldBeAnObjectContainingKeyAndValue('status', 'created');
        $this->createUserFriend($userDetails->id, ['friend_id' => config('USER_ID'), 'access_level' => 1])->shouldBe(false);
        
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
    
    function it_can_create_and_retrieve_user_courses()
    {
        $this->beConstructedWith(config('API_URI'), config('API_USER_KEY'));
    
        $name = time();
    
        $courseCount = count($this->getUserCourses(config('USER_ID'))->getWrappedObject()->data);
    
        $this->createCourse(config('USER_ID'), ['course_name' => $name, 'number_of_holes' => 18])->shouldBeAnObjectContainingKeyAndValue('course_name', $name);
        $this->getUserCourses(config('USER_ID'))->shouldBeAResultSetWithItemCount($courseCount + 1);
        $this->createCourse(config('USER_ID'), ['course_name' => ++$name, 'number_of_holes' => 18])->shouldBeAnObjectContainingKeyAndValue('course_name', $name);
        $this->createCourse(config('USER_ID'), ['course_name' => ++$name, 'number_of_holes' => 18])->shouldBeAnObjectContainingKeyAndValue('course_name', $name);
        $this->getUserCourses(config('USER_ID'))->shouldBeAResultSetWithItemCount($courseCount + 3);
    }
    
    function it_can_create_and_retrieve_user_friends()
    {
        $this->beConstructedWith(config('API_URI'), config('API_USER_KEY'));
    
        $name = time();
    
        $courseCount = count($this->getUserCourses(config('USER_ID'))->getWrappedObject()->data);
    
        $this->createCourse(config('USER_ID'), ['course_name' => $name, 'number_of_holes' => 18])->shouldBeAnObjectContainingKeyAndValue('course_name', $name);
        $this->getUserCourses(config('USER_ID'))->shouldBeAResultSetWithItemCount($courseCount + 1);
        $this->createCourse(config('USER_ID'), ['course_name' => ++$name, 'number_of_holes' => 18])->shouldBeAnObjectContainingKeyAndValue('course_name', $name);
        $this->createCourse(config('USER_ID'), ['course_name' => ++$name, 'number_of_holes' => 18])->shouldBeAnObjectContainingKeyAndValue('course_name', $name);
        $this->getUserCourses(config('USER_ID'))->shouldBeAResultSetWithItemCount($courseCount + 3);
    
    }
    
    public function getMatchers()
    {
        return [
            'beAnObjectContainingKeyAndValue' => function ($subject, $key, $value) {
                return !empty($subject) && property_exists($subject, $key) && $subject->$key == $value;
            },
            'beAResultSetWithItemCount' => function ($subject, $count) {
                return !empty($subject) && property_exists($subject, 'data') && count($subject->data) == $count;
            }
        ];
    }
    
    
}
