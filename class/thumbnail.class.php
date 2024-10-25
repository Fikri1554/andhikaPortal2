<?php
/*
Author: Blaz Grapar, blaz.grapar@email.si
20031030 Laurent G. Beaudet - Corrected the computation of height vs width

Please don't delete names in the script. Thanks.

This class creates a thumbnail of an image. Size of thumbnail is determined with maximum width and height of an thumbnail.


EXAMPLES

// This will output a thumbnail to the browser. Default maximum height and width are 100px
//
$MyThumb = new Thumbnail('image.jpg');
$MyThumb->Output();

// This will output a thumbnail to the file thumbnail.jpg. Maximum width is set to 200px an maximum height to 150px
//
$MyThumb = new Thumbnail('image.jpg', 200, 150, 'thumbnail.jpg');
$MyThumb->Output();

*/


class Thumbnail
	{
	var $source;
	var $max_width;
	var $max_height;
	var $dest;

//	function Thumbnail( $source, $max_width = 100, $max_height = 100, $dest = '' )
	function Thumbnail( $source, $max_width, $max_height, $dest = '' )
		{
		$this->source = $source;
		$this->max_width = $max_width;
		$this->max_height = $max_height;
		$this->dest = $dest;
		}
	
	// Get the width of the original image
	function GetWidth()
		{
		$size = GetImageSize($this->source);
		return $size[0];
		//return ImageSX($this->source);
		}
	

	// Get the height of the original image
	function GetHeight()
		{
		$size = GetImageSize($this->source);
		return $size[1];
		//return ImageSY($this->source);
		}

	// Get the type of the image
	function GetType()
		{
		$size = GetImageSize($this->source);

		switch ( $size[2] )
			{
			case 1:
				return 'gif';
				break;
			case 2:
				return 'jpg';
				break;
			case 3:
				return 'png';
				break;
			}
		}


	// Width calculation of thumbnail
	function CalcWidth()
		{
		if ($this->GetWidth() > $this->GetHeight()) {
			//
			// let's take the width as the largest dimension
			//
			return ($this->max_width);
			} else {
			//
			// the width will have to be of the same ration as the height
			//
			return(floor(($this->max_height * $this->GetWidth()) / $this->GetHeight()));
			}
		}

	// Height calculation of thumbnail
	function CalcHeight()
		{
		if ($this->GetWidth() > $this->GetHeight()) {
			//
			// the height will have to be of the same ration as the width
			//
			return(floor(($this->max_width * $this->GetHeight()) / $this->GetWidth()));
			} else {
			//
			// let's take the height as the largest dimension
			//
			return($this->max_height);
			}
		}

	// Creating a thumbnail
	function Create()
		{
		switch ($this->GetType())
			{
			case 'gif':
				$img_src = ImageCreateFromGIF ( $this->source );
				break;

			case 'jpg':
				$img_src = ImageCreateFromJPEG ( $this->source );
				break;

			case 'png':
				$img_src = ImageCreateFromPNG ( $this->source );
				break;
			}

		$img_des = ImageCreateTrueColor ( $this->CalcWidth(), $this->CalcHeight() );
		ImageCopyResized( $img_des, $img_src, 0, 0, 0, 0, $this->CalcWidth(), $this->CalcHeight(), $this->GetWidth(), $this->GetHeight() );

		return $img_des;
		}


	// Output the thumbnail to file or directly to a client
	function Output()
		{
		switch ($this->GetType())
			{
			case 'gif':
				if (empty($this->dest))
					{
					header ("Content-type: image/gif");
					return ImageGIF($this->Create());
					}
				else
					{
					return ImageGIF( $this->Create(), $this->dest );
					}
					break;

			case 'jpg':
				if (empty($this->dest))
					{
					header ("Content-type: image/jpeg");
					return ImageJPEG($this->Create());
					}
				else
					{
					return ImageJPEG( $this->Create(), $this->dest );
					}
				break;

			case 'png':
				if (empty($this->dest))
					{
					header ("Content-type: image/png");
					return ImagePNG($this->Create());
					}
				else
					{
					return ImagePNG( $this->Create(), $this->dest );
					}
				break;
			}
		}
	}
?>
