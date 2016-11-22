<?php
namespace Ilcfrance\StagiairePassportBundle\Form\StagiaireRecord;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
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
		$builder->add('recordDate', DateTimeType::class, array(
			'label' => 'StagiaireRecord.recordDate.label',
			'widget' => 'single_text',
			'date_format' => 'Y-m-d H:i:s'
		));

		$builder->add('worksCovered', TextareaType::class, array(
			'label' => 'StagiaireRecord.worksCovered.label',
			'required' => false
		));

		$builder->add('comments', TextareaType::class, array(
			'label' => 'StagiaireRecord.comments.label',
			'required' => false
		));

		$builder->add('homeworks', TextareaType::class, array(
			'label' => 'StagiaireRecord.homeworks.label',
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
		return 'StagiaireRecordAddForm';
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
				'recordDate',
				'worksCovered',
				'comments',
				'homeworks'
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