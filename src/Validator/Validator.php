<?php declare(strict_types=1);

namespace App\Validator;

interface Validator
{
    public function __construct(array $options = []);

    public function validate(array $dataToValid): array;
}