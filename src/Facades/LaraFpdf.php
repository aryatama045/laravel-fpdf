<?php

namespace Aryatama045\LaraFpdf\Facades;

use Illuminate\Support\Facades\Facade;
use Aryatama045\LaraFpdf\LaraFpdf as Fpdf;

/**
 * @see \Aryatama045\LaraFpdf\LaraFpdf
 * Class LaraFpdf
 * @package Aryatama045\LaraFpdf\Facades
 */
class LaraFpdf extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return Fpdf::class;
    }
}
