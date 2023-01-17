<?php

namespace App\Form;

use App\Entity\ServicedObject;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ServicedObjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        
        $builder->add('name');

        $builder->add('files', CollectionType::class, [
            'entry_type' => FileType::class,
            'entry_options' => ['label' => false],
            'allow_add' => true,
            'by_reference'=>false
        ]);
        
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ServicedObject::class,
        ]);
    }
}
