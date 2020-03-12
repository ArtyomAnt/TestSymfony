<?php

namespace App\Service;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploaderService
{
	private $targetDirectory;
	/**
	 * @var LoggerInterface
	 */
	private $logger;

	public function __construct(string $targetDirectory, LoggerInterface $logger)
	{
		$this->targetDirectory = $targetDirectory;
		$this->logger = $logger;
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