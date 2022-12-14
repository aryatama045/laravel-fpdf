<?php

namespace Aryatama\LaraFpdf\Facades;

use Illuminate\Support\Facades\Facade;
use Aryatama\LaraFpdf\LaraFpdf as Fpdf;

/**
 * @see \Aryatama\LaraFpdf\LaraFpdf
 * Class LaraFpdf
 * @package Aryatama\LaraFpdf\Facades
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
