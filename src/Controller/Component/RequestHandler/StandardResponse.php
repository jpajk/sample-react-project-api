<?php

namespace App\Controller\Component\RequestHandler;

use App\Model\Entity\User;
use JsonSerializable;

abstract class StandardResponse implements JsonSerializable
{
    /** @var User $user */
    private $user;

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }
}
