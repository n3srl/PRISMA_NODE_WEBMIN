<?php
/**
 * Class for DetectionFactory
 * 
 * @author: N3 S.r.l.
 */
class DetectionFactory extends DetectionFactoryBase 
{
	public static function CheckData($object, $clean = true){
		$errors = array();
		$parse_error = false;
		if (($object->erased === true && $object->erased!=null) || $object->erased =='true' || $object->erased =='1' ) {$object->erased = 1;}else{ $object->erased = 0;}
		if (!is_numeric($object->erased) && $object->erased!= null && $object->erased!='' && $object->erased!= 'null') {
			$errors[] = _('Rimosso non numerico');
			$parse_error = true;
		}
		if ($parse_error){
			$errors[] = ApiLogic::getFieldErrorCode();
			throw new ApiException(ApiException::$FieldException,$errors);
		}
	}
}
