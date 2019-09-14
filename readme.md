# Seamlessly integerate a one to one chat on your Laravel app

This is a demo application showing how to notify users of their mentions in Chatkit. You can read more on how this was built [here](https://pusher.com/tutorials/one-to-one-chat-laravel).


![Sample working example](./screenshots/one-on-one.gif)

## Project setup

First, clone the repository:
```
git clone https://github.com/dongido001/chatkit-laravel-one-to-one.git
```

Go to the project folder:

```
cd chatkit-laravel-one-to-one
```

Create your env file:

```
cp .env.example .env
```
- Add Chatkit Keys:

```
# ./.env
# [...]

CHATKIT_SECRET_KEY=<YOUR_CHATKIT_INSTANCE_LOCATOR>
CHATKIT_INSTANCE_LOCATOR=<YOUR_CHATKIT_INSTANCE_LOCATOR>

MIX_CHATKIT_SECRET_KEY="${CHATKIT_SECRET_KEY}"
MIX_CHATKIT_INSTANCE_LOCATOR="${CHATKIT_INSTANCE_LOCATOR}"
```

- Update `<YOUR_CHATKIT_SECRET_KEY>`, `<YOUR_CHATKIT_INSTANCE_LOCATOR>` with your [Chatkit keys](https://pusher.com/chatkit)


- Install PHP dependencies:

```
composer install
```

- Install JavaScript packages

```
npm install
```

Then build the files:

```
npm run build
```

Finally, start up the server:

```
php artisan serve
```

## Built with
 - [Chatkit](https://pusher.com/chatkit)
 - [Laravel](https://laravel.com)
 - [Vue.js](https://vuejs.org)
