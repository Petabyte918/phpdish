<?php

namespace PHPDish\Bundle\ForumBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class ThreadType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class, [
                'label' => 'form.thread.name'
            ])
            ->add('description', TextareaType::class, [
                'label' => 'form.thread.description',
            ])
            ->add('slug', TextType::class, [
                'label' => 'form.thread.slug',
            ])
            ->add('cover', HiddenType::class, [
                'label' => 'form.thread.cover',
            ]);
    }
}