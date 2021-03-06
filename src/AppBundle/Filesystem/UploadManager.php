<?php

/*
 * This file is part of the Instan't App project.
 *
 * (c) Instan't App <contact@instant-app.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Developed by MIT <contact@mit-agency.com>
 *
 */

namespace AppBundle\Filesystem;

use Gaufrette\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadManager
{
    /** @var FileManager */
    protected $fileManager;

    /**
     * UploadManager constructor.
     *
     * @param FileManager $fileManager
     */
    public function __construct(FileManager $fileManager)
    {
        $this->fileManager = $fileManager;
    }

    /**
     * @param UploadedFile $file
     * @param string       $dir
     *
     * @return File|mixed
     */
    public function upload(UploadedFile $file, $dir = ''): File
    {
        return $this->fileManager->createFile($file, $dir);
    }

    /**
     * @param UploadedFile $file
     * @param string       $dir
     * @param $filesSystem
     *
     * @return File
     */
    public function uploadWithFS(UploadedFile $file, $dir = '', $filesSystem): File
    {
        return $this->fileManager->createFile($file, $dir, $filesSystem);
    }
}
