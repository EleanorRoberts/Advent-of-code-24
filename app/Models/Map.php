<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Map extends Model
{
    private string $source;
    private string $destination;

    public function __construct(
        string $mapDetails,
        private array $map,
    ) {
        [ $this->source, $this->destination ] = preg_split('/-to-|[ ]/', $mapDetails);
    }

    public function findNextIndex($searchIndex): int
    {
        foreach ($this->map as $mapCoordinate) {
            $mappingArray = explode(' ', $mapCoordinate);
            $destinationRange = (int)$mappingArray[0];
            $sourceRange = (int)$mappingArray[1];
            $rangeLength = (int)$mappingArray[2];

            if ($searchIndex >= $sourceRange && $searchIndex <= ($sourceRange + $rangeLength)) {
                return $destinationRange + ($searchIndex - $sourceRange);
            }
        }

        return $searchIndex;
    }

    public function findLowestIndex($searchRange): int
    {
        foreach ($this->map as $mapCoordinate) {
            $mappingArray = explode(' ', $mapCoordinate);
            $destinationRange = (int)$mappingArray[0];
            $sourceRange = (int)$mappingArray[1];
            $rangeLength = (int)$mappingArray[2];


            // if the min or max in the searchRange is greater than the $sourceRange and less than the $sourceRange + $rangeLength,
            // Find the range of indexes which falls within both ranges
            if (
                ($searchRange['min'] >= $sourceRange && $searchRange['min'] <= $sourceRange) ||
                ($searchRange['max'] >= $sourceRange && $searchRange['max'] <= $sourceRange)
            ) {
                return; // $destinationRange + ($searchIndex - $sourceRange);
            }
        }

        return $searchIndex;
    }

    public function getSource()
    {
        return $this->source;
    }

    public function getDestination()
    {
        return $this->destination;
    }
}
