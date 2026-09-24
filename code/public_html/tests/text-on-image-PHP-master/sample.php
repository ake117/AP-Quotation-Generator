<!DOCTYPE html>

<html>

<body>


<h1>Placing a text in custom image</h1>


<?php

  //select the image on which you would like to write the text
 
 $sourcelocation = $_POST["imgfile"];

  
  
//create the image from existing image
  
$image = imagecreatefromjpeg($sourcelocation);

  
//destination file name where you want to store the new image

  $output = "images/textimage1.jpg";

 
 
 //declaring the colors
  
$white = imagecolorallocate($image,255,255,255);
  
$black = imagecolorallocate($image,0,0,0);

 
 //font size of the text

  $font_size = 40;


 
 //rotation of the text
  
$rotation = 0;

  
//x- coordinate where you want to pace the text

  $origin_x = $_POST["xcoordinate"];


  
//y- coordinate where you want to pace the text

  $origin_y =$_POST["ycoordinate"];


  
//font style of the text.we need to give the location of.ttf file
 
 $font = "font\open-sans\OpenSans-Bold.ttf";

 
 //text you want to add to the image
 
 $text=$_POST["textinp"];
$text1 = imagettftext($image,$font_size,$rotation,$origin_x,$origin_y,$white,$font,$text);

 
 imagejpeg($image,$output);


?>

<img src = "<?php echo $output?>">

</body>
</html>