<?php 
namespace App\Services;
/*
 * Calculos recurrentes
 */
class Calcular
{
  
    /**
     * Calcular Movimiento de Valor Existente.
     */
    public static function movimiento($valor_existente, $diferencia){
        return  $valor_existente + ($diferencia); 
    }
}