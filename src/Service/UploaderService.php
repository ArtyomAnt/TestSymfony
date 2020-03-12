<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploaderService
{
	private $targetDirectory;

	public function __construct($targetDirectory)
	{
		$this->targetDirectory = $targetDirectory;
	}

	public function upload(UploadedFile $file)
	{
		$fileName = 'csv_' . uniqid() . '.' . $file->guessExtension();
		try {
			$file->move($this->getTargetDirectory(), $fileName);
		} catch (FileException $e) {

		}

		return $fileName;
	}

	public function getTargetDirectory()
	{
		return $this->targetDirectory;
	}
}