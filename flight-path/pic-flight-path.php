<?php


	/** Flight-Path
	/* @ Author: Sameer Borate
	/* @ Last Update: 7th March 2007
	**/

	
	/** Input GET Variables
	/* @ jmodel
	/* @ jpwr
	/* @ jlss
	/* @ jhss
	/* @ jrng
	**/
	
	
$stringjmodel = urldecode($_GET['jmodel']);
$stringjpwr = urldecode($_GET['jpwr']);
$stringjlss = urldecode($_GET['jlss']);
$stringjhss = urldecode($_GET['jhss']);
$stringjrng = urldecode($_GET['jrng']);

	
/* Draw a antialiased line */	
function imagesmoothline ( $image , $x1 , $y1 , $x2 , $y2 , $color )
 {
  $colors = imagecolorsforindex ( $image , $color );
  if ( $x1 == $x2 )
  {
   imageline ( $image , $x1 , $y1 , $x2 , $y2 , $color ); // Vertical line
  }
  else
  {
   $m = ( $y2 - $y1 ) / ( $x2 - $x1 );
   $b = $y1 - $m * $x1;
   if ( abs ( $m ) <= 1 )
   {
   $x = min ( $x1 , $x2 );
   $endx = max ( $x1 , $x2 );
   while ( $x <= $endx )
   {
     $y = $m * $x + $b;
     $y == floor ( $y ) ? $ya = 1 : $ya = $y - floor ( $y );
     $yb = ceil ( $y ) - $y;
     $tempcolors = imagecolorsforindex ( $image , imagecolorat ( $image , $x , floor ( $y ) ) );
     $tempcolors['red'] = $tempcolors['red'] * $ya + $colors['red'] * $yb;
     $tempcolors['green'] = $tempcolors['green'] * $ya + $colors['green'] * $yb;
     $tempcolors['blue'] = $tempcolors['blue'] * $ya + $colors['blue'] * $yb;
     if ( imagecolorexact ( $image , $tempcolors['red'] , $tempcolors['green'] , $tempcolors['blue'] ) == -1 ) imagecolorallocate ( $image , $tempcolors['red'] , $tempcolors['green'] , $tempcolors['blue'] );
     imagesetpixel ( $image , $x , floor ( $y ) , imagecolorexact ( $image , $tempcolors['red'] , $tempcolors['green'] , $tempcolors['blue'] ) );
     $tempcolors = imagecolorsforindex ( $image , imagecolorat ( $image , $x , ceil ( $y ) ) );
     $tempcolors['red'] = $tempcolors['red'] * $yb + $colors['red'] * $ya;
     $tempcolors['green'] = $tempcolors['green'] * $yb + $colors['green'] * $ya;
     $tempcolors['blue'] = $tempcolors['blue'] * $yb + $colors['blue'] * $ya;
     if ( imagecolorexact ( $image , $tempcolors['red'] , $tempcolors['green'] , $tempcolors['blue'] ) == -1 ) imagecolorallocate ( $image , $tempcolors['red'] , $tempcolors['green'] , $tempcolors['blue'] );
     imagesetpixel ( $image , $x , ceil ( $y ) , imagecolorexact ( $image , $tempcolors['red'] , $tempcolors['green'] , $tempcolors['blue'] ) );
     $x ++;
   }
   }
   else
   {
   $y = min ( $y1 , $y2 );
   $endy = max ( $y1 , $y2 );
   while ( $y <= $endy )
   {
     $x = ( $y - $b ) / $m;
     $x == floor ( $x ) ? $xa = 1 : $xa = $x - floor ( $x );
     $xb = ceil ( $x ) - $x;
     $tempcolors = imagecolorsforindex ( $image , imagecolorat ( $image , floor ( $x ) , $y ) );
     $tempcolors['red'] = $tempcolors['red'] * $xa + $colors['red'] * $xb;
     $tempcolors['green'] = $tempcolors['green'] * $xa + $colors['green'] * $xb;
     $tempcolors['blue'] = $tempcolors['blue'] * $xa + $colors['blue'] * $xb;
     if ( imagecolorexact ( $image , $tempcolors['red'] , $tempcolors['green'] , $tempcolors['blue'] ) == -1 ) imagecolorallocate ( $image , $tempcolors['red'] , $tempcolors['green'] , $tempcolors['blue'] );
     imagesetpixel ( $image , floor ( $x ) , $y , imagecolorexact ( $image , $tempcolors['red'] , $tempcolors['green'] , $tempcolors['blue'] ) );
     $tempcolors = imagecolorsforindex ( $image , imagecolorat ( $image , ceil ( $x ) , $y ) );
     $tempcolors['red'] = $tempcolors['red'] * $xb + $colors['red'] * $xa;
     $tempcolors['green'] = $tempcolors['green'] * $xb + $colors['green'] * $xa;
     $tempcolors['blue'] = $tempcolors['blue'] * $xb + $colors['blue'] * $xa;
     if ( imagecolorexact ( $image , $tempcolors['red'] , $tempcolors['green'] , $tempcolors['blue'] ) == -1 ) imagecolorallocate ( $image , $tempcolors['red'] , $tempcolors['green'] , $tempcolors['blue'] );
     imagesetpixel ( $image , ceil ( $x ) , $y , imagecolorexact ( $image , $tempcolors['red'] , $tempcolors['green'] , $tempcolors['blue'] ) );
     $y ++;
   }
   }
  }
 }


 
