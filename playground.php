<?php

declare(strict_types=1);

use Scheel\Inquirer\Prompt\Confirm;
use Scheel\Inquirer\Prompt\Date;
use Scheel\Inquirer\Prompt\Editor;
use Scheel\Inquirer\Prompt\MultiSelect;
use Scheel\Inquirer\Prompt\Password;
use Scheel\Inquirer\Prompt\Select;
use Scheel\Inquirer\Prompt\Text;

require __DIR__.'/vendor/autoload.php';

$name = Text::make()->prompt('Name')->placeholder('John Doe')->run();
$password = Password::make()->prompt('Password')->confirm()->run();
$birthday = Date::make()->prompt('Birthday')->maxDate(date('Y-m-d'))->run();
$whitespace = Select::make()->prompt('Indentation')->options(['Tabs', 'Spaces'])->run();
$hobbies = MultiSelect::make()->options(['Programming', 'Music', 'Cooking'])->initial(['Programming'])->run();
$bio = Editor::make()->prompt('Your Bio')->hint('Close editor when finished')->run();
$confirm = Confirm::make()->prompt('Proceed')->default(true)->run();

echo json_encode(compact('name', 'password', 'birthday', 'whitespace', 'hobbies', 'bio', 'confirm'), JSON_PRETTY_PRINT);
