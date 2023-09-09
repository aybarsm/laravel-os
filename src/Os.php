<?php

namespace Aybarsm\Laravel\Os;

use Aybarsm\Laravel\Os\Concretes\OsDarwin;
use Aybarsm\Laravel\Os\Concretes\OsLinux;
use Aybarsm\Laravel\Os\Concretes\OsWindows;
use Aybarsm\Laravel\Os\Contracts\OsInterface;
use Aybarsm\Laravel\Support\Enums\ProcessReturnType;
use Illuminate\Process\Factory as ProcessFactory;
use Illuminate\Support\Str;
use Illuminate\Support\Traits\Conditionable;
use Illuminate\Support\Traits\Macroable;

abstract class Os implements OsInterface
{
    use Conditionable, Macroable;

    public function __construct() {
        $this->setHost();
    }

    abstract public function host(string $key = '', mixed $default = null): mixed;
    abstract protected function setHost(): void;

    public function cli(string|array $cmd, array $options = [], $returnAs = ProcessReturnType::INSTANCE): object|bool|string
    {
        $pendingProcess = (new ProcessFactory())->newPendingProcess();
        foreach ($options as $method => $args) {
            if ($method === 'run') {
                continue;
            }
            $args = [$args];
            $pendingProcess->{$method}(...$args);
        }

        return process_return($pendingProcess->run($cmd), $returnAs);
    }

    public function family(int $case = null): string
    {
        return str_case(PHP_OS_FAMILY, $case);
    }

    public function isFamily(string $name): bool
    {
        return $this->family(CASE_LOWER) === Str::lower($name);
    }

    public function uname($mode = 'a'): string
    {
        return php_uname($mode);
    }

    public function hostName(): string
    {
        return $this->uname('n');
    }

    public function release(): string
    {
        return $this->uname('r');
    }

    public function version(): string
    {
        return $this->uname('v');
    }

    public function arch(): string
    {
        return $this->uname('m');
    }
}
