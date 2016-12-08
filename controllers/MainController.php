<?php

/**
 * Connected Communities Initiative
 * Copyright (C) 2016 Queensland University of Technology
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace humhub\modules\bulk_import\controllers;

use humhub\modules\admin\models\UserSearch;
use humhub\modules\karma\models\Karma;
use humhub\modules\karma\models\KarmaSearch;
use Yii;
use humhub\modules\bulk_import\forms\BulkImportForm;
use humhub\modules\user\models\User;
use humhub\modules\user\models\Password;
use humhub\modules\space\models\Space;
use yii\helpers\Html;
use humhub\libs\ProfileImage;

class MainController extends \humhub\modules\admin\components\Controller
{


    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'acl' => [
                'class' => \humhub\components\behaviors\AccessControl::className(),
                'adminOnly' => true
            ]
        ];
    }

	/** 
	 * Registers a user
	 * @param $data
     * @return Bool
	 */
	private function registerUser($data) {

        $userModel = new User();
        $userModel->scenario = 'registration';

        $profileModel = $userModel->profile;
        $profileModel->scenario = 'registration';

        // User: Set values
        $userModel->username = $data['username'];
        $userModel->email = $data['email'];
        $userModel->group_id = $data['group_id'];
        $userModel->status = User::STATUS_ENABLED;

	    // Profile: Set values
		$profileModel->firstname = $data['firstname'];
		$profileModel->lastname = $data['lastname'];
		

		// Password: Set values
		$userPasswordModel = new Password();
		$userPasswordModel->setPassword($data['password']);

        if($userModel->save()) {

	        // Save user profile
			$profileModel->user_id = $userModel->id;
			$profileModel->save();

			// Save user password
			$userPasswordModel->user_id = $userModel->id;
			$userPasswordModel->save();

			// Join space / create then join space 
			foreach ($data['space_names'] as $key => $space_name) {

				// Find space by name attribute
                $space = Space::findOne(['name'=>$space_name]);

				// Create the space if not found
				if($space == null) {
					$space = new Space();
    				$space->name = $space_name;
    				$space->save(); 
				}

				// Add member into space
				$space->addMember($userModel->id);

			}

			return true;

        } else {
            Yii::$app->session->setFlash('error', Html::errorSummary($userModel));
        	return false;
        }

	}

    public function actionIndex(){
    	$form = new BulkImportForm;

        return $this->render('index', array(
            'model' => $form
        ));
    }

    public function actionSetDefaultPass() {

    	if(isset($_GET['user_ids'])) {
    		$user_ids = explode(",", $_GET['user_ids']);

	    	foreach($user_ids as $user_id) {
	        	$userPasswordModel = new Password();
	        	$userPasswordModel->user_id = $user_id;
		        $userPasswordModel->setPassword("password");
		        
		        if($userPasswordModel->save()) {
		        	echo "Saved... <br />";
		        }

		    }
    	} else {
    		echo "<p>?user_ids=user_id,user_id to reset the password of users to 'password'</p>";
    	}
    	

    }

    public function actionIdenticon() {

//        $assetPrefix = Yii::app()->assetManager->publish(dirname(__FILE__) . '/../assets', true, 0, defined('YII_DEBUG'));
//        Yii::app()->clientScript->registerScriptFile($assetPrefix . '/md5.min.js');
//        Yii::app()->clientScript->registerScriptFile($assetPrefix . '/jdenticon-1.3.0.min.js');

		if(isset($_POST['userids'])) {

			// Loop through selected users
			foreach($_POST['userids'] as $user_id) {

				// Find User by ID
				$user = User::findIdentity($user_id);

				// Upload new profile picture
				$this->uploadProfilePicture($user->guid, $_POST['identicon_'.$user_id.'_value']);

			}
		}

        $searchModel = new \humhub\modules\admin\models\UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('identicon', array(
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel
        ));

    }

	public function actionUpload() {
	    
//        $assetPrefix = Yii::$app->assetManager->publish(dirname(__FILE__) . '/../assets', array('forceCopy' => true));
//        Yii::$app->clientScript->registerScriptFile($assetPrefix . '/md5.min.js');
//        Yii::$app->clientScript->registerScriptFile($assetPrefix . '/jdenticon-1.3.0.min.js');
		require_once(dirname(__FILE__) . "/../lib/parsecsv.lib.php");
        $csv = new \parseCSV();
	    $model = new BulkImportForm;

	    $validImports = array();
	    $invalidImports = array();

	    if(isset($_POST['BulkImportForm']))
	    {

	        $model->attributes=$_POST['BulkImportForm'];
	        if(!empty($_FILES['BulkImportForm']['tmp_name']['csv_file']))
	        {

	            $file = \yii\web\UploadedFile::getInstance($model,'csv_file');
	            $group_id = 1;

				$csv->auto($file->tempName);

				foreach($csv->data as $data) {

					// Make a username from the first and last names if username is mising
					if(empty($data['username'])) {
						// $data['username'] = substr(str_replace(" ", "_", strtolower(trim($data['firstname']) . "_" . trim($data['lastname']))), 0, 25);
						$data['username'] = substr(ucfirst(trim($data['firstname'])) . " " . ucfirst(trim($data['lastname'])), 0, 25);
					}

					// Put data into correct format
			    	$importData = array(
			    		'username' => $data['username'],
			    		'password' => $data['password'], 

						'firstname' => $data['firstname'],
			    		'lastname' => $data['lastname'], 
			    		'email' => $data['email'],

			    		'space_names' => explode(",", $data['space_names']),
			    		'group_id' => $group_id,
			    	);

			    	// Register user
			    	if($this->registerUser($importData)) {
			    		$validImports[] = $importData;
			    	} else {
			    		$invalidImports[] = $importData;
			    	}

				}

	        }

	    }

        return $this->render('import_complete', array(
            'validImports' => $validImports,
            'invalidImports' => $invalidImports,
        ));

	}


    /** 
     * Uploads the identicon profile picture
     * @param int User ID
     * @param Base64 Image (identicon)
     */
    private function uploadProfilePicture($userId, $data) 
    {

        // Create temporary file
        $temp_file_name = tempnam(sys_get_temp_dir(), 'img') . '.png';
        $fp = fopen($temp_file_name,"w");
        fwrite($fp, file_get_contents($data));
        fclose($fp);

        // Store profile image for user
        $profileImage = new ProfileImage($userId);
        $profileImage->setNew($temp_file_name);

        // Remove temporary file
        unlink($temp_file_name);

    }

}