# dejframework (Work in Progress)
A Light and Easy to use PHP Framework.

# Introduction
dejframework is a simple and minimal PHP MVC framework focused on conciseness, simplicity and understandability. It's syntax is mainly inspired from Laravel. dejframework was developed by Ata Marzban for the Bachelor Degree's final project.

# Features
- MVC Architecture (Model-View-Controller)
- Simple and Concise Syntax.
- Class Autoloader (PSR-0 Standard).
- Very easy routing from a Request's URL & Method to a Controller & Action or a Closure.
- Easy to use Service Provider with Dependency Injection and Singletons.
- Universally accessible JSON configuration.
- MySQL Support with built-in prepared statements. (extensible to other DBs)
- Method-Chaining Query Builder.
- Object-Relational Mapping (ORM) Built into the Models.
- Easily interact with the HTTP protocol with the Request/Response Objects. (Getting & Setting headers, status codes, etc.)
- Separate your UI and Data Presentation with Views.
- Auto-Converting Objects to JSON makes REST API Implementation easy.
- Validate Data on a Request, a Model or anything else easily with Validation Rules.
- Multi-Language Validation errors with string formatting.

# Requirements
- Apache2
- Mod_Rewrite (.htaccess support)
- PHP 7
- MySQL

#Installation
- To set up your development environment, Clone this repository or download it and put it on the machine of your choice.
- Configure a Virtual Host on Apache and set the **/public** folder as the Document Root. All Requests go through the **/public/index.php** file on this folder. Also Enable "Mod_Rewrite" and set "AllowOverride All" on the Virtual Host.
- Rename **/config.sample.json** to **config.json**.
- That's it!

# Routing
For the framework to know what to do when a request comes, you should set routes for your applications in /app/routes.php. It is done easily.

A Route consists of an **HTTP Method, URL, and the destination that should execute if a request is made with the specified method to the specified URL.** Nothing is executed without setting routes. You can pass a closure as the destination:

```php
[/app/routes.php]

Route::set("GET", "/", function(){

  return "Hello World!";

});
```

This code will output "Hello World" if a you visit your site on yoursite.com.

```php
[/app/routes.php]

Route::set("GET", "/some/url", function(){

  return "This is some url!";

});
```

This code will output "This is some url!" if a you visit your site on yoursite.com/some/url.

```php
[/app/routes.php]

Route::set("POST", "/do/something", function(){

  return "Here you should write your own code to do the things you want.";

});
```

This code will be executed if you visit yoursite.com/do/something with the POST method. (from a form or an API call)

You can return Strings, Objects, Arrays or Views (Discussed later) in you closures or controllers. String will be output directly, while Objects or Views will be automatically converted to JSON and output, and Views will be rendered to output.

In The next part you'll learn how to direct routes to Controllers instead of Closures.

# Controllers
It's considered best practice in the MVC pattern that application logic should be put into controllers.
- First create your controller in **/app/controllers**. Refer to IndexController.php in this folder to see the example. Name Your controller according to your preference and add **Actions** to it as public static functions. Pay Attention to this example:

```php
[/app/controllers/YourController.php]

<?php
namespace app\controllers;

class YourController extends \dej\mvc\Controller
{

    public static function yourAction()
    {
        return "This is the right way to do it!";
    }

}
```
**Important:** As you can see in the example above, For the PSR-0 Autoloader to function correctly, you should follow the following conventions when adding any class to your application:
- The Namespace of the class should map to it's directory. (Case-Sensitive)
- The Name of the class should be the same as the php file name. (Case-Sensitive)
If these conventions are followed, you won't need to include classes manually, as they will be autoloader when you use them in your code.

Now let's continue learning how to make controllers work:
- after creating you controller and setting it's name and namespace correctly, set your routes like this:

```php
[/app/routes.php]

Route::set("GET", "/", "YourController@YourAction");
```

The specified action on the specified controller will be executed when the route triggers.
# Service Provider / Dependency Injector
Constantly Instantiating classes and passing dependencies to them can become a repetitive task in php development. The \dej\App Service provider aims to makes this process as DRY as possible. Using this service provider, you don't need to add **use** statements in each file and pass dependencies. Take This example:

