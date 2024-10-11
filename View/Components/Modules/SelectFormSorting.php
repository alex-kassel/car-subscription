<?php

declare(strict_types=1);

namespace CarSubscription\View\Components\Modules;

use CarSubscription\DomainService;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SelectFormSorting extends Component
{
    public array $parameters;
    public string $title;
    public array $options;
    public object $button;
    public object $button2;

    public function __construct(
        protected DomainService $service,
    ) {
        $this->parameters = $this->service->parameters();
        $this->title = __('Sortiere nach');
        $this->options = $this->getOptions();
        $this->button = $this->getButton();
        $this->button2 = $this->getButton2();
    }

    protected function getOptions(): array
    {
        $optionParameters = $this->parameters;
        $sortExploded = explode('-', $this->parameters['sort']);

        return $options = [
            (object) [
                'value' => (function () use (&$optionParameters, $sortExploded) {
                    $sortExploded[2] = 'cost';
                    $sortExploded[4] = 'month';
                    $optionParameters['sort'] = implode('-', $sortExploded);
                    return '?' . http_build_query($optionParameters);
                })(),
                'text' => 'Kosten pro Monat',
                'selected' => $optionParameters['sort'] == $this->parameters['sort'],
            ],
            (object) [
                'value' => (function () use (&$optionParameters, $sortExploded) {
                    $sortExploded[2] = 'cost';
                    $sortExploded[4] = 'kilometer';
                    $optionParameters['sort'] = implode('-', $sortExploded);
                    return '?' . http_build_query($optionParameters);
                })(),
                'text' => 'Kosten pro km',
                'selected' => $optionParameters['sort'] == $this->parameters['sort'],
            ],
            (object) [
                'value' => (function () use (&$optionParameters, $sortExploded) {
                    $sortExploded[2] = 'factor';
                    $sortExploded[4] = 'month';
                    $optionParameters['sort'] = implode('-', $sortExploded);
                    return '?' . http_build_query($optionParameters);
                })(),
                'text' => 'Abo-Faktor pro Monat',
                'selected' => $optionParameters['sort'] == $this->parameters['sort'],
            ],
            (object) [
                'value' => (function () use (&$optionParameters, $sortExploded) {
                    $sortExploded[2] = 'factor';
                    $sortExploded[4] = 'kilometer';
                    $optionParameters['sort'] = implode('-', $sortExploded);
                    return '?' . http_build_query($optionParameters);
                })(),
                'text' => 'Abo-Faktor pro km',
                'selected' => $optionParameters['sort'] == $this->parameters['sort'],
            ],
        ];
    }
    protected function getOptions2(): object
    {
        $optionParameters = $this->parameters;

        return (object) collect($this->service->options('sorting'))
            ->map(function ($group) use ($optionParameters)  {
                $group['data'] = collect($group['data'])
                    ->map(function ($optionValue, $optionKey) use ($optionParameters) {
                        if ($optionKey[0] != $this->parameters['sort'][0]) {
                            return null;
                        }

                        $optionParameters['sort'] = $optionKey;

                        return (object) [
                            'value' => '?' . http_build_query($optionParameters),
                            'text' => str_contains($optionKey, 'user')
                                ? sprintf($optionValue, number_format($this->parameters['km'], 0, ',', '.' ))
                                : $optionValue
                            ,
                            'selected' => $optionParameters['sort'] == $this->parameters['sort'],
                        ];
                    })->filter()->values()->all();

                return (object) $group;
            })->all();
    }

    protected function getButton(): object
    {
        $parameters = $this->parameters;

        if (str_contains($parameters['sort'], 'total')) {
            $parameters['sort'] = str_replace('total', 'base', $parameters['sort']);

            return (object) [
                'class' => 'btn-outline-primary',
                'url' => '?' . http_build_query($parameters),
                'icon' => 'bi bi-fuel-pump-fill',
            ];
        }

        $parameters['sort'] = str_replace('base', 'total', $parameters['sort']);

        return (object) [
            'class' => 'btn-outline-secondary',
            'url' => '?' . http_build_query($parameters),
            'icon' => 'bi bi-fuel-pump',
        ];
    }

    protected function getButton2(): object
    {
        $parameters = $this->parameters;

        if (str_ends_with($parameters['sort'], 'per-hp')) {
            $parameters['sort'] = str_replace('-per-hp', '', $parameters['sort']);

            return (object) [
                'class' => 'btn-outline-primary',
                'url' => '?' . http_build_query($parameters),
                'icon' => 'bi bi-speedometer2',
            ];
        }

        $parameters['sort'] .= '-per-hp';

        return (object) [
            'class' => 'btn-outline-secondary',
            'url' => '?' . http_build_query($parameters),
            'icon' => 'bi bi-speedometer2',
        ];
    }

    public function render(): View
    {
        return view('cars::components.modules.select-form-sorting');
    }
}
