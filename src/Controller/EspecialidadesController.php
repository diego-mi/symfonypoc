<?php

namespace App\Controller;

use App\Entity\Especialidade;
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
     * MedicosController constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
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
        $repositorioDeEspecialidades = $this->getDoctrine()
            ->getRepository(Especialidade::class);

        $list = $repositorioDeEspecialidades->findAll();

        return new JsonResponse($list);
    }
}
