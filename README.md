# Command Bus 

> Simple Command Bus for Laravel framework

## Installation

Require this package in your `composer.json` and update composer.
    
```php
"it-devgroup/laravel-command-bus ": "^1.0"
```
    
After updating composer, add the ServiceProvider to the providers array in `config/app.php`

```php
ItDevgroup\CommandBus\CommandBusServiceProvider::class,
```

## Example

```php
class UserController extends AbstractController
{
    public function store(Request $request)
    {
        $user = $this->dispatch(new RegisterUser(
            trim($request->input('email')),
            trim($request->input('password'))
        ));
    
        return $user;
    }
}
```

## Usage

#### Command

```php
class RegisterUser implements \ItDevgroup\CommandBus\Command
{
    private $email;
    private $password;
    
    public function __construct($email, $password)
    {
        $this->email = $email;
        $this->password = $password;
    }
    
    public function email()
    {
        return $this->email;
    }
    
    public function password()
    {
        return $this->password;
    }
}
```

#### Handler

```php
class RegisterUserHandler implements \ItDevgroup\CommandBus\Handler
{
    private $userRepository;
    
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    
    public function handle(\ItDevgroup\CommandBus\Command $command)
    {
        $user = new User(
            $command->email(),
            $command->password()
        );
        
        $this->userRepository->store($user);
        
        return $user;
    }
}
```

#### Controllers 

```php
class AbsctractController extends \Illuminate\Routing\Controller
{
    private $dispatcher;
    
    public function __construct(\ItDevgroup\CommandBus\CommandBus $dispatcher) 
    {
        $this->dispatcher = $dispatcher;
    }
    
    public function dispatch(\ItDevgroup\CommandBus\Command $command)
    {
        return $this->dispatcher->execute($command);
    }
}
```

```php
class UserController extends AbstractController
{
    public function store(Request $request)
    {
        $user = $this->dispatch(new RegisterUser(
            trim($request->input('email')),
            trim($request->input('password'))
        ));
    
        return $user;
    }
}
```