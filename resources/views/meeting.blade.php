@extends('layouts.app')

@section('title', getSetting('APPLICATION_NAME') . ' | ' . $page . ' | ' . $meeting->title)

@section('style')
    <link href="{{ asset('css/meeting.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="container meeting-details">
        <canvas id="audioOnly" hidden></canvas>
        <div class="row h-100 justify-content-center align-items-center">
            <div class="col-lg-7 video-detail">
                <div class="video-Section">
                    <video id="previewVideo" class="cam" autoplay playsinline muted></video>
                    <div class="cameraText">{{ __('Camera is off') }}</div>
                    <div class="video-controls">
                        <ul>
                            <li id="toggleAudioPreview" class="disabled" data-toggle="tooltip" data-placement="top"
                                title="{{ __('Toggle audio') }}">
                                <em class="fa fa-microphone-slash"></em>
                            </li>
                            <li id="toggleVideoPreview" class="disabled" data-toggle="tooltip" data-placement="top"
                                title="{{ __('Toggle video') }}">
                                <em class="fa fa-video-slash"></em>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="text-show" style="color: red;"></div>
            </div>
            <div class="col-lg-5">
                <div class="card mb-0">
                    <div class="card-header">
                        <h5>{{ $meeting->title }}</h5>
                    </div>
                    <div class="card-body">
                        @if ($meeting->timeLimit == -1)
                            <div class="ribbon-wrapper ribbon-xl">
                                <div class="ribbon bg-primary" title="{{ __('Time Limit') }}">
                                    {{ __('Unlimited') . ' ' . __('Minutes') }}
                                </div>
                            </div>
                        @else
                            <div class="ribbon-wrapper ribbon-lg">
                                <div class="ribbon bg-primary" title="{{ __('Time Limit') }}">
                                    {{ $meeting->timeLimit . ' ' . __('Minutes') }}
                                </div>
                            </div>
                        @endif
                        <form id="passwordCheck">
                            <div class="form-group">
                                <h6><i class="fa fa-id-badge mr-1"></i> {{ $meeting->meeting_id }}</h6>
                            </div>
                            @if (getSetting('AUTH_MODE') == 'enabled')
                                <div class="form-group">
                                    <h6><i class="fa fa-calendar mr-1"></i>
                                        {{ $meeting->date ? formatDate($meeting->date) : '-' }}</h6>
                                </div>
                                <div class="form-group">
                                    <h6><i class="fa fa-clock mr-1"></i>
                                        {{ $meeting->time ? formatTime($meeting->time) : '-' }}</h6>
                                </div>
                                <div class="form-group">
                                    <h6><i class="fa fa-globe mr-1"></i>
                                        {{ $meeting->timezone ? $meeting->timezone : '-' }}</h6>
                                </div>
                            @endif
                            <div class="form-group">
                                <p class="mb-1 meetDesc">{{ $meeting->description ? $meeting->description : '-' }}</p>
                            </div>

                            <div class="form-group row" @if (Auth::check()) hidden @endif>
                                <div class="col-12 col-md-10 offset-md-1">
                                    <input type="text" id="username" class="form-control"
                                        value="{{ $meeting->username }}" placeholder="{{ __('Enter your name') }}"
                                        maxlength="8" />
                                </div>
                            </div>

                            @if ($meeting->password)
                                <div class="form-group row">
                                    <div class="col-12 col-md-10 offset-md-1">
                                        <input id="password" type="text" class="form-control" name="password"
                                            placeholder="{{ __('Enter meeting password') }}" maxlength="8" required />
                                        <input type="hidden" name="id" value="{{ $meeting->id }}" />
                                    </div>
                                </div>
                            @endif

                            <div class="form-group row mb-0">
                                <div class="col-md-12 text-center">
                                    <button class="btn btn-primary" id="joinMeeting" data-toggle="tooltip"
                                        data-placement="top" title="{{ __('Join Meeting') }}" type="submit"
                                        disabled>{{ __('Join') }}</button>
                                    <button class="btn btn-info" type="button" data-toggle="modal"
                                        data-target="#shortcutInfo" data-toggle="tooltip" data-placement="top"
                                        title="{{ __('Shortcut Keys information') }}"><i class="fa fa-info"></i></button>
                                    <button class="btn btn-warning add" type="button" data-toggle="tooltip"
                                        data-placement="top" title="{{ __('Share Link') }}"><i
                                            class="fa fa-share-alt"></i></button>
                                </div>
                                <div id="error">
                                    <p>{{ __('Could not connect to the server, please try refreshing the page') }}</p>

                                    @if ($meeting->isAdmin)
                                        <a href="/signaling" target="_blank"><span class="badge badge-warning p-2"><i
                                                    class="fa fa-exclamation-triangle"></i>
                                                {{ __('Troubleshooting steps (Visible to the admin only)') }}</span></a>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid meeting-section">
        <div class="row">
            <div id="videos">
                <div class="videoContainer">
                    <img src="{{ asset('storage/images/SECONDARY_LOGO.png') }}" class="meeting-logo"
                        alt="{{ getSetting('APPLICATION_NAME') }}" />
                    <audio id="localAudio" autoplay muted></audio>
                    <video id="localVideo" class="cam" autoplay playsinline></video>
                    <span class="local-user-name">{{ __('You') }}</span>
                    <p class="user-initial"></p>
                </div>
                <div id="screenContainer" class="videoContainer">
                    <audio id="localScreenAudio" autoplay muted></audio>
                    <video id="localScreenVideo" autoplay playsinline></video>
                    <span class="local-user-name">{{ __('Your screen') }}</span>
                </div>
            </div>
            <div id="whiteboardSection"></div>
        </div>

        <div class="meeting-info text-center">
            <span id="meetingIdInfo" class="text-center"></span>
            <br>
            <span id="timer" class="text-center"></span>
        </div>

        <div class="chat-panel">
            <div class="chat-box">
                <div class="chat-header">
                    {{ __('Chat') }}
                    <i class="fas fa-times close-panel"></i>
                </div>
                <div class="chat-body">
                    <div class="empty-chat-body">
                        <i class="fa fa-comments chat-icon"></i>
                    </div>
                </div>
                <div class="chat-footer">
                    <form id="chatForm">
                        <div class="input-group">
                            <input type="text" id="messageInput" class="form-control note-input"
                                placeholder="{{ __('Type a message') }}" autocomplete="off" maxlength="250" />
                            <div class="input-group-append">
                                <button id="sendMessage" class="btn btn-outline-secondary" type="submit"
                                    title="{{ __('Send') }}">
                                    <i class="far fa-paper-plane"></i>
                                </button>
                                <button id="selectFile" class="btn btn-outline-secondary"
                                    title="{{ __('Attach File') }}" type="button">
                                    <i class="fas fa-paperclip"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                    <input type="file" name="file" id="file" data-max="50" hidden />
                </div>
            </div>
        </div>

        <div class="meeting-options">
            <button class="btn meeting-option" title="{{ __('Chat') }}" id="openChat">
                <i class="far fa-comment-alt"></i>
            </button>
            <button class="btn meeting-option" title="{{ __('Participants') }}" data-toggle="modal"
                data-target="#participantList" id="showParticipantList">
                <i class="fas fa-users"></i>
            </button>
            <button class="btn meeting-option" title="{{ __('Whiteboard') }}" id="whiteboard">
                <i class="fa fa-chalkboard"></i>
            </button>
            <button class="btn meeting-option" title="{{ __('Mute/Unmute Mic') }}" id="toggleMic">
                <i class="fa fa-microphone"></i>
            </button>
            <button class="btn btn-danger" title="{{ __('Leave Meeting') }}" id="leave">
                <i class="fas fa-phone"></i>
            </button>
            <button class="btn meeting-option" title="{{ __('On/Off Camera') }}" id="toggleVideo">
                <i class="fa fa-video"></i>
            </button>
            <button class="btn meeting-option" title="{{ __('Start/Stop ScreenShare') }}" id="screenShare">
                <i class="fa fa-desktop"></i>
            </button>
            <button class="btn meeting-option" title="{{ __('Raise Hand') }}" id="raiseHand">
                <i class="fa fa-hand-paper"></i>
            </button>
            <button class="btn meeting-option" title="{{ __('Start/Stop Recording') }}" id="recording">
                <i class="fa fa-record-vinyl"></i>
            </button>
            <button class="btn meeting-option openSettings" title="{{ __('Open Settings') }}">
                <i class="fa fa-cog"></i>
            </button>
        </div>
    </div>

    <div class="modal fade" id="previewModal" tabindex="-1" role="dialog" aria-labelledby="previewModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="previewModalLabel">{{ __('File Preview') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <img id="previewImage" src="" />
                    <p id="previewFilename"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="button" id="sendFile" class="btn btn-primary">{{ __('Send') }}</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="displayModal" tabindex="-1" role="dialog" aria-labelledby="displayModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="displayModalLabel">{{ __('File Display') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <img id="displayImage" src="" />
                    <p id="displayFilename"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                    <button type="button" id="downloadFile" class="btn btn-primary">{{ __('Download') }}</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="settings" tabindex="-1" role="dialog" aria-labelledby="settingsLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="settingsLabel">{{ __('Settings') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <div class="col-lg-3 col-md-4 text-left">
                            <label for="videoQualitySelect">{{ __('Video quality') }} </label>
                        </div>
                        <div class="col-lg-9 col-md-8">
                            <select id="videoQualitySelect" class="form-control">
                                <option id="QVGA" data-width="320" data-height="240">{{ __('QVGA') }}</option>
                                <option id="VGA" data-width="640" data-height="480">{{ __('VGA') }}</option>
                                <option id="HD" data-width="1280" data-height="720">{{ __('HD') }}</option>
                                <option id="FHD" data-width="1920" data-height="1080">{{ __('FHD') }}</option>
                                <option id="4K" data-width="3840" data-height="2160">{{ __('4K') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-3 col-md-4 text-left">
                            <label for="audioSource">{{ __('Audio input source') }} </label>
                        </div>
                        <div class="col-lg-9 col-md-8">
                            <select id="audioSource" class="form-control"></select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-3 col-md-4 text-left">
                            <label for="videoSource">{{ __('Video source') }} </label>
                        </div>
                        <div class="col-lg-9 col-md-8">
                            <select id="videoSource" class="form-control"></select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-lg-3 col-md-4 text-left">
                            <label for="videoSource">{{ __('Recording preference') }} </label>
                        </div>
                        <div class="col-lg-9 col-md-8">
                            <select id="recordingPreference" class="form-control">
                                <option value="with">{{ __('With whiteboard') }}</option>
                                <option value="without">{{ __('Without whiteboard') }}</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="shortcutInfo" tabindex="-1" role="dialog" aria-labelledby="shortcutInfoLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="shortcutInfoLabel">{{ __('Settings') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-borderless">
                        <thead>
                            <tr>
                                <th scope="col">{{ __('Shortcut Key') }}</th>
                                <th scope="col">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="row">C</th>
                                <td>{{ __('Chat') }}</td>
                            </tr>
                            <tr>
                                <th scope="row">F</th>
                                <td>{{ __('Attach File') }}</td>
                            </tr>
                            <tr>
                                <th scope="row">A</th>
                                <td>{{ __('Mute/Unmute Audio') }}</td>
                            </tr>
                            <tr>
                                <th scope="row">L</th>
                                <td>{{ __('Leave Meeting') }}</td>
                            </tr>
                            <tr>
                                <th scope="row">V</th>
                                <td>{{ __('On/Off Video') }}</td>
                            </tr>
                            <tr>
                                <th scope="row">S</th>
                                <td>{{ __('Screen Share') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="participantList" tabindex="-1" role="dialog" aria-labelledby="participantListLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="participantListLabel">{{ __('Participants') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-borderless">
                        <thead>
                            <tr>
                                <th scope="col">{{ __('#') }}</th>
                                <th scope="col">{{ __('Name') }}</th>
                            </tr>
                        </thead>
                        <tbody id="participantListBody">
                            <tr>
                                <th scope="row"></th>
                                <td>{{ __('You') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary add">{{ __('Invite') }}</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        const userInfo = {
            username: htmlEscape(username.value),
            meetingId: "{{ $meeting->meeting_id }}"
        };

        const passwordRequired = "{{ !!$meeting->password }}";
        const isModerator = "{{ $meeting->isModerator }}";
        const meetingTitle = "{{ $meeting->title }}";
        const timeLimit = "{{ $meeting->timeLimit == -1 ? 9999 : $meeting->timeLimit }}";
        const userLimit = "{{ $meeting->userLimit == -1 ? 9999 : $meeting->userLimit }}";
        const features = JSON.parse("{{ json_encode($meeting->features) }}".replace(/&quot;/g, '"'));
        Object.freeze(features);
    </script>
    <script src="{{ asset('js/socket.io.min.js') }}"></script>
    <script src="{{ asset('js/bundle.min.js') }}"></script>
    <script src="{{ asset('js/easytimer.min.js') }}"></script>
    <script src="{{ asset('js/siofu.min.js') }}"></script>
    <script src="{{ asset('js/MultiStreamsMixer.min.js') }}"></script>
    <script src="{{ asset('js/opentok-layout.min.js') }}"></script>
    <script src="{{ asset('js/canvas-designer-widget.js') }}"></script>
    <script src="{{ asset('js/meeting.js') }}"></script>
@endsection
