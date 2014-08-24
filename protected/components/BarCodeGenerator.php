<?php
class BarCodeGenerator
{
	private $requiredKeys = array('code', 'filetype', 'dpi', 'scale', 'rotation', 'font_family', 'font_size', 'text');

	private $filetype;

	private $classFile;
	private $className;
	private $baseClassFile;
	private $codeVersion;

	protected function __construct($code) {
		$barcodeBaseDir = "/home/work/bms/web/bms/vendor/barcode/";
		include_once($barcodeBaseDir . 'html' . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . $code . '.php');

		$class_dir = $barcodeBaseDir . 'class';
		require_once($class_dir . DIRECTORY_SEPARATOR . 'BCGColor.php');
		require_once($class_dir . DIRECTORY_SEPARATOR . 'BCGBarcode.php');
		require_once($class_dir . DIRECTORY_SEPARATOR . 'BCGDrawing.php');
		require_once($class_dir . DIRECTORY_SEPARATOR . 'BCGFontFile.php');

		include_once($class_dir . DIRECTORY_SEPARATOR . $classFile);
		include_once($barcodeBaseDir . 'html' . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . $baseClassFile);

		$this->filetypes = array('PNG' => BCGDrawing::IMG_FORMAT_PNG, 'JPEG' => BCGDrawing::IMG_FORMAT_JPEG, 'GIF' => BCGDrawing::IMG_FORMAT_GIF);

		$this->classFile = $classFile;
    	$this->className = $className;
    	$this->baseClassFile = $baseClassFile;
    	$this->codeVersion = $codeVersion;
	}

	public static function create($code) {
		$c = __class__;

		return new $c($code);
	}

	public function generate($text, $filename) {
		$drawException = null;
		$dpi = 72;
		// $scale = 2;
		$scale = 1;
		$height = 15;
		try {
			$color_black = new BCGColor(0, 0, 0);
			$color_white = new BCGColor(255, 255, 255);
			$font = new BCGFontFile('/home/work/bms/web/bms/vendor/barcode/font/Arial.ttf', 10);

			$code_generated = new $this->className();

			$code_generated->setScale(max(1, min(4, $scale)));
			$code_generated->setBackgroundColor($color_white);
			$code_generated->setForegroundColor($color_black);

			$code_generated->setThickness(15);
			$code_generated->setFont($font);

			$code_generated->parse($text);
		} catch(Exception $exception) {
			$drawException = $exception;
		}

		$drawing = new BCGDrawing($filename, $color_white);
		if($drawException) {
			$drawing->drawException($drawException);
		} else {
			$drawing->setBarcode($code_generated);
			//$drawing->setRotationAngle($rotation);
			$drawing->setDPI($dpi === 'NULL' ? null : max(72, min(300, intval($dpi))));
			$drawing->draw();

			$drawing->finish($this->filetypes['JPEG']);

		}
	}
}
?>
