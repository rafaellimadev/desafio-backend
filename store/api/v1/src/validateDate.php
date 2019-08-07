<?php

namespace src;

//VALIDA DATA DE ACORDO COM O FORMATO ESPECIFICADO
function validateDate($date, $format = 'd/m/Y')
{
    $d = \DateTime::createFromFormat($format, $date);
    
    if(!$d || $d->format($format) !== $date)
        throw new \InvalidArgumentException("Data/Horário inválido");
}