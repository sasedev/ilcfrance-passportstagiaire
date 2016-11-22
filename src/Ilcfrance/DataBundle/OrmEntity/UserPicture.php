<?php
namespace Ilcfrance\DataBundle\OrmEntity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Ilcfrance\DataBundle\Model\EntityTraceable;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * UserPicture
 * @ORM\Table(name="ilcfrance_users_pictures")
 * @ORM\Entity(repositoryClass="Ilcfrance\DataBundle\OrmRepository\UserPictureRepository")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Cache(usage="NONSTRICT_READ_WRITE", region="region_users_pictures")
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class UserPicture implements \Serializable, EntityTraceable
{

	/**
	 *
	 * @var User @ORM\Id
	 *      @ORM\OneToOne(targetEntity="User", inversedBy="picture", cascade={"persist"})
	 *      @ORM\JoinColumns({
	 *      @ORM\JoinColumn(name="user_id", referencedColumnName="id")
	 *      })
	 *      @ORM\Cache(usage="NONSTRICT_READ_WRITE", region="region_users")
	 */
	protected $id;

	/**
	 *
	 * @var string @ORM\Column(name="pic_url", type="text", nullable=true)
	 *      @Assert\NotBlank(groups={"url"})
	 */
	protected $url;

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
	public function __construct(User $user = null)
	{
		$this->dtCrea = new \DateTime('now');

		if (null != $user) {
			$this->setId($user);
		}
	}

	/**
	 * get $id
	 *
	 * @return User
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * Set $id
	 *
	 * @param User $id
	 *
	 * @return UserPicture
	 */
	public function setId(User $id)
	{
		$this->id = $id;

		return $this;
	}

	/**
	 * Get $url
	 *
	 * @return string
	 */
	public function getUrl()
	{
		return $this->url;
	}

	/**
	 * Set $url
	 *
	 * @param string $url
	 *
	 * @return UserPicture
	 */
	public function setUrl($url)
	{
		$this->url = $url;

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
	 * @return UserPicture
	 */
	public function setDtCrea(\DateTime $dtCrea = null)
	{
		$this->dtCrea = $dtCrea;

		return $this;
	}

	/**
	 * Get $dtUpdate
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
	 * @return UserPicture
	 */
	public function setDtUpdate(\DateTime $dtUpdate = null)
	{
		$this->dtUpdate = $dtUpdate;

		return $this;
	}

	/**
	 * Serializes the User.
	 * The serialized data have to contain the fields used by the equals method
	 * and the username.
	 *
	 * {@inheritdoc} @see Serializable::serialize()
	 * @return string
	 */
	public function serialize()
	{
		return \serialize(array(
			$this->id,
			$this->url
		));
	}

	/**
	 * Unserializes the UserPicture.
	 *
	 * {@inheritdoc} @see Serializable::unserialize()
	 * @param string $serialized
	 *
	 * @return UserPicture
	 */
	public function unserialize($serialized)
	{
		$data = \unserialize($serialized);
		// add a few extra elements in the array to ensure that we have enough
		// keys when
		// unserializing
		// older data which does not include all properties.
		$data = array_merge($data, array_fill(0, 2, null));

		list ($this->id, $this->url) = $data;

		return $this;
	}

	/**
	 * string representation of the object
	 *
	 * @return string
	 */
	public function __toString()
	{
		return ((string) $this->getId()) . $this->getUrl();
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
			'id' => $this->id->getId(),
			'url' => $this->url,
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
			'id' => $this->getId()->getId(),
			'class' => User::class
		);
		$ret[] = $related;

		return $ret;
	}
}