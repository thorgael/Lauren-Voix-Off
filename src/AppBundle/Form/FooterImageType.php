<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Form\ImageType;


class FooterImageType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options = [])
    {
        $builder
            ->add('link', TextType::class, array(
                'label' => 'Lien de l\'image',
                'attr' => array(
                    'class' => 'form-control'
                )))
            ->add('image', ImageType::class, array(
                    'required' => false
                ))
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
            'data_class' => 'AppBundle\Entity\FooterImage'
        ));
    }
}
