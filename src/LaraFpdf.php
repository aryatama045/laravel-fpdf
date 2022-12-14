<?php

namespace Aryatama\LaraFpdf;

use Aryatama\LaraFpdf\Fpdf\FPDF;

class LaraFpdf extends FPDF
{
    protected $maxWidth;
    protected $maxHeight;
    protected $marginLeft;
    protected $marginRight;
    protected $angle = 0;

    protected $javascript;
    protected $n_js;
    protected $NewPageGroup;   // variable indicating whether a new group was requested
    protected $PageGroups;     // variable containing the number of pages of the groups
    protected $CurrPageGroup;  // variable containing the alias of the current page group


    public function GetMaxWidth()
    {
        return $this->maxWidth;
    }

    public function GetMaxHeight()
    {
        return $this->maxHeight;
    }

    public function GetMarginLeft()
    {
        return $this->marginLeft;
    }

    public function GetMarginRight()
    {
        return $this->marginRight;
    }

    public function GetAngle()
    {
        return $this->angle;
    }

    public function SetMaxWidth($maxWidth)
    {
        $this->maxWidth = $maxWidth;
    }

    public function SetMaxHeight($maxHeight)
    {
        $this->maxHeight = $maxHeight;
    }

    public function SetMarginLeft($marginLeft)
    {
        $this->marginLeft = $marginLeft;
    }

    public function SetMarginRight($marginRight)
    {
        $this->marginRight = $marginRight;
    }

    public function SetAngle($angle)
    {
        $this->angle = $angle;
    }

    public function SetOficio($width = 216, $height = 330)
    {
        $this->setMaxHeight($width);
        $this->setMaxWidth($height);
    }

    public function SetA4($width = 210, $height = 297)
    {
        $this->setMaxHeight($width);
        $this->setMaxWidth($height);
    }

    public function Cell($w, $h = 0, $txt = '', $border = 0, $ln = 0, $align = '', $fill = false, $link = '')
    {
        parent::Cell($w, $h, utf8_decode($txt), $border, $ln, $align, $fill, $link);
    }

    public function MultiCel($w, $h, $txt, $border = 0, $align = 'J', $fill = false)
    {
        parent::MultiCell($this->CelX($w), $h, $txt, $border, $align, $fill);
    }

    public function Cel($w, $h = 0, $txt = '', $border = 0, $ln = 0, $align = '', $fill = false, $link = '')
    {
        parent::Cell($this->CelX($w), $h, utf8_decode($txt), $border, $ln, $align, $fill, $link);
    }

    public function Rotate($angle, $x = -1, $y = -1)
    {
        if ($x == -1) {
            $x = $this->x;
        }

        if ($y == -1) {
            $y = $this->y;
        }

        if ($this->angle != 0) {
            $this->_out('Q');
        }

        $this->angle = $angle;
        if ($angle != 0) {
            $angle *= M_PI / 180;
            $c = cos($angle);
            $s = sin($angle);
            $cx = $x * $this->k;
            $cy = ($this->h - $y) * $this->k;
            $this->_out(sprintf('q %.5F %.5F %.5F %.5F %.2F %.2F cm 1 0 0 1 %.2F %.2F cm', $c, $s, -$s, $c, $cx, $cy, -$cx, -$cy));
        }
    }

    public function CellRota($X, $Y, $text, $border, $alinha, $angle, $baixe = 0, $ln = 0)
    {
        $this->angle = $angle;
        $nx = $x = $this->GetX();
        $y = $this->GetY();
        $recuo = $x - $baixe;
        $r = 0;
        if ($recuo < 0) {
            $this->SetX($x + $Y);
            $x = $this->GetX();
            $recuo = $x - $baixe;
            $r = $Y;
        }
        if ($angle != 0) {
            $angle *= M_PI / 180;
            $c = cos($angle);
            $s = sin($angle);
            $cx = $x * $this->k;
            $cy = ($this->h - $y) * $this->k;
            $this->_out(sprintf('q %.5F %.5F %.5F %.5F %.2F %.2F cm 1 0 0 1 %.2F %.2F cm', $c, $s, -$s, $c, $cx, $cy, -$cx, -$cy));
        }
        $this->SetXY($recuo, $y - $r);
        $this->Cell($Y, $X, $text, $border, 0, $alinha);
        $this->_out('Q');
        $x = $nx;
        $this->SetXY($x + $X, $y);
        if ($ln) {
            $this->Cell(1, $Y, '', '', 1, '');
        }
    }

    public function MultiRota($X, $Y, $text, $border, $alinha, $angle, $baixe = 0, $ln = 0, $subLines = 3)
    {
        $this->angle = $angle;
        $nx = $x = $this->GetX();
        $y = $this->GetY();
        $recuo = $x - $baixe;
        $r = 0;
        if ($recuo < 0) {
            $this->SetX($x + $Y);
            $x = $this->GetX();
            $recuo = $x - $baixe;
            $r = $Y;
        }
        if ($angle != 0) {
            $angle *= M_PI / 180;
            $c = cos($angle);
            $s = sin($angle);
            $cx = $x * $this->k;
            $cy = ($this->h - $y) * $this->k;
            $this->_out(sprintf('q %.5F %.5F %.5F %.5F %.2F %.2F cm 1 0 0 1 %.2F %.2F cm', $c, $s, -$s, $c, $cx, $cy, -$cx, -$cy));
        }
        $this->SetXY($recuo, $y - $r);
        $this->MultiCell($Y, $X / $subLines, $text, $border, $alinha);
        $this->_out('Q');
        $x = $nx;
        $this->SetXY($x + $X, $y);
        if ($ln) {
            $this->Cell(1, $Y, '', '', 1, '');
        }
    }

