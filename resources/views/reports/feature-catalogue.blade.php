@php use App\Commands\Support\FeatureCatalogueStatusReport; @endphp
@php use App\Commands\Support\FeatureCatalogueStatus; @endphp
@extends('layouts.report')

@section('title', 'Feature catalogue coverage')

@section('content')
    <h1>Feature Catalogue</h1>

    <table class="table table-sm">
        <thead>
        <tr>
            <th>Class</th>
            <th>Status</th>
            <th>Superclass(es)</th>
        </tr>
        </thead>
        <tbody>
        @foreach($reports as /** @var FeatureCatalogueStatusReport $report */ $report)
            <tr class="table-{{
    match ($report->status) {
        FeatureCatalogueStatus::IS_ABSTRACT => 'secondary',
        FeatureCatalogueStatus::IMPLEMENTED => 'success',
        FeatureCatalogueStatus::NOT_IMPLEMENTED => 'danger',
        FeatureCatalogueStatus::SUPERCLASS_IMPLEMENTED => 'primary',
    }
    }}">
                <th>
                    <x-parser-class-link :class="$report->class"/>
                </th>
                <td>{{ $report->status->value }}</td>
                <td>
                    @if($report->status === FeatureCatalogueStatus::SUPERCLASS_IMPLEMENTED)
                        <ul class="m-0 p-0">
                            @foreach($report->superclasses as $superclass)
                                <li>
                                    <x-parser-class-link :class="$superclass"/>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection