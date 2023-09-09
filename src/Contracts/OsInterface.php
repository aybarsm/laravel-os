<?php

namespace Aybarsm\Laravel\Os\Contracts;

use Aybarsm\Laravel\Support\Enums\ProcessReturnType;

interface OsInterface
{
    public function host(string $key = '', mixed $default = null): mixed;
    public function cli(string|array $cmd, array $options = [], $returnAs = ProcessReturnType::INSTANCE): object|bool|string;
    public function family(int $case = null): string;
    public function isFamily(string $name): bool;
    public function uname($mode = 'a'): string;
    public function hostName(): string;
    public function release(): string;
    public function version(): string;
    public function arch(): string;
}
