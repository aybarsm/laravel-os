<?php

namespace Aybarsm\Laravel\Os\Concretes;

use Aybarsm\Laravel\Os\Os;
use Aybarsm\Laravel\Os\Services\LinuxHelper;
use Aybarsm\Laravel\Os\Traits\OsFamilyTrait;
use Aybarsm\Laravel\Support\Traits\Configurable;
use Dotenv\Dotenv;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class OsLinux extends Os
{
    use OsFamilyTrait;

    protected function setHost(): void
    {
        if (File::isReadable('/etc/os-release')){
            if (! $this->tryImportingReleaseFile('/etc/os-release')){
                return;
            }
        }

        $alternatives = array_keys(Arr::except(array_flip(File::glob('/etc/*-release')), ['/etc/os-release']));

        foreach($alternatives as $path){
            if ($this->tryImportingReleaseFile($path)){
                break;
            }
        }
    }

    protected function tryImportingReleaseFile(string $path): bool
    {
        $path = realpath('/etc/os-release');
        $entries = LinuxHelper::buildHostEntries(LinuxHelper::parseReleaseFile($path));

        if (empty($entries)){
            return false;
        }

        $this->config()->set('host', $entries);
        $this->config()->set('host.os.release_file', $path);
        return true;
    }

}
