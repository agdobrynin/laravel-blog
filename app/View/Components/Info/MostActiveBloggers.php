<?php
declare(strict_types=1);

namespace App\View\Components\Info;

use App\Dto\MostActiveBloggerDto;
use Illuminate\View\Component;
use Illuminate\View\View;

class MostActiveBloggers extends Component
{
    public function __construct(public readonly MostActiveBloggerDto $mostActiveBloggerDto)
    {
    }

    public function render(): View
    {
        return view('components.info.most-active-bloggers');
    }
}
