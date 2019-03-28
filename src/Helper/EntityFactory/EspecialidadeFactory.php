<?php
namespace App\Helper\EntityFactory;

use App\Entity\Especialidade;

class EspecialidadeFactory implements IEntidadeFactory
{

    /**
     * @param string $json
     * @return Especialidade
     */
    public function criarEntidade(string $json): Especialidade
    {
        $dadoEmJson = json_decode($json);

        $especialidade = new Especialidade();
        $especialidade->setDescricao($dadoEmJson->descricao);

        return $especialidade;
    }
}