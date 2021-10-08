<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    /** @var UserPasswordEncoderInterface $encoder */
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $users = [
            ['email' => 'admin@admin.fr', 'username' => 'Elrond', 'password' => 'admin', 'role' => 'ADMIN'],
            ['email' => 'user@user.fr', 'username' => 'Arwenn', 'password' => 'user1', 'role' => 'USER'],
        ];

        foreach ($users as $u) {
            $user = new User();
            $user->setEmail($u['email']);
            $user->setUsername($u['username']);
            $user->setActivate(true);
            $user->setRole($u['password']);

            $password = $this->encoder->encodePassword($user, $u['password']);
            $user->setPassword($password);

            $manager->persist($user);
        }

        $manager->flush();
    }
}
