<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use League\Csv\Reader;
use League\Csv\Statement;

class ImportCsvCommand extends Command
{
	protected static $defaultName = 'import:csv';

	protected function configure()
	{
		$this
			->setDescription('Add a short description for your command');
	}

	protected function execute(InputInterface $input, OutputInterface $output): int
	{
		$io = new SymfonyStyle($input, $output);
		$io->title('Attempting import of Feed...');

		$csv = Reader::createFromPath('%kernel.root_dir%/../src/Data/MOCK_DATA.csv');
		$csv->setHeaderOffset(0);
		$stmt = (new Statement())
			->offset(10)
			->limit(5);
		$records = $stmt->process($csv);
		$io->progressStart(iterator_count($records));

		foreach ($records as $row) {
			$io->progressAdvance();
			dump($row);
		}


		$io->progressFinish();
		$io->success('Command exited cleanly!');
		return 1;
	}
}
