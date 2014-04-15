<?php
/**
* @package   com_zoo
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

class Update244 implements iUpdate {

    /*
		Function: run
			Performs the update.

		Returns:
			bool - true if updated successful
	*/
	public function run($app) {

		// add application group field if it doesn't exist
		$fields = $app->database->getTableFields(ZOO_TABLE_APPLICATION);
		if (isset($fields[ZOO_TABLE_APPLICATION]) && !array_key_exists('alias', $fields[ZOO_TABLE_APPLICATION])) {
			$app->database->query('ALTER TABLE '.ZOO_TABLE_APPLICATION.' ADD alias VARCHAR(255) AFTER name');
		}

		// sanatize alias fields of the application
		foreach ($app->table->application->all() as $application) {

			if (empty($application->alias)) {

				$application->alias = $app->application->getUniqueAlias($application->id, $app->string->sluggify($application->name));

				try {

					$app->table->application->save($application);

				} catch (ApplicationTableException $e) {}
			}

		}

		// refresh database indexes
		$app->update->refreshDBTableIndexes();

	}

}