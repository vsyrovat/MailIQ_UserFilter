<?php declare(strict_types=1);

namespace AppBundle\Service;

use AppBundle\Entity\UserAbout;
use Doctrine\Common\Persistence\ObjectManager;

class FilterConstructor
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * @var \Doctrine\ORM\QueryBuilder
     */
    private $qb;

    private $counter;

    public function __construct(ObjectManager $entityManager)
    {
        $this->em = $entityManager;
        $this->qb = $this->em->createQueryBuilder();
        $this->counter = 0;
    }

    private function subQuery($field)
    {
        $tableAlias = sprintf('ua%d', $this->counter);
        $this->counter++;

        return $this->em->createQueryBuilder()
            ->select("{$tableAlias}.value")
            ->from(UserAbout::class, $tableAlias)
            ->where(sprintf("{$tableAlias}.user=u.id AND {$tableAlias}.item='%s'", $field))
            ->getDQL();
    }

    private function transformField(string $field)
    {
        switch ($field) {
            case 'id':
            case 'email':
                return sprintf('u.%s', $field);
            case 'country':
            case 'firstname':
            case 'state':
                return sprintf('(%s)', $this->subQuery($field));
            default:
                throw new \InvalidArgumentException(
                    "Unknown filter field: $field, expect on of 'id', 'email', 'country', 'firstname', 'state'"
                );
        }
    }

    private function escapeValue($value)
    {
        return $this->em->getConnection()->quote($value);
    }

    public function andX(...$conditions)
    {
        return $this->qb->expr()->andX(...$conditions);
    }

    public function orX(...$conditions)
    {
        return $this->qb->expr()->orX(...$conditions);
    }

    public function eq($field, $value)
    {
        return $this->qb->expr()->eq(
            $this->transformField($field),
            $this->escapeValue($value)
        );
    }

    public function neq($field, $value)
    {
        return $this->qb->expr()->neq(
            $this->transformField($field),
            $this->escapeValue($value)
        );
    }
}
