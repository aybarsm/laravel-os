<?php

namespace Aybarsm\Laravel\Os\Concretes;

use Aybarsm\Laravel\Os\Os;
use Aybarsm\Laravel\Os\Services\DarwinHelper;
use Aybarsm\Laravel\Os\Traits\OsFamilyTrait;
use Aybarsm\Laravel\Support\Enums\ProcessReturnType;
use Aybarsm\Laravel\Support\Enums\SemVerScope;
use Aybarsm\Laravel\Support\Supplements\Str\SemVer;
use Illuminate\Support\Arr;

class OsDarwin extends Os
{
    use OsFamilyTrait;

    public function setHost(): void
    {
        $json = parent::cli('system_profiler -json SPSoftwareDataType SPHardwareDataType', [], ProcessReturnType::OUTPUT);
        $entries = DarwinHelper::buildHostEntries($json);

        if (empty($entries)) {
            return;
        }

        $this->config()->set('host', $entries);
    }
}
