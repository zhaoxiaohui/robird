<?php
header("Content-Type:text/html;Charset=utf-8");
//include class file
include("GoogleImages.class.php");

//create class instance
$gimage = new GoogleImages();

/*****************************
call get_images method by providing 3 parameters
1.) query - what should be searched for
2.) cols - number of images per row
3.) rows - number of rows
*****************************/

$key = $_GET['key'];
if($key){
    $gimage->get_images($key, 4, 5);
}
else echo "请输入参数key";
//$gimage->get_images("周杰伦", 4, 5);

?>
