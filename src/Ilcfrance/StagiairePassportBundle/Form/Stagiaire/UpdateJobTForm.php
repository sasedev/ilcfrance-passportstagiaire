<?php
namespace Ilcfrance\StagiairePassportBundle\Form\Stagiaire;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class UpdateJobTForm extends AbstractType
{

	/**
	 * Form builder
	 *
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 *
	 * @return null
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('job', TextType::class, array(
			'label' => 'Stagiaire.job.label'
		));
	}

	/**
	 *
	 * {@inheritdoc} @see FormTypeInterface::getName()
	 * @return string
	 */
	public function getName()
	{
		return 'StagiaireUpdateJobForm';
	}

	/**
	 *
	 * {@inheritdoc} @see AbstractType::getBlockPrefix()
	 */
	public function getBlockPrefix()
	{
		return $this->getName();
	}

	/**
	 * get the default options
	 *
	 * @return multitype:string multitype:string
	 */
	public function getDefaultOptions()
	{
		return array(
			'validation_groups' => array(
				'job'
			)
		);
	}

	/**
	 *
	 * {@inheritdoc} @see AbstractType::configureOptions()
	 */
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults($this->getDefaultOptions());
	}
}