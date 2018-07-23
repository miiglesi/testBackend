<?php
/**
 *  MODELO DE DATOS
 *
 */
namespace App\Models;
Abstract  class Model{

    protected $db;
    public function __construct($db)
    {
        $this->db = $db;
    }

}