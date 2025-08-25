<?php
/**
 * Class for Docker
 * 
 * @author: N3 S.r.l.
 */
class Docker
{
	public $id;
	public $name=null;
	public $image=null;
	public $command=null;
	public $status=null;
	public $created=null;
	public $create_date=null;
	public $valid_from=null;
	public $valid_to=null;
	public $erased=0;
	public $last_update;
}
?>
