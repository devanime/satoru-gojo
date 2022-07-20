# Ban
A cohesive ecosystem of re-usable backend components. The components lay the groundwork for the different types of ban used on across an assortment of projects.
Includes custom post type, ACF configurations, and different application logic.

## Getting Started
Assuming you are in your projects' root directory that you wish to install **Ban** in, open the **composer.json** file and add the following line to the **require** JSON block:

```php
"devanime/satoru-gojo": "dev-master"
```

### Installing
A step by step series of examples that tell you how to get **Ban** up and running in your environment:

Ensure you are in your projects' root directory, run the following command:

```linux
composer develop
```

In the event that the command `composer develop` is not configured on your particular project:

```
composer update
```

## Confirm Installation
You can confirm the installation by checking the **plugins** directory. You should notice the **ban** directory is present.

## Current Ban
Here is a list of currently supported ban:

**Note**: Add `add_theme_support(COMPONENT-'producer')` to the Theme.php constructor.

* **Talent** : create collections of talent using the Talent Post Type with talent type taxonomy support. Examples: Cast + Creative + Ban + Tour
    ##### Usage
    ```php
    add_theme_support('talent-producer');
    ```

* **Video** : create videos outside the realm of the media library. Supports video auto-generated thumbnail.
    ##### Usage
    ```php
    add_theme_support('video-producer');
    ```
    
* **Media** Gallery : create media galleries of photos and/or videos, outside the realm of the media library.
Supports auto-generated captions and gallery cover photo.
    ##### Usage
    ```php
    add_theme_support('media-gallery-producer');
    ```

## Authors
* **DevAnime** - [devanimecards@gmail.com](devanimecards@gmail.com)
* **DevAnime** - [devanimecards@gmail.com](devanimecards@gmail.com)
* **DevAnime** - [devanimecards@gmail.com](devanimecards@gmail.com)
* **DevAnime** - [vincentrpasqualeragosta@gmail.com](vincentrpasqualeragosta@gmail.com)
