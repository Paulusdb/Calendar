<?php

declare(strict_types=1);

namespace App\Form\ChangeMonth;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class ChangeMonthType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('submit', SubmitType::class)
            ->add('changeMonth', HiddenType::class)
            ->add('currentDateTime', HiddenType::class)
        ;
    }
}