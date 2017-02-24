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
    
    function it_can_get_user_details_from_an_id()
    {
        $this->beConstructedWith(config('API_KEY'));
        $this->getUserDetails(config('USER_ID'))->shouldBeAnObjectContainingKeyAndValue('id', config('USER_ID'));
    }

    public function getMatchers()
    {
        return [
            'beAnObjectContainingKeyAndValue' => function ($subject, $key, $value) {
                return !empty($subject) && property_exists($subject, $key) && $subject->$key == $value;
            }
        ];
    }
}
