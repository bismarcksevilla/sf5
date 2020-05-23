<?= "<?php\n" ?>
namespace <?= $namespace ?>;

use <?= $entity_full_class_name ?>;
use <?= $form_full_class_name ?>;
<?php if (isset($repository_full_class_name)): ?>
use <?= $repository_full_class_name ?>;
<?php endif ?>

use Symfony\Bundle\FrameworkBundle\Controller\<?= $parent_class_name ?>;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @Route("<?= $route_path ?>")
 */
class <?= $class_name ?> extends <?= $parent_class_name; ?><?= "\n" ?>
{
    const LIMIT     = 10;
    const MENU_ITEM = '<?= $entity_class_name ?>';

    /**
     * @Route("/{<?= $entity_identifier ?>}/{page}", name="<?= $route_name ?>_editar",
     *      methods={"GET","POST"},
     *      requirements={
     *          "<?= $entity_identifier ?>"="[a-zA-Z0-9_-]{0,30}",
     *          "page"="\d+",
     *      }
     * )
     */
    public function vistaMixta($<?= $entity_identifier ?>=null, $page=1, Request $request, PaginatorInterface $paginator, <?= $repository_class_name ?> $Repo):Response
    {
        $em = $this->getDoctrine()->getManager();

        # Listar
        $search = $request->query->get("search");
        // $qb = $Repo->findAll();
        // $qb = $em->getRepository(<?= $entity_class_name ?>::class)->filtrar($search);
        $qb = $em->getRepository(<?= $entity_class_name ?>::class)->findAll();
        $<?= $entity_twig_var_plural ?> = $paginator->paginate($qb, $page, self::LIMIT);

        # Editar
        if($<?= $entity_identifier ?>):
            // $<?= $entity_class_name ?> = $Repo->findOneBySlug($slug);
            $<?= $entity_class_name ?> = $Repo->findOneBy<?= ucfirst($entity_identifier) ?>($<?= $entity_identifier ?>);
        else:
            $<?= $entity_class_name ?> = new <?= $entity_class_name ?>();
        endif;

        $form = $this->createForm(<?= $form_class_name ?>::class, $<?= $entity_class_name ?>);
        $form->handleRequest($request);

        if($form->isSubmitted()){
            if(!$form->isValid() ):
                $this->addFlash( 'danger', 'Corrija los errores señalados para continuar.');
            else:
                $em->persist($<?= $entity_class_name ?>);
                $em->flush();
                if( !$em->flush() ):
                    $this->addFlash( 'success', 'Cambios guardados correctamente.');
                    return $this->redirectToRoute('<?= $route_name ?>_editar',['<?= $entity_identifier ?>' => $<?= $entity_class_name ?>->get<?= ucfirst($entity_identifier) ?>()]);
                    // return $this->redirectToRoute('<?= $route_name ?>_editar',['slug' => $<?= $entity_class_name ?>->getSlug()]);
                    // return $this->redirectToRoute('<?= $route_name ?>_listar');
                else:
                    $this->addFlash( 'danger', 'Lo sentimos, ocurrio un error interno al guardar el registro.');
                endif;
            endif;
        }

        return $this->render('<?= $templates_path ?>/mix.html.twig', [
            'form' => $form->createView(),
            '<?= $route_name ?>' => $<?= $entity_class_name ?>,
            '<?= $entity_twig_var_plural ?>' => $<?= $entity_twig_var_plural ?>,
            'pagina' => $<?= $entity_twig_var_plural ?>,
            'search' => $search,
            'page'    => $page,
        ]);
    }

    /**
     * @Route("/eliminar/{<?= $entity_identifier ?>}", name="<?= $route_name ?>_eliminar",
     *      methods={"GET"},
     *      requirements={
     *          "<?= $entity_identifier ?>"="\d+"
     *      }
     *  )
     */
    public function eliminar($<?= $entity_identifier ?>=null, Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();

        if( $<?= $entity_class_name ?> = $em->getRepository(<?= $entity_class_name ?>::class)->findOneBy<?= ucfirst($entity_identifier) ?>( $<?= $entity_identifier ?> )) {

            $em->remove($<?= $entity_class_name ?>);

            if( !$em->flush() ):
                $this->addFlash( 'success', 'Elemento eliminado correctamente');
            else:
                $this->addFlash( 'danger', 'Lo sentimos, ocurrio un error interno al guardar el registro.');
            endif;
        }else{
            $this->addFlash( 'warning', 'Error, se requiere un identificador válido.');
        }

        return $this->redirect( $request->headers->get('referer') );
        // return $this->redirectToRoute('<?= $route_name ?>_listar');
    }
}