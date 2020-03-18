<?php

namespace App\Controller;

use App\Entity\Csv;
use App\Form\CsvType;
use App\Repository\CsvRepository;
use App\Service\CsvService;
use App\Service\UploaderService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CsvController extends AbstractController
{

	private $repository;

	private $container;

	/**
	 * CsvController constructor.
	 */
	public function __construct(CsvRepository $csvRepository, ContainerInterface $container)
	{
		$this->repository = $csvRepository;
		$this->container = $container;
	}

	/**
	 * @Route("/csv", name="app_csv")
	 * @param Request $request
	 * @param UploaderService $uploaderService
	 * @param EntityManagerInterface $em
	 * @param CsvService $csvService
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
	 * @throws \Doctrine\DBAL\DBALException
	 * @throws \League\Csv\Exception
	 */


	public function new(Request $request, UploaderService $uploaderService, EntityManagerInterface $em, CsvService $csvService)
	{
		$targetDirectory = $this->container->getParameter('csv_directory');
		$csvObject = new Csv();
		$form = $this->createForm(CsvType::class, $csvObject);
		$form->handleRequest($request);
		if ($request->isMethod('POST')) {
			if ($form->isSubmitted() && $form->isValid()) {
				$csvFilename = $form->get('csv')->getData();
				if ($csvFilename) {
					$uploaderService->upload($csvFilename);
					$filename = $uploaderService->getFileName();
					$csvObject->setFilename($filename);
					$em->persist($csvObject);
					$csvService->setFileName($filename);
					$records = $csvService->parse($targetDirectory,$filename);
					$this->createTable($csvService, $records);
				}
				$this->addFlash('success', 'Success');
				return $this->redirectToRoute('app_csv');
			}
			$em->flush();
		}
		return $this->render('csv/new.html.twig', [
			'form' => $form->createView(),
		]);
	}

	/**
	 * @param CsvService $csv
	 * @param $records
	 * @throws \Doctrine\DBAL\DBALException
	 */
	public function createTable($csv, $records)
	{
		$repository = $this->repository;
		$filename = $csv->getFileName();
		dd($filename);
		$tableName = $csv->getTableName($filename);
		$repository->createTable($tableName, $csv->getHeaders());
		$typeStart = array_fill_keys($csv->getHeaders(), 'integer');
		$typeFinish = $repository
			->insertData($tableName, $records, $typeStart);
		$field_dif = array_keys(array_intersect_assoc($typeStart, $typeFinish));
		$repository
			->updateFields($field_dif, $tableName);

	}
}
