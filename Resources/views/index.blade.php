<x-cars::debug from="index start" />

@extends('cars::layouts.app')

@section('main')
    <x-cars::modules.page-title />

    <section class="section">
        <x-cars::debug from="vor controls" />
        <x-cars::modules.controls />
        <x-cars::debug from="nach controls" />

        <x-cars::debug from="vor vehicles" />
        <x-cars::modules.vehicles limit="25" />
        <x-cars::debug from="nach vehicles" />
    </section>
@endsection

<x-cars::debug from="index end" />
