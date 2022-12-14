# Laravel FPDF

## install

**1) Composer**

```php
composer require aryatama045/laravel-fpdf
```

**2) Optional:** Laravel 5.4 and below

Add `Aryatama045\Toastr\ToastrServiceProvider::class` to `providers` in `config/app.php`. <br>
Add `'Toastr' => Aryatama045\Toastr\Facades\Toastr::class` to `aliases` in `config/app.php`. <br>

```
// config/app.php

'providers' => [
  // ...
  Aryatama045\Fpdf\FpdfServiceProvider::class,
],

'aliases' => [
  // ...
  'Fpdf' => Aryatama045\Fpdf\Facades\Fpdf::class,
],
```

<br>

## Usage

### (Method 1) Facade

```php
use App\Http\Controllers\Controller;
use Aryatama045\Fpdf\Facades\Fpdf;

class MyController extends Controller
{
    public function index()
    {
        Fpdf::AddPage();
        Fpdf::SetFont('Courier', 'B', 18);
        Fpdf::Cell(50, 25, 'Hello World!');
        Fpdf::Output('I', "MyPdf.pdf", true);

        exit;
    }
}
```

<br>

### (Method 2) Extends class

create your pdf class

```php
namespace App\Pdf;

use Aryatama045\Fpdf\Fpdf;

class MyPdf extends Fpdf
{
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
        parent::__construct('P', 'mm', 'A4');
        $this->SetA4();
        $this->SetTitle('My pdf title', true);
        $this->SetAuthor('Aryatama045', true);
        $this->AddPage('L');
        $this->Body();
    }

    public function Header()
    {
        // fixed all pages
    }

    public function Body()
    {
        $this->SetFont('Arial', 'B', '24');
        $this->Cell(50, 25, $this->data->title);

        $this->SetFont('Arial', '', '14');
        $this->Cell(50, 25, $this->data->content);
    }

    public function Footer()
    {
        // fixed all pages
    }
}
```

In your controll

```php
use App\Http\Controllers\Controller;
use App\Pdf\MyPdf;

class MyController extends Controller
{
    public function index()
    {
        $data = MyModel::all();

        $myPdf = new MyPdf($data);

        $myPdf->Output('I', "MyPdf.pdf", true);

        exit;
    }
}
```

<br>

# FPDF official docs

[http://www.fpdf.org](http://www.fpdf.org)
