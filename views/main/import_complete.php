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
        <?php 
        $flashes = Yii::app()->user->getFlashes();

        if(!empty($flashes)) {
            echo "<div class=\"well\">";
            echo "<h5>Errors</h5>";
            echo "<p>The input errors below should give you a hint as to what's going wrong.</p>";

            foreach($flashes as $key => $error) {
                echo $error;
            }

            echo "</div>";
        }

        ?>

        <h5>Failed Imports</h5>
        <table class="table">
            <tr>
                <td><b>username</b></td>
                <td><b>email</b></td>
                <td><b>firstname</b></td>
                <td><b>lastname</b></td>
                <td><b>spaces</b></td>
            </tr>
            <?php foreach($invalidImports as $data) { ?>
                <tr>
                    <td><?php echo $data['username']; ?></td>
                    <td><?php echo $data['email']; ?></td>
                    <td><?php echo $data['firstname']; ?></td>
                    <td><?php echo $data['lastname']; ?></td>
                    <td><?php echo implode(",", $data['space_names']); ?></td>
                </tr>
            <?php } ?>
        </table>

        <h5>Successful Imports</h5>
        <table class="table">
            <tr>
                <td><b>username</b></td>
                <td><b>email</b></td>
                <td><b>firstname</b></td>
                <td><b>lastname</b></td>
                <td><b>spaces</b></td>
            </tr>
            <?php foreach($validImports as $data) { ?>
                <tr>
                    <td><?php echo $data['username']; ?></td>
                    <td><?php echo $data['email']; ?></td>
                    <td><?php echo $data['firstname']; ?></td>
                    <td><?php echo $data['lastname']; ?></td>
                    <td><?php echo implode(",", $data['space_names']); ?></td>
                </tr>
            <?php } ?>
        </table>

    </div>
</div>

