<?php

namespace App\Controller;

use App\Entity\Especialidade;
use App\Repository\EspecialidadeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EspecialidadesController extends BaseController
{
    /**
     * MedicosController constructor.
     * @param EntityManagerInterface $entityManager
     * @param EspecialidadeRepository $especialidadeRepository
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        EspecialidadeRepository $especialidadeRepository
    )
    {
        parent::__construct($especialidadeRepository, $entityManager);
    }

    /**
     * @Route("/especialidades", methods="POST")
     * @param Request $request
     * @return Response
     */
    public function nova(Request $request): Response
    {
        $corpoRequisicao = $request->getContent();
        $dadosEmJson = json_decode($corpoRequisicao);

        $especialidade = new Especialidade();
        $especialidade->setDescricao($dadosEmJson->descricao);

        $this->entityManager->persist($especialidade);
        $this->entityManager->flush();

        return new JsonResponse($especialidade);
    }

    /**
     * @Route("/especialidades/{id}", methods={"PUT"})
     * @param int $id
     * @param Request $request
     * @return Response
     */
    public function atualiza(int $id, Request $request): Response
    {
        $corpoRequisicao = $request->getContent();
        $dadosEmJson = json_decode($corpoRequisicao);

        $especialidade = $this->repository->find($id);
        $especialidade->setDescricao($dadosEmJson->descricao);

        $this->entityManager->flush();

        return new JsonResponse($especialidade);
    }
}
