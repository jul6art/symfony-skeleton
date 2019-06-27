<?php
/**
 * Created by PhpStorm.
 * User: gkratz
 * Date: 19/04/2019
 * Time: 10:56.
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
        foreach (glob(rtrim($directory, '/').'/*', GLOB_NOSORT) as $item) {
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

        if ($size < 1024) {
            $size = $size.' Octets';
        } elseif ($size < 1048576 && $size > 1023) {
            $size = round($size / 1024, 1).' Ko';
        } elseif ($size < 1073741824 && $size > 1048575) {
            $size = round($size / 1048576, 1).' Mo';
        } else {
            $size = round($size / 1073741824, 1).' Go';
        }

        return $size;
    }
}
