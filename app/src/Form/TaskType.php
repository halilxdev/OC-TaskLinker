<?php

namespace App\Form;

use App\Entity\Employee;
use App\Entity\Project;
use App\Entity\Task;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use App\Enum\TaskStatus;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('deadline')
            ->add('employee', EntityType::class, [
                'class' => Employee::class,
                'choice_label' => function (Employee $employee) {
                    return $employee->getFirstName() . ' ' . $employee->getLastName();
                },
            ])
            ->add('status', EnumType::class, [
                'class' => TaskStatus::class,
                'choice_label' => function (TaskStatus $status) {
                    return match ($status) {
                        TaskStatus::TODO  => 'À faire',
                        TaskStatus::DOING => 'En cours',
                        TaskStatus::DONE  => 'Terminé',
                    };
                },
            ]);
        }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
        ]);
    }
}
