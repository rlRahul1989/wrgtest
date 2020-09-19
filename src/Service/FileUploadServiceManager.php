<?php

namespace App\Service;

use App\Entity\DocumentLog;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\DependencyInjection\ContainerInterface;

class FileUploadServiceManager
{

    /**
     * @var $container
     */
    private $container;

    /**
     * Constructor
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Upload file
     *
     * @param UploadedFile $file
     * @param string $path
     * @throws
     * @return string
     */
    public function upload(UploadedFile $file, $path)
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
        $fileName = $safeFilename . '-' . uniqid() . '.' . pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
        $this->logMediaInfo($fileName);
        if (!$file->move($path, $fileName)) {
            throw new \Exception('file_upload_failed');
        }
        return $fileName;
    }

    /**
     * log media information
     *
     * @param string $fileName
     * @return void
     */
    public function logMediaInfo($fileName)
    {
        $em = $this->container->get('doctrine')->getManager();
        $docLog = new DocumentLog();
        $docLog->setDocumentName($fileName);
        $em->persist($docLog);
        $em->flush();
    }
}