<?php 
namespace App\Services;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Routing\RouterInterface;
 
/**
 * Ayudante para enviar mail
 */
class Mail
{
    private $mailer;
    private $container;
    private $templating;
    private $router;
    
    # Constructor
    public function __construct( \Swift_Mailer $mailer, ContainerInterface $container, RouterInterface $router)
    {
        $this->mailer = $mailer;
         
        $this->container = $container;

        $this->templating   = $container->get('twig');

        $this->router       = $router;

    }


    /**
     * string: [filename] Nombre de plantilla mail
     *          require: data.mail, data.asunto
     * array: [data] información variable
     * array: [files] archivos adjuntos
     */
    public function enviar($filename, array $data=[], array $add_pdf_files=[] )
    {     
        $mensaje = $this->mailer->createMessage();

        # De: 
        if( !isset( $data['a'] )):
            
            $data['a'] = "contacto@raymondrosecc.com";
        endif;
        $mensaje->setTo($data['a']);

        # De: 
        if( !isset( $data['de'] )):
            
            $data['de'] = "contacto@raymondrosecc.com";
        endif;
        $mensaje->setFrom($data['de']);
        
        // $mensaje->setFrom( $this->container->getParameter('mailer_from') );

        # Asunto:
        if( !isset( $data['asunto'] )):
            
            $data['asunto'] = "RRCC - Mensaje web";
        endif;
        $mensaje->setSubject( $data['asunto'] );

        # Responder A:
        if( !isset( $data['responder'] ) ):
            
            $data['responder'] = "contacto@raymondrosecc.com";
        endif;
        $mensaje->setReplyTo( $data['responder'] );
        
        # Archivos adjuntos
        if( count($add_pdf_files)>0):
            foreach ($add_pdf_files as $path ):

                $mensaje->attach( \Swift_Attachment::fromPath( $path ), "application/pdf");
            endforeach;
        endif;

        # Contenido
        $data['context']= $this->router->getContext(); //url
        $mensaje->setBody( $this->templating->render('mails/'.$filename.'.html.twig', $data ),'text/html');
            
        return $this->mailer->send( $mensaje );
    }

} # END FUNCTION :)