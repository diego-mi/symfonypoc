<?php

namespace App\Controller;

use App\Entity\Especialidade;
use App\Helper\EntityFactory\EspecialidadeFactory;
use App\Helper\Request\FiltersAndPaginationRequest;
use App\Repository\EspecialidadeRepository;
use Doctrine\ORM\EntityManagerInterface;

class EspecialidadesController extends BaseController
{
    /**
     * MedicosController constructor.
     * @param EntityManagerInterface $entityManager
     * @param EspecialidadeRepository $especialidadeRepository
     * @param EspecialidadeFactory $factory
     * @param FiltersAndPaginationRequest $filtersAndPaginationRequest
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        EspecialidadeRepository $especialidadeRepository,
        EspecialidadeFactory $factory,
        FiltersAndPaginationRequest $filtersAndPaginationRequest
    )
    {
        parent::__construct($especialidadeRepository, $entityManager, $factory, $filtersAndPaginationRequest);
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
