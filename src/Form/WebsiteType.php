<?php

namespace Dridialaa\SyliusSiteParserPlugin\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Dridialaa\SyliusSiteParserPlugin\Entity\Website;

class WebsiteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Website Name',
            ])
            ->add('url', TextType::class, [
                'label' => 'Website URL',
            ])->add('cronExpression', TextType::class, [
                'label' => 'Cron Expression',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Ex: 0 * * * * (toutes les heures)',
                ],
                'help' => 'Utilisez une expression cron pour planifier le parsing.',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Website::class,
        ]);
    }
}