<?php

class RemoveOrphanedPageSlicesTask extends BuildTask{

	protected $enabled = true;
	protected $title = 'Remove orphaned pageslices task';
	protected $description = 'Check pageslices in Live table to see if they still have a Stage record';

	public function run($request){
		// get stage slices
		Versioned::set_reading_mode('Stage.Stage');
		$pageSlices = PageSlice::get();
		$stageSlices = array();
		if( $pageSlices->exists() ){
			foreach( $pageSlices as $pageSlice ){
				$stageSlices[] = $pageSlice->ID;
			}
		}
		// get live slices
		Versioned::set_reading_mode('Stage.Live');
		$pageSlices = PageSlice::get()->where('ID NOT IN('.implode(',',$stageSlices).')');
		if( $pageSlices->exists() ){
			foreach( $pageSlices as $pageSlice ){
				echo('Removing from live table: ' . $pageSlice->ID . '<br>');
				$pageSlice->deleteFromStage('Live');
			}
			echo('Cleanup ready!');
		} else {
			echo('No orphans found!');
		}
	}
}
