<?php

namespace App\Support\Facades;

use Codedge\Fpdf\Fpdf\Fpdf;
use Exception;

define('FPDF_FONTPATH', resource_path('fpdf/font'));

class Pdf extends Fpdf
{
    protected $widths;
    protected $aligns = "L";
    protected $fontsName;
    protected $fontsSize;
    protected $fontsStyle = "";
    protected $fillcol = "255,255,255";
    protected $textcol = "0,0,0";
    protected $drawcol = "255,255,255";
    protected $linewidth = "0.2";
    protected $borders = "TRBL";
    protected $valigns = "T";
    protected $heights = "5";
    protected $paddings = 0;
    protected $headerFn;
    protected $footerFn;

    protected $angle = 0;

    /**
     * @return void
     */
    public function setup($title = '', $subject = '', $keywords = '', $author = null, $creator = null)
    {
        self::SetTitle($title);
        self::SetSubject($subject);
        self::SetKeywords($keywords);
        self::SetAuthor($author ?: config('app.name'));
        self::SetCreator($creator ?: config('app.name'));
        self::AliasNbPages();
    }

    public function getCurOrientation()
    {
        return $this->CurOrientation;
    }

    public function getCurPageSize()
    {
        return $this->CurPageSize;
    }

    function GetFontFamily() { return $this->FontFamily; }
    function GetFontStyle() { return $this->FontStyle; }
    function GetFontSizePt() { return $this->FontSizePt; }

    function TableSetAlign($var)
    {
        $this->aligns = $var;
    }

    function TableSetWidth($var)
    {
        $this->widths = $var;
    }

    function TableSetFontsName($var)
    {
        $this->fontsName = $var;
    }

    function TableSetFontsSize($var)
    {
        $this->fontsSize = $var;
    }

    function TableSetFontsStyle($var)
    {
        $this->fontsStyle = $var;
    }

    function TableSetFillColor($var)
    {
        $this->fillcol = $var;
    }

    function TableSetTextColor($var)
    {
        $this->textcol = $var;
    }

    function TableSetDrawColor($var)
    {
        $this->drawcol = $var;
    }

    function TableSetLineWidth($var)
    {
        $this->linewidth = $var;
    }

    function TableSetBorder($var)
    {
        $this->borders = $var;
    }

    function TableSetValign($var)
    {
        $this->valigns = $var;
    }

    function TableSetHeight($var)
    {
        $this->heights = $var;
    }

    function TableSetPadding($var)
    {
        $this->paddings = $var;
    }

    function CalculateRowHeight($h)
    {
        return ($this->paddings > 0)
            ? ($h + ($this->paddings * 2))
            : $h;
    }

    function CheckPageBreak($h)
    {
        if ($this->GetY() + $h > $this->PageBreakTrigger)
            $this->AddPage($this->getCurOrientation(), $this->getCurPageSize());
    }

    function IsPageBreak($h)
    {
        return ($this->GetY() + $h > $this->PageBreakTrigger) ? true : false;
    }

    function NbLines($w, $txt)
    {
        $cw =& $this->CurrentFont['cw'];
        if ($w == 0)
            $w = $this->w - $this->rMargin - $this->x;
        $wmax = ($w - 2 * $this->cMargin) * 1000 / $this->FontSize;
        $s = str_replace("\r", '', $txt);
        $nb = strlen($s);
        if ($nb > 0 and $s[$nb - 1] == "\n")
            $nb--;
        $sep = -1;
        $i = 0;
        $j = 0;
        $l = 0;
        $nl = 1;
        while ($i < $nb) {
            $c = $s[$i];
            if ($c == "\n") {
                $i++;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
                continue;
            }
            if ($c == ' ')
                $sep = $i;
            $l += $cw[$c];
            if ($l > $wmax) {
                if ($sep == -1) {
                    if ($i == $j)
                        $i++;
                } else
                    $i = $sep + 1;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
            } else
                $i++;
        }
        return $nl;
    }

