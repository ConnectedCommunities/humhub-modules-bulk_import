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

/**
 * @package humhub.modules_core.admin.forms
 * @since 0.5
 */
class BulkImportForm extends CFormModel {

    public $csv_file;

    /**
     * Declares the validation rules.
     */
    public function rules() {
        return array(
            array('csv_file', 'file', 'types' => 'csv, xls', 'maxSize'=>5242880, 'allowEmpty' => true, 'wrongType'=>'Only .csv, .xls files are allowed.', 'tooLarge'=>'File too large! 5MB is the limit')
            // array('logo', 'file', 'types' => 'jpg, png, jpeg', 'maxSize' => 3 * 1024 * 1024, 'allowEmpty' => true),
        );
    }

    /**
     * Declares customized attribute labels.
     * If not declared here, an attribute would have a label that is
     * the same as its name with the first letter in upper case.
     */
    public function attributeLabels() {
        return array(
            // 'logo' => Yii::t('AdminModule.forms_BasicSettingsForm', 'Logo upload')
            'csv_file'=>'Upload CSV File',
        );
    }

}