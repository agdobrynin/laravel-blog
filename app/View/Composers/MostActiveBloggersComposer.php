<?php
declare(strict_types=1);

namespace App\View\Composers;

use App\Dto\MostActiveBloggerDto;
use App\Services\Contracts\MostActiveBloggersInterface;
use Illuminate\View\View;

class MostActiveBloggersComposer
{
    public function __construct(protected MostActiveBloggersInterface $mostActiveBloggers)
    {
    }

    public function compose(View $view): void
    {
        $mostActiveBloggerDto = new MostActiveBloggerDto(
            bloggers: $this->mostActiveBloggers->get(),
            lastMonth: $this->mostActiveBloggers->getLastMonth(),
            minCountPost: $this->mostActiveBloggers->getMinCountPost(),
        );

        $view->with('mostActiveBloggerDto', $mostActiveBloggerDto);
    }
}