    /**
     * @param $data
     */
    public function TableRow($data)
    {
        //Calculate the height of the row
        $nb = 0;
        $height = (isset($this->heights)) ? $this->heights : 5;
        for ($i = 0; $i < count($data); $i++)
            $nb = max($nb, $this->NbLines(($this->widths[$i] > 0) ? $this->widths[$i] : $this->GetRestWidth(), $data[$i]));
        $h = $height * $nb;

        // recalculate height for padding
        $h = $this->CalculateRowHeight($h);

        //Issue a page break first if needed
        $this->CheckPageBreak($h);
        //Draw the cells of the row
        for ($i = 0; $i < count($data); $i++) {
            // $w = $this->widths[$i];
            $w = ($this->widths[$i] > 0) ? $this->widths[$i] : $this->GetRestWidth();
            $a = ((is_array($this->aligns)) && (isset($this->aligns[$i]))) ? $this->aligns[$i] : $this->aligns;
            //Save the current position
            $x = $this->GetX();
            $y = $this->GetY();

            // set style
            $this->SetFont(
                ((is_array($this->fontsName)) && (isset($this->fontsName[$i])) ? $this->fontsName[$i] : $this->fontsName),
                ((is_array($this->fontsStyle)) && (isset($this->fontsStyle[$i])) ? $this->fontsStyle[$i] : $this->fontsStyle), // style
                (!is_array($this->fontsSize) ? $this->fontsSize : ((isset($this->fontsSize[$i])) ? $this->fontsSize[$i] : $this->FontSize))
            );

            $color = explode(",", ((is_array($this->fillcol)) && (isset($this->fillcol[$i])) ? $this->fillcol[$i] : $this->fillcol));
            $this->SetFillColor($color[0], $color[1], $color[2]);

            $color = explode(",", ((is_array($this->textcol)) && (isset($this->textcol[$i])) ? $this->textcol[$i] : $this->textcol));
            $this->SetTextColor($color[0], $color[1], $color[2]);

            $color = explode(",", ((is_array($this->drawcol)) && (isset($this->drawcol[$i])) ? $this->drawcol[$i] : $this->drawcol));
            $this->SetDrawColor($color[0], $color[1], $color[2]);

            $this->SetLineWidth(((is_array($this->linewidth)) && (isset($this->linewidth[$i])) ? $this->linewidth[$i] : $this->linewidth));

            $color = explode(",", ((is_array($this->fillcol)) && (isset($this->fillcol[$i])) ? $this->fillcol[$i] : $this->fillcol));
            $this->SetDrawColor($color[0], $color[1], $color[2]);

            // draw border hanya jika fill color tidak null
            //Draw the border
            // $this->Rect($x, $y, $w, $h, 'D'); // 'FD' // TEMPORARY DISABLED

            $color = explode(",", ((is_array($this->drawcol)) && (isset($this->drawcol[$i])) ? $this->drawcol[$i] : $this->drawcol));
            $this->SetDrawColor($color[0], $color[1], $color[2]);

            // Draw Cell Border
            if (substr_count(((is_array($this->borders)) && (isset($this->borders[$i])) ? $this->borders[$i] : $this->borders), "T") > 0) {
                $this->Line($x, $y, $x + $w, $y);
            }

            if (substr_count(((is_array($this->borders)) && (isset($this->borders[$i])) ? $this->borders[$i] : $this->borders), "B") > 0) {
                $this->Line($x, $y + $h, $x + $w, $y + $h);
            }

            if (substr_count(((is_array($this->borders)) && (isset($this->borders[$i])) ? $this->borders[$i] : $this->borders), "L") > 0) {
                $this->Line($x, $y, $x, $y + $h);
            }

            if (substr_count(((is_array($this->borders)) && (isset($this->borders[$i])) ? $this->borders[$i] : $this->borders), "R") > 0) {
                $this->Line($x + $w, $y, $x + $w, $y + $h);
            }

            $ht = $this->NbLines($w, $data[$i]);
            $hl = ((is_array($this->valigns)) && (isset($this->valigns[$i]))) ? $this->valigns[$i] : $this->valigns;
            $va = (strtolower($hl) == 'm') ? $h / $ht : $height;

            //Print top padding
            if (($this->paddings > 0)) {
                $this->SetY($y + $this->paddings, false);
            }

            //Print the text
            $this->MultiCell($w, $va, $data[$i], 0, $a, 0);

            //Put the position to the right of the cell
            $this->SetXY($x + $w, $y);
        }

        //Go to the next line
        $this->Ln($h);
    }

    public function GetRightMargin()
    {
        return $this->rMargin;
    }

    function GetRestWidth()
    {
        return $this->GetPageWidth() - $this->GetRightMargin() - $this->GetX();
    }

    function subWrite($h, $txt, $link='', $subFontSize=12, $subOffset=0) {
        // resize font
        $subFontSizeold = $this->FontSizePt;
        $this->SetFontSize($subFontSize);
        // reposition y
        $subOffset = ((($subFontSize - $subFontSizeold) / $this->k) * 0.3) + ($subOffset / $this->k);
        $subX = $this->x; $subY = $this->y;
        $this->SetXY($subX, $subY - $subOffset);
        //Output text
        $this->Write($h, $txt, $link);
        // restore y position
        $subX = $this->x;
        $subY = $this->y;
        $this->SetXY($subX, $subY + $subOffset);
        // restore font size
        $this->SetFontSize($subFontSizeold);
    }

    function SetHeader($fn) {
        $this->headerFn = $fn;
    }

    function Header()
    {
        if ($this->headerFn) {
            $fn = $this->headerFn;
            $fn();
        }
        else
            parent::Header();
    }

    function SetFooter($fn) {
        $this->footerFn = $fn;
    }

    function Footer()
    {
        if ($this->footerFn) {
            $fn = $this->footerFn;
            $fn();
        }
        else
            parent::Footer();
    }

    function TextWatermark($x, $y, $txt, $angle = 45)
    {
        $angle = $angle * M_PI / 180;
        $c = cos($angle);
        $s = sin($angle);
        $cx = $x * $this->k;
        $cy = ($this->h - $y) * $this->k;
        $this->_out(sprintf('q %.5F %.5F %.5F %.5F %.2F %.2F cm 1 0 0 1 %.2F %.2F cm', $c, $s, - $s, $c, $cx, $cy, - $cx, - $cy));
        $this->Text($x, $y, $txt);
        $this->_out('Q');
    }

