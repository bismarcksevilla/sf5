<?php

namespace App\Repository\User;

use App\Entity\User\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newEncodedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    /**
     * Pagina
     */
    public function filtrar( $search=false)
    {
        $qb = $this->createQueryBuilder('u')
            ->orderBy('u.nombre', 'DESC')
        ;

        if( $search ){
            $qb->setParameter('search',  "%".trim($search)."%" );
            $qb->andWhere('
                CONCAT(
                    u.nombre,
                    u.apellido
                ) like :search
            ');
        }else{
            $qb->andWhere("u.estado = 'ACTIVO'" );
        }

        return $qb;
    }


    /**
     * Lista de selección
     */
    public function getChoices()
    {
        $qb = $this->createQueryBuilder('u')
            ->orderBy('u.nombre', 'ASC')
        ;

        return $qb;
    }
}
