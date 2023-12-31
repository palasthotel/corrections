<?php

namespace Palasthotel\WordPress\Corrections\Source;

use Palasthotel\WordPress\Corrections\Model\Revision;

class Revisions {

	/**
	 * @param int|string $post_id
	 *
	 * @return Revision[]
	 */
	public static function getLatestOfAuthors( $post_id): array {
		global $wpdb;
		$results = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT ID, post_author, post_date_gmt FROM $wpdb->posts WHERE post_type = 'revision' AND post_parent = %d ORDER BY post_date DESC",
				$post_id
			)
		);

		$revisions = [];
		foreach ($results as $item){
			if(!isset($revisions[$item->post_author])){
				$revisions[$item->post_author] = $item;
			}
			if(strtotime($revisions[$item->post_author]->post_date_gmt) < strtotime($item->post_date_gmt)){
				$revisions[$item->post_author]->post_date_gmt = $item->post_date_gmt;
			}
		}

		return array_map(function($item){
			return new Revision(
				intval($item->ID),
				intval($item->post_author),
				strtotime($item->post_date_gmt)
			);
		}, array_values($revisions));
	}
}
