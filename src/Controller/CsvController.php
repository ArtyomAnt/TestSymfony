<?php

namespace App\Controller;

use App\Entity\Csv;
use App\Form\CsvType;
use App\Service\CsvService;
use App\Service\UploaderService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CsvController extends AbstractController
{
	/**
	 * @Route("/csv", name="app_csv")
	 */
	public function new(Request $request, UploaderService $uploaderService, EntityManagerInterface $em, CsvService $csvService)
	{
		$csvObject = new Csv();
		$form = $this->createForm(CsvType::class, $csvObject);
		$form->handleRequest($request);

		if ($request->isMethod('POST')) {
			if ($form->isSubmitted() && $form->isValid()) {
				$csvFilename = $form->get('csv')->getData();
				if ($csvFilename) {
					$FileName = $uploaderService->upload($csvFilename);
					$csvObject->setFilename($FileName);
					$em->persist($csvObject);
					$csvService->setFileName($FileName);
					$csvService->parse();
				}
				$this->addFlash('success', 'Succes');
				return $this->redirectToRoute('app_csv');
			}
			$em->flush();
		}
		return $this->render('csv/new.html.twig', [
			'form' => $form->createView(),
		]);
	}
}
