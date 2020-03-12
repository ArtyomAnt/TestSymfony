<?php

namespace App\Repository;

use App\Entity\Csv;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Csv|null find($id, $lockMode = null, $lockVersion = null)
 * @method Csv|null findOneBy(array $criteria, array $orderBy = null)
 * @method Csv[]    findAll()
 * @method Csv[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CsvRepository extends ServiceEntityRepository
{
	public function __construct(ManagerRegistry $registry)
	{
		parent::__construct($registry, Csv::class);
	}


	public function createTable($tableName, $headers)
	{
		$sql = 'CREATE TABLE IF NOT EXISTS ' . $tableName . ' (id INT AUTO_INCREMENT NOT NULL, ';
		foreach ($headers as $head) {
			$sql .= " `$head` VARCHAR(255) NOT NULL,";
		}
		$sql .= ' PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB';
		$statement = $this->getEntityManager()->getConnection()->prepare($sql);
		$statement->execute();
	}

	public function updateFields($field_dif, $tableName)
	{
		foreach ($field_dif as $k => $v) {
			$sql = 'ALTER TABLE ' . $tableName . ' CHANGE ' . $v . ' ' . $v . ' INT NOT NULL;';
			$statement = $this->getEntityManager()->getConnection()->prepare($sql);
			$statement->execute();
		}
	}

	public function insertData($tableName, $records, $typeFinish)
	{
		foreach ($records as $row) {
			foreach ($row as $k => $v) {
				if (!is_numeric($v)) {
					$typeFinish[$k] = 'string';
				}
			}

			$connection = $this->getEntityManager()->getConnection();
			$connection->insert($tableName, $row);
		}
		return $typeFinish;
	}

	// /**
	//  * @return Csv[] Returns an array of Csv objects
	//  */
	/*
	public function findByExampleField($value)
	{
		return $this->createQueryBuilder('c')
			->andWhere('c.exampleField = :val')
			->setParameter('val', $value)
			->orderBy('c.id', 'ASC')
			->setMaxResults(10)
			->getQuery()
			->getResult()
		;
	}
	*/

	/*
	public function findOneBySomeField($value): ?Csv
	{
		return $this->createQueryBuilder('c')
			->andWhere('c.exampleField = :val')
			->setParameter('val', $value)
			->getQuery()
			->getOneOrNullResult()
		;
	}
	*/
}
