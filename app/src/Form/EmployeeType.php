<?php

namespace App\Form;

use App\Entity\Employee;
use App\Entity\Project;
use App\Enum\EmployeeContract;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmployeeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('first_name')
            ->add('last_name')
            ->add('entry_date')
            ->add('contract', EnumType::class, [
                'class'     => EmployeeContract::class
            ])
            ->add('email')
            // ->add('projects', EntityType::class, [
            //     'class' => Project::class,
            //     'choice_label' => 'name',
            //     'multiple' => true,
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Employee::class,
        ]);
    }
}
