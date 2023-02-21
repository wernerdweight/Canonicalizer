Canonicalizer
==

Simple PHP string canonicalizer

[![Build Status](https://app.travis-ci.com/wernerdweight/Canonicalizer.svg?branch=master)](https://app.travis-ci.com/wernerdweight/Canonicalizer)
[![Latest Stable Version](https://poser.pugx.org/wernerdweight/canonicalizer/v/stable)](https://packagist.org/packages/wernerdweight/canonicalizer)
[![Total Downloads](https://poser.pugx.org/wernerdweight/canonicalizer/downloads)](https://packagist.org/packages/wernerdweight/canonicalizer)
[![License](https://poser.pugx.org/wernerdweight/canonicalizer/license)](https://packagist.org/packages/wernerdweight/canonicalizer)

Instalation
--

1) Download using composer

```bash
composer require wernerdweight/canonicalizer
```

2) Use in your project

```php
use WernerDweight\Canonicalizer\Canonicalizer;
 
$string = 'This is an interesteing string with some strange cháračtěřš in it. Хорошо?'
$maxLength = 255;   // maximal resulting canonical length (excessive chars will be trimmed)

$canonicalizer = new Canonicalizer($maxLength);
$canonical = $canonicalizer->canonicalize($string);

echo $canonical;    // this-is-an-interesting-string-with-some-strange-characters-in-it-choroso
```

API
--

* **`canonicalize(string $string[, string $ending[, string $separator]]): string`** \
  * `$string` - string to be canonicalized,
  * `$ending` - string to be appended at the end of canonicalized string (ending is included in `$maxLength`) - useful to append ids, file formats etc.; default `''` (empty string),
  * `$separator` - string used to separate canonicalized words; default `-` (dash).

* **`setBeforeCallback(?callable): self`**  \
Allows to set a callback function that will be called **before** the given string is canonicalized. \
The callback should respenct this definition `function (string): string`. \
**Warning:** the callback remains in place until explicitly nulled (`$canonicalizer->setBeforeCallback(null)`).


* **`setAfterCallback(?callebld): self`**
Allows to set a callback function that will be called **after** the given string is canonicalized.
The callback should respenct this definition `function (string): string`.
**Warning:** the callback remains in place until explicitly nulled (`$canonicalizer->setAfterCallback(null)`).
