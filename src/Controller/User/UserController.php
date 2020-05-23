<?php

namespace App\Controller\User;

use App\Entity\User\User;
use App\Form\User\AyudaType;
use App\Form\User\PerfilType;
use App\Form\User\UserType;
use App\Repository\User\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

use App\Services\Mail;

class UserController extends AbstractController
{
    private $limit = 10;

    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('resumen');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('user/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }


    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \Exception('it will be intercepted by the logout key on your firewall');
    }


    /**
     * @Route("/usuario/listar/{page}", name="usuario_listar",
     *      requirements={
     *          "page"="\d+"
     *      }
     * )
     */
    public function listar(Request $request, $page=1, UserRepository $Repo, PaginatorInterface $paginator)
    {
        $search = $request->query->get("search");
        $Usuarios = $paginator->paginate($Repo->filtrar($search), $page, $this->limit);

        return $this->render('user/listar.html.twig', [
            'pagina'   => $Usuarios,
            'usuarios' => $Usuarios,
            'search'   => $search,
        ]);
    }


    /**
     * @Route("/usuario/editar/{slug}", name="usuario_editar",
     *      requirements={
     *          "slug"="[a-zA-Z0-9_-]{0,30}"
     *      }
     * )
     */
    public function editar(Request $request, $slug=false, UserPasswordEncoderInterface $encoder)
    {
        $em = $this->getDoctrine()->getManager();

        if($slug):
            $Usuario = $em->getRepository(User::class)->findOneBySlug($slug);
            $Usuario_mail =  $Usuario->getEmail();
        else:
            $Usuario = new User();
        endif;

        $form = $this->createForm( UserType::class, $Usuario);
        $form->handleRequest($request);

        if($form->isSubmitted()):
            if(!$form->isValid() ):

                $this->addFlash( 'danger', 'Corrija los errores señalados para continuar.');
            else:

                if ($Usuario->getPassword()== null) {
                    $Usuario->setPassword( $encoder->encodePassword( $Usuario, 1234 ));
                    $Usuario->setRole('ROLE_COLABORADOR');
                    $this->addFlash( 'primary', 'El usuario se configuro como COLABORADOR con password [1234]');
                }

                $em->persist($Usuario);

                if( !$em->flush() ):
                    $this->addFlash( 'success', 'Cambios guardados correctamente.');
                    return $this->redirectToRoute('usuario_listar');
                else:
                    $this->addFlash( 'danger', 'Lo sentimos, ocurrio un error interno al guardar el registro.');
                endif;
            endif;
        endif;

        return $this->render('user/editar.html.twig', [
            'form' => $form->createView(),
            'usuario' => $Usuario,
        ]);
    }


    /**
     * @Route("/w/perfil", name="usuario_perfil")
     */
    public function perfil(Request $request, UserPasswordEncoderInterface $encoder)
    {
        if( $Usuario=$this->getUser() ):
            $Usuario_mail =  $Usuario->getEmail();
        else:
            $Usuario = new User();
        endif;

        $form = $this->createForm( PerfilType::class, $Usuario);
        $form->handleRequest($request);

        if($form->isSubmitted()):
            if(!$form->isValid() ):
                $this->addFlash( 'danger', 'Corrija los errores señalados para continuar.');
            else:
                $Usuario->setPassword( $encoder->encodePassword( $Usuario, $Usuario->getPassword())); //$form->get('password')->getData()
                $em = $this->getDoctrine()->getManager();
                $em->persist($Usuario);

                if( !$em->flush() ):
                    $this->addFlash( 'success', 'Usuario creado correctamente.');
                    return $this->redirectToRoute('app_login');
                else:
                    $this->addFlash( 'danger', 'Lo sentimos, ocurrio un error interno al guardar el registro.');
                endif;
            endif;
        endif;

        return $this->render('user/perfil.html.twig', [
            'form' => $form->createView(),
            'usuario' => $Usuario,
        ]);
    }

    /**
     * @Route("/u/eliminar/{slug}" , name="usuario_eliminar",
     *      requirements={
     *          "slug"="[a-zA-Z0-9_]{0,30}"
     *      }
     * )
     */
    public function eliminar(Request $request, $slug=false)
    {
        $em = $this->getDoctrine()->getManager();

        if($Usuario = $em->getRepository(User::class)->findOneBySlug($slug) ){

            if ($Usuario) { //bloquear usuario
                $Usuario->setEstado('INACTIVO');
                $Usuario->setRole('ROLE_USER');

                $em->persist($Usuario);

                if( !$em->flush() ):
                    $this->addFlash( 'success', 'Cambios guardados correctamente.');
                else:
                    $this->addFlash( 'danger', 'Lo sentimos, ocurrio un error interno al guardar el registro.');
                endif;


            # @TODO: habilitar borrar usuarios no comprometidos.
            // }else{ // eliminar

            //     $em->remove( $Usuario );

            //     if( !$em->flush() ):
            //         $this->addFlash( 'success', 'Elemento eliminado correctamente');
            //     else:
            //         $this->addFlash( 'danger', 'Lo sentimos, ocurrio un error interno al guardar el registro.');
            //     endif;
            }
        }else{
            $this->addFlash( 'danger', 'El elemento no existe');
        }

        return $this->redirect( $request->headers->get('referer') );

    }


    /**
     * @Route("/w/ayuda", name="usuario_ayuda")
     */
    public function ayuda( Request $request, Mail $Mail )
    {

        $form = $this->createForm(AyudaType::class);
        $form->handleRequest($request);

        if( $form->isSubmitted() ):
            if( !$form->isValid() ):
                $this->addFlash( 'danger', 'Corrija los errores señalados para continuar.');
            else:

                # reCaptcha de Google
                // $recaptcha  = new ReCaptcha($this->container->getParameter('ga_recaptcha_secret'), new \ReCaptcha\RequestMethod\CurlPost());
                // $captcha    = $recaptcha->verify($request->get('g-recaptcha-response'), $request->getClientIp() );

                // if ( !$captcha->isSuccess() ):

                //     $this->addFlash( 'danger', 'Por favor verifique el captcha.');
                // else:

                if( $Usuario = $this->getDoctrine()->getManager()->getRepository(User::class)->findOneByEmail(
                    $form->get("email")->getData()
                )):

                    if( $Mail->enviar( "user/ayuda", [
                        "user" => $Usuario,
                        "asunto" => "Recuperar Acceso",
                        "a" => $Usuario->getEmail(),
                    ])):
                        $this->addFlash( 'success', 'Revise su bandeja de correo para recuperar el acceso.');
                    else:
                        $this->addFlash( 'danger', 'Error al enviar el mensaje solicitado.');
                    endif;

                    return $this->redirectToRoute('app_login');
                else:

                    $this->addFlash( 'warning', 'Su correo no fué encontrado, por favor registrese.');

                    return $this->redirectToRoute('app_login');
                endif;
                // endif;
            endif;
        endif;

        # Render
        return $this->render('user/ayuda.html.twig', [
            'form'   => $form->createView(),
        ]);

    } # END

}