<?php
namespace Ilcfrance\DataBundle\OrmRepository;

use Doctrine\ORM\EntityRepository;
use Ilcfrance\DataBundle\OrmEntity\Locale;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class LocaleRepository extends EntityRepository
{

	/**
	 * count All
	 *
	 * @param boolean $cache
	 *
	 * @return Ambigous <\Doctrine\ORM\mixed, mixed, multitype:,
	 *         \Doctrine\DBAL\Driver\Statement, \Doctrine\Common\Cache\mixed>
	 */
	public function count($cache = false)
	{
		$qb = $this->createQueryBuilder('l')->select('count(l)');
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
		$qb = $this->createQueryBuilder('l')->orderBy('l.id', 'ASC');
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

	/**
	 * count for Status enabled
	 *
	 * @param boolean $cache
	 *
	 * @return Ambigous <\Doctrine\ORM\mixed, mixed, multitype:,
	 *         \Doctrine\DBAL\Driver\Statement, \Doctrine\Common\Cache\mixed>
	 */
	public function countEnabled($cache = false)
	{
		$qb = $this->createQueryBuilder('l')->select('count(l)')->where('l.status = :st')->setParameter('st', Locale::ST_ENABLED);
		$query = $qb->getQuery();

		if ($cache) {
			$query->setCacheable('true')->useQueryCache(true)->setLifetime(60)->useResultCache(true, 60);
		}

		return $query->getSingleScalarResult();
	}

	/**
	 * Get Query for Status enabled
	 *
	 * @return \Doctrine\ORM\Query
	 */
	public function getAllEnabledQuery()
	{
		$qb = $this->createQueryBuilder('l')->where('l.status = :status')->orderBy('l.id', 'ASC')->setParameter('status', Locale::ST_ENABLED);
		$query = $qb->getQuery();

		return $query;
	}

	/**
	 * Get All for Status enabled
	 *
	 * @param boolean $cache
	 *
	 * @return Ambigous <\Doctrine\ORM\mixed,
	 *         \Doctrine\ORM\Internal\Hydration\mixed,
	 *         \Doctrine\DBAL\Driver\Statement,
	 *         \Doctrine\Common\Cache\mixed>
	 */
	public function getAllEnabled($cache = false)
	{
		$query = $this->getAllEnabledQuery();

		if ($cache) {
			$query->setCacheable('true')->useQueryCache(true)->setLifetime(60)->useResultCache(true, 60);
		}
		return $query->execute();
	}
}