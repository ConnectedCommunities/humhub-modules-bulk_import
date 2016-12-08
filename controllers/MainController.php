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

class MainController extends Controller{

	public $subLayout = "application.modules_core.admin.views._layout";

	/** 
	 * Registers a user
	 * @param array data
	 */
	private function registerUser($data) {

        $userModel = new User('register');
        $userPasswordModel = new UserPassword();
        $profileModel = $userModel->profile;
        $profileModel->scenario = 'register';

        // User: Set values
        $userModel->username = $data['username'];
        $userModel->email = $data['email'];
        $userModel->group_id = $data['group_id'];
        $userModel->status = User::STATUS_ENABLED;

	    // Profile: Set values
		$profileModel->firstname = $data['firstname'];
		$profileModel->lastname = $data['lastname'];
		

		// Password: Set values
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
				$space = Space::model()->findByAttributes(array('name'=>$space_name));

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
        	Yii::app()->user->setFlash('error', CHtml::errorSummary($userModel));
        	return false;
        }

	}

    public function actionIndex(){
    	$form = new BulkImportForm;
        $this->render('index', array('model' => $form));
    }

    public function actionSetDefaultPass() {

    	if(isset($_GET['user_ids'])) {
    		$user_ids = explode(",", $_GET['user_ids']);

	    	foreach($user_ids as $user_id) {
	        	$userPasswordModel = new UserPassword();
	        	$userPasswordModel->user_id = 1;
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

        $assetPrefix = Yii::app()->assetManager->publish(dirname(__FILE__) . '/../resources', true, 0, defined('YII_DEBUG'));
        Yii::app()->clientScript->registerScriptFile($assetPrefix . '/md5.min.js');
        Yii::app()->clientScript->registerScriptFile($assetPrefix . '/jdenticon-1.3.0.min.js');
		
		$model = new User('search');

		if(isset($_POST['userids'])) {

			// Loop through selected users
			foreach($_POST['userids'] as $user_id) {

				// Find User by ID
				$user = User::model()->findByPk($user_id);

				// Upload new profile picture
				$this->uploadProfilePicture($user->guid, $_POST['identicon_'.$user_id.'_value']);

			}
		}
        	
    	$this->render('identicon', array(
    		'model' => $model
    	));
    }

	public function actionUpload() {
	    
        $assetPrefix = Yii::app()->assetManager->publish(dirname(__FILE__) . '/../resources', true, 0, defined('YII_DEBUG'));
        Yii::app()->clientScript->registerScriptFile($assetPrefix . '/md5.min.js');
        Yii::app()->clientScript->registerScriptFile($assetPrefix . '/jdenticon-1.3.0.min.js');


	    require_once("lib/parsecsv.lib.php");
		$csv = new parseCSV();
	    $model = new BulkImportForm;

	    $validImports = array();
	    $invalidImports = array();

	    if(isset($_POST['BulkImportForm']))
	    {
	        $model->attributes=$_POST['BulkImportForm'];
	        if(!empty($_FILES['BulkImportForm']['tmp_name']['csv_file']))
	        {
	            $file = CUploadedFile::getInstance($model,'csv_file');
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

	    $this->render('import_complete', array('validImports' => $validImports, 'invalidImports' => $invalidImports));

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