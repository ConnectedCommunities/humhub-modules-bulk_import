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
?>
<div class="panel panel-default">
    <div class="panel-heading"><strong>Bulk</strong> Import</div>
    <div class="panel-body">
        <h4>Upload CSV</h4>
        <div class="well">
        <?php 
        $form=$this->beginWidget('CActiveForm', array(
            'id'=>'registration-form',
            'enableAjaxValidation'=>true,
            'htmlOptions' => array('enctype' => 'multipart/form-data'),
            'action' => Yii::app()->createUrl('//bulk_import/main/upload')

        )); 
        ?>

        <?php echo $form->fileField($model,'csv_file'); ?>
        <?php echo $form->error($model, 'csv_file'); ?>
        <br />
        <?php  echo CHtml::submitButton('Upload CSV',array("class"=>"")); ?>
        <?php echo $form->errorSummary($model); ?>

        <?php $this->endWidget(); ?>
        </div>

        <br />
        <br />
        <hr>
        <br />
        <br />
        <h4>FAQ</h4>
        
        <h5>What can be bulk imported?</h5>
        <p>This tool allows you to bulk import users and join/create spaces.</p>
        <br />

        <h5>Example CSV Format</h5>
        <p>Ensure your csv file has these columns</p>
        <table class="table">
            <tr>
                <td><b>username</b></td>
                <td><b>email</b></td>
                <td><b>firstname</b></td>
                <td><b>lastname</b></td>
                <td><b>space_names</b></td>
            </tr>
            <tr>
                <td>user1</td>
                <td>user1@example.com</td>
                <td>User</td>
                <td>Wan</td>
                <td>Space 1</td>
            </tr>
            <tr>
                <td>user2</td>
                <td>user2@example.com</td>
                <td>User</td>
                <td>Two</td>
                <td>Space 1,Space 2</td>
            </tr>
        </table>
    </div>
</div>

