<?php
/**
 * Created by PhpStorm.
 * User: tarab
 * Date: 09/04/2020
 * Time: 16:02
 */

namespace App\Form;

use Symfony\Component\Form\Forms;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Entity\Auction;

class UserAuctionType
{
    public function buildForm($auction){

        $formFactoryBuilder = Forms::createFormFactoryBuilder();

// Form factory builder configuration ...

        $formFactory = $formFactoryBuilder->getFormFactory();

        $form = $formFactory->createBuilder(FormType::class, $task)
            ->setAction('bidOnAuction')
            ->setMethod('POST')
            ->add('number', TextType::class)
            ->add('name', TextType::class)
            ->add('deadline', DateType::class)
            ->add('save', SubmitType::class)
            ->getForm();
    }
}