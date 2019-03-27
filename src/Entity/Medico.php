<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 * Class Medico
 * @package App\Entity
 * @ORM\Entity()
 */
class Medico implements JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $crm;

    /**
     * @ORM\Column(type="string")
     */
    private $nome;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Especialidade")
     * @ORM\JoinColumn(nullable=false)
     */
    private $especialidade;

    /**
     * @return mixed
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getCrm(): ?int
    {
        return $this->crm;
    }

    /**
     * @param mixed $crm
     * @return Medico
     */
    public function setCrm($crm): self
    {
        $this->crm = $crm;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getNome(): ?string
    {
        return $this->nome;
    }

    /**
     * @param mixed $nome
     * @return Medico
     */
    public function setNome($nome): self
    {
        $this->nome = $nome;

        return $this;
    }

    /**
     * @return Especialidade|null
     */
    public function getEspecialidade(): ?Especialidade
    {
        return $this->especialidade;
    }

    /**
     * @param Especialidade|null $especialidade
     * @return Medico
     */
    public function setEspecialidade(?Especialidade $especialidade): self
    {
        $this->especialidade = $especialidade;

        return $this;
    }

    /**
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'crm' => $this->getCrm(),
            'nome' => $this->getNome(),
            'especialidade' => $this->getEspecialidade()->getId()
        ];
    }
}