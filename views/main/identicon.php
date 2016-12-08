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


use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;
use yii\widgets\ActiveForm;
use humhub\widgets\DataSaved;

humhub\modules\bulk_import\Assets::register($this);
?>

<script>
    
    function renderJdenticons() {

        $(".identicon").each(function(el) {
            $(this).jdenticon(md5($(this).attr('data-to-hash')));
            $("#"+$(this).attr('id')+"_value").val($(this).get(0).toDataURL());
        });
    }
    
    $(function() {
        renderJdenticons();    
    });

</script>

<?php $form = ActiveForm::begin(array(
    'id'=>'registration-form',
    'enableAjaxValidation'=>false,
    'action' => Url::to(['/bulk_import/main/identicon'])
)); ?>

<div class="panel panel-default">
    <div class="panel-heading"><?php echo Yii::t('AdminModule.views_user_index', '<strong>Identicon</strong> uploader'); ?></div>
    <div class="panel-body">
        <p>
            Bulk upload identicons for users
        </p>

        <?php
        echo GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                array(
                    'class' => 'yii\grid\CheckboxColumn',
                    'checkboxOptions' => function ($model, $key, $index, $column) {
                        return [
                            'value' => $model->id
                        ];
                    },
                    'name' => 'userids'
                ),
                'username',
                'email',
                array(
                    'attribute' => 'Old',
                    'format' => 'raw',
                    'options' => array('width' => '35px'),
                    'value' => function($model) {
                        return Html::img($model->profileImage->getUrl(), array("style" => "width:35px; height: 35px; background-color:#000;"));
                    }
                ),
                array(
                    'attribute' => 'New',
                    'format' => 'raw',
                    'options' => array('width' => '35px'),
                    'value' => function($model) {
                        return '<canvas class="identicon" id="identicon_'.$model['id'].'" data-to-hash="'.$model['email'].'" width="35" height="35" /></canvas><input type="hidden" id="identicon_'.$model['id'].'_value" name="identicon_'.$model['id'].'_value" />';
                    }
                ),

            ]
        ]);

        /*$this->widget('zii.widgets.grid.CGridView', array(
            'id' => 'user-grid',
            'dataProvider' => $model->resetScope()->search(),
            'filter' => $model,
            'itemsCssClass' => 'table table-hover',
            'afterAjaxUpdate' => 'function(){ renderJdenticons() }',
            // 'loadingCssClass' => 'loader',
            'columns' => array(
                array(
                    'class'=>'CCheckBoxColumn',
                    'selectableRows' => 2,
                    'checkBoxHtmlOptions' => array(
                        'name' => 'userids[]',
                    ),
                    'value'=>'$data->id',
                ),  
                array(
                    'name' => 'username',
                    'header' => Yii::t('AdminModule.views_user_index', 'Username'),
                    'filter' => CHtml::activeTextField($model, 'username', array('placeholder' => Yii::t('AdminModule.views_user_index', 'Search for username'))),
                ),
                array(
                    'name' => 'email',
                    'header' => Yii::t('AdminModule.views_user_index', 'Email'),
                    'filter' => CHtml::activeTextField($model, 'email', array('placeholder' => Yii::t('AdminModule.views_user_index', 'Search for email'))),
                ),
                array(
                    'header' => 'old',
                    'value' => 'CHtml::image($data->profileImage->getUrl(), "", array("style" => "width:35px; height: 35px; background-color:#000;"))',
                    'type' => 'raw',
                    'htmlOptions' => array('width' => '35px'),
                ),
                array(
                    'header' => 'new',
                    'type' => 'raw',
                    'value'=>function($data, $i){
                        return '<canvas class="identicon" id="identicon_'.$data['id'].'" data-to-hash="'.$data['email'].'" width="35" height="35" /></canvas><input type="hidden" id="identicon_'.$data['id'].'_value" name="identicon_'.$data['id'].'_value" />';
                        // return '<canvas id="identicon_'.$data['id'].'" width="35" height="35" /></canvas><input type="hidden" id="identicon_'.$data['id'].'_value" name="identicon_'.$data['id'].'_value" /><script> $(function() { generateJdenticon("#identicon_'.str_replace(" ", "_", $data['id']).'", "'.$data['email'].'"); }); </script>';
                    },
                    'htmlOptions' => array('width' => '35px'),
                ),
            ),
            'pager' => array(
                'class' => 'CLinkPager',
                'maxButtonCount' => 5,
                'nextPageLabel' => '<i class="fa fa-step-forward"></i>',
                'prevPageLabel' => '<i class="fa fa-step-backward"></i>',
                'firstPageLabel' => '<i class="fa fa-fast-backward"></i>',
                'lastPageLabel' => '<i class="fa fa-fast-forward"></i>',
                'header' => '',
                'htmlOptions' => array('class' => 'pagination'),
            ),
            'pagerCssClass' => 'pagination-container',
        ));
           */
        ?>
        <?php echo Html::submitButton('Upload Identicons', array('class' => 'btn btn-primary pull-right')); ?>
    </div>
</div>

<?php $form->end(); ?>



