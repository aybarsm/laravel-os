<?php

namespace Aybarsm\Laravel\Os\Traits;

trait PackageManagerTrait
{
    public function add(string|array $packages): void
    {
        $this->install($packages);
    }
    public function remove(string|array $packages): void
    {
        $this->uninstall($packages);
    }

    public function delete(string|array $packages): void
    {
        $this->uninstall($packages);
    }
}
