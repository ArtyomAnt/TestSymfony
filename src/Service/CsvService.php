<?php

namespace App\Service;

use App\Entity\Csv;
use App\Repository\CsvRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use League\Csv\Reader;
use League\Csv\Statement;
use phpDocumentor\Reflection\Types\Array_;

class CsvService
{
	/**
	 * @var string
	 */
	private $targetDirectory;

	/**
	 * @var string
	 */
	private $filename;

	/**
	 * @var EntityManager
	 */
	private $entityManager;



	/**
	 * CsvImportCommand constructor.
	 *
	 * @param string $targetDirectory
	 *
	 * @throws \Symfony\Component\Console\Exception\LogicException
	 */
	public function __construct($targetDirectory)
	{
		$this->targetDirectory = $targetDirectory;
	}

	public function setFileName($filename)
	{
		$this->filename = $filename;
	}

	public function parse()
	{
		$csv = Reader::createFromPath($this->targetDirectory . $this->filename);
		$csv->setHeaderOffset(0);
		$stmt = (new Statement())
			->offset(0);
		$records = $stmt->process($csv);
		$em = $this->entityManager;
		$headers = $records->getHeader();
		$tableName = pathinfo($this->filename, PATHINFO_FILENAME);
		$repository = $em->getRepository(Csv::class);
		$repository
			->createTable($tableName, $headers);
		$typeStart = array_fill_keys($headers, 'integer');
		$typeFinish = $typeStart;
		$typeFinish = $repository
			->insertData($tableName, $records, $typeFinish);
		$field_dif = array_keys(array_intersect_assoc($typeStart,$typeFinish));
		$repository
			->updateFields($field_dif,$tableName);
	}

	public function setEntityManager(EntityManager $entityManager)
	{
		$this->entityManager = $entityManager;
	}

}