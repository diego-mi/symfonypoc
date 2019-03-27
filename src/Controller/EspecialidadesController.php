<?php

namespace App\Controller;

use App\Entity\Especialidade;
use App\Repository\EspecialidadeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EspecialidadesController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var EspecialidadeRepository
     */
    private $especialidadeRepository;

    /**
     * MedicosController constructor.
     * @param EntityManagerInterface $entityManager
     * @param EspecialidadeRepository $especialidadeRepository
     */
    public function __construct(EntityManagerInterface $entityManager, EspecialidadeRepository $especialidadeRepository)
    {
        $this->entityManager = $entityManager;
        $this->especialidadeRepository = $especialidadeRepository;
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
     * @Route("/especialidades", methods={"GET"})
     * @return Response
     */
    public function buscarTodos(): Response
    {
        $list = $this->especialidadeRepository->findAll();

        return new JsonResponse($list);
    }


    /**
     * @Route("/especialidades/{id}", methods={"GET"})
     * @param int $id
     * @return Response
     */
    public function buscarUm(int $id): Response
    {
        return new JsonResponse($this->especialidadeRepository->find($id));
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

        $especialidade = $this->especialidadeRepository->find($id);
        $especialidade->setDescricao($dadosEmJson->descricao);

        $this->entityManager->flush();

        return new JsonResponse($especialidade);
    }

    /**
     * @Route("/especialidades/{id}", methods={"DELETE"})
     * @param int $id
     * @return Response
     */
    public function remover(int $id): Response
    {
        $especialidade = $this->especialidadeRepository->find($id);
        $this->entityManager->remove($especialidade);
        $this->entityManager->flush();

        return new JsonResponse('', Response::HTTP_NO_CONTENT);
    }
}
