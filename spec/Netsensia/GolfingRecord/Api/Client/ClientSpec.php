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

    function it_can_create_and_retrieve_user_courses()
    {
        $pagination = PHP_INT_MAX;

        $this->beConstructedWith(config('API_URI'), config('API_USER_KEY'));

        $name = time();

        $courseCount = count($this->getUserCourses(config('USER_ID'))->getWrappedObject()->data);

        $this->createCourse(config('USER_ID'), getCourseData($name))->shouldBeAnObjectContainingKeyAndValue('course_name', $name);
        $this->getUserCourses(config('USER_ID'))->shouldBeAResultSetWithItemCount($courseCount + 1 < $pagination ? $courseCount + 1 : $pagination);
        $this->createCourse(config('USER_ID'), getCourseData(++$name))->shouldBeAnObjectContainingKeyAndValue('course_name', $name);
        $this->createCourse(config('USER_ID'), getCourseData(++$name))->shouldBeAnObjectContainingKeyAndValue('course_name', $name);
        $this->getUserCourses(config('USER_ID'))->shouldBeAResultSetWithItemCount($courseCount + 3 < $pagination ? $courseCount + 3 : $pagination);
    }

    function it_can_create_and_retrieve_user_friends()
    {
        $pagination = PHP_INT_MAX;

        $this->beConstructedWith(config('API_URI'), config('API_USER_KEY'));

        $name = time();

        $courseCount = count($this->getUserCourses(config('USER_ID'))->getWrappedObject()->data);

        $this->createCourse(config('USER_ID'), getCourseData($name))->shouldBeAnObjectContainingKeyAndValue('course_name', $name);
        $this->getUserCourses(config('USER_ID'))->shouldBeAResultSetWithItemCount($courseCount + 1 < $pagination ? $courseCount + 1 : $pagination);
        $this->createCourse(config('USER_ID'), getCourseData(++$name))->shouldBeAnObjectContainingKeyAndValue('course_name', $name);
        $this->createCourse(config('USER_ID'), getCourseData(++$name))->shouldBeAnObjectContainingKeyAndValue('course_name', $name);
        $this->getUserCourses(config('USER_ID'))->shouldBeAResultSetWithItemCount($courseCount + 3 < $pagination ? $courseCount + 3 : $pagination);

    }

    function it_will_return_diagnostics_and_they_will_be_good()
    {
        $this->beConstructedWith(config('API_URI'), config('API_ADMIN_KEY'));

        $this->getDiagnostics()->shouldBeAnObjectContainingKeyAndValue('ok', true);
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
