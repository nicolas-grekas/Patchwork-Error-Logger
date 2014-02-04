<?php // vi: set fenc=utf-8 ts=4 sw=4 et:
/*
 * Copyright (C) 2014 Nicolas Grekas - p@tchwork.com
 *
 * This library is free software; you can redistribute it and/or modify it
 * under the terms of the (at your option):
 * Apache License v2.0 (http://apache.org/licenses/LICENSE-2.0.txt), or
 * GNU General Public License v2.0 (http://gnu.org/licenses/gpl-2.0.txt).
 */

namespace Patchwork\Bundle\PhpDumperBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class PatchworkPhpDumperBundle extends Bundle
{
    public function boot()
    {
        parent::boot();

        $container = $this->container;

        set_dump_handler(function ($v) use ($container)
        {
            return $container->get('patchwork.dumper')->walk($v);
        });
    }
}
