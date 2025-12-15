<p align="center">
<a href="https://laravel.com" target="_blank"><img src="https://user-images.githubusercontent.com/5760249/132945127-a7d3a4bb-1ffc-4658-8096-c9cfc2f5c3dd.png" width="400"></a>
</p>

# Laravel Vue Starter

The project was created to save myself time for redoing the same things all over again when starting a new Laravel/Vue project.

The main goal of this project is to reduce code and make everything simpler for bootstrapping new projects. 

The project is built with the following components:

- Vue 3 / Pinia / VueRouter
- Vue 3 Composition API
- Vite 3
- Laravel Framework
- Laravel Sanctum
- Laravel Fortify
- Tailwind
- ForkAwesome

## ⚡️ How to install

Installation is simple. Just like your ordinary Laravel app.

1. `git clone`
2. `cd HRIS-laravel-vue`
3. `composer install`
4. `cp .env.example .env`
5. `php artisan key:generate`   
6. `npm install`
7. `npm run dev` (or if production `npm run build`)

## ⚡️ How it works

### ➡️ Theming

The project supports theming, you can set a global color for the application theme, it can be done in `tailwind.config.js`.

```js
module.exports = {
    // ...
    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },
    //...
};
```

### ➡️ Authentication

The project ships with complete authentication boilerplate including:
- Login
- Register
- Forget Password
- Reset Password
- Dashboard
- Menu Management
- User Management


### ➡️ Users CRUD 

For your convenience the project comes with complete `users` crud that includes examples of:

- List page with filters and pagination
- Edit/create pages with form for editing user that includes ajax based role search field

### ➡️ Structure

The front-end code is located in `resources/js`. The code is organized in different directories to make things more readable.

| Directory    | Description                           |
|--------------|---------------------------------------|
| Components   | The home of the reusable components   |
| + icons      | The home of the icons                 |
| + widgets    | The home of the widget components     |
| Layouts      | The home of the global layouts        |
| Pages        | The home of the pages                 |
| + Auth       | Authentication pages                  |
| + Menu       | Menu pages                            |
| + Profile    | Profile pages                         |
| + User       | User pages                            |
| data         | The home of static data/constants     |
| router       | The home of the router configuration  |
| store        | The home of the Pinia stores          |
| + modules    | Vuex/Pinia store modules              |
### ➡️ Components

The project ships with the most useful components that are required for one application, organized in a clean structure:

| Name                  | Description                                                | Location                    |
|-----------------------|------------------------------------------------------------|-----------------------------|
| ApplicationLogo       | Application logo component                                 | js/Components               |
| Checkbox              | Custom checkbox component                                  | js/Components               |
| ConfirmDialog         | Confirmation dialog modal                                  | js/Components               |
| DangerButton          | Danger-themed button component                             | js/Components               |
| Dropdown              | Dropdown menu component                                    | js/Components               |
| DropdownLink          | Dropdown menu link item                                    | js/Components               |
| InputError            | Input error message display                                | js/Components               |
| InputLabel            | Input label component                                      | js/Components               |
| Modal                 | Modal wrapper for creating modals                          | js/Components               |
| NavLink               | Navigation link component                                  | js/Components               |
| PrimaryButton         | Primary-themed button component                            | js/Components               |
| ResponsiveNavLink     | Responsive navigation link                                 | js/Components               |
| SecondaryButton       | Secondary-themed button component                          | js/Components               |
| TextInput             | Custom text input field                                    | js/Components               |
| **Icons**             | Icon components collection                                 | js/Components/icons         |
| **Widgets**           | Reusable widget components                                 | js/Components/widgets       |

#### Layouts

| Name                    | Description                              | Location                    |
|-------------------------|------------------------------------------|-----------------------------|
| AuthenticatedLayout     | Layout for authenticated pages           | js/Layouts                  |
| AuthenticatedLayout copy| Copy/variant of authenticated layout     | js/Layouts                  |
| GuestLayout             | Layout for guest/public pages            | js/Layouts                  |
| footer                  | Footer component                         | js/Layouts                  |
| NavlinkNew              | New navigation link component            | js/Layouts                  |
| sidebar                 | Sidebar navigation component             | js/Layouts                  |
| topbar                  | Top navigation bar component             | js/Layouts                  |

#### Pages

Pages are organized by feature area:

| Directory  | Description                              |
|------------|------------------------------------------|
| Auth       | Authentication pages (login, register)   |
| Menu       | Menu management pages                    |
| Profile    | User profile pages                       |
| User       | User management pages                    |
| Dashboard  | Dashboard views                          |
| Welcome    | Welcome/landing page                     |

Note: Please always look in the components directory for the most up-to-date list. Each component may have additional props, events, and slots not listed here.

From here, you are on your own. Develop new pages, models, components, use professional IDE for development to improve your efficiency.


## ⚡️ Contributions

Pull requests are welcome, feel free to contribute to this project.


<!-- 
<!-- <p><img width="100%" src="https://user-images.githubusercontent.com/5760249/210167222-e04312ac-46ef-4dcd-a4d5-00c3a207bf32.gif"/></p>

### ➡️ CORS

Please make sure you have APP_URL, SANCTUM_STATEFUL_DOMAINS and SESSION_DOMAIN set correctly in [.env](https://github.com/gdarko/laravel-vue-starter/blob/master/.env.example) file as follows:

#### Normal domain

```
APP_URL=http://mywebsite.com

SANCTUM_STATEFUL_DOMAINS=mywebsite.com
SESSION_DOMAIN=mywebsite.com
```

#### Localhost with port

```
APP_URL=http://localhost:8000

SANCTUM_STATEFUL_DOMAINS=localhost:8000
SESSION_DOMAIN=localhost
```


## ⚡️ License

```
Copyright (C) 2022 Darko Gjorgjijoski (https://darkog.com)

This file is part of Laravel Vue Starter

Laravel Vue Starter is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
(at your option) any later version.

Laravel Vue Starter is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Laravel Vue Starter. If not, see <https://www.gnu.org/licenses/>.
``` -->
