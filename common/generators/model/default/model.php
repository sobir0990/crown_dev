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
/* @var $properties array list of properties (property => [type, name. comment]) */
/* @var $labels string[] list of attribute labels (name => label) */
/* @var $rules string[] list of validation rules */
/* @var $relations array list of relations (name => relation declaration) */

echo "<?php\n";
?>

namespace <?= $generator->ns ?>;

use Yii;

/**
* This is the model class for table "<?= $generator->generateTableName($tableName) ?>".
*

<?php foreach ($properties as $property => $data): ?>
    * @property <?= "{$data['type']} \${$property}" . ($data['comment'] ? ' ' . strtr($data['comment'], ["\n" => ' ']) : '') . "\n" ?>
<?php endforeach; ?>
<?php if (!empty($relations)): ?>
    <?php $relationsVars = []; ?>
    *
    <?php foreach ($relations as $name => $relation): ?><?php $relationsVars[] = lcfirst($name); ?>
        * @property <?= $relation[1] . ($relation[2] ? '[]' : '') . ' $' . lcfirst($name) . "\n" ?>
    <?php endforeach; ?>
<?php endif; ?>
*/
/**
* @OA\Schema(
*     description="<?= count($relationsVars) > 0 ? "include=" . implode(",", $relationsVars) : "" ?>"
* )
*/
class <?= $className ?> extends <?= '\\' . ltrim($generator->baseClass, '\\') . "\n" ?>
{
<?php foreach ($properties as $property => $data): ?>
    <?php
    $swaggerType = $data['type'];
    if ($swaggerType == "int") {
        $swaggerType = "integer";
    }
    ?>
    /**
    * @OA\Property(
    *   property="<?= str_replace("$", null, $property) ?>",
    *   type="<?= $swaggerType ?>",
    *   description="<?= $labels[str_replace("$", null, $property)] ?>"
    * )
    */
<?php endforeach; ?>

/**
* {@inheritdoc}
*/
public static function tableName()
{
return '<?= $generator->generateTableName($tableName) ?>';
}
<?php if ($generator->db !== 'db'): ?>

    /**
    * @return \yii\db\Connection the database connection used by this AR class.
    */
    public static function getDb()
    {
    return Yii::$app->get('<?= $generator->db ?>');
    }
<?php endif; ?>

/**
* {@inheritdoc}
*/
public function rules()
{
return [<?= empty($rules) ? '' : ("\n            " . implode(",\n            ", $rules) . ",\n        ") ?>];
}

/**
* {@inheritdoc}
*/
public function attributeLabels()
{
return [
<?php foreach ($labels as $name => $label): ?>
    <?= "'$name' => " . $generator->generateString($label) . ",\n" ?>
<?php endforeach; ?>
];
}
<?php foreach ($relations as $name => $relation): ?>

    /**
    * @return \yii\db\ActiveQuery
    */
    public function get<?= $name ?>()
    {
    <?= $relation[0] . "\n" ?>
    }
<?php endforeach; ?>
<?php if ($queryClassName): ?>
    <?php
    $queryClassFullName = ($generator->ns === $generator->queryNs) ? $queryClassName : '\\' . $generator->queryNs . '\\' . $queryClassName;
    echo "\n";
    ?>
    /**
    * {@inheritdoc}
    * @return <?= $queryClassFullName ?> the active query used by this AR class.
    */
    public static function find()
    {
    return new <?= $queryClassFullName ?>(get_called_class());
    }

<?php endif; ?>


<?php if (count($relationsVars) > 0): ?>
    public function extraFields()
    {
    $fields = parent::extraFields();
    <?php foreach ($relationsVars as $var): ?>
        $fields['<?= $var ?>'] = "<?= $var ?>";
    <?php endforeach; ?>
    return $fields;
    }
<?php endif; ?>
}
