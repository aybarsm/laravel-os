<?php

namespace Aybarsm\Laravel\Os\Services;

use Aybarsm\Laravel\Os\Traits\OsFamilyHelperTrait;
use Dotenv\Dotenv;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Stringable;

class LinuxHelper
{
    use OsFamilyHelperTrait;
    protected static array $hostEntries = [
        'os.name' => 'NAME',
        'os.prettyName' => 'PRETTY_NAME',
        'os.versionId' => 'VERSION_ID',
        'os.version' => 'VERSION',
        'os.versionCodename' => ['VERSION_CODENAME', 'UBUNTU_CODENAME'],
        'os.id' => 'ID',
        'os.idLike' => 'ID_LIKE',
    ];


    public static function buildHostEntries(array $array): array
    {
        if (empty($array)){
            return [];
        }

        $hostInfo = static::getStandardisedHostInfo($array);
        $hostEntries = static::getStandardisedHostEntries(self::$hostEntries);
        $entries = [];

        foreach($hostEntries as $target => $search){
            $info = self::resolveHostInfo($hostInfo, $search);

            if (is_null($info) || $info->isEmpty()){
                continue;
            }

            $entries[$target] = $info->value();
        }

        return static::getStandardisedEntries($entries);
    }

    public static function parseReleaseFile(string $path): array
    {
        if (! File::isReadable($path)){
            return [];
        }

        try {
            return Dotenv::parse(File::get($path));
        } catch (Exception $e) {
            return [];
        }
    }
}
