<?php

namespace App\Service;

use App\Repository\CsvRepository;
use League\Csv\Reader;
use League\Csv\Statement;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CsvService
{
	/**
	 * @var string
	 */
	private $filename;
	/**
	 * @var CsvRepository
	 */
	private $csvRepository;

	/**
	 * @var array
	 */
	private $headers;

	/**
	 * @var ContainerInterface
	 */
	private $container;


	public function __construct(CsvRepository $csvRepository, ContainerInterface $container)
	{
		$this->csvRepository = $csvRepository;
		$this->container = $container;
	}

	/**
	 * @param $targetDirectory
	 * @param $filename
	 * @return \League\Csv\TabularDataReader
	 * @throws \League\Csv\Exception
	 */
	public function parse($targetDirectory,$filename)
	{
		$csv = Reader::createFromPath($targetDirectory . $filename);
		$csv->setHeaderOffset(0);
		$stmt = (new Statement())
			->offset(0);
		$records = $stmt->process($csv);
		$this->headers = $records->getHeader();
		return $records;
	}

	/**
	 * @return string
	 */
	public function getFileName()
	{
		return $this->filename;
	}

	/**
	 * @param string $filename
	 */
	public function setFileName($filename)
	{
		$this->filename = $filename;
	}

	/**
	 * @param string $filename
	 * @return string
	 */
	public function getTableName($filename)
	{
		return pathinfo($filename, PATHINFO_FILENAME);
	}

	/**
	 * @return array
	 */
	public function getHeaders()
	{
		return $this->headers;
	}
}