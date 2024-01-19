@extends('layouts.app')

@section('title', getSetting('APPLICATION_NAME') . ' | ' . $page)

@section('style')
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-lg-3 col-sm-5 col-md-4 col-xl-3 col-12 mb-3 center-div">
                <div class="col-12 col-xl-12 col-md-12 col-sm-12 d-flex align-items-center justify-content-between"
                    style="padding: 0; gap:10px;">
                    <button class="w-100 btn btn-primary shadow-sm meet-create" data-toggle="modal"
                        data-target="#createMeeting" title="{{ __('Create Meeting') }}"><i class="fa fa-plus-circle mr-1"
                            aria-hidden="true"></i>
                        {{ __('Create') }} <span class="title-hide">{{ __('Meeting') }}</span></button>
                    <button class="btn btn-primary shadow-sm w-100 pl-0 pr-0" id="instantMeeting"
                        title="{{ __('Instant Meeting') }}"><i class="fa fa-paper-plane mr-1" aria-hidden="true"></i>
                        {{ __('Instant') }} <span class="title-hide">{{ __('Meeting') }}</span></button>
                </div>
            </div>
            <div class="col-lg-9 col-sm-7 col-md-8 col-xl-9 col-12 p-0">
                <form id="meetingDashboard">
                    <div class="input-group mb-3 col-sm-10 col-md-7 col-lg-4 col-xl-3 col-12 float-right">
                        <input type="text" id="conferenceId" class="form-control" name="id"
                            placeholder="{{ __('Enter Meeting ID') }}" maxlength="9" required />
                        <div class="input-group-append">
                            <button id="join" type="submit" class="btn btn-primary">{{ __('Join') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3 col-xl-3 col-md-4 col-sm-5 col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">{{ __('My Meetings') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="search-meeting mb-3" @if (!$firstMeeting) hidden @endif>
                            <input id="search" type="text" class="form-control"
                                placeholder="{{ __('Search meetings') }}" autocomplete="off" maxlength="50" />
                        </div>
                        <ul class="list-group meeting-list pr-1">
                            <span id="emptyMeeting"
                                @if ($firstMeeting) hidden @endif>{{ __('Your meetings will appear here') }}</span>

                            @if ($firstMeeting)
                                @foreach ($meetings as $key => $value)
                                    <div class="card w-100 mb-2 mt-1 pr-4 meeting-card"
                                        data-description="{{ $value->description }}" data-id="{{ $value->id }}"
                                        data-auto="{{ $value->meeting_id }}" data-password="{{ $value->password }}"
                                        data-date="{{ formatDate($value->date) }}"
                                        data-time="{{ formatTime($value->time) }}" data-timezone="{{ $value->timezone }}">
                                        <div class="card-body">
                                            <h5 class="card-title meeting-title font-weight-bold mb-3">
                                                {{ $value->title }}
                                            </h5>
                                            <p class="card-text meeting-description">
                                                {{ $value && $value->description ? (strlen($value->description) > 40 ? substr($value->description, 0, 40) . '...' : $value->description) : '-' }}
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-9 col-xl-9 col-md-8 col-sm-7">
                <div id="meetingDetail" class="card w-100" @if (!$firstMeeting) hidden @endif>
                    <div class="card-header">
                        <h5 class="mb-0" id="meetingTitleDetail">
                            {{ $firstMeeting ? $firstMeeting->title : '' }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <p id="meetingDescriptionDetail" class="card-text">
                            {{ $firstMeeting && $firstMeeting->description ? $firstMeeting->description : '-' }}</p>
                        <p class="card-text">
                            <span class="card-text-label">{{ __('Meeting ID:') }}</span> <span id="meetingIdDetail"
                                class="font-weight-bold">{{ $firstMeeting ? $firstMeeting->meeting_id : '' }}</span>
                        </p>
                        <p class="card-text">
                            <span class="card-text-label">{{ __('Password:') }}<span> <span id="meetingPasswordDetail"
                                        class="font-weight-bold">{{ $firstMeeting && $firstMeeting->password ? $firstMeeting->password : '-' }}</span>
                        </p>
                        <p class="card-text">
                            <span class="card-text-label">{{ __('Date:') }}<span> <span id="meetingDateDetail"
                                        class="font-weight-bold">{{ $firstMeeting && $firstMeeting->date ? formatDate($firstMeeting->date) : '-' }}</span>
                        </p>
                        <p class="card-text">
                            <span class="card-text-label">{{ __('Time:') }}<span> <span id="meetingTimeDetail"
                                        class="font-weight-bold">{{ $firstMeeting && $firstMeeting->time ? formatTime($firstMeeting->time) : '-' }}</span>
                        </p>
                        <p class="card-text">
                            <span class="card-text-label">{{ __('Timezone:') }}<span> <span id="meetingTimezoneDetail"
                                        class="font-weight-bold">{{ $firstMeeting && $firstMeeting->timezone ? $firstMeeting->timezone : '-' }}</span>
                        </p>
                    </div>
                    <div class="card-body">
                        <a href="{{ $firstMeeting ? 'meeting/' . $firstMeeting->meeting_id : '' }}" class="card-link"
                            id="meetingStart">{{ __('Start') }}</a>
                        <a href="#" id="invite" class="card-link"
                            data-id="{{ $firstMeeting ? $firstMeeting->id : '' }}">{{ __('Invite People') }}</a>

                        <a href="#" id="edit" class="card-link"
                            data-id="{{ $firstMeeting ? $firstMeeting->id : '' }}">{{ __('Edit') }}</a>

                        <a href="#" id="delete" class="card-link"
                            data-id="{{ $firstMeeting ? $firstMeeting->id : '' }}">{{ __('Delete') }}</a>

                        <a href="#" id="copy" class="card-link"
                            data-id="{{ $firstMeeting ? $firstMeeting->id : '' }}">{{ __('Copy Link') }}</a>

                        <a class="card-link dropdown-toggle" role="button" data-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false">
                            {{ __('Add to Calendar') }}
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" id="addToGoogle"><img alt="{{ __('Google Calendar') }}"
                                    src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciICB2aWV3Qm94PSIwIDAgNDggNDgiIHdpZHRoPSI0OHB4IiBoZWlnaHQ9IjQ4cHgiPjxyZWN0IHdpZHRoPSIyMiIgaGVpZ2h0PSIyMiIgeD0iMTMiIHk9IjEzIiBmaWxsPSIjZmZmIi8+PHBvbHlnb24gZmlsbD0iIzFlODhlNSIgcG9pbnRzPSIyNS42OCwyMC45MiAyNi42ODgsMjIuMzYgMjguMjcyLDIxLjIwOCAyOC4yNzIsMjkuNTYgMzAsMjkuNTYgMzAsMTguNjE2IDI4LjU2LDE4LjYxNiIvPjxwYXRoIGZpbGw9IiMxZTg4ZTUiIGQ9Ik0yMi45NDMsMjMuNzQ1YzAuNjI1LTAuNTc0LDEuMDEzLTEuMzcsMS4wMTMtMi4yNDljMC0xLjc0Ny0xLjUzMy0zLjE2OC0zLjQxNy0zLjE2OCBjLTEuNjAyLDAtMi45NzIsMS4wMDktMy4zMywyLjQ1M2wxLjY1NywwLjQyMWMwLjE2NS0wLjY2NCwwLjg2OC0xLjE0NiwxLjY3My0xLjE0NmMwLjk0MiwwLDEuNzA5LDAuNjQ2LDEuNzA5LDEuNDQgYzAsMC43OTQtMC43NjcsMS40NC0xLjcwOSwxLjQ0aC0wLjk5N3YxLjcyOGgwLjk5N2MxLjA4MSwwLDEuOTkzLDAuNzUxLDEuOTkzLDEuNjRjMCwwLjkwNC0wLjg2NiwxLjY0LTEuOTMxLDEuNjQgYy0wLjk2MiwwLTEuNzg0LTAuNjEtMS45MTQtMS40MThMMTcsMjYuODAyYzAuMjYyLDEuNjM2LDEuODEsMi44NywzLjYsMi44N2MyLjAwNywwLDMuNjQtMS41MTEsMy42NC0zLjM2OCBDMjQuMjQsMjUuMjgxLDIzLjczNiwyNC4zNjMsMjIuOTQzLDIzLjc0NXoiLz48cG9seWdvbiBmaWxsPSIjZmJjMDJkIiBwb2ludHM9IjM0LDQyIDE0LDQyIDEzLDM4IDE0LDM0IDM0LDM0IDM1LDM4Ii8+PHBvbHlnb24gZmlsbD0iIzRjYWY1MCIgcG9pbnRzPSIzOCwzNSA0MiwzNCA0MiwxNCAzOCwxMyAzNCwxNCAzNCwzNCIvPjxwYXRoIGZpbGw9IiMxZTg4ZTUiIGQ9Ik0zNCwxNGwxLTRsLTEtNEg5QzcuMzQzLDYsNiw3LjM0Myw2LDl2MjVsNCwxbDQtMVYxNEgzNHoiLz48cG9seWdvbiBmaWxsPSIjZTUzOTM1IiBwb2ludHM9IjM0LDM0IDM0LDQyIDQyLDM0Ii8+PHBhdGggZmlsbD0iIzE1NjVjMCIgZD0iTTM5LDZoLTV2OGg4VjlDNDIsNy4zNDMsNDAuNjU3LDYsMzksNnoiLz48cGF0aCBmaWxsPSIjMTU2NWMwIiBkPSJNOSw0Mmg1di04SDZ2NUM2LDQwLjY1Nyw3LjM0Myw0Miw5LDQyeiIvPjwvc3ZnPg=="
                                    width="20">
                                {{ __('Google Calendar') }}</a>
                            <a class="dropdown-item" id="addToOutlook"> <img alt="{{ __('Microsoft Outlook') }}"
                                    src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciICB2aWV3Qm94PSIwIDAgNDggNDgiIHdpZHRoPSI0OHB4IiBoZWlnaHQ9IjQ4cHgiPjxwYXRoIGZpbGw9IiMwM0E5RjQiIGQ9Ik0yMSwzMWMwLDEuMTA0LDAuODk2LDIsMiwyaDE3YzEuMTA0LDAsMi0wLjg5NiwyLTJWMTZjMC0xLjEwNC0wLjg5Ni0yLTItMkgyM2MtMS4xMDQsMC0yLDAuODk2LTIsMlYzMXoiLz48cGF0aCBmaWxsPSIjQjNFNUZDIiBkPSJNNDIsMTYuOTc1VjE2YzAtMC40MjgtMC4xMzctMC44MjMtMC4zNjctMS4xNDhsLTExLjI2NCw2LjkzMmwtNy41NDItNC42NTZMMjIuMTI1LDE5bDguNDU5LDVMNDIsMTYuOTc1eiIvPjxwYXRoIGZpbGw9IiMwMjc3QkQiIGQ9Ik0yNyA0MS40Nkw2IDM3LjQ2IDYgOS40NiAyNyA1LjQ2eiIvPjxwYXRoIGZpbGw9IiNGRkYiIGQ9Ik0yMS4yMTYsMTguMzExYy0xLjA5OC0xLjI3NS0yLjU0Ni0xLjkxMy00LjMyOC0xLjkxM2MtMS44OTIsMC0zLjQwOCwwLjY2OS00LjU1NCwyLjAwM2MtMS4xNDQsMS4zMzctMS43MTksMy4wODgtMS43MTksNS4yNDZjMCwyLjA0NSwwLjU2NCwzLjcxNCwxLjY5LDQuOTg2YzEuMTI2LDEuMjczLDIuNTkyLDEuOTEsNC4zNzgsMS45MWMxLjg0LDAsMy4zMzEtMC42NTIsNC40NzQtMS45NzVjMS4xNDMtMS4zMTMsMS43MTItMy4wNDMsMS43MTItNS4xOTlDMjIuODY5LDIxLjI4MSwyMi4zMTgsMTkuNTk1LDIxLjIxNiwxOC4zMTF6IE0xOS4wNDksMjYuNzM1Yy0wLjU2OCwwLjc2OS0xLjMzOSwxLjE1Mi0yLjMxMywxLjE1MmMtMC45MzksMC0xLjY5OS0wLjM5NC0yLjI4NS0xLjE4N2MtMC41ODEtMC43ODUtMC44Ny0xLjg2MS0wLjg3LTMuMjExYzAtMS4zMzYsMC4yODktMi40MTQsMC44Ny0zLjIyNWMwLjU4Ni0wLjgxLDEuMzY4LTEuMjExLDIuMzU1LTEuMjExYzAuOTYyLDAsMS43MTgsMC4zOTMsMi4yNjcsMS4xNzhjMC41NTUsMC43OTUsMC44MzMsMS44OTUsMC44MzMsMy4zMUMxOS45MDcsMjQuOTA2LDE5LjYxOCwyNS45NjgsMTkuMDQ5LDI2LjczNXoiLz48L3N2Zz4="
                                    width="20">
                                {{ __('Microsoft Outlook') }}</a>
                        </div>
                    </div>
                </div>
                <div id="emptyDetails" class="w-100 text-center" @if ($firstMeeting) hidden @endif>
                    <img src="{{ asset('images/list.png') }}" width="100" alt="list">
                    <p>{{ __('Meeting details will appear here') }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="createMeeting" tabindex="-1" role="dialog" aria-labelledby="createMeetingLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createMeetingLabel">{{ __('Create Meeting | ID') }} <span
                            id="meetingId"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="col-lg-12">
                        <form id="meetingsForm">
                            <div class="form-group row">
                                <label for="title" class="col-md-4 col-lg-3">{{ __('Title*') }}</label>

                                <div class="col-md-8 col-lg-9">
                                    <input id="title" type="text" class="form-control" name="title"
                                        placeholder="{{ __('Enter meeting title') }}" maxlength="100" required />
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="description" class="col-md-4  col-lg-3">{{ __('Description') }}</label>

                                <div class="col-md-8 col-lg-9">
                                    <textarea id="description" class="form-control" name="description"
                                        placeholder="{{ __('Enter meeting description') }}" maxlength="1000"></textarea>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-lg-3">{{ __('Password') }}</label>

                                <div class="col-md-8 col-lg-9">
                                    <input id="password" type="text" class="form-control" name="password"
                                        placeholder="{{ __('Enter meeting password') }}" maxlength="8" />
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="date" class="col-md-4 col-lg-3">{{ __('Date') }}</label>

                                <div class="col-md-8 col-lg-9">
                                    <input id="date" type="date" class="form-control" name="date"
                                        placeholder="{{ __('Enter meeting date') }}" min="{{ date('d-m-Y') }}" />
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="time" class="col-md-4 col-lg-3">{{ __('Time') }}</label>

                                <div class="col-md-8 col-lg-9">
                                    <input id="time" type="time" class="form-control" name="time"
                                        placeholder="{{ __('Enter meeting time') }}" />
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="timezone" class="col-md-4 col-lg-3">{{ __('Timezone') }}</label>

                                <div class="col-md-8 col-lg-9">
                                    <select class="form-control" id="timezone" name="timezone">
                                        <option value="">{{ __('Select meeting timezone') }}</option>
                                        @foreach ($timezones as $timezone)
                                            <option value="{{ $timezone['value'] }}">{{ $timezone['value'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <input type="hidden" id="meetingsFormId" name="meeting_id" />

                            <hr />

                            <div class="text-right">
                                <button type="button" class="btn btn-secondary"
                                    data-dismiss="modal">{{ __('Cancel') }}</button>
                                <button type="submit" class="btn btn-primary"
                                    id="save">{{ __('Save') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editMeeting" tabindex="-1" role="dialog" aria-labelledby="editMeetingLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editMeetingLabel">{{ __('Edit Meeting | ID:') }} <span
                            id="meetingIdEdit"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="col-lg-12">
                        <form id="meetingsFormEdit">
                            <div class="form-group row">
                                <label for="titleEdit" class="col-md-4 col-lg-3 ">{{ __('Title*') }}</label>

                                <div class="col-md-8  col-lg-9">
                                    <input id="titleEdit" type="text" class="form-control" name="title"
                                        placeholder="{{ __('Enter meeting title') }}" maxlength="100" required />
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="descriptionEdit" class="col-md-4 col-lg-3 ">{{ __('Description') }}</label>

                                <div class="col-md-8  col-lg-9">
                                    <textarea id="descriptionEdit" class="form-control" name="description"
                                        placeholder="{{ __('Enter meeting description') }}" maxlength="1000"></textarea>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="passwordEdit" class="col-md-4 col-lg-3 ">{{ __('Password') }}</label>

                                <div class="col-md-8  col-lg-9">
                                    <input id="passwordEdit" type="text" class="form-control" name="password"
                                        placeholder="{{ __('Enter meeting password') }}" maxlength="8" />
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="dateEdit" class="col-md-4 col-lg-3">{{ __('Date') }}</label>

                                <div class="col-md-8 col-lg-9">
                                    <input id="dateEdit" type="date" class="form-control" name="date"
                                        placeholder="{{ __('Enter meeting date') }}" min="{{ date('d-m-Y') }}" />
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="timeEdit" class="col-md-4 col-lg-3">{{ __('Time') }}</label>

                                <div class="col-md-8 col-lg-9">
                                    <input id="timeEdit" type="time" class="form-control" name="time"
                                        placeholder="{{ __('Enter meeting time') }}" />
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="timezoneEdit" class="col-md-4 col-lg-3">{{ __('Timezone') }}</label>

                                <div class="col-md-8 col-lg-9">
                                    <select class="form-control" id="timezoneEdit" name="timezone">
                                        <option value="">{{ __('Select meeting timezone') }}</option>
                                        @foreach ($timezones as $timezone)
                                            <option value="{{ $timezone['value'] }}">{{ $timezone['value'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <input type="hidden" id="meetingsFormIdEdit" name="id" />

                            <hr />

                            <div class="text-right">
                                <button type="button" class="btn btn-secondary"
                                    data-dismiss="modal">{{ __('Cancel') }}</button>
                                <button type="submit" class="btn btn-primary"
                                    id="saveEdit">{{ __('Save') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="showInvites" tabindex="-1" role="dialog" aria-labelledby="showInvitesLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="showInvitesLabel">{{ __('Invite People') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="col-lg-12">
                        <form id="inviteForm">
                            <div class="form-group row">
                                <label for="passwordEdit" class="col-lg-3 col-md-3">{{ __('Email*') }}</label>
                                <div class="col-lg-6 col-md-6">
                                    <select class="form-control form-select" name="email[]" id="inviteEmail" multiple
                                        style="width:200px;">
                                        @forelse($contacts as $contact)
                                            <option value="{{ $contact->email }}">{{ $contact->email }}</option>
                                        @empty
                                        @endforelse
                                    </select>
                                </div>
                                <div class="col-lg-3 col-md-3">
                                    <button type="submit" class="btn btn-primary">{{ __('Invite') }}</button>
                                </div>
                            </div>
                            <input type="hidden" id="inviteId" name="id" />
                        </form>
                        <div class="row">
                            <div class="col-12">
                                <ul class="list-group list-group-flush invite-list"></ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script type="text/javascript">
        let meetingId;
        let timeLimit = "{{ $timeLimit }}";
        let errorExist = "{{ $errors->any() }}";
        let meetingExist = "{{ !$meetings->isEmpty() }}" || null;

        if (meetingExist) {
            $('.meeting-card:first').addClass('active-meeting');
            meetingId = "{{ $firstMeeting ? $firstMeeting->id : '' }}";
        }

        if (errorExist) {
            showError("{{ $errors->first() }}");
        }
    </script>
    <script src="{{ asset('js/select2.min.js') }}"></script>
    <script src="{{ asset('js/dashboard.js') }}"></script>
@endsection
