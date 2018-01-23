<?php

namespace ItDevgroup\CommandBus\Tests;

use Mockery;
use PHPUnit_Framework_TestCase;
use ItDevgroup\CommandBus\CommandBus;
use ItDevgroup\CommandBus\LaravelContainer;
use ItDevgroup\CommandBus\NameInflector;

class CommandBusTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var CommandBus
     */
    private $commandBus;

    public function setUp()
    {
        $registerUserHandlerMock = Mockery::mock(RegisterUserHandler::class);
        $registerUserHandlerMock->shouldReceive('handle');

        $containerMock = Mockery::mock(LaravelContainer::class);
        $containerMock->shouldReceive('make')->once()->andReturn($registerUserHandlerMock);

        $this->commandBus = new CommandBus($containerMock, new NameInflector());
    }

    /**
     * @test
     */
    public function should_call_method_handle_from_bus()
    {
        $this->assertEquals($this->commandBus->execute(new RegisterUser()), null);
    }
}
