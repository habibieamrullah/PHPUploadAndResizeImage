<?php

include("compressimage.php");

?>

<!DOCTYPE html>
<html>
	<head>
		<title>Upload and resize image with PHP before storing in upload folder</title>
	</head>
	<body>
		<h1><a href="index.php">Upload and resize image with PHP before storing in upload folder</a></h1>
		<?php
		if(isset($_POST["uploadnow"])){
			$maxsize = 524288; //maximum size of allowed image being uploaded (around half MB)
			$maxwidth = 512; //maximum width of allowed image dimension in pixels
			if($_FILES["imageupload"]["size"] == 0){
				echo "Please try again.";
			}else{
				if($_FILES['imageupload']['error'] > 0) { 
					echo "Error during uploading new image, try again later."; 
				}
				
				$extsAllowed = array( 'jpg', 'jpeg', 'png' ); //allowed extensions
				$uploadedfile = $_FILES["imageupload"]["name"];
				$extension = pathinfo($uploadedfile, PATHINFO_EXTENSION);
				
				//if uploaded image is in one of allowed extensions/formats, then proceed to next steps
				if(in_array($extension, $extsAllowed) ) { 
					
					//generate random image file name
					$newppic = substr(str_shuffle(str_repeat("0123456789abcdefghijklmnopqrstuvwxyz", 5)), 0, 10);
					$name = "upload/" . $newppic .".". $extension;
					
					//if uploaded image is exceeding max size then compress it
					if(($_FILES['imageupload']['size'] >= $maxsize)){
						echo "Uploaded image size is greater than $maxsize.<br>";
						compressimage($_FILES['imageupload']['tmp_name'], "upload/" . $newppic .".". $extension, $maxwidth); // resize it to 512pixels width
					}else{
						//check if the uploaded image width in pixels is greater than maxwidth
						list($width, $height, $type, $attr) = getimagesize($_FILES['imageupload']['tmp_name']);
						if($width > $maxwidth){
							echo "Uploaded image width is greater than $maxwidth.<br>";
							compressimage($_FILES['imageupload']['tmp_name'], "upload/" . $newppic .".". $extension, $maxwidth); // resize it to 512pixels width
						}else{
							echo "This image is just nice.<br>";
							$result = move_uploaded_file($_FILES['imageupload']['tmp_name'], $name);
						}
					}
					echo "New image has been uploaded.";
					
				} else { 
					echo "Image file is not valid. Please try uploading another image."; 
				}
			}
		}else{
			?>
			
			<form method="post" enctype="multipart/form-data">
				<input type="file" name="imageupload" accept="image/*">
				<input type="submit" name = "uploadnow" value="Upload">
			</form>
			
			<?php
		}
		?>
	</body>
</html>

<?php