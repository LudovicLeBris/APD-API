<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Project;
use App\Domain\AppUser\Entity\AppUser;
use Doctrine\Persistence\ManagerRegistry;
use App\Domain\AppUser\Entity\AppUserRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements AppUserRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function getAppUserByEmail(string $email): ?AppUser
    {
        $user = $this->findOneBy(['email' => $email]);

        if ($user === null) {
            return null;
        }

        $appUser = new AppUser(
            $user->getEmail(),
            $user->getPassword(),
            $user->getLastname(),
            $user->getFirstname(),
            $user->getCompany(),
            $user->getRoles()[0],
            $user->isIsEnable()
        );
        $appUser->setId($user->getId());

        return $appUser;
    }

    public function getUserByEmail(string $email): ?User
    {
        $user = $this->findOneBy(['email' => $email]);

        if ($user === null) {
            return null;
        } else {
            return $user;
        }
    }

    public function getAppUserById(int $id): ?AppUser
    {
        $user = $this->find($id);

        if ($user === null) {
            return null;
        }

        $appUser = new AppUser(
            $user->getEmail(),
            $user->getPassword(),
            $user->getLastname(),
            $user->getFirstname(),
            $user->getCompany(),
            $user->getRoles()[0],
            $user->isIsEnable()
        );
        $appUser->setId($user->getId());

        return $appUser;
    }

    public function getUserbyId(int $id): ?User
    {
        $user = $this->find($id);

        if ($user === null) {
            return null;
        } else {
            return $user;
        }
    }

    public function addAppUser(AppUser $appUser): void
    {
        $user = new User();
        $user
            ->setEmail($appUser->getEmail())
            ->setPassword($appUser->getPassword())
            ->setLastname($appUser->getLastname())
            ->setFirstname($appUser->getFirstname())
            ->setCompany($appUser->getCompany())
            ->setRoles([$appUser->getRole()])
            ->setIsEnable($appUser->getIsEnable())
        ;
        
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    public function updateAppUser(AppUser $appUser): void
    {
        $user = $this->find($appUser->getId());
        $user
            ->setEmail($appUser->getEmail())
            ->setPassword($appUser->getPassword())
            ->setLastname($appUser->getLastname())
            ->setFirstname($appUser->getFirstname())
            ->setCompany($appUser->getCompany())
            ->setRoles([$appUser->getRole()])
            ->setIsEnable($appUser->getIsEnable())
        ;
        
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush($user);
    }

    public function deleteAppUser(int $id): void
    {
        $user = $this->find($id);
        $this->getEntityManager()->remove($user);
        $this->getEntityManager()->flush();
    }

//    /**
//     * @return User[] Returns an array of User objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?User
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
