<?php

namespace App\Validators;

use App\Conf\Conf;
use Symfony\Component\Validator\Exception\InvalidArgumentException;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;

class RequestParametersValidator
{

    public function validate(array $parameters, Conf $conf)
    {
        $constraint = new Assert\Collection(
            [
                'players' => [
                    new Assert\Type('array'),
                    new Assert\Count(['min' => $conf::MIN_PLAYERS, 'max' => $conf::MAX_PLAYERS])
                ]
            ]
        );

        $validator = Validation::createValidator();

        $violations = $validator->validate(['players' => $parameters['players']], $constraint);

        if (0 !== $violations->count()) {
            throw new InvalidArgumentException("Invalid request parameter : players");
        }

    }
}