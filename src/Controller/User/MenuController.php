<?php

namespace App\Controller\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MenuController extends AbstractController
{
    public function generar($display="vertical", $route=false, $menu='app'):Response
    {
        return $this->render('global/menu/desplegar_elemento.html.twig', [
            'menu' => self::menuApp($menu),
            'route' => $route,
            'display' => $display,
        ]);
    }


    protected static function menuApp($menu):array
    {
        // $esquema['app'] = [
        //     'RESUMEN'=>[
        //         'icon'  => 'clipboard',
        //         'menu'=> 'Resumen',
        //         'route' => 'resumen',
        //         'role'  => 'ROLE_COLABORADOR',
        //     ],
        //     'CONFIG'=>[
        //         'icon' => 'user-cog',
        //         'menu'=> '',
        //         'role' => 'ROLE_ADMIN',
        //         'items' =>[
        //             'Usuarios'=>[
        //                 'icon'=> 'users',
        //                 'menu'=> '',
        //                 'route'=> 'usuario_listar',
        //                 'role' => 'ROLE_ADMIN',
        //             ],
        //         ],
        //     ],
        // ];

        $esquema['user'] = [
            'Mi Perfil'=>[
                'icon'=> 'user',
                'menu'=> '',
                'route'=> 'usuario_perfil',
                'role' => 'ROLE_USER',
            ],
            'Salir'=>[
                'icon'=> 'user-lock',
                'menu'=> '',
                'route'=> 'app_logout',
                'role' => 'ROLE_USER',
            ],
        ];

        return $esquema[$menu];
    }


}
