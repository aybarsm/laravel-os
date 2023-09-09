<?php

namespace Aybarsm\Laravel\Os\Concretes;

use Aybarsm\Laravel\Os\Contracts\PacketManagerInterface;
use Aybarsm\Laravel\Os\Traits\PackageManagerTrait;

class PackageManager implements PacketManagerInterface
{
    use PackageManagerTrait;

    public function install(array|string $packages): void
    {
        // TODO: Implement install() method.
    }

    public function uninstall(array|string $packages): void
    {
        // TODO: Implement uninstall() method.
    }

    public function search(array|string $packages): void
    {
        // TODO: Implement search() method.
    }

    public function list(array|string $packages): void
    {
        // TODO: Implement list() method.
    }
}
