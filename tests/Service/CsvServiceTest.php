<?php


namespace App\Tests\Service;


use App\Repository\CsvRepository;
use App\Service\CsvService;
use App\Service\UploaderService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CsvServiceTest extends TestCase
{

	/**
	 * @var \PHPUnit\Framework\MockObject\MockObject|CsvRepository
	 */
	private $repository;

	/**
	 * @var \PHPUnit\Framework\MockObject\MockObject|ContainerInterface
	 */
	private $container;

	/**
	 * @var CsvService
	 */
	private $service;

	public function setUp(): void
	{
		$this->repository = $this->createMock(CsvRepository::class);
		$this->container = $this->createMock(ContainerInterface::class);
		$this->service = new CsvService($this->repository, $this->container);
	}

	/**
	 * @dataProvider getFileName
	 */
	public function testFileName($filename)
	{
		$this->service->setFileName($filename);
		$file = $this->service->getFileName();
		$this->assertIsString($file);
		$this->assertStringNotContainsString('.', $this->service->getTableName($file));
	}



	public function getFileName()
	{
		return [
			// Specification, is large is carnivorous
			['dinosaur.csv'],
			['give.csv'],
			['large'],
			['2364523'],
		];
	}
}