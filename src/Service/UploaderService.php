<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploaderService
{
	private $targetDirectory;

	private $filename;

	public function __construct($targetDirectory)
	{
		$this->targetDirectory = $targetDirectory;
	}

	public function upload(UploadedFile $file)
	{
		$fileName = 'csv_' . uniqid() . '.'. $file->getClientOriginalExtension();
		$file->move($this->targetDirectory, $fileName);
		$this->filename = $fileName;
	}

	public function getFileName(){
		return $this->filename;
	}

}