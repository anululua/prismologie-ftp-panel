<?php

use yii\helpers\Html;


$this->title = 'Archives';

?>


    <div class="archives-index">
        <h1>
            <?= Html::encode($this->title) ?>
        </h1>

        <div class="col-md-12">
            <?php if(!empty($dataProvider)){?>
            <table class="table table-striped" name="archives_table" id="archives_table">
                <colgroup>
                    <col span="1" style="width: 50%;">
                    <col span="1" style="width: 50%;">
                </colgroup>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Options</th>
                    </tr>
                </thead>
                <?php foreach ($dataProvider as $data): ?>
                <tr>
                    <td>
                        <?php echo Html::a($data); ?>
                    </td>
                    <td>
                        <?= Html::a('<i class="glyphicon glyphicon-trash"></i>',['archive-delete','path'=>$path.$data.'/'], ['class' => 'btn btn-black', 'id' => 'del_utility','title' => 'Delete']);?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
            <?php } else {
                echo "Empty Archives";
            }?>

        </div>
    </div>
