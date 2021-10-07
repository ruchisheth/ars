<?php

namespace App\Libraries;
use Illuminate\Database\Eloquent\Model;
use Baum\Node;

// class Document extends Model
class Document extends Node
{
	protected $table = 'documents';

	protected $primaryKey = 'id_document';

	protected $parentColumn = 'id_parent';

	 // 'lft' column name
	protected $leftColumn = 'lft';

    // 'rgt' column name
	protected $rightColumn = 'rgt';

    // 'depth' column name
	protected $depthColumn = 'depth';

	protected $guarded = array('id_document', 'id_parent', 'lft', 'rgt', 'depth');

	protected $fillable = [ 
		'id_client', 'document_name', 'file_name', 'lft', 'rgt', 'id_parent', 'depth','document_type'
	];
}
