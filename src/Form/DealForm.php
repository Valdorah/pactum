<?php

namespace App\Form;

use App\Entity\Deal;
use App\Entity\DealType;
use App\Entity\Group;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class DealForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class)
            ->add('description', TextareaType::class)
            ->add('image', UrlType::class)
            ->add('url', UrlType::class)
            ->add('price', NumberType::class)
            ->add('normalPrice', NumberType::class)
            ->add('deliveryCost', NumberType::class)
            ->add('discountCode', TextType::class)
            ->add('groups', EntityType::class, [
                'class' => Group::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('g');
                },
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true
            ])
            ->add('type', EntityType::class, [
                'class' => DealType::class,
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('t');
                },
                'choice_label' => 'typeName',
                'expanded' => true
            ])
            ->add('save', SubmitType::class)

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Deal::class,
        ]);
    }
}
