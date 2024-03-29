<?php

namespace App\Repository;

use App\Entity\Property;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query;
use App\Entity\PropertySearch;

/**
 * @method Property|null find($id, $lockMode = null, $lockVersion = null)
 * @method Property|null findOneBy(array $criteria, array $orderBy = null)
 * @method Property[]    findAll()
 * @method Property[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PropertyRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Property::class);
    }

    /**
     * récupère les biens qui ne sont pas vendu
     * @return Query[]
     */
    public function findAllVisibleQuery(PropertySearch $search): Query
    {
        // return $this->createQueryBuilder('p')
        //     ->where('p.sold = false')
        //     ->getQuery()
        //     ->getOneOrNullResult()
        // ;

        $query = $this->findVisibleQuery();
        if ($search->getMaxPrice()) {
            $query = $query
                ->andWhere('p.price <= :maxprice')
                ->setParameter('maxprice', $search->getMaxPrice());
        }

        if ($search->getMinSurface()) {
            $query = $query
                ->andWhere('p.surface >= :minsurface')
                ->setParameter('minsurface', $search->getMinSurface());
        }

        if ($search->getOptions()->count() > 0) {
            $k = 0;
            foreach ($search->getOptions() as $option ) {
                $k++;
                $query = $query
                    ->andWhere(":options$k MEMBER OF p.options")
                    ->setParameter("options$k",$option);
            }
        }

        return $query->getQuery();
        
    }


    /**
     * récupère les 4 biens récent qui ne sont pas vendu
     * @return Property[]
     */
    public function findLatest(): array
    {
        // return $this->createQueryBuilder('p')
        //     ->where('p.sold = false')
        //     ->setMaxResults(4)
        //     ->getQuery()
        //     ->getOneOrNullResult()
        // ;

        return $this->findVisibleQuery()
            ->setMaxResults(4)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Ceci pour éviter de se répéter dans les méthodes findAllVisible et findLatest
     */
    private function findVisibleQuery(): QueryBuilder{
        return $this->createQueryBuilder('p')
            ->where('p.sold = false');
    }

    // /**
    //  * @return Property[] Returns an array of Property objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Property
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
