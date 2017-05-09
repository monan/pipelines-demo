<?php

namespace NickVeenhof\Hmac;

interface KeyLoaderInterface
{
    /**
     * @param string $id
     *
     * @return \NickVeenhof\Hmac\KeyInterface|false
     */
    public function load($id);
}
