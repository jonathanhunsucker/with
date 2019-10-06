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

## Pros
- Less boilerplate code
- Colocates a concerns enter and exit logic

## Cons
- Another layer of indirection
