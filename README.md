<div align="center">
  <h1>Laravel Translations</h1>
  <p>
    <strong>This lightweight Laravel package enables you to eager load models along with their translations in the specified language, efficiently preventing the N+1 query problem. Additionally, it allows you to retrieve a single translation (instead of an array) without unnecessarily loading extra models into memory..</strong>
  </p>
  <p>
    <a href="https://packagist.org/packages/vershub/laraveltranslations">
        <img src="https://img.shields.io/packagist/v/vershub/laraveltranslations.svg?style=flat-square" alt="Latest Stable Version">
    </a>
    <a href="https://packagist.org/packages/vershub/laraveltranslations">
        <img src="https://img.shields.io/packagist/dt/vershub/laraveltranslations.svg?style=flat-square" alt="Total Downloads">
    </a>
    <a href="LICENSE.md">
        <img src="https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square" alt="License">
    </a>
  </p>
</div>



## 🚀 Installation  

Install the package via Composer:  

```bash  
composer require vershub/laraveltranslations:dev-master
```

## 🛠️ Usage  

### 1. Create a Translatable Model  

Extend the `TranslatableModel` class and implement the required abstract methods:  

```php  
namespace App\Models;  

use Vershub\LaravelTranslations\TranslatableModel;  

class CarBrand extends TranslatableModel  
{  
    protected $fillable = ['name'];  

    protected function getTranslationModel(): string  
    {  
        return CarBrandTranslation::class;  
    }  

    protected function getForeignKeyForTranslation(): string  
    {  
        return 'car_brand_id';  
    }  
}
```

### 2. Create a Translation Model  

The translation model should be a standard Eloquent model:  

```php  
namespace App\Models;  

use Illuminate\Database\Eloquent\Model;  

class CarBrandTranslation extends Model  
{  
    protected $fillable = ['locale_code', 'name', 'description'];  
}
```

Example of translation table migration:   

```php  
use Illuminate\Database\Migrations\Migration;  
use Illuminate\Database\Schema\Blueprint;  
use Illuminate\Support\Facades\Schema;  

class CreateCarBrandTranslationsTable extends Migration  
{  
    public function up()  
    {  
        Schema::create('car_brand_translations', function (Blueprint $table) {  
            $table->id();  
            $table->foreignId('car_brand_id')->constrained()->cascadeOnDelete();  
            $table->string('locale_code');  
            $table->string('name');  
            $table->text('description')->nullable();  
            $table->timestamps();  
        });  
    }  

    public function down()  
    {  
        Schema::dropIfExists('car_brand_translations');  
    }  
}  
```

### 3. Use the `withTranslation` Scope  

#### Retrieve translations for the current locale  

```php  
use App\Models\CarBrand;  

$carBrands = CarBrand::withTranslation()->get();  

foreach ($carBrands as $carBrand) {  
    echo $carBrand->translation->name;  
}
```

#### Retrieve translations for a specific locale  

```php 
use App\Models\CarBrand;  

$carBrands = CarBrand::withTranslation('fr')->get();  

foreach ($carBrands as $carBrand) {  
    echo $carBrand->translation->name;  
}
```

## ⚙️ Customization  

### Override the Locale Code Column  

By default, the package uses `locale_code`. You can override this by adding the `getLocaleCodeColumn` method to your model:  

```php
protected function getTranslationModel(): string  
{  
    return CustomTranslationModel::class;  
}  

protected function getForeignKeyForTranslation(): string  
{  
    return 'custom_foreign_key';  
}

protected function getLocaleCodeColumn(): string  
{  
    return 'language_code';  
}  
```
