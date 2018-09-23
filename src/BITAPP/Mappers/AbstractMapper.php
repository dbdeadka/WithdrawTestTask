<?php declare(strict_types=1);

namespace BITAPP\Mappers;

abstract class AbstractMapper
{
    /**
     * Get the Name of the entity table
     *
     * @return string
     */
    abstract public static function getTableName() : string;

    /**
     * @param array $dbrow
     * Get the Name of the entity table
     */
    abstract public static function createEntityFromDB(array $dbrow);
}
