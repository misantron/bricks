<?php

namespace Bricks\Validator;

class RequiredValidator extends Validator
{
    protected $message = 'is required';

    public function validate($value)
    {
        $isValid = false;
        if(!is_null($value)) {
            if(is_array($value)) {
                foreach($value as $item) {
                    if($item !== '') {
                        $isValid = true;
                        break;
                    }
                }
            } elseif ($value !== '') {
                $isValid = true;
            }
        }
        return $isValid;
    }
}