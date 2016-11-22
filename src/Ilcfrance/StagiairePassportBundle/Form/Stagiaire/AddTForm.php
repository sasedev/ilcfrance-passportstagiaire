<?php
namespace Ilcfrance\StagiairePassportBundle\Form\Stagiaire;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class AddTForm extends AbstractType
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
		$builder->add('lastName', TextType::class, array(
			'label' => 'Stagiaire.lastName.label'
		));

		$builder->add('firstName', TextType::class, array(
			'label' => 'Stagiaire.firstName.label'
		));

		$builder->add('address', TextType::class, array(
			'label' => 'Stagiaire.address.label'
		));

		$builder->add('town', TextType::class, array(
			'label' => 'Stagiaire.town.label'
		));

		$builder->add('job', TextType::class, array(
			'label' => 'Stagiaire.job.label'
		));

		$builder->add('level', TextType::class, array(
			'label' => 'Stagiaire.level.label'
		));

		$builder->add('needs', TextareaType::class, array(
			'label' => 'Stagiaire.needs.label'
		));

		$builder->add('courses', TextType::class, array(
			'label' => 'Stagiaire.courses.label',
			'required' => false
		));
	}

	/**
	 *
	 * {@inheritdoc} @see FormTypeInterface::getName()
	 * @return string
	 */
	public function getName()
	{
		return 'StagiaireAddForm';
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
				'lastName',
				'firstName',
				'address',
				'town',
				'job',
				'initLevel',
				'level',
				'needs',
				'courses'
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