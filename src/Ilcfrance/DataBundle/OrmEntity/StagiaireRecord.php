<?php
namespace Ilcfrance\DataBundle\OrmEntity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Ilcfrance\DataBundle\Model\EntityTraceable;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * StagiaireRecord
 * @ORM\Table(name="ilcfrance_stagaire_records")
 * @ORM\Entity(repositoryClass="Ilcfrance\DataBundle\OrmRepository\StagiaireRecordRepository")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Cache(usage="NONSTRICT_READ_WRITE", region="region_stagaire_records")
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class StagiaireRecord implements EntityTraceable
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
	 * @var Stagiaire @ORM\ManyToOne(targetEntity="Stagiaire", inversedBy="records", cascade={"persist"})
	 *      @ORM\JoinColumns({
	 *      @ORM\JoinColumn(name="stagiaire_id", referencedColumnName="id")
	 *      })
	 */
	protected $stagiaire;

	/**
	 *
	 * @var User @ORM\ManyToOne(targetEntity="User", inversedBy="records", cascade={"persist"})
	 *      @ORM\JoinColumns({
	 *      @ORM\JoinColumn(name="teacher_id", referencedColumnName="id")
	 *      })
	 */
	protected $teacher;

	/**
	 *
	 * @var string @ORM\Column(name="teacher_name", type="text", nullable=true)
	 */
	protected $teacherName;

	/**
	 *
	 * @var \DateTime @ORM\Column(name="record_date", type="datetimetz", nullable=true)
	 *      @Assert\NotNull(groups={"recordDate"})
	 */
	protected $recordDate;

	/**
	 *
	 * @var string @ORM\Column(name="works_covered", type="text", nullable=true)
	 *      @Assert\NotBlank(groups={"url"})
	 */
	protected $worksCovered;

	/**
	 *
	 * @var string @ORM\Column(name="comments", type="text", nullable=true)
	 *      @Assert\NotBlank(groups={"url"})
	 */
	protected $comments;

	/**
	 *
	 * @var string @ORM\Column(name="homeworks", type="text", nullable=true)
	 */
	protected $homeworks;

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
		$this->dtCrea = new \DateTime('now');
	}

	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Ilcfrance\DataBundle\Model\EntityTraceable::getId()
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * Get $stagaire
	 *
	 * @return Stagiaire
	 */
	public function getStagiaire()
	{
		return $this->stagiaire;
	}

	/**
	 *
	 * @param Stagiaire $stagiaire
	 *
	 * @return StagiaireRecord
	 */
	public function setStagiaire(Stagiaire $stagiaire)
	{
		$this->stagiaire = $stagiaire;

		return $this;
	}

	/**
	 * Get $teacher
	 *
	 * @return User
	 */
	public function getTeacher()
	{
		return $this->teacher;
	}

	/**
	 * Set $teacher
	 *
	 * @param User $teacher
	 *
	 * @return StagiaireRecord
	 */
	public function setTeacher(User $teacher = null)
	{
		$this->teacher = $teacher;

		return $this;
	}

	/**
	 * Get $teacherName
	 *
	 * @return string
	 */
	public function getTeacherName()
	{
		return $this->teacherName;
	}

	/**
	 * Set $teacherName
	 *
	 * @param string $teacherName
	 *
	 * @return StagiaireRecord
	 */
	public function setTeacherName($teacherName)
	{
		$this->teacherName = $teacherName;

		return $this;
	}

	/**
	 * Get $recordDate
	 *
	 * @return \DateTime
	 */
	public function getRecordDate()
	{
		return $this->recordDate;
	}

	/**
	 * Set $recordDate
	 *
	 * @param \DateTime $recordDate
	 *
	 * @return StagiaireRecord
	 */
	public function setRecordDate(\DateTime $recordDate = null)
	{
		$this->recordDate = $recordDate;

		return $this;
	}

	/**
	 * Get $worksCovered
	 *
	 * @return string
	 */
	public function getWorksCovered()
	{
		return $this->worksCovered;
	}

	/**
	 * Set $worksCovered
	 *
	 * @param string $worksCovered
	 *
	 * @return StagiaireRecord
	 */
	public function setWorksCovered($worksCovered)
	{
		$this->worksCovered = $worksCovered;

		return $this;
	}

	/**
	 * Get $comments
	 *
	 * @return string
	 */
	public function getComments()
	{
		return $this->comments;
	}

	/**
	 * Set $comments
	 *
	 * @param string $comments
	 *
	 * @return StagiaireRecord
	 */
	public function setComments($comments)
	{
		$this->comments = $comments;

		return $this;
	}

	/**
	 * Get $homeworks
	 *
	 * @return string
	 */
	public function getHomeworks()
	{
		return $this->homeworks;
	}

	/**
	 * Set $homeworks
	 *
	 * @param string $homeworks
	 *
	 * @return StagiaireRecord
	 */
	public function setHomeworks($homeworks)
	{
		$this->homeworks = $homeworks;

		return $this;
	}

	/**
	 * Get $dtCrea
	 *
	 * @return \DateTime
	 */
	public function getDtCrea()
	{
		return $this->dtCrea;
	}

	/**
	 * Set $dtCrea
	 *
	 * @param \DateTime $dtCrea
	 *
	 * @return StagiaireRecord
	 */
	public function setDtCrea(\DateTime $dtCrea = null)
	{
		$this->dtCrea = $dtCrea;

		return $this;
	}

	/**
	 * Get \$dtUpdate
	 *
	 * @return \DateTime
	 */
	public function getDtUpdate()
	{
		return $this->dtUpdate;
	}

	/**
	 * Set $dtUpdate
	 *
	 * @param \DateTime $dtUpdate
	 *
	 * @return StagiaireRecord
	 */
	public function setDtUpdate(\DateTime $dtUpdate = null)
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
		$recordDate = null;
		if (null != $this->recordDate) {
			$recordDate = $this->recordDate->format(\DateTime::ISO8601);
		}
		$teacher = null;
		if (null != $this->teacher) {
			$teacher = $this->teacher->getId();
		}

		return [
			'id' => $this->id,
			'stagiaire' => $this->stagiaire->getId(),
			'teacher' => $teacher,
			'teacherName' => $this->teacherName,
			'recordDate' => $recordDate,
			'worksCovered' => $this->worksCovered,
			'comments' => $this->comments,
			'homeworks' => $this->homeworks,
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

		$related = array(
			'id' => $this->getStagiaire()->getId(),
			'class' => Stagiaire::class
		);
		$ret[] = $related;

		if (null != $this->getTeacher()) {
			$related = array(
				'id' => $this->getTeacher()->getId(),
				'class' => User::class
			);
			$ret[] = $related;
		}

		return $ret;
	}
}