<?php
/**
 * Class for StationFactory
 * 
 * @author: N3 S.r.l.
 */
class StationFactory extends StationFactoryBase 
{
	public static function CheckData($object, $clean = true){
		$errors = array();
		$parse_error = false;
		if (!is_numeric($object->camera_id) && $object->camera_id!= null && $object->camera_id!='' && $object->camera_id!= 'null') {
			$errors[] = _('camera_id non numerico');
			$parse_error = true;
		}
		if (!is_numeric($object->focal) && $object->focal!= null && $object->focal!='' && $object->focal!= 'null') {
			$errors[] = _('focal non numerico');
			$parse_error = true;
		}
		if (!is_numeric($object->aperture) && $object->aperture!= null && $object->aperture!='' && $object->aperture!= 'null') {
			$errors[] = _('aperture non numerico');
			$parse_error = true;
		}
		if (!is_numeric($object->logitude) && $object->logitude!= null && $object->logitude!='' && $object->logitude!= 'null') {
			$errors[] = _('logitude non numerico');
			$parse_error = true;
		}
		if (!is_numeric($object->latitude) && $object->latitude!= null && $object->latitude!='' && $object->latitude!= 'null') {
			$errors[] = _('latitude non numerico');
			$parse_error = true;
		}
		if (!is_numeric($object->elevation) && $object->elevation!= null && $object->elevation!='' && $object->elevation!= 'null') {
			$errors[] = _('elevation non numerico');
			$parse_error = true;
		}
		if (!is_numeric($object->acq_day_gain) && $object->acq_day_gain!= null && $object->acq_day_gain!='' && $object->acq_day_gain!= 'null') {
			$errors[] = _('acq_day_gain non numerico');
			$parse_error = true;
		}
		if (!is_numeric($object->acq_night_gain) && $object->acq_night_gain!= null && $object->acq_night_gain!='' && $object->acq_night_gain!= 'null') {
			$errors[] = _('acq_night_gain non numerico');
			$parse_error = true;
		}
		if ($parse_error){
			$errors[] = ApiLogic::getFieldErrorCode();
			throw new ApiException(ApiException::$FieldException,$errors);
		}
	}
}
