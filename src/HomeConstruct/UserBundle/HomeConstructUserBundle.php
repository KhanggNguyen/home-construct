<?php

namespace HomeConstruct\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class HomeConstructUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
