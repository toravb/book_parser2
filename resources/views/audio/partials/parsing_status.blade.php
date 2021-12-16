@if($status->last_parsing != null)

    <span class="sr-only">
                                    {{$status->last_parsing}} ({{$status->min_count}}/{{$status->max_count}})
                            </span>
@else
    @if($status->paused == true)
        <span class="badge badge-success">
                                    Пауза
                                </span>
    @endif
    <span>
                            {{$status->status}}
                            </span>
    <br>
    @if($status->max_count > 0)
        <span>{{$status->min_count}}/{{$status->max_count}}</span>
    <div class="progress">
        <div class="progress-bar bg-info" role="progressbar" aria-valuenow="{{$status->min_count}}"
             aria-valuemin="0" aria-valuemax="{{$status->max_count}}"
             style="width: {{($status->min_count/$status->max_count) * 100}}%">
        </div>
    </div>
    @endif
@endif
