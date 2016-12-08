<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Form\ImageType;


class PortFolioType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options = [])
    {
        $builder
            ->add('title', TextType::class, array(
                'label' => 'Titre',
                'attr' => array(
                    'class' => 'form-control'
                )))
            ->add('sound', TextType::class, array(
                'label' => 'Lien de partage Soundcloud',
                'attr' => array(
                    'class' => 'form-control'
                )))
            ->add('date', DateType::class, array(
                'label' => 'Date',
                'attr' => array(
                    'class' => 'form-control'
                )))
            ->add('company', TextType::class, array(
                'label' => 'Date',
                'attr' => array(
                    'class' => 'form-control'
                )))
            ->add('image', ImageType::class, array(
                    'required' => false
                ))
            ->add('descript', TextareaType::class, array(
                'label' => 'Description du projet',
                'attr' => array(
                    'class' => 'form-control'
                )))
            ->add('save', SubmitType::class, array(
                    'label' => 'Valider',
                    'attr' => array(
                        'class' => 'btn btn-default'
                )))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\PortFolio'
        ));
    }
}
