<?php

/**
 * Created by devinthehood.
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
        $unities = ['o', 'Ko', 'Mo', 'Go', 'To'];

        $i = 0;
        while ($size / 1024 > 1) {
            $size = $size / 1024;
            ++$i;
        }

        return sprintf('%s %s', round($size, 1), $unities[$i]);
    }
}
