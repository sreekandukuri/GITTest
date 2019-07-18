<?php
if(isset($_POST['frmsubmit'])){
	
	function generatThumbnail($image, $destination_path, $desired_width,$desired_height) {

		/* read the source image */
		list($img_width, $img_height, $img_type) = @getimagesize($image);		
		
		/* find the "desired height" of this thumbnail, relative to the desired width  */
		if($desired_height!='' && $desired_width!=''){
			if(($img_width>$desired_width) || ($img_height>$desired_height)){

				if($img_width>=$img_height){
					if($img_width > $desired_width){ 
						$percent = (($img_width-$desired_width)/$img_width)*100;
						$desired_width = $img_width - ($img_width*($percent/100)); 
						$desired_height = $img_height - ($img_height*($percent/100));
					}
					else{
						$percent = (($desired_width-$img_width)/$img_width)*100;
						$desired_width = $img_width + ($img_width*($percent/100));
						$desired_height = $img_height + ($img_height*($percent/100));
					}
				}else{ 
					if($img_height>$desired_height){
						$percent = (($img_height-$desired_height)/$img_height)*100;
						$desired_height = $img_height - ($img_height*($percent/100));
						$desired_width = $img_width - ($img_width*($percent/100));
					}
					else{
						$percent = (($desired_height-$img_height)/$img_height)*100;
						$desired_height = $img_height + ($img_height*($percent/100));
						$desired_width = $img_width + ($img_width*($percent/100));
					}
				}
			}
			else{
			$desired_width = $img_width;
			$desired_height = $img_height;
			}
		}elseif($desired_height!='' && $desired_width==''){
			$desired_width = floor($img_width * ($desired_height / $img_height));
		}elseif($desired_width!='' && $desired_height==''){
			$desired_height = floor($img_height * ($desired_width / $img_width));
		}else{
				$desired_width = $img_width;
				$desired_height = $img_height;
			}
		/* create a new, "virtual" image */
		$destimg = imagecreatetruecolor($desired_width, $desired_height);
		
		

		$quality=100;
			if($img_type == '2'){
			$srcimg=imagecreatefromjpeg($image);
			imagecopyresampled($destimg,$srcimg,0,0,0,0, $desired_width, $desired_height, $img_width, $img_height);
			imagejpeg($destimg,$destination_path,$quality);
		}elseif($img_type == '3'){			
			$srcimg=imagecreatefrompng($image);
			//$background = imagecolorallocatealpha($srcimg,255,0,255,127);
			//imagecolortransparent($destimg, $background);
			imagealphablending($destimg, FALSE);
			imagesavealpha($destimg, TRUE);
			imagecopyresampled($destimg,$srcimg,0,0,0,0, $desired_width, $desired_height, $img_width, $img_height);
			imagepng($destimg,$destination_path,9);		
		}elseif($img_type == '1'){
			$srcimg=imagecreatefromgif($image);
			imagecopyresampled($destimg,$srcimg,0,0,0,0, $desired_width, $desired_height, $img_width, $img_height);
			imagegif($destimg,$destination_path,$quality);
		}
	}
	 $path = $_SERVER['DOCUMENT_ROOT'].'/thumbnail_code/'; //create folder a where thumpanel save "thumbnail_generate"
	
	
	if($_FILES['upload_file']['tmp_name']!=''){
		$file_name  = time().'-'.$_FILES['upload_file']['name'];
		$fullpath = $path.$file_name;
		generatThumbnail($_FILES['upload_file']['tmp_name'], $fullpath, $_POST['imgwidth'], $_POST['imgheight']);
	}else{
		$file_name  = basename($_POST['img_url']); 
		$file_name  = time().'-'.$file_name;
		$fullpath = $path.$file_name;
		generatThumbnail($_POST['img_url'], $fullpath, $_POST['imgwidth'], $_POST['imgheight']);
	}

}

?>

<form name="frmpayment" action="" method="POST"  enctype="multipart/form-data" accept-charset="utf-8">				
					
	<table border="0" width="90%" cellpadding="3" cellspacing="1" align="right" class="member-form">

		<tr><td>&nbsp;</td></tr>
		<tr>
			<td width="110">Image:<span class="mandatory">*</span></td>
			<td colspan='3'><input type="file"  name="upload_file" id="upload_file" value="" style="width:200px;"/>OR &nbsp; URL:<input type="text"  name="img_url" id="img_url" value="" style="width:200px"/>
			<span id="upload_file_error"></span>
			</td>
		</tr>
		<tr>
			<td width="110">&nbsp;</td>
			<td colspan='3'>
			Width:<input type="text"  name="imgwidth" id="imgwidth" value="" /> Height:<input type="text"  name="imgheight" id="imgheight" value="" />
			<span id="width_error"></span>
			</td>
		</tr>


		<tr>

			<td width="110">&nbsp;</td>

			<td><input type="submit" name="frmsubmit" value="Submit"  class="form-style-2" onclick="return frmValidate();" ></td>

		</tr>

	</table>

</form>

<script>
function frmValidate(){	
	if(document.getElementById("upload_file").value=='' && document.getElementById("img_url").value==''){	
		document.getElementById("upload_file_error").innerHTML="<font color='red'>Please select file or enter a Image url</font";
		return false;
	}else if(document.getElementById("imgwidth").value=='' && document.getElementById("imgheight").value==''){		
		document.getElementById("width_error").innerHTML="<font color='red'>Please enter either width and height or both</font>";
		document.getElementById("upload_file_error").innerHTML="";
				
		return false;
	}else{
		return true;
	}
}
</script>