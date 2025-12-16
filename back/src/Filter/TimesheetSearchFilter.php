<?php

namespace App\Filter;

use ApiPlatform\Doctrine\Orm\Filter\AbstractFilter;
use ApiPlatform\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Metadata\Operation;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\TypeInfo\Type;
final class TimesheetSearchFilter extends AbstractFilter
{
    protected function filterProperty(
        string $property,
               $value,
        QueryBuilder $queryBuilder,
        QueryNameGeneratorInterface $queryNameGenerator,
        string $resourceClass,
        Operation $operation = null,
        array $context = []
    ): void {

        if (!$this->isPropertyEnabled($property, $resourceClass)) {
            return;
        }

        if (null === $value || '' === $value) {
            return;
        }

        $alias = $queryBuilder->getRootAliases()[0];
        $parameterName = $queryNameGenerator->generateParameterName($property);

        switch ($property) {
            case 'uuid':
                $queryBuilder
                    ->andWhere(sprintf('%s.uuid = :%s', $alias, $parameterName))
                    ->setParameter($parameterName, $value);
                break;

            case 'startPeriod':
            case 'endPeriod':
                try {
                    $date = new \DateTime($value);

                    $queryBuilder
                        ->andWhere(sprintf('%s.%s = :%s', $alias, $property, $parameterName))
                        ->setParameter($parameterName, $date);
                } catch (\Exception $e) {

                }
                break;

            case 'status':
                $queryBuilder
                    ->andWhere(sprintf('%s.status = :%s', $alias, $parameterName))
                    ->setParameter($parameterName, $value);
                break;
        }

    }

    public function getDescription(string $resourceClass): array
    {
        $description = [];

        $properties = $this->getProperties();
        if (null === $properties) {
            $properties = array_fill_keys(
                $this->getClassMetadata($resourceClass)->getFieldNames(),
                null
            );
        }

        foreach ($properties as $property => $strategy) {
            $description["search[$property]"] = [
                'property' => $property,
                'type' => Type::string(),
                'required' => false,
                'description' => "Search by $property",
                'openapi' => [
                    'example' => $this->getExampleValue($property),
                    'allowReserved' => false,
                    'allowEmptyValue' => true,
                    'explode' => false,
                ],
            ];
        }

        return $description;
    }

    private function getExampleValue(string $property): string
    {
        return match ($property) {
            'uuid' => '123e4567-e89b-12d3-a456-426614174000',
            'startPeriod', 'endPeriod' => '2025-12-06T00:00:00+00:00',
            'status' => 'approved',
            default => 'value',
        };
    }
}
