<?php
/**
* 
*/
class FileUtils
{	
	private static $_filepath="upload";
	
	function __construct()
	{
		
	}

	public static function GetPath()
	{
		return self::$_filepath;
	}

	public static function CheckType($file,$type)
	{
		if ($file["type"] == "application/pdf")
		{
		  	return true;
		}
		else return false;
	}

	public static function UpFile($file)
	{
		if ($file["error"] > 0)
		{
		    echo "Error! Return Code: " . $file["error"] . "<br />";
		}
	 	 else
	    {
	    	if (!file_exists(self::$_filepath))
	    	{ 
	    	  mkdir(self::$_filepath);
	    	}
	    	echo "Upload: " . $file["name"] . "<br />";
	    	echo "Type: " . $file["type"] . "<br />";
	    	echo "Size: " . ($file["size"] / 1024) . " Kb<br />";
	    	echo "Temp file: " . $file["tmp_name"] . "<br />";
	    	$path = self::$_filepath."/" . mb_convert_encoding($file["name"],"gbk", "utf-8");
	      if(move_uploaded_file($file["tmp_name"],$path))
	      {
	      	echo "Stored in: " . $path."<br />";
	      }
	      else {
	      	echo "Stored Error!<br />";
	      }
	    }
	}
}
?>