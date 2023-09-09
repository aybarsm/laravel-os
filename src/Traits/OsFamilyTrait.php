<?php

namespace Aybarsm\Laravel\Os\Traits;

use Aybarsm\Laravel\Support\Traits\Configurable;

trait OsFamilyTrait
{
    use Configurable;

    public function host(string $key = '', mixed $default = null): mixed
    {
        $key = ! empty($key) ? "host.{$key}" : 'host';
        return $this->config()->get($key, $default);
    }
}
