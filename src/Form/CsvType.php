<?php

namespace App\Form;

use App\Entity\Csv;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class CsvType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
		$builder
			// ...
			->add('csv', FileType::class, [
				'label' => 'CSV',

				'mapped' => false,

				'required' => false,

				'constraints' => [
					new File()
				],
			])
			->add('submit', SubmitType::class, ['label' => 'Upload'])
		;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
		$resolver->setDefaults([
			'data_class' => Csv::class,
		]);
    }
}
