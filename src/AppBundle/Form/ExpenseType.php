<?php

namespace AppBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Choice;
use Doctrine\ORM\EntityRepository;
//use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ExpenseType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('account', EntityType::class, array(
                'class' => 'AppBundle:Account',
                'choice_label' => 'name',
                'required' => false,
            ))
            ->add('supplier', EntityType::class, array(
                'class' => 'AppBundle:Supplier',
                'choice_label' => 'name',
                'required' => false,
            ))
            ->add('description')
            ->add('currency', EntityType::class, array(
                'class' => 'AppBundle:Currency',
                'choice_label' => 'code',
                'required' => false,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')->orderBy('c.id', 'ASC');
                }
            ))
            ->add('price')
            ->add('exchangeRate')
            ->add('quantity')
            ->add('total')
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Expense'
        ));
    }
}
