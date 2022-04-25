### Task

* Your main goal is to create product image gallery in detailed product page (similar
  to [this example](https://www.imperija.lt/lt/buitine-technika/indaploves/smeg-indaplove-stfabbl3/?item=11028))
  following these requirements:
    * Images should be obtained from API https://gallery-api.engine.lt/ (documentation username: demo password:
      gallery123)
        * Follow steps in documentation to successfully implement API
        * Note - images are for product modification, not product or item
        * Note - all requests are throttled, use cache where possible. Auth - 5 per minute, Gallery - 20 per minute,
          Images - 360 per minute
    * Only certain amount of thumbails should be visible at one (with ability to scroll to others), limit constant can
      be found in `config/controllers/entity/products.cfg.php`
    * Image should be opened in full size using [fancybox](http://fancybox.net/) on click

### E-ngine stack & environment requirements

* PHP 7.4 (compatible with PHP 7.2)
* Composer
* Npm

### Environment preparation

* First time:
    * `git clone git@bitbucket.org:elab-assigments/engine-lite.git .`
    * Import `sql/lite_database.sql` to your database
    * Copy `config/db.example.cfg.php` to `config/db.cfg.php` and change configs according your mysql setup
    * `npm install`
    * `composer install`
    * `npm run prod`
* To continuously rebuild css and js use:
    * `npm run watch`

### E-ngine lite description

**PHP**

* Main parts:
    * Entities - php classes used to work with single entity (e.g. page, product, new, etc.)
        * To access certain controller use `$this->get_e('<controller_name>')`
            * e.g. to select language from
              database `$project_lang = $this->get_e('languages')->get_element($project['default_lang'])`
        * Common functions
            * `get_element($id, $key = 'id', $formatting_mode = false, $cache = true)` - returns single element from
              database by given id (or any other key), also applies given formatting
                * e.g. `$this->get_e('brands')->get_element($data['brand_url'], 'url', 'photos')` - finds brand by given
                  url and applies `format_photos` to it
            * `find_elements($where = "", $format_mode = false, $limit = false)` - returns all elements from database
              which satisfies given where clause, also applies given formatting
            * `format_<format_name>($element)` - applies formatting on given element
    * FrontendControllers - all logic (information from entities, smarty view load & services function calls) should be
      here
        * Most logic what other controller functions should be called further are written in `logic()`
    * Services - logic which are not part of entities or controllers
* Structure:
    * config - configs of system
        * config/* - general configs of system
            * constants.cfg.php - default place for various constants used in system, often used to store credentials of
              third party systems
        * config/controllers/entity - configuration for specific entity
        * config/controllers/frontend - configuration for specific controller
    * resources - all scss/js resources should be placed here (you can check `webpack.config.js` to understand how
      production js & css are compiled)
    * src - all php code is placed here
        * **Request lifecycle** - All frontend controllers & engine itself always perform these functions in particular
          order:
            * `init()`
                * In application level
                    * Loads all neccessary configs to memory
                    * Initializes session & connection with database
                    * Loads page types with urls from database
            * `prepare()`
                * In application level sets correct language & main page from `url`
                * In controller level sets correct `content_layout` in smarty
            * `logic()`
                * In application level this function decides from `url` which frontend controller should be loaded
                * In controller level this function decides from `url` which controller functions should be loaded
                  further
            * `before_render()`
                * In application level pass default parameters to `smarty`
            * `render()`
                * In application level:
                    * For ajax requests. If `$_GET['display']` is set.
                      Example `?display=content_types/news/detailed.tpl&args[0]=jonas&args[1]=petras`
                        * Sets 'views/frontend/content_types/news/detailed.tpl'
                        * Calls NewsFrontendController->detailed('jonas', 'petras')
                        * Returns plain view response without any layout (great for reloading single page part
                          using `ajaxnav`)
                    * Otherwise sets correct `page_layout` in smarty
            * `clean_up()`
                * In application level closes db connection
* For making api requests instead of using plain curl consider using [Guzzle](https://docs.guzzlephp.org/en/stable/)
* Consider creating `Services` & calling them in controllers instead of inflating controller classes

---
**JS**

* Small javascript logic which is only used in single template can be written inside smarty template
* Bigger components should be written as separate class in`resources/js/frontend`
    * If component is only used in certain parts of system & not whole website it should be separated as separate config
      in webpack & called in smarty using helper `{$h->mix('<name>.js','frontend')}` only where necessary

---
**CSS**

* SCSS is used in all framework
* For new components create separate scss file in `resources/scss/frontend/components` & include it in main file
* Generic bootstrap & framework custom variables should be used where possible
    * Create new variables if necessary