    // function Rotate($angle,$x=-1,$y=-1)
    // {
    //     if($x==-1)
    //         $x=$this->x;
    //     if($y==-1)
    //         $y=$this->y;
    //     if($this->angle!=0)
    //         $this->_out('Q');
    //     $this->angle=$angle;
    //     if($angle!=0)
    //     {
    //         $angle*=M_PI/180;
    //         $c=cos($angle);
    //         $s=sin($angle);
    //         $cx=$x*$this->k;
    //         $cy=($this->h-$y)*$this->k;
    //         $this->_out(sprintf('q %.5F %.5F %.5F %.5F %.2F %.2F cm 1 0 0 1 %.2F %.2F cm',$c,$s,-$s,$c,$cx,$cy,-$cx,-$cy));
    //     }
    // }
    //
    // function _endpage()
    // {
    //     if($this->angle!=0)
    //     {
    //         $this->angle=0;
    //         $this->_out('Q');
    //     }
    //     parent::_endpage();
    // }

    function Error($msg)
    {
        // Fatal error
        throw new Exception('FPDF error: '.$msg);
    }

    function MultiCellIndent($w, $h, $txt, $border=0, $align='J', $fill=false, $indent=0)
    {
        //Output text with automatic or explicit line breaks
        $cw=&$this->CurrentFont['cw'];
        if($w==0)
            $w=$this->w-$this->rMargin-$this->x;

        $wFirst = $w-$indent;
        $wOther = $w;

        $wmaxFirst=($wFirst-2*$this->cMargin)*1000/$this->FontSize;
        $wmaxOther=($wOther-2*$this->cMargin)*1000/$this->FontSize;

        $s=str_replace("\r",'',$txt);
        $nb=strlen($s);
        if($nb>0 && $s[$nb-1]=="\n")
            $nb--;
        $b=0;
        if($border)
        {
            if($border==1)
            {
                $border='LTRB';
                $b='LRT';
                $b2='LR';
            }
            else
            {
                $b2='';
                if(is_int(strpos($border,'L')))
                    $b2.='L';
                if(is_int(strpos($border,'R')))
                    $b2.='R';
                $b=is_int(strpos($border,'T')) ? $b2.'T' : $b2;
            }
        }
        $sep=-1;
        $i=0;
        $j=0;
        $l=0;
        $ns=0;
        $nl=1;
        $first=true;
        while($i<$nb)
        {
            //Get next character
            $c=$s[$i];
            if($c=="\n")
            {
                //Explicit line break
                if($this->ws>0)
                {
                    $this->ws=0;
                    $this->_out('0 Tw');
                }
                $this->Cell($w,$h,substr($s,$j,$i-$j),$b,2,$align,$fill);
                $i++;
                $sep=-1;
                $j=$i;
                $l=0;
                $ns=0;
                $nl++;
                if($border && $nl==2)
                    $b=$b2;
                continue;
            }
            if($c==' ')
            {
                $sep=$i;
                $ls=$l;
                $ns++;
            }
            $l+=$cw[$c];

            if ($first)
            {
                $wmax = $wmaxFirst;
                $w = $wFirst;
            }
            else
            {
                $wmax = $wmaxOther;
                $w = $wOther;
            }

            if($l>$wmax)
            {
                //Automatic line break
                if($sep==-1)
                {
                    if($i==$j)
                        $i++;
                    if($this->ws>0)
                    {
                        $this->ws=0;
                        $this->_out('0 Tw');
                    }
                    $SaveX = $this->x;
                    if ($first && $indent>0)
                    {
                        $this->SetX($this->x + $indent);
                        $first=false;
                    }
                    $this->Cell($w,$h,substr($s,$j,$i-$j),$b,2,$align,$fill);
                    $this->SetX($SaveX);
                }
                else
                {
                    if($align=='J')
                    {
                        $this->ws=($ns>1) ? ($wmax-$ls)/1000*$this->FontSize/($ns-1) : 0;
                        $this->_out(sprintf('%.3f Tw',$this->ws*$this->k));
                    }
                    $SaveX = $this->x;
                    if ($first && $indent>0)
                    {
                        $this->SetX($this->x + $indent);
                        $first=false;
                    }
                    $this->Cell($w,$h,substr($s,$j,$sep-$j),$b,2,$align,$fill);
                    $this->SetX($SaveX);
                    $i=$sep+1;
                }
                $sep=-1;
                $j=$i;
                $l=0;
                $ns=0;
                $nl++;
                if($border && $nl==2)
                    $b=$b2;
            }
            else
                $i++;
        }
        //Last chunk
        if($this->ws>0)
        {
            $this->ws=0;
            $this->_out('0 Tw');
        }
        if($border && is_int(strpos($border,'B')))
            $b.='B';
        $this->Cell($w,$h,substr($s,$j,$i),$b,2,$align,$fill);
        $this->x=$this->lMargin;
    }
}
