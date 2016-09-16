<?php
// Upload
class MediumUploadHandler extends UploadHandler {

	// Init
	protected function initialize() {
		parent::initialize();
	}

	protected function validate($uploaded_file, $file, $error, $index) {

		$rptypes = [
			'gif'	=> '/(gif)$/i',
			'jpg'	=> '/(jpe?g)$/i',
			'jpeg'	=> '/(jpe?g)$/i',
			'pjpeg'	=> '/(jpe?g)$/i',
			'pjpeg'	=> '/(jpe?g)$/i',
			'png' 	=> '/(png)$/i'
		];

		$rpinfo = pathinfo($file->name);

		if($rpreg = @$rptypes[$rpinfo['extension']]){
			$rpbuffer = file_get_contents($uploaded_file);
			$rpfinfo = new finfo(FILEINFO_MIME_TYPE);
			$rpmime = $rpfinfo->buffer($rpbuffer);
			
			if (preg_match($rpreg, $rpmime)){
				return parent::validate($uploaded_file, $file, $error, $index);
			}else{
				$file->error = $this->get_error_message('accept_file_types');
				return false;
			}

		}else{
			$file->error = $this->get_error_message('accept_file_types');
			return false;
		}

	}

	protected function trim_file_name($file_path, $name, $size, $type, $error, $index, $content_range) {
		$name = trim($this->basename(stripslashes($name)), ".\x00..\x20");
		$info = pathinfo($name);
		return $this->hash().'.'.$info['extension'];
	}

	// Create a random hash
	private function hash()
	{
		return substr(md5(microtime()), 0, 20);
	}

}