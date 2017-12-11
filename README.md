Tree.php
--
A non-production-ready PHP web framework based on Swoole  
  
Features
--
- [x] Rest Controller  
- [x] Return, Render, Redirect  
- [x] Concurrent Model  
- [ ] ORM  
- [x] Router  
- [x] Module  
- [ ] Redis  
- [ ] Migration  
- [ ] Settings  
- [ ] Logging  
- [ ] Async Lib  
- [ ] MVC Generator  
  
Server Setup
--
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
class Testing extends \Tree\Core\Model {
	
	protected static $table_name = 'test';
	protected static $primary_key = ['id'];
	
}
```
```
$this->get('/db', function() {
	$fetchone = Testing::fetch()->where(['id' => 1])->order(['name' => 'ASC'])->select(['name', 'count'])->one();
	$fetchone->set('desc', 'new value');
	$fetchone->save();
	$createone = new Testing();
	$createone->set('desc', 'new row');
	$createone->save();
	$createone->delete();
});
```
