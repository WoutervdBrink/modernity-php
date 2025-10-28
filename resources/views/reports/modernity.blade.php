@php use App\Language\PhpVersionVector; @endphp
@php use App\Language\PhpVersionConstraint; @endphp
@extends('layouts.report')

@php
    /** @var string $path */
    /** @var string $code */
    /** @var PhpVersionConstraint $constraint */
@endphp

@section('title', 'Modernity analysis: ' . $path)

@section('content')
    <h1>Modernity analysis</h1>

    <h2>Information</h2>
    <dl class="row">
        <dt class="col-3">
            File name:
        </dt>
        <dd class="col-9">
            <code>{{ $path }}</code>
        </dd>

        <dt class="col-3">
            Minimum version:
        </dt>
        <dd class="col-9">
            @if($constraint->min)
                PHP {{ $constraint->min->toVersionString() }}
            @else
                <em>Any</em>
            @endif
        </dd>

        <dt class="col-3">
            Maximum version:
        </dt>
        <dd class="col-9">
            @if($constraint->max)
                PHP {{ $constraint->max->toVersionString() }}
            @else
                <em>Any</em>
            @endif
        </dd>
    </dl>

    <h2>Modernity vector</h2>

    @php $max = $vector->max(); @endphp

    <table class="table" style="table-layout: fixed">
        <thead>
        <tr>
            <th>Version</th>
            <th colspan="2">Value</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($vector as $version => $value)
            @php
                if ($max === 0) {
                    $percent = 0;
                } else {
                    $percent = ($value / $max) * 100;
                }
            @endphp
            <tr>
                <th>{{ $version->toVersionString() }}</th>
                <td style="font-variant-numeric: tabular-nums">{{ $value }}</td>
                <td>
                    <div class="progress" role="progressbar" aria-valuenow="{{ $percent }}"
                         aria-valuemin="0" aria-valuemax="100">
                        <div class="progress-bar" style="width: {{ $percent }}%"></div>
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <h2>File contents</h2>

    <div class="bg-light border rounded p-3">
        {!! highlight_string($code, true) !!}
    </div>
@endsection