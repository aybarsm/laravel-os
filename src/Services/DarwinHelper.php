<?php

namespace Aybarsm\Laravel\Os\Services;

use Aybarsm\Laravel\Os\Traits\OsFamilyHelperTrait;
use Aybarsm\Laravel\Support\Enums\SemVerScope;
use Aybarsm\Laravel\Support\Supplements\Str\SemVer;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class DarwinHelper
{
    use OsFamilyHelperTrait;
    protected static array $hostEntries = [
        'os.kernel.name' => 'SPSoftwareDataType.kernel_version',
        'os.kernel.version' => 'SPSoftwareDataType.kernel_version',
        'os.hostName' => 'SPSoftwareDataType.local_host_name',
        'os.name' => 'SPSoftwareDataType.os_version',
        'os.version' => 'SPSoftwareDataType.os_version',
        'os.build' => 'SPSoftwareDataType.os_version',
        'hardware.chip' => 'SPHardwareDataType.chip_type',
        'hardware.model' => 'SPHardwareDataType.machine_model',
        'hardware.modelNumber' => 'SPHardwareDataType.model_number',
        'hardware.memory' => 'SPHardwareDataType.physical_memory',
    ];
    protected static array $versionNames = [
        '14' => 'Sonoma', '13' => 'Ventura', '12' => 'Monterey', '11' => 'Big Sur',
        '10.15' => 'Catalina', '10.14' => 'Mojave', '10.13' => 'High Sierra',
        '10.12' => 'Sierra', '10.11' => 'El Capitan', '10.10' => 'Yosemite',
        '10.9' => 'Mavericks', '10.8' => 'Mountain Lion', '10.7' => 'Lion',
        '10.6' => 'Snow Leopard', '10.5' => 'Leopard', '10.4' => 'Tiger',
        '10.3' => 'Panther', '10.2' => 'Jaguar', '10.1' => 'Puma', '10.0' => 'Cheetah',
    ];

    public static function buildHostEntries(string $json): array
    {
        if (! Str::isJson($json)){
            return [];
        }

        $hostInfo = Arr::map(json_decode($json, true), fn ($val, $key) => Arr::isAssoc($val) ? $val : (Arr::isAssoc($val[0]) ? $val[0] : []));
        $hostInfo = static::getStandardisedHostInfo($hostInfo);
        $hostEntries = static::getStandardisedHostEntries(self::$hostEntries);
        $entries = [];

        foreach($hostEntries as $target => $search){
            $info = self::resolveHostInfo($hostInfo, $search);

            if (is_null($info) || $info->isEmpty()){
                continue;
            }

            $info = match($target){
                'os.kernel.name', 'os.name' => $info->before(' '),
                'os.kernel.version' => $info->afterLast(' '),
                'os.version' => $info->between(' ', ' '),
                'os.build' => $info->afterLast(' ')->between('(', ')'),
                default => $info
            };

            if ($info->isEmpty()){
                continue;
            }

            $entries[$target] = $info->value();
        }

        if (! is_null($versionName = self::getVersionName($entries['os.version']))){
            $entries['os.versionName'] = $versionName;
        }

        return static::getStandardisedEntries($entries);
    }
    protected static function getVersionName(string $ver): ?string
    {
        if (! SemVer::validate($ver)){
            return null;
        }

        $ver = SemVer::make($ver);
        $major = $ver->getScope(SemVerScope::MAJOR, false);
        $minor = $ver->getScope(SemVerScope::MINOR, false);

        return (self::$versionNames[$major] ?? (self::$versionNames["{$major}.{$minor}"]) ?? null);
    }
}
