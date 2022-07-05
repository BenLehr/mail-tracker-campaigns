@extends(config('mail-tracker.admin-template.name'))
@section(config('mail-tracker.admin-template.section'))
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <h1>Mail Tracker</h1>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <table class="table table-striped">
                    <tr>
                        <th>{{ __('mail-tracker.index.id') }}</th>
                        <th>{{ __('mail-tracker.index.date') }}</th>
                        <th>{{ __('mail-tracker.index.name') }}</th>
                        <th>{{ __('mail-tracker.index.receivers') }}</th>
                        <th>{{ __('mail-tracker.index.openings') }}</th>
                        <th>{{ __('mail-tracker.index.opening-rate') }}</th>
                        <th>&nbsp;</th>
                    </tr>
                    @foreach($campaigns as $campaign)
                        <tr class="">
                            <td>{{$campaign->id}}</td>
                            <td>{{$campaign->date}}</td>
                            <td>{{$campaign->name}}</td>
                            <td>{{$campaign->emailsSend()}}</td>
                            <td>{{$campaign->emailsOpened()}}</td>
                            <td>{{$campaign->openingRate()}}</td>
                            <td><a href="{{ route('mailTracker_Detail', $campaign->id) }}">Details</a></td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
@endsection
