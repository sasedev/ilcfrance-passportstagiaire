<?php
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{

	public function registerBundles()
	{
		$bundles = [
			new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
			new Symfony\Bundle\SecurityBundle\SecurityBundle(),
			new Symfony\Bundle\TwigBundle\TwigBundle(),
			new Symfony\Bundle\MonologBundle\MonologBundle(),
			new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
			new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
			new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),

			new Doctrine\Bundle\DoctrineCacheBundle\DoctrineCacheBundle(),
			new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle(),
			new Doctrine\Bundle\MongoDBBundle\DoctrineMongoDBBundle(),
			new Snc\RedisBundle\SncRedisBundle(),
			new Symfony\Bundle\AsseticBundle\AsseticBundle(),
			new JMS\SerializerBundle\JMSSerializerBundle(),
			new JMS\DiExtraBundle\JMSDiExtraBundle($this),
			new JMS\AopBundle\JMSAopBundle(),
			new JMS\SecurityExtraBundle\JMSSecurityExtraBundle(),
			new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),
			new Liuggio\ExcelBundle\LiuggioExcelBundle(),
			new Knp\Bundle\PaginatorBundle\KnpPaginatorBundle(),
			new Liip\ImagineBundle\LiipImagineBundle(),

			new Sasedev\SharedBundle\SasedevSharedBundle(),

			new Ilcfrance\DataBundle\IlcfranceDataBundle(),
			new Ilcfrance\ResBundle\IlcfranceResBundle(),
			new Ilcfrance\StagiairePassportBundle\IlcfranceStagiairePassportBundle(),
			new Ilcfrance\SecurityBundle\IlcfranceSecurityBundle()
		];

		if (in_array($this->getEnvironment(), [
			'dev',
			'test'
		], true)) {
			$bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();
			$bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
			$bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
			$bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
		}

		return $bundles;
	}

	public function getRootDir()
	{
		return __DIR__;
	}

	public function getCacheDir()
	{
		return dirname(__DIR__) . '/var/cache/' . $this->getEnvironment();
	}

	public function getLogDir()
	{
		return dirname(__DIR__) . '/var/logs';
	}

	public function registerContainerConfiguration(LoaderInterface $loader)
	{
		$loader->load($this->getRootDir() . '/config/config_' . $this->getEnvironment() . '.yml');
	}
}
