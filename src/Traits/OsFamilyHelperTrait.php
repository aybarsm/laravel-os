<?php

namespace Aybarsm\Laravel\Os\Traits;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Stringable;

trait OsFamilyHelperTrait
{
    protected static function resolveHostInfo(array $info, string|array $search): ?Stringable
    {
        $search = Arr::wrap($search);
        return Arr::first($info, fn ($val, $key) => in_array($key, $search));
    }
    protected static function getStandardisedEntries(array $entries): array
    {
        return Arr::undot($entries);
    }
    protected static function getStandardisedHostInfo(array $hostInfo): array
    {
        return array_change_key_case(Arr::map(Arr::dot(Arr::undot($hostInfo)), fn ($val, $key): Stringable => str($val)->squish()));
    }

    protected static function getStandardisedHostEntries(array $hostEntries): array
    {
        return Arr::map($hostEntries, fn ($val, $key): string|array => is_string($val) ? Str::lower($val) : Arr::map($val, fn ($valD, $keyD): string => Str::lower($valD)));
    }
}
