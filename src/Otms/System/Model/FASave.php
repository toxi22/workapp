<?php

/**
 * This file is part of the Workapp project.
*
* (c) Dmitry Samotoy <dmitry.samotoy@gmail.com>
*
*/

namespace Otms\System\Model;

use Engine\Model;
use PDO;

/**
 * FASave Model class
 *
 * Сохраняет файлы в ФС с помощью JS загрузчика http://github.com/valums/file-uploader
 *
 * @author Dmitry Samotoy <dmitry.samotoy@gmail.com>
 */

class FASave extends Model {
	/**
	 * реальное имя файла
	* @var string
	*/
	private $filename = null;
	
	/**
	 * md5 имя файла в ФС
	* @var string (MD5 hash)
	*/
	private $md5 = null;
	
	/**
	 * Сохраняет "php://input" в файл
	 * Сохраняет размер, реальное имя, сгенерированное md5 и диреткорию назначения в БД
	* @return TRUE - успешное сохранение
	* @return FALSE - ошибка при сохранении
	*/
	function save() {
		if ($this->registry["auth"]) {
			$input = fopen("php://input", "r");
			$temp = tmpfile();
			$realSize = stream_copy_to_stream($input, $temp);
			fclose($input);
			 
			if ($realSize != $this->getSize()){
				return false;
			};

			$sql = "INSERT INTO fm_fs (`md5`, `filename`, `pdirid`, `size`) VALUES (:md5, :filename, :curdir, :size)";
			 
			$res = $this->registry['db']->prepare($sql);
			$param = array(":md5" => $this->md5, ":filename" => $this->filename, ":curdir" => '1', ":size" => $realSize);
			$res->execute($param);

			$target = fopen($this->registry['path']['root'] . "/" . $this->registry['path']['upload'] . $this->md5, "w");
			fseek($temp, 0, SEEK_SET);
			stream_copy_to_stream($temp, $target);
			fclose($target);

			return true;
		} else {
			return false;
		}
	}

	/**
	 * Получает реальное имя сохраняемого файла
	 * 
	 * @return string
	 */
	function getName() {
		$this->filename = $_GET['qqfafile'];
		$this->md5 = md5($this->filename . date("YmdHis"));

		return $this->filename;
	}
	
	
	/**
	 * Получает размер сохраняемого файла
	*
	* @return int
	*/
	function getSize() {
		if (isset($_SERVER["CONTENT_LENGTH"])){
			return (int)$_SERVER["CONTENT_LENGTH"];
		} else {
			throw new Exception('Getting content length is not supported.');
		}
	}
}