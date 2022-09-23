## Installation

- Create empty database in MySQL
- Update database configuration in `.env`
- `php artisan migrate`

## How to work

- `php artisan serve`
- Create a HTTP request to the endpoint you want

## Endpoints

### POST
`/api/credit-analysis`

### Payload:

  - `name` | varchar(75) | plain text
  - `cpf` | varchar(14) | xxx.xxx.xxx-xx
  - `negative` | boolean | true or false
  - `salary` | float | xxxx.yy
  - `card_limit` | float | xxxx.yy
  - `rent_value` | float | xxxx.yy
  - `road` | varchar(120) | plain text
  - `number` | int | x
  - `city` | varchar(75) | plain text
  - `federative_unit` | varchar(2) | AA
  - `cep` | varchar(9) | xxxxx-xxx

### Response

  - `reference_code` | XXXXXXXX-00001
  - `score` | XX
  - `result`: [disapproved, derivative, approved]


### GET
`/api/credit-analysis`

### Payload:

  - `cpf` | varchar(14) | xxx.xxx.xxx-xx

### Response

  - `reference_code` | XXXXXXXX-00001
  - `score` | XX
  - `result`: [disapproved, derivative, approved]


## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
