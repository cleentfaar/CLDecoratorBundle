# Usage

This bundle implements the [Decorator](https://github.com/cleentfaar/decorator) library, specifically by adding
a `decorate` function to your `Twig` environment and a `Compiler` class that loads your factories automatically by tag.

If you haven't already, you should first read the original usage documentation found [here](https://github.com/cleentfaar/decorator/src/CL/Decorator/Resources/doc/usage.md).
The following examples assume you have have created a `DecoratorFactory` and a `Decorator` class for the value you want
to decorate.



## 1) Tagging your factory

Continuing with the `UserDecoratorFactory` we created earlier, you will need to 'tag' it so the `DelegatingDecoratorFactory`
will pick it up. This makes your factory available with the `@cl_decorator.delegating_decorator_factory` service,
which is also used by the `decorate()` function for Twig.

There are multiple ways to tag your services (YAML, annotation, ...), but I'm just going to show you the YAML version.
If you use a different method you will have to look up the correct translation in the Symfony docs.

```yaml
# src/Acme/FoobarBundle/Decorator/Resources/config/services.yml

# ...

acme_foobar.decorator_factory.user:
    class: Acme\FoobarBundle\Decorator\UserDecoratorFactory
    tags:
        - { name: cl_decorator.decorator_factory }

# ...
```

That's it! You can now use the decorator in your controllers and Twig templates, as illustrated below.


## 2) Using the decorator in your Symfony project

Having tagged your factory in the previous step, you can now easily decorate any object that your factories support.

In a controller:
```php
<?php
// src/Acme/FoobarBundle/Controller/ProfileController.php

namespace Acme\FoobarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class ProfileController extends Controller
{
    /**
     * @Route(name="profile", path="/my_profile")
     * @Template
     */
    public function viewAction()
    {
        $user = $em->find(123); // some UserModel with ID 123

        $delegatingFactory = $this->get('cl_decorator.delegating_decorator_factory');
        $userDecorator     = $delegatingFactory->decorate($user);

        return [
            'user' => $userDecorator
        ];
    }
}

```

### The original value

So what if you still need to access the original `UserModel` in your Twig templates? Well you have two options: either
let your `UserDecorator` extend the `AbstractMagicDecorator` as documented [here](https://github.com/cleentfaar/decorator/src/CL/Decorator/Resources/docs/usage.md),
which automatically passes method calls that are not available in the decorator to the original value (if it's an object).

Or, you could still pass the original `UserModel` to the twig template:
```php
    public function viewAction()
    {
        // ...

        return [
            'user' => $user
        ];
    }
```
And then simply use the `decorate()` function in your template whenever you really need the decorated object:
```twig
{# ... #}

<h1>My profile</h1>
<div>Username: {{ user.username }}</div> {# Will call $user->getUsername() #}
<div>E-mail: {{ user.email }}</div> {# Will call $user->getEmail() #}
<hr/>
{% set userDecorated = decorate(user) %}
<div>Age: {{ userDecorated.age }}</div> {# Will call $userDecorator->getAge() #}

{# ... #}
```
