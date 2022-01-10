<?php
/**
 * Class for FreetureFactory
 * 
 * @author: N3 S.r.l.
 */
class FreetureFactory extends FreetureFactoryBase 
{
	public static function CheckData($object, $clean = true){
		$errors = array();
		$parse_error = false;
		if (($object->show === true && $object->show!=null) || $object->show =='true' || $object->show =='1' ) {$object->show = 1;}else{ $object->show = 0;}
		if (!is_numeric($object->show) && $object->show!= null && $object->show!='' && $object->show!= 'null') {
			$errors[] = _('show non numerico');
			$parse_error = true;
		}
		if (($object->erased === true && $object->erased!=null) || $object->erased =='true' || $object->erased =='1' ) {$object->erased = 1;}else{ $object->erased = 0;}
		if (!is_numeric($object->erased) && $object->erased!= null && $object->erased!='' && $object->erased!= 'null') {
			$errors[] = _('erased non numerico');
			$parse_error = true;
		}
		if ($parse_error){
			$errors[] = ApiLogic::getFieldErrorCode();
			throw new ApiException(ApiException::$FieldException,$errors);
		}
	}
}
