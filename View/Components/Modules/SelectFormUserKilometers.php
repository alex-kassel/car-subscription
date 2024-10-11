<?php

declare(strict_types=1);

namespace CarSubscription\View\Components\Modules;

use CarSubscription\DomainService;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SelectFormUserKilometers extends Component
{
    public array $parameters;
    public string $title;
    public array $options;
    public object$button;

    public function __construct(
        protected DomainService $service,
    ) {
        $this->parameters = $this->service->parameters();
        $this->title = __('Deine Monatskilometer');
        $this->options = $this->getOptions();
        $this->button = $this->getButton();
    }

    protected function getOptions(): array
    {
        $optionParameters = $this->parameters;

        return array_map(function ($option) use ($optionParameters) {
            $optionParameters['km'] = $option;

            return (object) [
                'value' => '?' . http_build_query($optionParameters),
                'text' => number_format($option, 0, ',', '.'),
                'selected' => $optionParameters['km'] == $this->parameters['km'],
            ];
        }, $this->service->options('user_kilometers'));
    }

    protected function getButton(): object
    {
        $parameters = $this->parameters;

        if (str_contains($parameters['sort'], 'user')) {
            $parameters['sort'] = str_replace('user', 'default', $parameters['sort']);

            return (object) [
                'class' => 'btn-outline-primary',
                'url' => '?' . http_build_query($parameters),
                'icon' => 'bi bi-toggle-on',
                'selectDisabled' => false,
            ];
        }

        $parameters['sort'] = str_replace('default', 'user', $parameters['sort']);

        return (object) [
            'class' => 'btn-outline-secondary',
            'url' => '?' . http_build_query($parameters),
            'icon' => 'bi bi-toggle-off',
            'selectDisabled' => true,
        ];
    }

    public function render(): View
    {
        return view('cars::components.modules.select-form-user-kilometers');
    }
}
