<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager)
    {
        $users = [
            ['email' => 'admin@admin.fr', 'username' => 'Elrond', 'password' => 'admin', 'roles' => ['ROLE_ADMIN']],
            ['email' => 'user@user.fr', 'username' => 'Arwenn', 'password' => 'user1', 'roles' => ['ROLE_USER']],
        ];

        foreach ($users as $u) {
            $user = new User();
            $user->setEmail($u['email']);
            $user->setUsername($u['username']);
            $user->setActivate(true);
            $user->setRoles($u['roles']);

            $user->setPassword($this->passwordHasher->hashPassword(
                $user,
                'the_new_password'
            ));

            $this->addReference($u['username'], $user);
            $manager->persist($user);
        }

        $manager->flush();
    }
}
