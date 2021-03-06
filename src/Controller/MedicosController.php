<?php
namespace App\Controller;

use App\Entity\Medico;
use App\Helper\Request\FiltersAndPaginationRequest;
use App\Helper\EntityFactory\MedicoFactory;
use App\Repository\MedicoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MedicosController extends BaseController
{
    /**
     * MedicosController constructor.
     * @param EntityManagerInterface $entityManager
     * @param MedicoFactory $medicoFactory
     * @param MedicoRepository $medicoRepository
     * @param FiltersAndPaginationRequest $filtersAndPaginationRequest
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        MedicoFactory $medicoFactory,
        MedicoRepository $medicoRepository,
        FiltersAndPaginationRequest $filtersAndPaginationRequest
    )
    {
        parent::__construct($medicoRepository, $entityManager, $medicoFactory, $filtersAndPaginationRequest);
    }

    /**
     * @Route("/especialidades/{especialidadeId}/medicos", methods={"GET"})
     * @param int $especialidadeId
     * @return Response
     */
    public function buscaPorEspecialidade(int $especialidadeId): Response
    {
        $medicos = $this->repository->findBy([
            'especialidade' => $especialidadeId
        ]);

        return new JsonResponse($medicos);
    }

    /**
     * @param Medico $entidadeExistente
     * @param Medico $entidadeRecebida
     * @return mixed
     */
    public function updateEntity($entidadeExistente, $entidadeRecebida): void
    {
        $entidadeExistente
            ->setCrm($entidadeRecebida->getCrm())
            ->setNome($entidadeRecebida->getNome());
    }
}