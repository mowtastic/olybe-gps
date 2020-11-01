<?php

namespace App\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class SearchStationModel
 *
 * @package App\Form\Model
 */
class SearchStationModel
{
    /**
     * @var string $coordinates
     * @Assert\NotBlank
     */
    private $coordinates;

    /**
     * @return string
     */
    public function getCoordinates(): string
    {
        return $this->coordinates;
    }

    /**
     * @param string $coordinates
     */
    public function setCoordinates(string $coordinates): void
    {
        $this->coordinates = $coordinates;
    }

}