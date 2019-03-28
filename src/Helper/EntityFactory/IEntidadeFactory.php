<?php
namespace App\Helper\EntityFactory;

interface IEntidadeFactory
{
    public function criarEntidade(string $json);
}