```php
/** 
* Without a Service Provider
* when you want to build a query
*/

use \dej\db\Connection;
$connection = Connection::getInstance();

use \dej\db\Query;
$query = new Query($connection);

$result = $query->select()->from('someTable')->getAll();
return $result;
```

And this should be repeated every time you want to use the query builder.
Now, using the Service Provider:

```php
use \dej\App;
return App::Query()->select()->from('some_table');
```
That's it! Take a look at **dej/App.php** to see how it works. An static method named 'Query' is called on the App class. It instantiates the Query class and passes a connection instance as the constructor parameters to it. Piece of cake!

# Database
**Configuration:** First enter the database configuration in **/config.json**.
dejframework deals with databases in a 3-Layer Architecture:

**Layer 1 - Database Connection Object:**
This extends the Singleton abstract class. What that means is that it is instantiated only once, the first time it's called. Some other services on dejframework are like this too. To prevent the overhead of connecting to the DB every time you want to run a Query. Here is how you can use it:

```php
//simple query
$result = App::Connection()->executeQuery("SELECT * FROM some_table");

//NonQuery: a query that doesn't return rows, only the number of affected rows.
$result = App::Connection()->executeNonQuery("DELETE FROM some_table WHERE some_field = 'some_value'");

//A Query using prepared statements, To protect against SQL Injection.
$result = App::Connection()->executeQuery("SELECT * FROM some_table WHERE some_field = ?", ["some_value"]);

//A Query using prepared statements, To protect against SQL Injection. With Multiple Parameters.
$result = App::Connection()->executeQuery("SELECT * FROM some_table WHERE some_field = ? AND another_field = ?", [$some_value, "another_value"]);

//A Query using prepared statements, To protect against SQL Injection. With Named Parameters.
$result = App::Connection()->executeQuery("SELECT * FROM some_table WHERE some_field = :some_value_name
                                                                    AND another_field = :another_value_name",

                                                                    [":some_value_name" => $some_value,
                                                                     "another_value_name" => "another_value"]);
```
You can do this anywhere, provided that you have added ``` use \dej\App; ```.

**Layer 2 - Query Builder:**
This class builds queries and uses the Connection class to run them using secure prepared statements. It should be instantiated for each new query, this is done for you by /dej/App automatically each time you type ``` App::Query() ```, just like we saw in the Service Povider section example. You can build queries with it using method chaining. Take a look at the examples below:
```php
$result = App::Query()->select()->from('users')->getAll();

$result = App::Query()->select()->from('users')->getOne();

$result = App::Query()->select()->from('users')->getJson();

$query = App::Query()->select()->from('users')->getQuery();
```
As you can see, using the dej Query Builder is simple, use call App::Query() and it automatically passes a new, dependency injected Query class to you, and then you chain methods on it to add conditions of your liking to it, such as select(), from() and so on, then finally, you use one of the get methods to fetch either the top result (``` getOne() ```), All results (``` getAll() ```), All results as JSON (``` getJson() ```), Or the constructed query (``` getQuery() ```). The results are fetched in stdClass format that you can use easily. It's worth noting that without using one of the get methods at the end of you query, the results won't be fetched. Also, you can chain methods on multiple lines and in multiple steps, for example, to change it by some condition:
```php
$query = App::Query()->select();

if($somecondition == true ) $query->from('users');
else $query->from('another_table');

$result = $query->getAll();
```
Let's see other methods available on the query builder in the following examples:
```php
//All queries will be executed using prepared statements and parameters will be handled automatically.
//SELECT Queries:
$result = App::Query()->select()->from('users')->where('id', '=', '22')->getAll();

$result = App::Query()->select()->from('users')->where('city', '=', 'Berlin')
                                                ->andWhere('age', '>', '20')->getAll();

$result = App::Query()->select()->from('users')->where('city', '=', 'Berlin')
                                                ->orWhere('city', '=', 'Paris')->getAll();

$result = App::Query()->select()->from('users')->orderBy('age', 'DESC')
                                                ->limit(25)
                                                ->offset(50)->getAll();

//INSERT Query:
$affectedRows = App::Query()->insertInto('users')->values(["username" => "jameshetfield",
                                                      "password" => "19831983",
                                                      "city" => "Downey"])->do();
```
**Note That** Queries that don't return results, must be executed with ```php do() ``` and it will automatically return the number of affected rows.
```php
//UPDATE Query:
$affectedRows = App::Query()->update('users')->set(["age" => 53,
                                                    "band" => "Metallica"])
                                                ->where('username', '=', 'jameshetfield')->do();

//DELETE Query:
$affectedRows = App::Query()->deleteFrom('users')->where('username', '=', 'someone')->do();
```
**Note That** DELETE or UPDATE Queries can result in loss of data if there's no WHERE clause provided, as a security measure, dejframework will throw an exception if it encounters such a situation. Please run such queries using the Connection class manually.

