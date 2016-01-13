#### Note: This SDK is still a work in progress but should be mostly working.

# Installation
`composer.json`:
```
"repositories": [
      {
          "type": "vcs",
          "url":  "https://github.com/arleslie/enom-php.git"
      }
  ],
  "require": {
    "arleslie/enom-php": "dev-master"
  }
  ```
  
# Usage
Note: All functions return raw response from Enom's API. (This will change in the future)
```
$enom = \arleslie\Enom\Client('user-id', 'password', true); // Last argument is for testmode.

// Get Available TLDs
$enom->Domain()->getTLDList();

// Check Domain Availablity
$enom->Domain()->check('google', 'com');
```

Currently all of the domain registration (except for preconfigure) is available.
