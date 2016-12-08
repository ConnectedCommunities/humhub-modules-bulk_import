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

namespace humhub\modules\bulk_import;

use Yii;
use yii\helpers\Url;


class Events extends \yii\base\Object
{
    /**
     * Defines what to do if admin menu is initialized.
     *
     * @param type $event
     */
    /**
     * Defines what to do if admin menu is initialized.
     *
     * @param type $event
     */
    public static function onAdminMenuInit($event)
    {

        $event->sender->addItem(array(
            'label' => Yii::t('BulkImportModule.base', 'Bulk Import'),
            'url' => Url::to(['/bulk_import/main/index']),
            'group' => 'manage',
            'icon' => '<i class="fa fa-paw"></i>',
            'isActive' => (Yii::$app->controller->module && Yii::$app->controller->module->id == 'bulk_import' && Yii::$app->controller->id == 'admin'),
            'sortOrder' => 700,
        ));

        $event->sender->addItem(array(
            'label' => Yii::t('BulkImportModule.base', 'Bulk Import Identicons'),
            'url' => Url::to(['/bulk_import/main/identicon']),
            'group' => 'manage',
            'icon' => '<i class="fa fa-paw"></i>',
            'isActive' => (Yii::$app->controller->module && Yii::$app->controller->module->id == 'bulk_import' && Yii::$app->controller->id == 'admin'),
            'sortOrder' => 700,
        ));

    }

}