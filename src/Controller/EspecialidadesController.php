<?php

namespace App\Controller;

use App\Entity\Especialidade;
use App\Helper\EspecialidadeFactory;
use App\Repository\EspecialidadeRepository;
use Doctrine\ORM\EntityManagerInterface;

class EspecialidadesController extends BaseController
{
    /**
     * MedicosController constructor.
     * @param EntityManagerInterface $entityManager
     * @param EspecialidadeRepository $especialidadeRepository
     * @param EspecialidadeFactory $factory
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        EspecialidadeRepository $especialidadeRepository,
        EspecialidadeFactory $factory
    )
    {
        parent::__construct($especialidadeRepository, $entityManager, $factory);
    }

    /**
     * @param Especialidade $entidadeExistente
     * @param Especialidade $entidadeRecebida
     */
    public function updateEntity($entidadeExistente, $entidadeRecebida): void
    {
        $entidadeExistente->setDescricao($entidadeRecebida->getDescricao());
    }
}
