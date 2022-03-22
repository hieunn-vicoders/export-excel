<?php

namespace VCComponent\Laravel\Export\Validators;

use VCComponent\Laravel\Vicoders\Core\Validators\AbstractValidator;

class ExportValidator extends AbstractValidator
{
    protected $rules = [
        'RULE_EXPORT' => [
            'extension' => ['required', 'regex:/(^xlsx$)|(^csv$)/'],
        ],
        'RULE_EXPORT_CONTACT' => [
            'slug' => ['required', 'exists:contact_forms'],
            'from_date' => ['date'],
            'to_date' => ['date', 'after_or_equal:from_date'],
            'status' => ['numeric'],
        ],
    ];
}
