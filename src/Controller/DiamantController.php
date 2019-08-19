<?php

namespace App\Controller;

use Swift_Message;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Contracts\Translation\TranslatorInterface;

class DiamantController extends AbstractController
{
    public function uvod()
    {
        return $this->render('uvod.html.twig', [
            'active' => 'uvod',
        ]);
    }

    public function chytraPena()
    {
        return $this->render('chytra-pena.html.twig', [
            'active' => 'chytraPena',
        ]);
    }

    public function zatepleniDomu()
    {
        return $this->render('zatepleni-domu.html.twig', [
            'active' => 'zatepleniDomu',
        ]);
    }

    public function zatepleniDomuPodkrovi()
    {
        return $this->render('zatepleni-domu-podkrovi.html.twig', [
            'active' => 'zatepleniDomu',
        ]);
    }

    public function zatepleniDomuSteny()
    {
        return $this->render('zatepleni-domu-steny.html.twig', [
            'active' => 'zatepleniDomu',
        ]);
    }

    public function zatepleniDomuStrechy()
    {
        return $this->render('zatepleni-domu-strechy.html.twig', [
            'active' => 'zatepleniDomu',
        ]);
    }

    public function strikanaIzolace()
    {
        return $this->render('strikana-izolace.html.twig', [
            'active' => 'strikanaIzolace',
        ]);
    }

    public function graco()
    {
        return $this->render('graco.html.twig', [
            'active' => 'graco',
        ]);
    }

    public function kontakt(Request $request, \Swift_Mailer $mailer, TranslatorInterface $translator)
    {
        $form = $this->createFormBuilder()
            ->add('name', TextType::class, [
                'attr' => ['maxlength' => 50, 'class' => 'form-control', 'placeholder' => $translator->trans('name')],
                'constraints' => [
                    new NotBlank(),
                    new Length(array('max' => 50)),
                ],
            ])
            ->add('email', TextType::class, [
                'attr' => ['maxlength' => 255],
                'constraints' => [
                    new NotBlank(),
                    new Email(),
                    new Length(array('max' => 255)),
                ],
            ])
            ->add('phone', TextType::class, [
                'attr' => ['maxlength' => 50],
                'constraints' => [
                    new NotBlank(),
                    new Length(array('max' => 50)),
                ],
            ])
            ->add('message', TextareaType::class, [
                'attr' => ['maxlength' => 1000],
                'constraints' => [
                    new NotBlank(),
                    new Length(array('max' => 1000)),
                ],
            ])
            ->add('save', SubmitType::class, ['label' => $translator->trans('send')])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();

            $message = (new Swift_Message($translator->trans('contact_email_title')))
                ->setFrom($data['email'])
                ->setTo($this->getParameter('mailer_to'))
                ->setBody($this->renderView('email/contact.html.twig', $data))
            ;
            $sent = $mailer->send($message);

            $this->addFlash(
                'success',
                $translator->trans('contact_email_success')
            );

            return $this->redirectToRoute('kontakt');
        }

        return $this->render('kontakt.html.twig', [
            'active' => 'kontakt',
            'phone' => $this->getParameter('diamant_phone'),
            'form' => $form->createView(),
        ]);
    }

    public function mapaStranek()
    {
        return $this->render('mapa-stranek.html.twig', [
            'active' => 'mapaStranek',
        ]);
    }
}
