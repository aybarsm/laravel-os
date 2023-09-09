<?php

namespace Aybarsm\Laravel\Os\Contracts;

interface PackageManagerInterface
{
    public function install(string|array $packages): void;
    // Substitute for install
    public function uninstall(string|array $packages): void;
    // Substitute for install
    public function add(string|array $packages): void;
    // Substitute for uninstall
    public function delete(string|array $packages): void;
    // Substitute for uninstall
    public function remove(string|array $packages): void;
    public function search(string|array $packages): void;
    public function list(string|array $packages): void;
}
