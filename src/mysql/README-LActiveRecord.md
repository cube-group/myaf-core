##LActiveRecord (基于LDBKernel) 
提供面向对象的方式用以访问数据库中的数据。一个LActiveRecord类关联一张数据表， 每个对象对应表中的一行，对象的属性映射到数据行的对应列。
###声明一个AR类
实现tableName方法, 返回表名
```
use libs\Orm\LActiveRecord;

/**
 * Class Task
 * @property $id
 * @property $name
 * @property $ip
 * @property $port
 * @property $create_time
 */
class Task extends LActiveRecord
{
    /**
     * @return string
     */
    public function tableName()
    {
        return "p_task";
    }
}
```
###初始化对象(构造方法传入LDB对象)
```
* $model = new Task($db);
```
###插入一行数据
```
$model = new Task($db);
$model->name = 'test task';
$model->ip = "127.0.0.1";
$model->port = '22';
$model->create_time = date('Y-m-d H:i:s');
$model->save();  // 一行新数据插入task表
```
###链式查询
find方法返回一个LActiveQuery对象, 能使用所有LDBKernel的链式查询方法
```
$query = $model->find();
$query->where(['name'=>'test task'])->andWhere("ip='127.0.0.1'");
```
###与LDBKernel不同的是, select返回对象数组, one返回单个对象
```
$tasks =$query->select();
$task = $query->one();
```
###query调用asArray()方法返回数组, 与LDBKernel执行结果一样
```
$tasksArray = $query->asArray()->select();
$taskArray = $query->asArray()->one();

```
###访问LActiveRecord对象属性
* 对象形式
```
$taskObj->name
```
* 数组形式
```
$taskObj['name']
```
* foreach遍历
```
foreach ($taskObj as $key => $value) {
    echo "{$key}--{$value}";
}
```
* 获取属性数组
```
$task->toArray()
```
* 获取json
```
$task->toJson()
```
###设置属性
* 对象方式设置属性
```
$task->name = "test A";
```
* setAttribute方法设置单个属性
```
$task->setAttribute('ip','127.0.0.2');//设置单个属性
```
* setAttributes方法批量设置属性
```
$task->setAttributes(['ip' => '127.0.0.3', 'name' => 'test setAttributes']);
```
###更新到数据库
save()保存到数据库, 主键存在新增,不存在插入
```
$task->save()
```
##获取save修改之前的所有属性
```
$task->getOldAttributes()
```
##获取发生修改的属性
```
$task->getDirtyAttributes()
```
##自定义查询语句
* 新建自定义查询对象
```
/**
 * Class TaskQuery
 */
class TaskQuery extends \libs\Orm\LActiveQuery
{
    /**
     * 命名范围(自定义查询条件)
     *
     * @param $value
     * @return $this
     */
    public function name($value)
    {
        return $this->where("name='{$value}'");
    }

}
```
* 重写模型类的find()方法, 利用刚刚新建对Query对象, 如下
```
class Task extends LActiveRecord
{
    /**
     * @return string
     */
    public function tableName()
    {
        return "p_task";
    }

    /**
     * @return TaskQuery
     */
    public function find()
    {
        $activeQuery = new TaskQuery($this->_db, $this->trueTableName());
        $activeQuery->setModelClass(get_called_class());
        return $activeQuery;
    }
}
```
* 调用自定义查询方法
```
$task = $taskModel->find()->name("测试机(123.57.157.78)")->one();
```

##钩子方法
* beforeSave (save前调用,返回false将阻止save调用)
* afterSave (save后调用)












