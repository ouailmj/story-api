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

use AppBundle\Entity\Image;
use AppBundle\Entity\Media;
use AppBundle\Entity\User;
use AppBundle\Entity\Video;
use AppBundle\Exception\FileNotAuthorizedException;
use AppBundle\Filesystem\FileManager;
use AppBundle\Filesystem\UploadManager;
use Doctrine\ORM\EntityManagerInterface;
use Gaufrette\File;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\HttpFoundation\File\File as SymfonyFile;
use Symfony\Component\HttpFoundation\File\UploadedFile;
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

    /**
     * MediaManager constructor.
     *
     * @param UploadManager          $uploadManager
     * @param FileManager            $fileManager
     * @param TokenStorageInterface  $tokenStorage
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(UploadManager $uploadManager, FileManager $fileManager, TokenStorageInterface $tokenStorage, EntityManagerInterface $entityManager)
    {
        $this->uploadManager = $uploadManager;
        $this->fileManager = $fileManager;
        $this->tokenStorage = $tokenStorage;
        $this->entityManager = $entityManager;
    }

    /**
     * Creates a media from a Gaufrette file.
     *
     * @param File   $file
     * @param User   $by
     * @param bool   $andSave
     * @param string $type
     *
     * @return Media
     */
    public function createMediaFromFile(File $file, User $by, $andSave = true, $type = Media::class)
    {
        /** @var Media $media * */
        $media = new $type();
        $media->setSrc($file->getKey());
        $media->setUploadedAt(new \DateTime());

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
     * @param bool      $andSave
     *
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

    public function deleteMedia(int $mediaId)
    {
        // Remove the file from the filesystem.

        // Remove Entity.
    }

    public function trashMedia(int $mediaId)
    {
        // Put $media to trash
    }

    public function unTrashMedia(int $mediaId)
    {
    }

    /**
     * @param UploadedFile $file
     * @param User|null    $by
     * @param bool         $andSave
     *
     * @throws FileNotAuthorizedException
     *
     * @return Media
     */
    public function uploadImage(UploadedFile $file, User $by = null, $andSave = true)
    {
        $imageTypes = ['JPG', 'PNG', 'JPEG'];

        // TODO: use the mime content type function @see mime_content_type($filename)
        if (in_array(strtoupper($file->getClientOriginalExtension()), $imageTypes, true)) {
            $file = $this->uploadManager->upload($file);

            return $this->createMediaFromFile($file, $by, $andSave, Image::class);
        }
        throw new FileNotAuthorizedException();
    }

    /**
     * @param UploadedFile $file
     * @param User|null    $by
     * @param bool         $andSave
     *
     * @throws FileNotAuthorizedException
     *
     * @return Media
     */
    public function uploadVideo(UploadedFile $file, User $by = null, $andSave = true)
    {
        $videoTypes = ['MP4', 'MPEG4', 'AVI', 'FLV'];

        // TODO: use the mime content type function @see mime_content_type($filename)
        if (in_array(strtoupper($file->getClientOriginalExtension()), $videoTypes, true)) {
            $file = $this->uploadManager->upload($file);

            return $this->createMediaFromFile($file, $by, $andSave, Video::class);
        }
        throw new FileNotAuthorizedException();
    }

    private function isImage($file)
    {
        // TODO: use the mime content type function @see mime_content_type($filename)
    }

    private function isVideo($file)
    {
        // TODO: use the mime content type function @see mime_content_type($filename)
    }
}
