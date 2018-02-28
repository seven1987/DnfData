<?php
/**
 * This is the template for generating the model class of a specified table.
 */

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\model\Generator */
/* @var $tableName string full table name */
/* @var $className string class name */
/* @var $queryClassName string query class name */
/* @var $tableSchema yii\db\TableSchema */
/* @var $labels string[] list of attribute labels (name => label) */
/* @var $rules string[] list of validation rules */
/* @var $relations array list of relations (name => relation declaration) */

echo "<?php\n";
?>
<?php 


?>
namespace <?= $generator->ns ?>;

use Yii;

trait <?= $className ?>
{

  /**
     * 返回数据库字段信息，用于mongodb与php数据类型转换
     */
    public static function getColumnType(){
        return array(
        <?php
            foreach ($tableSchema->columns as $column){
                //$defaultValue = $column->phpType == 'string' ? '"'.$column->defaultValue.'"' : $column->defaultValue;
                 echo "'{$column->name}' => '{$column->type}',\n\t\t" ;
             } 
         ?>
     );
    }


    /**
    * 返回当前表所采用数据库
    */
    public static function getDb()
    {
        return \Yii::$app->get('db_dm_game');
    }

    /**
    * 返回当前表字段名,包括_id
    */
    public function attributes()
    {
        return ['_id',<?php
            foreach ($tableSchema->columns as $column){
                echo "'{$column->name}',";
            }
            ?>];
    }

    /**
    * 返回表主键
    */
    public static function primaryKey()
    {
        return [<?php
            foreach ($tableSchema->columns as $column){
                if ($column->isPrimaryKey == true){
                    echo "'{$column->name}',";
                }
            }
        ?>];
    }
}
