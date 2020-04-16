<?php

namespace App\Tests\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\BlogPost;
use App\Entity\User;
use App\EventSubscriber\AuthoredEntitySubscriber;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * Class AuthoredEntitySubscriberTest
 * @package App\Tests\EventSubscriber
 */
class AuthoredEntitySubscriberTest extends TestCase
{
    public function testConfiguration()
    {
        $result = AuthoredEntitySubscriber::getSubscribedEvents();

        $this->assertArrayHasKey(KernelEvents::VIEW, $result);

        $this->assertEquals(
            ['getAuthenticatedUser', EventPriorities::PRE_WRITE],
            $result[KernelEvents::VIEW]
        );
    }

    /**
     * @param string $className
     * @param bool $shouldCallSetAtuhor
     * @param string $method
     *
     * Removing "Final" from ViewEvent class to pass the test
     * Lower version than symfony 5 have class GetResponseForControllerResultEvent
     * instead of ViewEvent. That class was not the final.
     *
     * @dataProvider providerSetAuthorCall
     */
    public function testSetAuthorCall(string $className, bool $shouldCallSetAtuhor, string  $method)
    {
        $entityMock = $this->getEntityMock($className, $shouldCallSetAtuhor);

        $eventMock = $this->getEventMock($method, $entityMock);

        $tokenStorageMock = $this->getTokenStorageMock();

        (new AuthoredEntitySubscriber($tokenStorageMock))->getAuthenticatedUser($eventMock);
    }


    /**
     * @return array|array[]
     */
    public function providerSetAuthorCall(): array
    {
        return [
            [BlogPost::class, true, 'POST'],
            [BlogPost::class, false, 'GET'],
            ['NonExisting', false, 'POST'],
        ];
    }


    public function testNoTokenPresent()
    {
        $eventMock = $this->getEventMock('POST', new class {});

        $tokenStorageMock = $this->getTokenStorageMock(false);

        (new AuthoredEntitySubscriber($tokenStorageMock))->getAuthenticatedUser($eventMock);
    }

    /**
     * @param bool $hasToken
     * @return MockObject|TokenStorageInterface
     */
    private function getTokenStorageMock(bool $hasToken = true): MockObject
    {
        $tokenMock = $this->getMockBuilder(TokenInterface::class)
            ->getMockForAbstractClass();

        $tokenMock->expects($hasToken ? $this->once() : $this->never())
            ->method('getUser')
            ->willReturn(new User());

        $tokenStorageMock = $this->getMockBuilder(TokenStorageInterface::class)
            ->getMockForAbstractClass();

        $tokenStorageMock->expects($this->once())
            ->method('getToken')
            ->willReturn($hasToken ? $tokenMock : null);

        return $tokenStorageMock;
    }

    /**
     * @param string $method
     * @param $controllerResult
     * @return MockObject|ViewEvent
     */
    private function getEventMock(string $method, $controllerResult): MockObject
    {
        $requestMock = $this->getMockBuilder(Request::class)
            ->getMock();

        $requestMock->expects($this->once())
            ->method('getMethod')
            ->willReturn($method);

        $eventMock = $this->getMockBuilder(ViewEvent::class)
            ->disableOriginalConstructor()
            ->getMock();

        $eventMock->expects($this->once())
            ->method('getControllerResult')
            ->willReturn($controllerResult);

        $eventMock->expects($this->once())
            ->method('getRequest')
            ->willReturn($requestMock);

        return $eventMock;
    }

    /**
     * @param string $className
     * @param bool $shouldCallSetAtuhor
     * @return MockObject
     */
    private function getEntityMock(string $className, bool $shouldCallSetAtuhor): MockObject
    {
        $entityMock = $this->getMockBuilder($className)
            ->setMethods(['setAuthor'])
            ->getMock();
        $entityMock->expects($shouldCallSetAtuhor ? $this->once() : $this->never())
            ->method('setAuthor');
        return $entityMock;
    }
}