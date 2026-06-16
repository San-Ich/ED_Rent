<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class WelcomeWidget extends Widget
{
    protected int | string | array $columnSpan = 'full';

    protected string $view = 'filament-widget';

    protected static ?int $sort = -2;
}
