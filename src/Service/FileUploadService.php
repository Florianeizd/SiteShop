<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploadService implements FileUploadServiceInterface
{
    private $pathImageArticle;
    private $slugger;

    public function __construct($pathImageArticle, SluggerInterface $slugger)
    {
        $this->pathImageArticle = $pathImageArticle;
        $this->slugger = $slugger;
    }

    public function upload(UploadedFile $file): string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $filename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

        try {
            $file->move($this->getPathImageArticle(), $filename);
        } catch (FileException $e) {
            throw new \Exception('Error upload file' . $e->getTraceAsString());
        }

        return $filename;
    }

    private function getPathImageArticle(): string
    {
        return $this->pathImageArticle;
    }
}