**Layer 3 - Object-Relational Mapping:** ORM will be discussed in the next section.

# Object-Relational Mapping
According to Wikipedia:
>Object-relational mapping (ORM) ... is a programming technique for converting data between incompatible type systems in object-oriented programming languages. This creates, in effect, a "virtual object database" that can be used from within the programming language.

Since dejframework works in an MVC Architecture, Data persistence is contained in the Model component of MVC. Models relate to the entities in your application, such as a User, a Purchase, a Product, etc. in a shopping system. They need to be saved and retrieved to and from the database. Relational databases work in the SQL language, and dejframework, like most frameworks operates in an Object-Oriented environment. And SQL code in the middle of PHP code is considered not to be best-practice. Thus, dejframework tries to seperate you from SQL code in 3 levels, 2 of them were elaborated in the previous section, now we will see how to work with Models:

1. Create your Model in **/dej/app/models**. Be sure to follow the PSR-0 namespacing conventions. an example is set up for you, named **User.php**, Also, Create the corresponding table and fields in the Database.
2. Add the properties that your model has. (According to your design: for example username, password and city for User)
3. For the Model to know which database table it corresponds to, set it as a class property, Also set the primary key:
```php
class User extends \dej\mvc\Model
{
	protected static $dbTable = "users";

	//Format: ["db_field_name" => "modelPropertyName"]
	protected static $primaryKey = ["id" => "id"];

  //Model properties
	public $username;
	public $password;
	public $city;
	.
	.
	.
	}
```
3. For the Model to know which of it's properties correspond to which Database table columns, set it as a class property, Ideally, their names should be the same.
```php
class User extends \dej\mvc\Model
{
	protected static $dbTable = "users";

	//Format: ["db_field_name" => "modelPropertyName"]
	protected static $dbFields = ["username" => "username",
		                            "password" => "password",
		                            "city" => "city",
	                            	"id" => "id"];

	public $username;
	public $password;
	public $city;
	.
	.
	.
	}
```
**Note That** The ``` id ``` property is defined in the ```/dej/mvc/Model``` class that your models extend. Thus, if you want your Model to have an id, there's no need to define it again.

4. For the model to know it's own name, set it as a class property:
```php
class User extends \dej\mvc\Model
{
	protected static $dbTable = "users";
	protected static $dbFields = ["username" => "username",
		                            "password" => "password",
		                            "city" => "city",
	                            	"id" => "id"];
	//Exactly the same as the class name
	protected static $modelName = "User";

	public $username;
	public $password;
	public $city;
	.
	.
	.
	}
```

**That's it for now,** provided that you have set these configurations correctly, you will be able to use the ORM methods on your Models. Take a look at the examples below:
```php
//Creating a new record
use \app\models\User;

$user = new User();
$user->username = "jameshetfield";
$user->password = "13831383";
$user->city = "Downey";
$user->create();  //Saved into the database.
```
The ORM uses the dej Query builder underneath, to generate necessary queries.

