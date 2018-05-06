<?php declare(strict_types = 1);

namespace App\Transaction\Form;

use App\Entity\Account;
use App\Entity\Transaction;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TransactionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'type',
                ChoiceType::class,
                [
                    'choices' => [
                        Transaction::TYPE_SAVE => Transaction::TYPE_SAVE,
                        Transaction::TYPE_SPEND => Transaction::TYPE_SPEND,
                    ],
                    'expanded' => true,
                ]
            )
            ->add(
                'account',
                EntityType::class,
                [
                    'class' => Account::class,
                    'choice_label' => 'name',
                ]
            )
            ->add('title')
            ->add('amount', MoneyType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('data_class', TransactionData::class);
    }
}