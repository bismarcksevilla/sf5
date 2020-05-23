<?php
namespace App\Entity\User;

use App\Entity\Admin\Compania;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use App\Services\Token;

/**
 * @ORM\Entity(repositoryClass="App\Repository\User\UserRepository")
 * @ORM\Table(name="gl_usuarios")
 * @ORM\HasLifecycleCallbacks
 * @UniqueEntity(fields={"email"}, message="El correo ya está registrado")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $slug;

    /**
     * @ORM\Column(type="string", length=180, unique=true, nullable=false)
     */
    private $email;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $mailconfirmado = 0;

    /**
     * @ORM\Column(type="text")
     */
    private $role = 'ROLE_USER';

    /**
     * @var string The hashed password
     * @ORM\Column(type="string", nullable=true)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=40)
     *
     * @Assert\NotBlank(message="El campo es requerido")
     * @Assert\Length(
     *      min = "1",
     *      max = "40",
     *      minMessage = "Muy pocos caracteres para validar.",
     *      maxMessage = "Escriba menos caracteres."
     * )
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=40, nullable=true)
     *
     * @Assert\Length(
     *      min = "1",
     *      max = "40",
     *      minMessage = "Muy pocos caracteres para validar.",
     *      maxMessage = "Escriba menos caracteres."
     * )
     */
    private $nombre2;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=40)
     *
     * @Assert\NotBlank(message="El campo es requerido")
     * @Assert\Length(
     *      min = "1",
     *      max = "40",
     *      minMessage = "Muy pocos caracteres para validar.",
     *      maxMessage = "Escriba menos caracteres."
     * )
     */
    private $apellido;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=40, nullable=true)
     *
     * @Assert\Length(
     *      min = "1",
     *      max = "40",
     *      minMessage = "Muy pocos caracteres para validar.",
     *      maxMessage = "Escriba menos caracteres."
     * )
     */
    private $apellido2;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=20, nullable=true)
     *
     * @Assert\NotBlank(message="El campo es requerido")
     * @Assert\Length(
     *      min = "8",
     *      max = "200",
     *      minMessage = "Muy pocos caracteres para validar.",
     *      maxMessage = "Escriba menos caracteres."
     * )
     */
    private $telefono;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=20)
     */
    private $estado = 'ACTIVO';

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private $efectivo;

    /**
     * @ORM\Column(type="date")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="date")
     */
    private $updatedAt;



    # # # # # # # # # # #
    # # # # # # # # # # #
    # F U N C T I O N S #
    # # # # # # # # # # #
    # # # # # # # # # # #

    /**
     * A visual identifier that represents this user.
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }


    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        // $roles = $this->roles;

        // guarantee every user at least has ROLE_USER
        // $roles[] = 'ROLE_USER';

        // return array_unique($roles);
        return [$this->getRole()];
    }


    public function setRoles(array $roles): self
    {
        // $this->roles = $roles;
        $this->role = $roles[0];

        return $this;
    }


    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }


    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }


    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }


    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * El efectivo es sumado o restado al valor existente
     */
    public function setEfectivo(?string $efectivo): self
    {
        $this->efectivo += ($efectivo);

        return $this;
    }


    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function update()
    {
        $this->setUpdatedAt(new \DateTime('now'));

        if ($this->getCreatedAt() == null):
            $this->setCreatedAt(new \DateTime('now'));
        endif;

        if ($this->getSlug() == null):
            $this->setSlug( Token::generar(30,"l") );
        endif;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getMailconfirmado(): ?bool
    {
        return $this->mailconfirmado;
    }

    public function setMailconfirmado(?bool $mailconfirmado): self
    {
        $this->mailconfirmado = $mailconfirmado;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getNombre2(): ?string
    {
        return $this->nombre2;
    }

    public function setNombre2(?string $nombre2): self
    {
        $this->nombre2 = $nombre2;

        return $this;
    }

    public function getApellido(): ?string
    {
        return $this->apellido;
    }

    public function setApellido(string $apellido): self
    {
        $this->apellido = $apellido;

        return $this;
    }

    public function getApellido2(): ?string
    {
        return $this->apellido2;
    }

    public function setApellido2(?string $apellido2): self
    {
        $this->apellido2 = $apellido2;

        return $this;
    }

    public function getTelefono(): ?string
    {
        return $this->telefono;
    }

    public function setTelefono(?string $telefono): self
    {
        $this->telefono = $telefono;

        return $this;
    }

    public function getEstado(): ?string
    {
        return $this->estado;
    }

    public function setEstado(string $estado): self
    {
        $this->estado = $estado;

        return $this;
    }

    public function getEfectivo(): ?string
    {
        return $this->efectivo;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getCompania(): ?Compania
    {
        return $this->compania;
    }

    public function setCompania(?Compania $compania): self
    {
        $this->compania = $compania;

        return $this;
    }

}