**Note That** create(), update(), and delete() return affected rows which you can check to see if the operation was successful.
```php
//Finding a record by a field named 'id'
$user = User::findById(11);

//changing it's properties
$user->password = "through_the_never";
$user->update();  //Updated in the database.

//deleting it.
$user->delete();
```
**Note That** These functions work with the primary key of records.
```php
//Finding records by some condition.
$users = User::find()->where('city', '=', 'Sari')->getAll(); //Returns an array of User objects.

//A more complex one
$users = User::find()->where('city', '=', 'Sari')->andWhere('age', '>', 20)->orderBy('age', 'ASC')
                                                                            ->limit(25)
                                                                            ->offset(100)->getAll();
                                                                            //Don't forget the get*() method!
//Retrieveing all records
$users = User::getAll();  //doesn't need a getAll() at the end because it knows what to do.

//The method for deleting by condition is named 'wipe'
$users = User::wipe()->where('status', '=', 'banned')->orWhere('email_confirmation', '=', '0');

//counting all records
$userCount = User::countAll();

//counting records that have a certain condition
$userCount = User::count()->where('city', '=', 'Sari')->getInt(); //getInt() returns the count of the results as an Integer.
```

# Data Validation
Data Validation in dejframework is handled by The ```\dej\Validator``` Class. You can use it in various ways:

1. **Standalone:** You can use the validation service anywhere in your application with ```App::Validator()``` which gives you the singleton instance of it. It accepts 2 parameters: A **Value** to be validated. (String, Int, Array, Object) and a set of **Rules** to validate the data against.

Let's see how it works:
```php
use \dej\App;

//Validate a single value according to a set of rules seperated by "|".
$result = App::Validator()->validate("This is going to be validated", "required|string|min:5|max:30");

//Validate an object or and array:
$result = App::Validator()->validate($user, ['username' => 'required|string|email|min:5|max:30',
						'password' => 'required|string|min:10|max:100'],
						'age' => 'int|min:18|max:99');
```
The ```validate()``` method returns an array. If there are no validation errors and the data is valid, it will be an empty array. so you can check if the data is valid with ```empty()```. If there are any validation errors, you will see them in the array.
```php
//The rules have changed
$errors = App::Validator()->validate("This is going to be validated", "required|string|email|min:5|max:10");
var_dump($errors);
```
This is what the above code will output:
```
array
  0 => 'This Field should be an email'
  1 => 'This Field should be less than 10'
```
Now with an object:
```php
$errors = App::Validator()->validate($obj, ['email' => 'required|string|email',
                                            'password' => 'required|string',
                                            'age' => 'int']);
var_dump($errors);
```
Output:
```
array (size=3)
  'email' =>
    array (size=1)
      0 => string 'This Field should be an email'
  'password' =>
    array (size=2)
      0 => string 'This Field is Required'
      1 => string 'This Field should be more than 10'
  'age' =>
    array (size=1)
      0 => string 'This Field should be a number'
```
As you can see, if you pass an object or array to the validator, it will return the errors related to each field in an associative array where the keys are field names and the values are arrays containing errors related to that field.

2. **On the Request object:** You can validate request parameters (GET & POST Parameters) using the ```validate()``` method on The Request object.
```php
$errors = App::Request()->validate(['email' => 'required|string|email',
                                    'password' => 'required|string|min:10|max:100']);
var_dump($errors);
```
Visiting ```oyoursite.local/?email=notanemail&password=123``` will result in:
```
array (size=2)
  'email' =>
    array (size=1)
      0 => string 'This Field should be an email'
  'password' =>
    array (size=1)
      0 => string 'This Field should be more than 10'
```

3. **On Models:** You can validate models by setting the validation rules in their class definition. for example, see the User model included in ```/app/models/User.php```:
```php
class User extends \dej\mvc\Model
{
.
.
.
protected static $validationRules = ["username" => "required|string|min:5|max:20",
					"password" =>"required|string|min:5|max:255",
					"city" => "string|max:10"];
.
.
.
}
```
Now you can validate instances of the models:
```php
$user = User::getById(11);
$errors = $user->validate(); //returns errors in array like the previous examples.
$isValid = $user->isValid(); //returns true or false
```
//TODO Complete Documentation