    public function _endpage()
    {
        if ($this->angle != 0) {
            $this->angle = 0;
            $this->_out('Q');
        }
        parent::_endpage();
    }

    public function CelX($width)
    {
        return ($width * ($this->w - $this->lMargin - $this->rMargin)) / 100;
    }

    public function PosX($width)
    {
        $this->SetX($this->CelX($width) + $this->lMargin);
    }

    public function SetDarkFill($inverse = false)
    {
        if ($inverse) {
            $this->SetFillColor(204, 204, 204);
            $this->SetTextColor(34, 34, 34);
        } else {
            $this->SetFillColor(102, 102, 102);
            $this->SetTextColor(255, 255, 255);
        }
    }

    public function SetDefaultFill()
    {
        $this->SetFillColor(255, 255, 255);
        $this->SetTextColor(34, 34, 34);
    }


    function IncludeJS($script, $isUTF8=false) {
        if(!$isUTF8)
            $script=utf8_encode($script);
        $this->javascript=$script;
    }

    function _putjavascript() {
        $this->_newobj();
        $this->n_js=$this->n;
        $this->_put('<<');
        $this->_put('/Names [(EmbeddedJS) '.($this->n+1).' 0 R]');
        $this->_put('>>');
        $this->_put('endobj');
        $this->_newobj();
        $this->_put('<<');
        $this->_put('/S /JavaScript');
        $this->_put('/JS '.$this->_textstring($this->javascript));
        $this->_put('>>');
        $this->_put('endobj');
    }

    function _putresources() {
        parent::_putresources();
        if (!empty($this->javascript)) {
            $this->_putjavascript();
        }
    }

    function _putcatalog() {
        parent::_putcatalog();
        if (!empty($this->javascript)) {
            $this->_put('/Names <</JavaScript '.($this->n_js).' 0 R>>');
        }
    }

    //==================================================================
    var $widths;
    var $aligns;

    function SetWidths($w)
    {
        //Set the array of column widths
        $this->widths=$w;
    }

    function SetAligns($a)
    {
        //Set the array of column alignments
        $this->aligns=$a;
    }

    function Row($data,$garis)
    {
        //Calculate the height of the row
        $nb=0;
        for($i=0;$i<count($data);$i++)
            $nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
        $h=5*$nb;
        //Issue a page break first if needed
        $this->CheckPageBreak($h);
        //Draw the cells of the row
        for($i=0;$i<count($data);$i++)
        {
            $w=$this->widths[$i];

            $a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';

            //Save the current position
            $x=$this->GetX();
            $y=$this->GetY();
            //Draw the border
            if ($garis == 1) {
                $this->Rect($x,$y,$w,$h);
            }
            //Print the text
            $this->MultiCell($w,5,$data[$i],0,$a);
            //Put the position to the right of the cell
            $this->SetXY($x+$w,$y);
        }

        //Go to the next line
        $this->Ln($h);
    }

    function CheckPageBreak($h)
    {
        //If the height h would cause an overflow, add a new page immediately
        if($this->GetY()+$h>$this->PageBreakTrigger)
            $this->AddPage($this->CurOrientation);
    }

    function NbLines($w,$txt)
    {
        //Computes the number of lines a MultiCell of width w will take
        $cw=&$this->CurrentFont['cw'];
        if($w==0)
            $w=$this->w-$this->rMargin-$this->x;
        $wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
        $s=str_replace("\r",'',$txt);
        $nb=strlen($s);
        if($nb>0 and $s[$nb-1]=="\n")
            $nb--;
        $sep=-1;
        $i=0;
        $j=0;
        $l=0;
        $nl=1;
        while($i<$nb)
        {
            $c=$s[$i];
            if($c=="\n")
            {
                $i++;
                $sep=-1;
                $j=$i;
                $l=0;
                $nl++;
                continue;
            }
            if($c==' ')
                $sep=$i;
            $l+=$cw[$c];
            if($l>$wmax)
            {
                if($sep==-1)
                {
                    if($i==$j)
                        $i++;
                }
                else
                    $i=$sep+1;
                $sep=-1;
                $j=$i;
                $l=0;
                $nl++;
            }
            else
                $i++;
        }
        return $nl;
    }

    //FUNGSI LINE DASH
    function SetDash($black=false, $white=false)
    {
        if($black and $white)
            $s=sprintf('[%.3f %.3f] 0 d', $black*$this->k, $white*$this->k);
        else
            $s='[] 0 d';
        $this->_out($s);
    }

    //New Page number =======================================================================================
    function StartPageGroup()
    {
        $this->NewPageGroup = true;
    }

    // current page in the group
    function GroupPageNo()
    {
        return $this->PageGroups[$this->CurrPageGroup];
    }

    // alias of the current page group -- will be replaced by the total number of pages in this group
    function PageGroupAlias()
    {
        return $this->CurrPageGroup;
    }

    function _beginpage($orientation, $format, $rotation)
    {
        parent::_beginpage($orientation, $format, $rotation);
        if($this->NewPageGroup)
        {
            // start a new group
            $n = sizeof($this->PageGroups)+1;
            $alias = "{nb$n}";
            $this->PageGroups[$alias] = 1;
            $this->CurrPageGroup = $alias;
            $this->NewPageGroup = false;
        }
        elseif($this->CurrPageGroup)
            $this->PageGroups[$this->CurrPageGroup]++;
    }

    function _putpages()
    {
        $nb = $this->page;
        if (!empty($this->PageGroups))
        {
            // do page number replacement
            foreach ($this->PageGroups as $k => $v)
            {
                for ($n = 1; $n <= $nb; $n++)
                {
                    $this->pages[$n] = str_replace($k, $v, $this->pages[$n]);
                }
            }
        }
        parent::_putpages();
    }

}
