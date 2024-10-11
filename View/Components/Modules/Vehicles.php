<?php

declare(strict_types=1);

namespace CarSubscription\View\Components\Modules;

use CarSubscription\DomainService;
use CarSubscription\Repositories\VehicleRepository;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class Vehicles extends Component
{
    public array $parameters;
    public Collection $vehicles;

    public function __construct(
        protected DomainService $service,
        protected VehicleRepository $repository,
        protected int $limit,
    ) {
        $this->parameters = $this->service->parameters();
        $this->vehicles = $this->repository->getVehicles($this->parameters, $limit);
    }

    public function render(): View
    {
        return view('cars::components.modules.vehicles');
    }
}
