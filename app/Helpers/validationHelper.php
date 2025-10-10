<?php

use Illuminate\Support\MessageBag;

if(!function_exists('formatedError')){

    function formatedError(MessageBag $errors)
    {

        $formattedError = [];

        foreach ($errors->toArray() as $field => $messages) {
            $formattedError[$field] = implode('',$messages);
        }

        return $formattedError;
    }
}