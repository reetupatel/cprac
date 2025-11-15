<?php

if (! function_exists('successMessage')) {
    function successMessage($model, $action)
    {
        return $model . ' ' . $action . ' successfully.';
    }
}

if (! function_exists('errorMessage')) {
    function errorMessage($model, $action)
    {
        return 'Failed to ' . $action . ' ' . $model . '. Please try again.';
    }
}
