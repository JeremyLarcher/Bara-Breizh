<?php

namespace App\Form;

use App\Entity\Ardoise;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Symfony\Component\Validator\Constraints as Assert;

class ArdoiseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            /*->add('nomPhoto')*/
            ->add('titre')
            ->add('descriptif')
            ->add('imageFile', VichFileType::class, [
                'required' => false,
                'allow_delete' => true,
                'constraints' => [
                    new Assert\File([
                        'maxSize' => '2M', // Limite de taille en mégaoctets
                        'mimeTypes' => ['image/*'], // Autoriser uniquement les fichiers image
                        'mimeTypesMessage' => 'Veuillez télécharger une image valide.',
                    ]),
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ardoise::class,
        ]);
    }
}
