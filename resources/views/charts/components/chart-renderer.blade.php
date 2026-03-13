@switch($data['type'])
    @case('line')
        @include('charts.components.line-chart', ['data' => $data])
        @break

    @case('doughnut')
        @include('charts.components.doughnut-chart', ['data' => $data])
        @break

    @case('bar')
        @include('charts.components.bar-chart', ['data' => $data])
        @break

    @case('radar')
        @include('charts.components.radar-chart', ['data' => $data])
        @break

    @default
        @include('charts.components.line-chart', ['data' => $data])
@endswitch
