<?php

namespace Lockate\APIBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserManipulationTest extends WebTestCase
{
    public function testUserCreationIncludingGatewayNodesField() {
        $kernel = self::bootKernel();
        $userManager = $kernel->getContainer()
            ->get('fos_user.user_manager');
        $user = $userManager->createUser();
        $user->setUsername('loco');
        $user->setEmail('p@e.pe');
        $user->setPlainPassword('loco');
        $user->setEnabled(true);
        $user->setUserGatewaySensors('{"hello"}');
        $userManager->updateUser($user);
    }
}