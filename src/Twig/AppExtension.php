<?php
namespace App\Twig;

use Twig\Extension\AbstractExtension ;
use Twig\TwigFilter;

use App\Services\Token;
// use App\Services\Fecha;
use App\Services\Moneda;
// \Twig_Extension


class AppExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            // new \Twig_SimpleFilter('dolar', [$this, 'dolarFilter']),
            new TwigFilter('file', [$this, 'fileFilter']),
            new TwigFilter('price', [$this, 'priceFilter']),
            new TwigFilter('semana', [$this, 'semanaFilter']),
            new TwigFilter('token', [$this, 'tokenFilter']),
            new TwigFilter('letras', [$this, 'letrasFilter']),
            new TwigFilter('dia', [$this, 'diaFilter']),
            new TwigFilter('mes', [$this, 'mesFilter']),
            new TwigFilter('hora12', [$this, 'hora12Filter']),
            new TwigFilter('hora', [$this, 'horaFilter']),
            new TwigFilter('dhora', [$this, 'dhoraFilter']),
            new TwigFilter('fecha', [$this, 'fechaFilter']),
        ];
    }

    // # Valor a dolar
    // public function dolarFilter( $valorD )
    // {

    //     return $valorC;
    // }

    # Decimal a Hora
    public function dhoraFilter( $decimal )
    {
        $hora = floor($decimal);
        // $minutos = round(60*($decimal - $hora));
        $minutos = 60*($decimal - $hora);
        
        if($minutos<10){
            $minutos = "0".round($minutos);
        }else{
            $minutos = round($minutos);
        }

        return $hora.":".$minutos;
    }

    # Hora
    public function fechaFilter( $dateTime )
    {
        return $dateTime->format('d/m/Y h:i:s a');
    }

    # Hora
    public function horaFilter( $dateTime )
    {
        return $dateTime->format('H:i');
    }

    # Hora 12
    public function hora12Filter( $dateTime )
    {
        return $dateTime->format('h:i A');
        // return date("h:i A", strtotime( $dateTime->format('Y/m/d H:i:s')));
    }


    # letras
    public function letrasFilter( $valor, $moneda=false )
    {
        return Moneda::letras( $valor, $moneda);
    }


    # File
    public function fileFilter( $ruta )
    {
        $cont = file_get_contents( $ruta, FILE_USE_INCLUDE_PATH );
        return $cont;
    }

    # Semana
    public function semanaFilter( $num_semana )
    {
        $dateTime = new \DateTime();
        $anio_actual= $dateTime->format('Y');

        # Analiza Anio:
        if($num_semana > 156):

            $anio_actual = $anio_actual + 3;

            $num_semana = $num_semana - 156;
        elseif($num_semana > 104):

            $anio_actual = $anio_actual + 2;

            $num_semana = $num_semana - 104;
        elseif($num_semana > 52):

            $anio_actual = $anio_actual + 1;

            $num_semana = $num_semana - 52;
        endif;

        # Calcula Fecha
        $dateTime->setISODate($anio_actual, $num_semana);

        # Cadena
        $result =
              "Del Lunes "
            . $dateTime->format('d');

        $dateTime->modify('+6 days'); // + 1 semana

        $result
            .=" al Domingo "
            . $dateTime->format('d')
            . " de "
            . Fecha::mes($dateTime->format('m'))
            ." del "
            .$dateTime->format('Y');

        # Out
        return $result;
    }

    # Precio
    public function priceFilter($number, $decimals = 0, $decPoint = '.', $thousandsSep = ',')
    {
        $price = number_format($number, $decimals, $decPoint, $thousandsSep);
        $price = '$'.$price;

        return $price;
    }

    # Token
    public function tokenFilter( $l = 5 )
    {
        return Token::generar( $l, "L" );
    }


    # letras
    public function  mesFilter( $mes )
    {
        // return $mes;
        switch ( (int)$mes) {

            case 1:
                return "Enero";

            case 2:
                return "Febrero";

            case 3:
                return "Marzo";

            case 4:
                return "Abril";

            case 5:
                return "Mayo";

            case 6:
                return "Junio";

            case 7:
                return "Julio";

            case 8:
                return "Agosto";

            case 9:
                return "Septiembre";

            case 10:
                return "Octubre";

            case 11:
                return "Noviembre";

            case 12:
                return "Diciembre";

            default:
                return "";

        }
    }


    # letras
    public function diaFilter( $dia )
    {
        // return $dia;
        switch ( (int)$dia) {

            case 0:
                return "Dom.";

            case 1:
                return "Lun.";

            case 2:
                return "Mar.";

            case 3:
                return "Mié.";

            case 4:
                return "Jue.";

            case 5:
                return "Vie.";

            case 6:
                return "Sáb.";

            case 7:
                return "Dom.";

            default:
                return "";

        }
    }

}