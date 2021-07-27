@extends('layouts.main')

@section('styles')
  <style>
    .bnd-matches-list-container{
      padding: 20px;
    }

    .table-header{
      font-size: 18px;
      font-weight: bold;
    }

    .match-ongoing{
      color: green;
    }

    .match-status {
      text-transform: uppercase;
      font-weight: 500;
    }

    .match-name a{
      color: #414141 !important;
      font-size: 16px;
    }

    .view-settings-btn{
      margin-right: 10px;
    }
  </style>
@endsection

@section('content')
<div class="bnd-matches-list-container">
  <table class="table table-striped">
    <thead class="table-header">
      <td>ID</td>
      <td>Name</td>
      <td>League</td>
      <td>Status</td>
      <td colspan="">&nbsp;</td>
    </thead>
    @foreach($matches as $key => $match)
      <tr>
        <td><a href="/match/{{ $match->id }}" target="_blank">{{ $match->id }}</a></td>
        <td class="match-name"><a href="/match/{{ $match->id }}" target="_blank">{{ $match->name }}</a></td>
        <td class="match-name">{{ $match->league->name }}</td>
        <td class="match-status match-{{ $match->status }}">{{ $match->status }}</td>

        <td>
          <a href="/bnd/{{ $match->id }}" target="_blank" class="btn btn-info btn-xs view-settings-btn">MW / Handicap / Game 1 SETTINGS</a>

          @foreach ($match->subMatches->where('sub_type','main') as $sub)
            @if($sub->game_grp != 1)
              <a href="/bnd/{{ $sub->id }}" target="_blank" class="btn btn-info btn-xs view-settings-btn">{{ $sub->name }}</a>
            @endif
          @endforeach
        </td>
      </tr>
    @endforeach
  </table>
</div>
@endsection