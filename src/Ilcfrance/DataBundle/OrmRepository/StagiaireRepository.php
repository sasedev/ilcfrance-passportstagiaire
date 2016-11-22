<?php
namespace Ilcfrance\DataBundle\OrmRepository;

use Doctrine\ORM\EntityRepository;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class StagiaireRepository extends EntityRepository
{

	/**
	 * Count All
	 *
	 * @param boolean $cache
	 *
	 * @return Ambigous <\Doctrine\ORM\mixed, mixed, multitype:, \Doctrine\DBAL\Driver\Statement,
	 *         \Doctrine\Common\Cache\mixed>
	 */
	public function count($cache = false)
	{
		$qb = $this->createQueryBuilder('s')->select('count(s)');
		$query = $qb->getQuery();

		if ($cache) {
			$query->setCacheable('true')->useQueryCache(true)->setLifetime(60)->useResultCache(true, 60);
		}

		return $query->getSingleScalarResult();
	}

	/**
	 * Get Query for All Entities
	 *
	 * @return \Doctrine\ORM\Query
	 */
	public function getAllQuery()
	{
		$qb = $this->createQueryBuilder('s')->orderBy('s.lastName', 'ASC')->addOrderBy('s.firstName', 'ASC');
		$query = $qb->getQuery();

		return $query;
	}

	/**
	 * Get All Entities
	 *
	 * @param boolean $cache
	 *
	 * @return Ambigous <\Doctrine\ORM\mixed,
	 *         \Doctrine\ORM\Internal\Hydration\mixed,
	 *         \Doctrine\DBAL\Driver\Statement,
	 *         \Doctrine\Common\Cache\mixed>
	 */
	public function getAll($cache = false)
	{
		$query = $this->getAllQuery();

		if ($cache) {
			$query->setCacheable('true')->useQueryCache(true)->setLifetime(60)->useResultCache(true, 60);
		}

		return $query->execute();
	}
}