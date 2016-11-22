<?php
namespace Ilcfrance\DataBundle\OrmEntity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Ilcfrance\DataBundle\Model\EntityTraceable;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Stagiaire
 * @ORM\Table(name="ilcfrance_documents")
 * @ORM\Entity(repositoryClass="Ilcfrance\DataBundle\OrmRepository\DocRepository")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Cache(usage="NONSTRICT_READ_WRITE", region="ilcfrance_documents")
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class Doc implements EntityTraceable
{

	/**
	 *
	 * @var guid @ORM\Column(name="id", type="guid", nullable=false)
	 *      @ORM\Id
	 *      @ORM\GeneratedValue(strategy="UUID")
	 */
	protected $id;

	/**
	 *
	 * @var string @ORM\Column(name="filename", type="text", nullable=false)
	 *      @Assert\File(maxSize="20480k", groups={"fileName"})
	 */
	protected $fileName;

	/**
	 *
	 * @var integer @ORM\Column(name="filesize", type="bigint", nullable=false)
	 */
	protected $size;

	/**
	 *
	 * @var string @ORM\Column(name="filemimetype", type="text", nullable=false)
	 */
	protected $mimeType;

	/**
	 *
	 * @var string @ORM\Column(name="filemd5", type="text", nullable=false)
	 */
	protected $md5;

	/**
	 *
	 * @var string @ORM\Column(name="fileoname", type="text", nullable=false)
	 */
	protected $originalName;

	/**
	 *
	 * @var string @ORM\Column(name="filedesc", type="text", nullable=true)
	 */
	protected $description;

	/**
	 *
	 * @var integer @ORM\Column(name="filedls", type="bigint", nullable=false)
	 */
	protected $nbrDownloads;

	/**
	 *
	 * @var \DateTime @ORM\Column(name="created_at", type="datetimetz", nullable=true)
	 */
	protected $dtCrea;

	/**
	 *
	 * @var \DateTime @ORM\Column(name="updated_at", type="datetimetz", nullable=true)
	 *      @Gedmo\Timestampable(on="update")
	 */
	protected $dtUpdate;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->size = 0;
		$this->nbrDownloads = 0;
		$this->dtCrea = new \DateTime('now');
	}

	/**
	 * Get id
	 *
	 * @return guid
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * Get fileName
	 *
	 * @return string
	 */
	public function getFileName()
	{
		return $this->fileName;
	}

	/**
	 * Set fileName
	 *
	 * @param string $fileName
	 *
	 * @return Doc
	 */
	public function setFileName($fileName)
	{
		$this->fileName = $fileName;

		return $this;
	}

	/**
	 * Get size
	 *
	 * @return integer
	 */
	public function getSize()
	{
		return $this->size;
	}

	/**
	 * Set size
	 *
	 * @param integer $size
	 *
	 * @return Doc
	 */
	public function setSize($size)
	{
		$this->size = $size;

		return $this;
	}

	/**
	 * Get mimeType
	 *
	 * @return string
	 */
	public function getMimeType()
	{
		return $this->mimeType;
	}

	/**
	 * Set mimeType
	 *
	 * @param string $mimeType
	 *
	 * @return Doc
	 */
	public function setMimeType($mimeType)
	{
		$this->mimeType = $mimeType;

		return $this;
	}

	/**
	 * Get md5
	 *
	 * @return string
	 */
	public function getMd5()
	{
		return $this->md5;
	}

	/**
	 * Set md5
	 *
	 * @param string $md5
	 *
	 * @return Doc
	 */
	public function setMd5($md5)
	{
		$this->md5 = $md5;

		return $this;
	}

	/**
	 * Get originalName
	 *
	 * @return string
	 */
	public function getOriginalName()
	{
		return $this->originalName;
	}

	/**
	 * Set originalName
	 *
	 * @param string $originalName
	 *
	 * @return Doc
	 */
	public function setOriginalName($originalName)
	{
		$this->originalName = $originalName;

		return $this;
	}

	/**
	 * Get description
	 *
	 * @return string
	 */
	public function getDescription()
	{
		return $this->description;
	}

	/**
	 * Set description
	 *
	 * @param string $description
	 *
	 * @return Doc
	 */
	public function setDescription($description)
	{
		$this->description = $description;

		return $this;
	}

	/**
	 * Get nbrDownloads
	 *
	 * @return integer
	 */
	public function getNbrDownloads()
	{
		return $this->nbrDownloads;
	}

	/**
	 * Set nbrDownloads
	 *
	 * @param integer $nbrDownloads
	 *
	 * @return Doc
	 */
	public function setNbrDownloads($nbrDownloads)
	{
		$this->nbrDownloads = $nbrDownloads;

		return $this;
	}

	/**
	 * Get dtCrea
	 *
	 * @return \DateTime
	 */
	public function getDtCrea()
	{
		return $this->dtCrea;
	}

	/**
	 * Set dtCrea
	 *
	 * @param \DateTime $dtCrea
	 *
	 * @return Doc
	 */
	public function setDtCrea($dtCrea)
	{
		$this->dtCrea = $dtCrea;

		return $this;
	}

	/**
	 * Get dtUpdate
	 *
	 * @return \DateTime
	 */
	public function getDtUpdate()
	{
		return $this->dtUpdate;
	}

	/**
	 * Set dtUpdate
	 *
	 * @param \DateTime $dtUpdate
	 *
	 * @return Doc
	 */
	public function setDtUpdate($dtUpdate)
	{
		$this->dtUpdate = $dtUpdate;

		return $this;
	}

	public function jsonSerialize()
	{
		$dtCrea = null;
		if (null != $this->dtCrea) {
			$dtCrea = $this->dtCrea->format(\DateTime::ISO8601);
		}
		$dtUpdate = null;
		if (null != $this->dtUpdate) {
			$dtUpdate = $this->dtUpdate->format(\DateTime::ISO8601);
		}

		return [
			'id' => $this->id,
			'fileName' => $this->fileName,
			'size' => $this->size,
			'mimeType' => $this->mimeType,
			'md5' => $this->md5,
			'originalName' => $this->originalName,
			'description' => $this->description,
			'nbrDownloads' => $this->nbrDownloads,
			'dtCrea' => $dtCrea,
			'dtUpdate' => $dtUpdate
		];
	}

	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Ilcfrance\DataBundle\Model\EntityTraceable::getRelated()
	 */
	public function getRelated()
	{
		$ret = array();
		return $ret;
	}
}