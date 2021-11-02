<?php


namespace App\Service;

use Symfony\Component\Security\Core\Security;

class SecurityService
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function userAuth()
    {
        // returns User object or null if not authenticated
        $user = $this->security->getUser();
    }
}