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
 * Trait FileServiceAwareTrait.
 */
trait FileServiceAwareTrait
{
    /**
     * @var FileService
     */
    private $fileService;

    /**
     * @return FileService
     */
    public function getFileService(): FileService
    {
        return $this->fileService;
    }

    /**
     * @param FileService $fileService
     *
     * @required
     */
    public function setFileService(FileService $fileService): void
    {
        $this->fileService = $fileService;
    }
}
