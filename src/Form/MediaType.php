<?php

namespace App\Form;

use App\Entity\Lieu;
use App\Entity\Media;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Mime\MimeTypes;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\Image;

class MediaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('mediaLieu', FileType::class, [
            'label' => '* Ajouter vos images',
            'mapped' => false,
            'multiple' => true,
            'constraints' => [
                new All([ 
                    'constraints' => [
                        new Image([
                            'maxSize' => '20M', // Correction de la taille maximale
                            'maxSizeMessage' => 'Votre image {{ name }} {{ suffix }} au lieu de {{ limit }} {{ suffix }}',
                        ])
                    ]
                ])
            ]
                        ]);}

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Media::class,
        ]);
    }
}
