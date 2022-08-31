# Guara
Guara es un validador de datos para PHP.

Permite ahorrarte condiciones engorrosas con una simple sintaxis limpia y entendible.

## ¿Cómo usar Guara?
Solo debemos instanciar el objeto _Validator_ y pasarle al constructor la pila de datos que querramos validar.

```php
use Guara\Validator;

$data = [
    "name" => "Juan",
    "email" => "juan@domain.com",
    "password" => "123456789",
    "confirmPassword" => "123456789a",
];
$validator = new Validator($data);
```

Para validar un campo en específico vamos a usar el método _field(string $name): Field_.

```php
$validator->field('name'); // Nos retorna un objeto Field
```

Lo que hace _field(string $name)_ es instanciar un objeto Field y ponerlo en una lista de condiciones y evaluar si posee o no errores.

## Objeto Field
El objeto _Field_ tiene varios métodos que nos van a permitir validar los campos.

### isEmpty(): bool
Valida si el campo está vacío y retorna un boleano.
```php
$validator->field('name')->isEmpty(); // false
```

### min(int|float $minimum): bool
Compara dos valores y establece si es mínimo al indicado ($minimum) o si es mayor,  si es menor retorna _false_ y si es mayor retorna _true_.

min(int|float $minimum) logra diferenciar entre dígitos y strings, por lo que si el campo que vamos a validar es un string, entonces, contará los carácteres que posee y comparará con el mínimo indicado; si es un dígito, lo que hará es comparar los dos números y determinar si el valor del campo es menor al que pasamos por argumento.
```php
$validator->field('password')->min(8); // true
```

### max(int|float $maximum): bool
Funciona igual que el método _min_ nada más que acá se compara el máximo de carácteres.
```php
$validator->field('password')->max(16); // true
```
Con estos dos método podemos definir rangos.
```php
$validator->field('password')
                            ->min(8) // true
                            ->max(16); // true
```

### equalsTo(Field $field): bool
_equalsTo_ nos permitirá comparar dos campos dentro de nuestra pila de información. Para esto el objeto _Validator_ nos brinda un método llamado _getField_ básicamente obtiene de la lista de condiciones el field que hemos añadido con anterioridad.
```php
$validator->field('confirmPassword')->equalsTo($validator->getField('password')); // false
```

## Mensajes de error personalizados
Todos los métodos mostrados anteriormente te permiten además lanzar un mensaje de error personalizado usando un callback que devuelva un **string**. Éste tiene dos parámetros para ofrecerte: _$name_ y _$value_.
* $name: es el nombre del campo.
* $value: es el valor del campo.

Veamos:
```php
// Podemos usar una función flecha o una funcion anónima comúm.
// Yo voy a usar una función flecha porque se me hace más limpio.
$validator->field('number')->min(5, fn($name, $value) => "El campo $name de valor $value debe ser mayor o igual a 5");
```
## Condiciones personalizadas
Al igual que podes establecer mensajes de error personalizados, también vas a poder tener la condicione que vos quieras en caso que el objeto _Field_ no la tenga, usando el método _condition_.
_Condition_ recibe un callback el cual te brinda un argumento el cual da acceso a los datos del campo, éste callback tiene que retornar un mensaje de error en caso de que la condición se cumpla.
Ejemplo:

```php
$data = [
    "numbers" => [54, 23, 9, 87]
];
$validator = new Validator($data);

$validator->field('numbers')->condition(function($field) {
    if (!is_array($field->value)) {
        return "$field->name debe ser un array";
    }
});

```