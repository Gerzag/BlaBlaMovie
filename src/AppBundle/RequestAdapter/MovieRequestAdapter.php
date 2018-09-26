<?php

namespace AppBundle\RequestAdapter;

use AppBundle\Entity\UserChoice;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class MovieRequestAdapter
 * @package AppBundle\RequestAdapter
 */
class MovieRequestAdapter
{
    /**
     * @param Request $request
     *
     * @return UserChoice
     */
    public function getUserChoice(Request $request)
    {

    }
}