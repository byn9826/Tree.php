Tree.php
--
Just another PHP web framework based on Swoole  
  
Features
--
- [x] Router  
- [x] Module  
- [x] Rest Controller  
- [x] Return, Render, Redirect  
- [x] Concurrent Model  
- [x] ORM  
- [x] Logging  
- [x] Migration  
- [x] Generator  

- [ ] Redis   
- [ ] Rbac   
- [ ] Async Lib  
  
Setup
--
./configure --enable-mysqlnd --enable-coroutine  
composer update  
php index.php   
http://ubuntu-byn982627438.codeanyapp.com   
  
Rest Controller
--
```
class ExampleController extends \Tree\Core\Controller {
  public function actions() {
    $this->get('/index', function() {
      $this->render('index', [
        'name' => $this->params('name'),
        'id' => $this->params('id')
      ]);
    });
    $this->post('/index', function() {
      $this->return([
        'type' => 'post',
        'location' => '/index',
        'params' => $this->params('id')
      ]);
    });
    $this->put('/test', function() {
      $this->redirect('index', [
        'id' => $this->params('id'), 'name' => 'From Put'
      ]);
    });	
    $this->delete('/index', function() {
      $this->responseFormat = 'raw';
      $this->responseCode = '401';
      return 'Unauthorized';
    });
    $this->all('/test', function() {
      return "I'll catch all';
    });
  }
}
```
  
Concurrent Model
--
```
//Define in model   
class Testing extends \Tree\Core\Model {
  protected static $table_name = 'test';
  protected static $primary_key = 'id';
}
```
```
//Use in controller  
$this->get('/db', function() {
  $fetchone = Testing::fetch()->where(['id' => 1])->order(['name' => 'ASC'])->select(['name', 'count'])->one();
	$desc = $fetchone->get('desc');
	$fetchone->set('desc', 'new');
	$fetchone->save();
	
	$fetchall = Testing::fetch()->where(['desc' => 'new'])->all();
	foreach($fetchall as $data) {
    $data->delete();
	}

	$createone = new Testing();
	$createone->set('desc', 'old');
	$createone->save();
});
```
  
Migration
--
Docs: https://book.cakephp.org/3.0/en/phinx.html  
```
//Create
php ./tools/migrate-create.php NameOfThisMigrate    
  
//Migrate Up
php ./tools/migrate-up.php

//Migrate Down
php ./tools/migrate-down.php

```

Generator
--
```
//Generate a model  
php ./tools/generator-model.php NameOfTheNewModel  

//Generate a controller  
php ./tools/generator-controller.php NameOfTheNewController  
```