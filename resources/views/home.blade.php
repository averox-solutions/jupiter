@extends('layouts.application')

@section('title', __('Meta title :appName', ['appName' => getSetting('APPLICATION_NAME')]))
@section('description', __('Meta description :appName', ['appName' => getSetting('APPLICATION_NAME')]))

@section('style')
    <link href="{{ asset('css/home.css') }}" rel="stylesheet">
@endsection

@section('content')
    <section class="dashboard-section">
        <div id="permission"></div>
        <div class="left-center">
            <div class="site-detail">{!! $page->content !!}</div>
        </div>
        <div class="right-center">
            <div class="entering-info">
                <form id="meeting">
                    <div class="input-group mb-2">
                        <input type="text" name="id" class="form-control conference-id" id="conferenceId"
                            aria-label="{{ __('Meeting ID') }}" aria-describedby="initiate" required autofocus
                            autocomplete="off" maxlength="9" pattern="^[a-zA-Z0-9]+$"
                            title="{{ __('Special characters and spaces are not allowed') }}" />
                        <label class="form-control-placeholder" for="conferenceId">{{ __('Meeting ID') }}</label>
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="submit" id="initiate"
                                title="{{ __('Start the meeting') }}"><i class="fa fa-arrow-right"></i></button>
                        </div>
                    </div>
                    @if (getSetting('AUTH_MODE') == "enabled")
                        <a href="{{ route('dashboard') }}">{{ __('Host a meeting?') }}</a>
                    @endif
                </form>
            </div>
        </div>
    </section>

    @if (getSetting('LANDING_PAGE') == 'enabled')
        <!-- About section :: start -->
        <section class="page-section  about-desc lighBg">
            <div class="container pb-5 about-data">
                <div class="row align-items-center pb-5 mt-5">
                    <div class="col-12 col-lg-6">
                        <h1 class="main-title w-100 text-left ">{{ __('About The Site') }}</h1>
                        <p class="">{{ __('About The Site Description') }}</p>
                        <div class="use-show mt-3">
                            <div class="row text-center align-items-center">
                                <div class="col-12 col-sm-4 ">
                                    <i class="fa fa-video"></i>
                                    <p>{{ __('Host Meeting') }}</p>
                                </div>
                                <div class="col-12 col-sm-4 ">
                                    <i class="fa fa-user-plus"></i>
                                    <p>{{ __('Invite People') }}</p>
                                </div>
                                <div class="col-12 col-sm-4 ">
                                    <i class="fas fa-caret-square-right"></i>
                                    <p>{{ __('Have fun') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-6 text-center">
                        <div class="about-icon">
                            <i class="fas fa-chalkboard-teacher"></i>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- About section :: end -->

        <!-- feature section :: start -->
        <section class="about-section feature-section page-section">
            <div class="container pt-3">
                <div class="row w-100">
                    <h1 class="main-title  w-100 text-center">{{ __('Video Conference Features') }}</h1>
                </div>
                <div class="row text-center mt-4">
                    <div class="col-12 col-md-3">
                        <div class="icon-box">
                            <em class="fa fa-users"></em>
                            <h5 class="box-title">{{ __('feature_1') }}</h5>
                            <p>{{ __('feature_description_1') }} </p>
                        </div>
                    </div>
                    <div class="col-12 col-md-3">
                        <div class="icon-box ">
                            <em class="fa fa-comments"></em>
                            <h5 class="box-title ">{{ __('feature_2') }}</h5>
                            <p>{{ __('feature_description_2') }} </p>
                        </div>
                    </div>
                    <div class="col-12 col-md-3">
                        <div class="icon-box ">
                            <em class="fab fa-slideshare"></em>
                            <h5 class="box-title ">{{ __('feature_3') }}</h5>
                            <p>{{ __('feature_description_3') }} </p>
                        </div>
                    </div>
                    <div class="col-12 col-md-3">
                        <div class="icon-box ">
                            <em class="fab fa-creative-commons-share"></em>
                            <h5 class="box-title ">{{ __('feature_4') }}</h5>
                            <p>{{ __('feature_description_4') }} </p>
                        </div>
                    </div>
                    <div class="col-12 col-md-3">
                        <div class="icon-box ">
                            <em class="fa fa-chalkboard"></em>
                            <h5 class="box-title ">{{ __('feature_5') }}</h5>
                            <p>{{ __('feature_description_5') }} </p>
                        </div>
                    </div>
                    <div class="col-12 col-md-3">
                        <div class="icon-box">
                            <em class="fa fa-record-vinyl"></em>
                            <h5 class="box-title">{{ __('feature_6') }}</h5>
                            <p>{{ __('feature_description_6') }} </p>
                        </div>
                    </div>
                    <div class="col-12 col-md-3">
                        <div class="icon-box ">
                            <em class="fa fa-cogs"></em>
                            <h5 class="box-title ">{{ __('feature_7') }}</h5>
                            <p>{{ __('feature_description_7') }} </p>
                        </div>
                    </div>
                    <div class="col-12 col-md-3">
                        <div class="icon-box ">
                            <em class="fa fa-shield-alt"></em>
                            <h5 class="box-title ">{{ __('feature_8') }}</h5>
                            <p>{{ __('feature_description_8') }} </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- feature section :: end -->

        <!-- Chat section :: start -->
        <section class="page-section theme-bg ">
            <div class="container text-white">
                <div class="row w-100">
                    <h1 class="main-title w-100 text-center">{{ __('How To Host Video Meetings') }}</h1>
                </div>
                <div class="row mt-5 text-center">
                    <p>{{ __('Once you are logged into the system, navigate to the dashboard and create a new meeting. Then, click and the Start link and Join the meeting. When the browser prompts for media permission, then approve it. You should be inside the video meeting now') }}
                    </p>

                    <p>{{ __('Invite someone by clicking on the share icon. When they enter the same meeting ID as yours, you both will be connected. Enjoy rich features such as chat, screen sharing, file sharing, etc') }}
                    </p>
                </div>
            </div>
        </section>
        <!-- Chat section :: end -->

        <!-- FAQ section :: start -->
        <section class="page-section about-section faq_section">
            <div class="container">
                <div class="row w-100">
                    <h1 class="main-title w-100 text-center">{{ __('Frequently Asked Questions') }}</h1>
                </div>
                <div class="row align-items-center mt-5">
                    <div class="col-12 ">
                        <!--Accordion wrapper-->
                        <div class="accordion md-accordion" id="accordionEx" role="tablist" aria-multiselectable="true">

                            <!-- Accordion card -->
                            <div class="card">

                                <!-- Card header -->
                                <div class="card-header" role="tab" id="headingOne1">
                                    <a data-toggle="collapse" data-parent="#accordionEx" href="#collapseOne1"
                                        aria-expanded="true" aria-controls="collapseOne1">
                                        <h5 class="mb-0">
                                            {{ __('faq_q1') }} <i class="fas fa-angle-down rotate-icon float-right"></i>
                                        </h5>
                                    </a>
                                </div>

                                <!-- Card body -->
                                <div id="collapseOne1" class="collapse show" role="tabpanel" aria-labelledby="headingOne1"
                                    data-parent="#accordionEx">
                                    <div class="card-body">
                                        {{ __('faq_a1') }}
                                    </div>
                                </div>

                            </div>
                            <!-- Accordion card -->

                            <!-- Accordion card -->
                            <div class="card">

                                <!-- Card header -->
                                <div class="card-header" role="tab" id="headingTwo2">
                                    <a class="collapsed" data-toggle="collapse" data-parent="#accordionEx"
                                        href="#collapseTwo2" aria-expanded="false" aria-controls="collapseTwo2">
                                        <h5 class="mb-0">
                                            {{ __('faq_q2') }} <i class="fas fa-angle-down rotate-icon float-right"></i>
                                        </h5>
                                    </a>
                                </div>

                                <!-- Card body -->
                                <div id="collapseTwo2" class="collapse" role="tabpanel" aria-labelledby="headingTwo2"
                                    data-parent="#accordionEx">
                                    <div class="card-body">
                                        {{ __('faq_a2') }}
                                    </div>
                                </div>

                            </div>
                            <!-- Accordion card -->

                            <!-- Accordion card -->
                            <div class="card">

                                <!-- Card header -->
                                <div class="card-header" role="tab" id="headingThree3">
                                    <a class="collapsed" data-toggle="collapse" data-parent="#accordionEx"
                                        href="#collapseThree3" aria-expanded="false" aria-controls="collapseThree3">
                                        <h5 class="mb-0">
                                            {{ __('faq_q3') }} <i class="fas fa-angle-down rotate-icon float-right"></i>
                                        </h5>
                                    </a>
                                </div>

                                <!-- Card body -->
                                <div id="collapseThree3" class="collapse" role="tabpanel"
                                    aria-labelledby="headingThree3" data-parent="#accordionEx">
                                    <div class="card-body">
                                        {{ __('faq_a3') }}
                                    </div>
                                </div>

                            </div>
                            <!-- Accordion card -->

                            <!-- Accordion card -->
                            <div class="card">

                                <!-- Card header -->
                                <div class="card-header" role="tab" id="headingThree4">
                                    <a class="collapsed" data-toggle="collapse" data-parent="#accordionEx"
                                        href="#collapseThree4" aria-expanded="false" aria-controls="collapseThree4">
                                        <h5 class="mb-0">
                                            {{ __('faq_q4') }} <i class="fas fa-angle-down rotate-icon float-right"></i>
                                        </h5>
                                    </a>
                                </div>

                                <!-- Card body -->
                                <div id="collapseThree4" class="collapse" role="tabpanel"
                                    aria-labelledby="headingThree4" data-parent="#accordionEx">
                                    <div class="card-body">
                                        {{ __('faq_a4') }}
                                    </div>
                                </div>

                            </div>
                            <!-- Accordion card -->

                        </div>
                        <!-- Accordion wrapper -->
                    </div>
                </div>
            </div>
        </section>
        <!-- FAQ section :: end -->

        <!-- rating section :: start -->
        <section class=" page-section lighBg">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-12 text-center">
                        <div class="d-flex justify-content-center rating-section mb-4">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <h1 class="main-title w-100 text-center">{{ __('5-Star Rated Application') }}</h1>
                        <p>{{ __('The application is the best way to host video meetings online. The easy-to-use features make it more convenient to use this application for users. Try hosting a meeting now, you will get to know how easy to use the features are') }}
                        </p>
                    </div>
                </div>
            </div>
        </section>
        <!-- rating section :: end -->

        <!-- Start chat section :: start -->
        <section class="page-section theme-bg">
            <div class="conatiner">
                <div class="row">
                    <h1 class="main-title text-white w-100 text-center">
                        {{ __('What are you waiting for? Host a meeting now') }}
                    </h1>
                    <a class="start-btn">{{ __('Start Now') }}</a>
                </div>
            </div>
        </section>
        <!-- start chat section :: end -->
    @endif
@endsection

@section('script')
    <script type="text/javascript">
        let errorExist = "{{ $errors->any() }}";

        if (errorExist) {
            showError("{{ $errors->first() }}");
        }
    </script>
@endsection
