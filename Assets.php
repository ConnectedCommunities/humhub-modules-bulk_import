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

use yii\web\AssetBundle;

class Assets extends AssetBundle
{
    public $js = [
        '//cdn.jsdelivr.net/jdenticon/1.3.2/jdenticon.min.js',
        '//cdnjs.cloudflare.com/ajax/libs/blueimp-md5/2.3.0/js/md5.min.js'
    ];

    public function init()
    {
        // $this->sourcePath = dirname(__FILE__) . '/assets';
        parent::init();
    }
}