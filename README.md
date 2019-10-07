# with
Implementation of Python's [with statement](https://docs.python.org/3/whatsnew/2.6.html#pep-343-the-with-statement) as a helper function.

Before `with`:

```php
$handle = fopen("file.txt", "w");

try {
    mightThrowException();
} finally {
    fclose($handle);
}
```

After `with`:

```php
with(new Open("file.txt", "w"))->do(function ($handle) {
    mightThrowException();
});
```

## Analysis
### Pros
- Less boilerplate code
- Colocates a concerns enter and exit logic

### Cons
- Another layer of indirection

### Risks
- Single error handling logic implemented in this library, is mismatch for most domains


## Usage
### Multiple contexts
```php
with(
	new Open("one.txt", "w"),
	new Open("two.txt", "w")
)->do(function ($one, $two) {
	// todo
});
```


### Writing a custom context
A context is an instance of a class implementing `JonathanHunsucker\With\Context`, which requires two functions: `enter()` and `exit()`.

Here's an example custom context, which sets and restores a static member:

```php
use JonathanHunsucker\With\Context;

class UserStoredInStaticVar implements Context
{
    private $user;
    private $original_value;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function enter()
    {
        $this->original_value = MyProjectGlobals::getUser();
        MyProjectGlobals::setUser($this-user);
    }

    public function exit()
    {
        MyProjectGlobals::setUser($this->original_value);
    }
}
```