/* Draws a Beizer curve */
 
function bezier($p,$steps)
{
   $t = 1 / $steps;
   $temp = $t * $t;
   $ret = array();
   $f = $p[0];
   $fd = 3 * ($p[1] - $p[0]) * $t;
   $fdd_per_2=3*($p[0]-2*$p[1]+$p[2])*$temp;
   $fddd_per_2=3*(3*($p[1]-$p[2])+$p[3]-$p[0])*$temp*$t;
   $fddd = $fddd_per_2 + $fddd_per_2;
   $fdd = $fdd_per_2 + $fdd_per_2;
   $fddd_per_6 = $fddd_per_2 * (1.0 / 3);
   for ($loop=0; $loop<$steps; $loop++) {
       array_push($ret,$f);
       $f = $f + $fd + $fdd_per_2 + $fddd_per_6;
       $fd = $fd + $fdd + $fddd_per_2;
       $fdd = $fdd + $fddd;
       $fdd_per_2 = $fdd_per_2 + $fddd_per_2;
   }
   return $ret;
}



/* Start of main processing */


$stringjmodel = urldecode($_GET['jmodel']);
$stringjpwr = urldecode($_GET['jpwr']);
$stringjlss = urldecode($_GET['jlss']);
$stringjhss = urldecode($_GET['jhss']);
$stringjrng = urldecode($_GET['jrng']);

$rightBumpFactor = 20; // The size of the right curve
$leftBumpFactor = 9; // The size of the left curve


/* Validate input values */

// jlss range : 0 .. 5
if($stringjlss < 0 || $stringjlss > 5)
	$stringjlss = 0;

// hlss range : 0 .. (-3)
if($stringjhss > 0 || $stringjhss < -3)
	$stringjhss = 0;

    
/* Set the max length of the Y co-ordinate depending on the 'jrng' value passed */

$yMax = 46; // Default to 'jrng=5'
$yMiddle = 120;

$yMax = 162 - (14.5 * (2 + $stringjrng));

if($stringjrng == 2)
{
	$yMax = 162 - (14.5 * (2 + $stringjrng));
	$yMiddle = 120;
}

if($stringjrng >= 1 && $stringjrng < 2)
{
	$yMax = 162 - (14.5 * (2 + $stringjrng));
	$yMiddle = 130;
}

if($stringjrng == 0)
{
	$yMax = 162 - (14.5 * (2 + $stringjrng));
	$yMiddle = 140;
}

if($stringjrng < 1 && $stringjrng > 0)
	$yMiddle = 140;
    

/* The number of line segments for the Beizer curve.  */
$segmentos =3000;
$x=array(53, ($rightBumpFactor * abs($stringjhss)) + 53, 53, 56 + ($leftBumpFactor * (0 - $stringjlss)));
$y=array(162 , $yMiddle, $yMiddle, $yMax);


$by = bezier($y,$segmentos);
$bx = bezier($x,$segmentos);

$im = imagecreatefrompng("pic-flight-path.png");

$black = imagecolorallocate ($im, 0, 0, 0);
$yellow = imagecolorallocate ($im, 242, 230, 42);

imagesetthickness( $im, 2 );

$max_x = 0;
$min_x = 10000;

for($i=0;$i<$segmentos-1;$i++)
{
    if($bx[$i] > $max_x )
		$max_x = $bx[$i];

    if($bx[$i] < $min_x )
		$min_x = $bx[$i];

	imagesmoothline($im,$bx[$i],$by[$i],$bx[$i+1],$by[$i+1],$yellow);

}


/* Draw x-axis markers */
imagesmoothline($im, $max_x , 167 , $max_x, 160, $black);
imagesmoothline($im, $min_x , 167 , $min_x, 160, $black);
ImageTTFText ($im, 7, 0, $max_x - 6, 180, $black,"arial.ttf", "-" . $stringjhss  );
ImageTTFText ($im, 7, 0, $min_x - 6, 180, $black,"arial.ttf", "+" . $stringjlss  );

/* Draw y-axis markers */
for($i= 46; $i < 162 ; $i = $i + 14.5)
	imageline($im, 48 , $i , 58, $i, $black);
	
/* Draw power number */
if(($stringjpwr - intval($stringjpwr)) > 0)
	ImageTTFText ($im, 12, 0, 82, 210, $black,"arial.ttf", $stringjpwr );
else
	ImageTTFText ($im, 12, 0, 88, 210, $black,"arial.ttf", $stringjpwr );

	
/* Draw Model Name */
ImageTTFText ($im, 14, 0, 10, 24, $black,"arial.ttf", $stringjmodel );


header ("Content-type: image/png");
imagepng($im); 
imagedestroy($im);

?>