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

use AppBundle\Entity\Media;
use AppBundle\Entity\User;
use AppBundle\Filesystem\FileManager;
use AppBundle\Filesystem\UploadManager;
use Doctrine\ORM\EntityManagerInterface;
use Gaufrette\File;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\HttpFoundation\File\File as SymfonyFile;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class MediaManager
{
    /**
     * @var UploadManager
     */
    protected $uploadManager;

    /**
     * @var FileManager
     */
    protected $fileManager;

    /** @var TokenStorageInterface */
    protected $tokenStorage;

    /** @var EntityManagerInterface */
    protected $entityManager;

    /** @var UserManager */
    protected $userManager;

    /**
     * MediaManager constructor.
     *
     * @param UploadManager $uploadManager
     * @param FileManager $fileManager
     * @param TokenStorageInterface $tokenStorage
     * @param EntityManagerInterface $entityManager
     * @param UserManager $userManager
     */
    public function __construct(UploadManager $uploadManager, FileManager $fileManager, TokenStorageInterface $tokenStorage, EntityManagerInterface $entityManager, UserManager $userManager)
    {
        $this->uploadManager = $uploadManager;
        $this->fileManager = $fileManager;
        $this->tokenStorage = $tokenStorage;
        $this->entityManager = $entityManager;
        $this->userManager = $userManager;
    }


    /**
     * Creates a media from a Gaufrette file.
     *
     * @param File $file
     * @param User|null $by
     * @param bool $andSave
     * @return Media
     */
    public function createMediaFromFile(File $file, User $by = null, $andSave = true)
    {
        $media = new Media();
        $media->setSrc($file->getKey());
        $media->setUploadedAt(new \DateTime());

        $by = ($by instanceof User) ? $by : $this->userManager->getLoggedInUser();

        $media->setCreatedBy($by);

        if ($andSave) {
            $this->saveMedia($media, true);
        }

        return $media;
    }

    /**
     * Creates a media from a file in the local filesystem.
     *
     * @param $filePath
     * @param User|null $by
     * @param bool $andSave
     * @return Media
     */
    public function createMediaFromLocalFile($filePath, User $by = null, $andSave = true)
    {
        if (file_exists($filePath)) {
            $file = $this->fileManager
                ->createFile(new SymfonyFile($filePath));

            return $this->createMediaFromFile($file, $by, $andSave);
        }
        throw new FileNotFoundException($filePath);
    }

    public function saveMedia(Media $media, $andFlush = true)
    {
        $this->entityManager->persist($media);
        if ($andFlush) {
            $this->entityManager->flush();
        }

        return $media;
    }

    public function deleteMedia($media)
    {
        // Remove the file from the filesystem.

        // Remove Entity.
    }
}
