## Install dependencies

```
docker run --rm --interactive --tty \
  --volume $PWD:/app \
  composer install
```

## Demo

### Syntax highlighting

```js
$(".class").each(e => console.log(e));
```