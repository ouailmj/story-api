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

namespace AppBundle\Model;

use AppBundle\Entity\File;
use Doctrine\ORM\EntityManagerInterface;
use Stof\DoctrineExtensionsBundle\Uploadable\UploadableManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileManager
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;
    /**
     * @var UploadableManager
     */
    private $uploadableManager;

    /**
     * FileManager constructor.
     *
     * @param UploadableManager      $uploadableManager
     * @param EntityManagerInterface $em
     */
    public function __construct(UploadableManager $uploadableManager, EntityManagerInterface $em)
    {
        $this->uploadableManager = $uploadableManager;
        $this->em = $em;
    }

    /**
     * @param UploadedFile $mediaUpload
     *
     * @return File
     */
    public function upload(UploadedFile $mediaUpload)
    {
        $file = new File();

        $mediaUpload->getClientOriginalName();
        $this->uploadableManager->markEntityToUpload($file, $mediaUpload);
        $file->setDisplayName($mediaUpload->getClientOriginalName());

        return $file;
    }
}
