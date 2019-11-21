<?php

/**
 * Created by VsWeb.
 * Project: symfony-skeleton
 * User: Jul6art
 * Date: 21/11/2019
 * Time: 21:39.
 */

namespace App\Service;

/**
 * Class FileService.
 */
class FileService
{
    /**
     * @param string $directory
     *
     * @return int
     */
    public function getSize($directory = ''): int
    {
        $size = 0;
        foreach (glob(rtrim($directory, '/') . '/*', GLOB_NOSORT) as $item) {
            $size += \is_file($item) ? filesize($item) : $this->getSize($item);
        }

        return $size;
    }

    /**
     * @param string $directory
     *
     * @return string
     */
    public function getSizeAndUnit($directory = ''): string
    {
        $size = $this->getSize($directory);
        $unity = 'Octets';

        if ($size < 1048576 and $size > 1023) {
            $unity = 'Ko';
            $size = round($size / 1024, 1);
        } elseif ($size < 1073741824 and $size > 1048575) {
            $unity = 'Mo';
            $size = round($size / 1048576, 1);
        } elseif ($size > 1073741823) {
            $unity = 'Go';
            $size = round($size / 1073741824, 1);
        }

        return "$size $unity";
    }
}
